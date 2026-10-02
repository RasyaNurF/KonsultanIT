<?php

namespace Tests\Feature;

use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_send_first_chat_message_with_identity(): void
    {
        $response = $this->postJson(route('kontak.chat'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'body' => 'Halo, saya butuh aplikasi kasir.',
        ]);

        $response->assertOk();
        $response->assertJsonCount(2, 'messages');
        $response->assertJsonPath('messages.0.sender', 'guest');
        $response->assertJsonPath('messages.1.sender', 'admin');
        $response->assertJsonPath('messages.1.is_auto', true);
        $response->assertJsonPath('messages.1.body', ChatMessage::WAITING_REPLY_MESSAGE);

        $this->assertDatabaseHas('chat_messages', [
            'sender' => 'guest',
            'name' => 'Budi Santoso',
            'body' => 'Halo, saya butuh aplikasi kasir.',
        ]);
        $this->assertDatabaseHas('chat_messages', [
            'sender' => 'admin',
            'is_auto' => true,
        ]);
    }

    public function test_follow_up_chat_message_does_not_repeat_the_automatic_reply(): void
    {
        $this->postJson(route('kontak.chat'), [
            'name' => 'Budi Santoso',
            'body' => 'Pesan pertama.',
        ])->assertOk();

        $this->postJson(route('kontak.chat'), [
            'body' => 'Ada informasi tambahan.',
        ])->assertOk()
            ->assertJsonCount(1, 'messages')
            ->assertJsonPath('messages.0.body', 'Ada informasi tambahan.');

        $this->assertDatabaseCount('chat_messages', 3);
        $this->assertSame(1, ChatMessage::query()->where('is_auto', true)->count());
        $this->assertSame('unread', ChatParticipant::query()->firstOrFail()->status);
    }

    public function test_chat_message_requires_a_body(): void
    {
        $response = $this->postJson(route('kontak.chat'), [
            'name' => 'Budi Santoso',
            'body' => '',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('body');
    }

    public function test_first_message_requires_a_name(): void
    {
        $response = $this->postJson(route('kontak.chat'), [
            'body' => 'Halo.',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('name');
    }

    public function test_contact_page_only_shows_the_visitor_thread(): void
    {
        ChatMessage::create([
            'guest_token' => 'other-token',
            'sender' => 'guest',
            'name' => 'Orang Lain',
            'body' => 'Pesan orang lain.',
        ]);

        $response = $this->get(route('kontak'));

        $response->assertOk();
        $response->assertDontSee('Pesan orang lain.');
    }
}
