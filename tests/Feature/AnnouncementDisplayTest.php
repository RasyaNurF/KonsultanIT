<?php

namespace Tests\Feature;

use App\Enums\AnnouncementPlacement;
use App\Models\Announcement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_bar_announcement_is_shown_on_public_pages(): void
    {
        Announcement::create([
            'placement' => AnnouncementPlacement::Bar,
            'message' => 'Promo akhir tahun untuk semua layanan.',
            'link_label' => 'Hubungi kami',
            'link_url' => '/kontak',
            'is_active' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Promo akhir tahun untuk semua layanan.')
            ->assertSee('data-promo', false);
    }

    public function test_inactive_or_expired_bar_announcement_is_hidden(): void
    {
        Announcement::create([
            'placement' => AnnouncementPlacement::Bar,
            'message' => 'Promo yang dinonaktifkan.',
            'is_active' => false,
        ]);

        Announcement::create([
            'placement' => AnnouncementPlacement::Bar,
            'message' => 'Promo yang kedaluwarsa.',
            'is_active' => true,
            'ends_at' => now()->subDay(),
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('Promo yang dinonaktifkan.')
            ->assertDontSee('Promo yang kedaluwarsa.');
    }

    public function test_bar_announcement_is_hidden_when_dismiss_cookie_matches(): void
    {
        $bar = Announcement::create([
            'placement' => AnnouncementPlacement::Bar,
            'message' => 'Promo yang bisa ditutup.',
            'is_active' => true,
            'is_dismissible' => true,
        ]);

        $this->withUnencryptedCookie('kit_bar_dismissed', (string) $bar->id)
            ->get(route('home'))
            ->assertOk()
            ->assertDontSee('Promo yang bisa ditutup.');
    }

    public function test_popup_with_always_frequency_shows_even_when_seen_cookie_exists(): void
    {
        $popup = Announcement::create([
            'placement' => AnnouncementPlacement::Popup,
            'title' => 'Pengumuman masuk',
            'message' => 'Selamat datang di situs kami.',
            'frequency' => 'always',
            'is_active' => true,
        ]);

        $this->withUnencryptedCookie('kit_popup_seen', (string) $popup->id)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('data-announcement-popup', false)
            ->assertSee('Pengumuman masuk');
    }

    public function test_popup_with_session_frequency_is_hidden_when_seen_cookie_matches(): void
    {
        $popup = Announcement::create([
            'placement' => AnnouncementPlacement::Popup,
            'title' => 'Pengumuman sesi',
            'message' => 'Muncul sekali per sesi.',
            'frequency' => 'session',
            'is_active' => true,
        ]);

        $this->withUnencryptedCookie('kit_popup_seen', (string) $popup->id)
            ->get(route('home'))
            ->assertOk()
            ->assertDontSee('data-announcement-popup', false);
    }

    public function test_cookie_notice_is_shown_until_consent_is_given(): void
    {
        Announcement::create([
            'placement' => AnnouncementPlacement::Cookie,
            'title' => 'Kami memakai cookie',
            'message' => 'Cookie membantu kami memahami kunjungan.',
            'is_active' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('data-cookie-notice', false)
            ->assertSee('Cookie membantu kami memahami kunjungan.');

        $this->withUnencryptedCookie('kit_cookie_consent', 'accepted')
            ->get(route('home'))
            ->assertOk()
            ->assertDontSee('data-cookie-notice', false);
    }
}
