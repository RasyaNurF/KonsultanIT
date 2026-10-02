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
            ->assertJsonPath('message.body', 'Saya ingin bertanya soal integrasi API.')
            ->assertJsonPath('auto_reply.sender', 'admin')
            ->assertJsonPath('auto_reply.is_auto', true)
            ->assertJsonPath('auto_reply.body', ChatMessage::WAITING_REPLY_MESSAGE);

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
        $this->assertSame(2, $participant->messages()->count());
        $this->assertSame(0, $participant->fresh()->unreadForUser());
    }

    public function test_polling_with_after_returns_only_new_messages(): void
    {
        $user = User::factory()->user()->create();

        $this->actingAs($user)->postJson(route('pesan.store'), ['body' => 'Pesan pertama']);

        $latestId = $this->actingAs($user)->getJson(route('pesan.index'))->json('messages.1.id');

        $this->actingAs($user)->postJson(route('pesan.store'), ['body' => 'Pesan kedua']);

        $this->actingAs($user)
            ->getJson(route('pesan.index', ['after' => $latestId]))
            ->assertOk()
            ->assertJsonCount(1, 'messages')
            ->assertJsonPath('messages.0.body', 'Pesan kedua');
    }

    public function test_follow_up_messages_reuse_the_same_conversation(): void
    {
        $user = User::factory()->user()->create();

        $this->actingAs($user)->postJson(route('pesan.store'), ['body' => 'Pesan pertama']);
        $this->actingAs($user)->postJson(route('pesan.store'), ['body' => 'Pesan kedua'])
            ->assertJsonPath('auto_reply', null);

        $this->assertDatabaseCount('chat_participants', 1);
        $this->assertDatabaseCount('chat_messages', 3);
        $this->assertSame(1, ChatMessage::query()->where('is_auto', true)->count());
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
            ->assertJsonPath('messages.0.body', 'Balasan dari tim KIT Konsultan IT.');
    }

    public function test_chat_timestamps_use_indonesian_date_and_wib_after_utc_day_boundary(): void
    {
        $user = User::factory()->user()->create();
        $participant = $this->participantWithAdminReply($user);
        $participant->messages()->firstOrFail()
            ->forceFill(['created_at' => '2026-10-03 17:30:00'])
            ->save();

        $this->actingAs($user)
            ->getJson(route('pesan.index'))
            ->assertOk()
            ->assertJsonPath('messages.0.time', '04 Okt, 00:30 WIB');
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

    public function test_admin_typing_is_visible_only_in_the_matching_user_conversation(): void
    {
        $user = User::factory()->user()->create();
        $otherUser = User::factory()->user()->create();
        $admin = User::factory()->create();

        $this->actingAs($user)->postJson(route('pesan.store'), ['body' => 'Halo tim KIT.'])->assertOk();
        $participant = ChatParticipant::query()->where('user_id', $user->id)->firstOrFail();

        $this->actingAs($admin)
            ->postJson(route('admin.messages.typing', $participant), ['typing' => true])
            ->assertOk()
            ->assertJsonPath('typing', true);

        $this->actingAs($user)
            ->getJson(route('pesan.index'))
            ->assertOk()
            ->assertJsonPath('admin_typing', true);

        $this->actingAs($otherUser)
            ->getJson(route('pesan.index'))
            ->assertOk()
            ->assertJsonPath('admin_typing', false);

        $this->actingAs($admin)
            ->postJson(route('admin.messages.typing', $participant), ['typing' => false])
            ->assertOk()
            ->assertJsonPath('typing', false);

        $this->actingAs($user)
            ->getJson(route('pesan.index'))
            ->assertJsonPath('admin_typing', false);
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
            'name' => 'Admin KIT Konsultan IT',
            'body' => 'Balasan dari tim KIT Konsultan IT.',
            'is_read' => true,
        ]);

        return $participant;
    }
}
