<?php

namespace Tests\Feature;

use App\Enums\MessageStatus;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMessagesLiveTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_the_live_endpoints(): void
    {
        $participant = $this->conversation();

        $this->getJson(route('admin.messages.unread'))->assertUnauthorized();
        $this->getJson(route('admin.messages.live'))->assertUnauthorized();
        $this->getJson(route('admin.messages.poll', $participant))->assertUnauthorized();
    }

    public function test_editors_cannot_access_the_live_endpoints(): void
    {
        $participant = $this->conversation();

        $this->actingAs(User::factory()->editor()->create())
            ->getJson(route('admin.messages.unread'))
            ->assertForbidden();

        $this->actingAs(User::factory()->editor()->create())
            ->getJson(route('admin.messages.poll', $participant))
            ->assertForbidden();
    }

    public function test_admin_can_read_the_unread_count(): void
    {
        $this->conversation();

        $this->actingAs(User::factory()->create())
            ->getJson(route('admin.messages.unread'))
            ->assertOk()
            ->assertJsonPath('unread', 1);
    }

    public function test_live_endpoint_returns_rendered_rows(): void
    {
        $this->conversation();

        $response = $this->actingAs(User::factory()->create())
            ->getJson(route('admin.messages.live'))
            ->assertOk()
            ->assertJsonPath('unread', 1);

        $this->assertStringContainsString('Sinta Dewi', $response->json('html'));
        $this->assertStringContainsString('Butuh bantuan', $response->json('html'));
    }

    public function test_poll_returns_new_messages_and_marks_the_conversation_read(): void
    {
        $participant = $this->conversation();

        $response = $this->actingAs(User::factory()->create())
            ->getJson(route('admin.messages.poll', $participant))
            ->assertOk()
            ->assertJsonCount(1, 'messages')
            ->assertJsonPath('messages.0.sender', 'guest')
            ->assertJsonPath('status', MessageStatus::Read->value)
            ->assertJsonPath('unread', 0);

        $this->assertSame(MessageStatus::Read->value, $participant->fresh()->status);
        $this->assertTrue((bool) $participant->messages()->first()->is_read);
    }

    public function test_poll_with_after_returns_only_newer_messages(): void
    {
        $participant = $this->conversation();

        $firstId = $participant->messages()->first()->id;

        ChatMessage::create([
            'chat_participant_id' => $participant->id,
            'guest_token' => $participant->guest_token,
            'sender' => 'guest',
            'name' => 'Sinta Dewi',
            'body' => 'Pesan lanjutan.',
            'is_read' => false,
        ]);

        $this->actingAs(User::factory()->create())
            ->getJson(route('admin.messages.poll', ['participant' => $participant, 'after' => $firstId]))
            ->assertOk()
            ->assertJsonCount(1, 'messages')
            ->assertJsonPath('messages.0.body', 'Pesan lanjutan.');
    }

    private function conversation(): ChatParticipant
    {
        $participant = ChatParticipant::query()->create([
            'guest_token' => 'token-live-uji',
            'name' => 'Sinta Dewi',
            'email' => 'sinta@example.com',
            'subject' => 'Butuh bantuan integrasi API',
            'status' => MessageStatus::Unread->value,
            'last_message_at' => now(),
        ]);

        ChatMessage::create([
            'chat_participant_id' => $participant->id,
            'guest_token' => $participant->guest_token,
            'sender' => 'guest',
            'name' => 'Sinta Dewi',
            'body' => 'Butuh bantuan integrasi API.',
            'is_read' => false,
        ]);

        return $participant;
    }
}
