<?php

namespace Tests\Feature;

use App\Enums\MessageStatus;
use App\Enums\PublishStatus;
use App\Models\Article;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_administrators_are_redirected_to_the_admin_dashboard(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_registered_user_sees_the_account_overview(): void
    {
        $user = User::factory()->user()->create(['name' => 'Sinta Dewi']);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Akun')
            ->assertSee('Sinta Dewi')
            ->assertSee('Total Pesan')
            ->assertSee('Akses Cepat')
            ->assertSee('Detail Akun');
    }

    public function test_dashboard_shows_the_latest_conversation_messages(): void
    {
        $user = User::factory()->user()->create();
        $participant = $this->conversation($user);

        ChatMessage::create([
            'chat_participant_id' => $participant->id,
            'guest_token' => $participant->guest_token,
            'sender' => 'admin',
            'name' => 'Admin Nusakode',
            'body' => 'Terima kasih, kami kirimkan penawaran hari ini.',
            'is_read' => true,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Terima kasih, kami kirimkan penawaran hari ini.');
    }

    public function test_dashboard_shows_the_unread_count(): void
    {
        $user = User::factory()->user()->create();
        $this->conversation($user);

        $participant = ChatParticipant::query()->where('user_id', $user->id)->firstOrFail();

        ChatMessage::create([
            'chat_participant_id' => $participant->id,
            'guest_token' => $participant->guest_token,
            'sender' => 'admin',
            'name' => 'Admin Nusakode',
            'body' => 'Balasan baru untuk Anda.',
            'is_read' => true,
        ]);

        $this->actingAs($user)->get(route('dashboard'))->assertOk();

        $this->assertSame(1, $participant->fresh()->unreadForUser());
    }

    public function test_dashboard_recommends_published_articles(): void
    {
        $user = User::factory()->user()->create();

        Article::create([
            'title' => 'Panduan Memilih Vendor Software',
            'slug' => 'panduan-memilih-vendor-software',
            'excerpt' => 'Ringkasan panduan.',
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);

        Article::create([
            'title' => 'Draf yang Belum Terbit',
            'slug' => 'draf-belum-terbit',
            'excerpt' => 'Belum terbit.',
            'status' => PublishStatus::Draft,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Bacaan untuk Anda')
            ->assertSee('Panduan Memilih Vendor Software')
            ->assertDontSee('Draf yang Belum Terbit');
    }

    private function conversation(User $user): ChatParticipant
    {
        $participant = ChatParticipant::query()->create([
            'user_id' => $user->id,
            'guest_token' => 'token-dashboard-uji',
            'name' => $user->name,
            'email' => $user->email,
            'subject' => 'Diskusi layanan',
            'status' => MessageStatus::Replied->value,
            'last_message_at' => now(),
        ]);

        ChatMessage::create([
            'chat_participant_id' => $participant->id,
            'guest_token' => $participant->guest_token,
            'sender' => 'user',
            'name' => $user->name,
            'body' => 'Ada yang ingin saya tanyakan.',
            'is_read' => true,
        ]);

        return $participant;
    }
}
