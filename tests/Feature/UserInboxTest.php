<?php

namespace Tests\Feature;

use App\Enums\MessageStatus;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserInboxTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_use_the_chat_endpoints(): void
    {
        $this->getJson(route('pesan.index'))->assertUnauthorized();
        $this->postJson(route('pesan.store'), ['body' => 'Halo'])->assertUnauthorized();
    }

    public function test_chat_widget_is_available_to_every_authenticated_role(): void
    {
        $users = [
            User::factory()->user()->create(),
            User::factory()->editor()->create(),
            User::factory()->create(),
            User::factory()->superAdmin()->create(),
        ];

        foreach ($users as $user) {
            $this->actingAs($user)
                ->get(route('home'))
                ->assertOk()
                ->assertSee('data-chat-widget', false);
        }
    }

    public function test_chat_widget_is_hidden_from_guests(): void
    {
        $this->get(route('home'))->assertDontSee('data-chat-widget', false);
    }

    public function test_conversation_starts_empty(): void
    {
        $this->actingAs(User::factory()->user()->create())
            ->getJson(route('pesan.index'))
            ->assertOk()
            ->assertJsonCount(0, 'messages');
    }

    public function test_user_can_send_a_message_that_creates_a_conversation(): void
    {
        $user = User::factory()->user()->create(['name' => 'Sinta Dewi', 'email' => 'sinta@example.com']);

        $this->actingAs($user)
            ->postJson(route('pesan.store'), ['body' => 'Saya ingin bertanya soal integrasi API.'])
            ->assertOk()
            ->assertJsonPath('message.sender', 'user')
            ->assertJsonPath('message.body', 'Saya ingin bertanya soal integrasi API.');

        $participant = ChatParticipant::query()->where('user_id', $user->id)->first();

        $this->assertNotNull($participant);
        $this->assertSame('Sinta Dewi', $participant->name);
        $this->assertSame('sinta@example.com', $participant->email);
        $this->assertSame(MessageStatus::Unread->value, $participant->status);
        $this->assertNotNull($participant->last_message_at);

        $this->assertDatabaseHas('chat_messages', [
            'chat_participant_id' => $participant->id,
            'sender' => 'user',
            'body' => 'Saya ingin bertanya soal integrasi API.',
        ]);
    }

    public function test_polling_with_after_returns_only_new_messages(): void
    {
        $user = User::factory()->user()->create();

        $this->actingAs($user)->postJson(route('pesan.store'), ['body' => 'Pesan pertama']);

        $firstId = $this->actingAs($user)->getJson(route('pesan.index'))->json('messages.0.id');

        $this->actingAs($user)->postJson(route('pesan.store'), ['body' => 'Pesan kedua']);

        $this->actingAs($user)
            ->getJson(route('pesan.index', ['after' => $firstId]))
            ->assertOk()
            ->assertJsonCount(1, 'messages')
            ->assertJsonPath('messages.0.body', 'Pesan kedua');
    }

    public function test_follow_up_messages_reuse_the_same_conversation(): void
    {
        $user = User::factory()->user()->create();

        $this->actingAs($user)->postJson(route('pesan.store'), ['body' => 'Pesan pertama']);
        $this->actingAs($user)->postJson(route('pesan.store'), ['body' => 'Pesan kedua']);

        $this->assertDatabaseCount('chat_participants', 1);
        $this->assertDatabaseCount('chat_messages', 2);
    }

    public function test_conversation_appears_in_the_admin_message_center(): void
    {
        $user = User::factory()->user()->create(['name' => 'Sinta Dewi']);
        $admin = User::factory()->create();

        $this->actingAs($user)->postJson(route('pesan.store'), ['body' => 'Butuh bantuan integrasi API.']);

        $participant = ChatParticipant::query()->where('user_id', $user->id)->firstOrFail();

        $this->actingAs($admin)->get(route('admin.messages.index'))
            ->assertOk()
            ->assertSee('Sinta Dewi')
            ->assertSee('Butuh bantuan integrasi API.');

        $this->actingAs($admin)->get(route('admin.messages.show', $participant))
            ->assertOk()
            ->assertSee('Butuh bantuan integrasi API.')
            ->assertSee('Pengguna terdaftar');
    }

    public function test_admin_reply_is_visible_when_loading_the_conversation(): void
    {
        $user = User::factory()->user()->create(['name' => 'Sinta Dewi']);
        $participant = $this->participantWithAdminReply($user);

        $this->actingAs($user)
            ->getJson(route('pesan.index'))
            ->assertOk()
            ->assertJsonPath('messages.0.sender', 'admin')
            ->assertJsonPath('messages.0.body', 'Balasan dari tim Nusakode.');
    }

    public function test_loading_the_conversation_clears_the_unread_count(): void
    {
        $user = User::factory()->user()->create();
        $participant = $this->participantWithAdminReply($user);

        $this->assertSame(1, $participant->fresh()->unreadForUser());

        $this->actingAs($user)->getJson(route('pesan.index'))->assertOk();

        $this->assertSame(0, $participant->fresh()->unreadForUser());
    }

    public function test_users_only_see_their_own_conversation(): void
    {
        $other = User::factory()->user()->create();
        $this->actingAs($other)->postJson(route('pesan.store'), ['body' => 'Rahasia milik pengguna lain.']);

        $this->actingAs(User::factory()->user()->create())
            ->getJson(route('pesan.index'))
            ->assertOk()
            ->assertJsonCount(0, 'messages');
    }

    public function test_message_body_is_required(): void
    {
        $user = User::factory()->user()->create();

        $this->actingAs($user)
            ->postJson(route('pesan.store'), ['body' => ''])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('body');

        $this->assertDatabaseCount('chat_participants', 0);
    }

    private function participantWithAdminReply(User $user): ChatParticipant
    {
        $participant = ChatParticipant::query()->create([
            'user_id' => $user->id,
            'guest_token' => 'token-uji',
            'name' => $user->name,
            'email' => $user->email,
            'status' => MessageStatus::Replied->value,
            'last_message_at' => now(),
        ]);

        ChatMessage::create([
            'chat_participant_id' => $participant->id,
            'guest_token' => $participant->guest_token,
            'sender' => 'admin',
            'name' => 'Admin Nusakode',
            'body' => 'Balasan dari tim Nusakode.',
            'is_read' => true,
        ]);

        return $participant;
    }
}
