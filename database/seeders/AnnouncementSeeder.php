<?php

namespace Database\Seeders;

use App\Enums\AnnouncementFrequency;
use App\Enums\AnnouncementPlacement;
use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        Announcement::query()->firstOrCreate(
            ['placement' => AnnouncementPlacement::Bar->value],
            [
                'message' => 'Butuh software, website, atau aplikasi mobile untuk bisnis Anda?',
                'link_label' => 'Konsultasi gratis',
                'link_url' => '/kontak',
                'frequency' => AnnouncementFrequency::Always->value,
                'is_active' => true,
                'is_dismissible' => true,
                'sort_order' => 1,
            ],
        );

        Announcement::query()->firstOrCreate(
            ['placement' => AnnouncementPlacement::Popup->value],
            [
                'title' => 'Selamat datang di Nusakode',
                'message' => 'Kami siap membantu kebutuhan digital perusahaan Anda, mulai dari konsultasi awal tanpa biaya.',
                'link_label' => 'Mulai konsultasi',
                'link_url' => '/kontak',
                'frequency' => AnnouncementFrequency::Session->value,
                'is_active' => false,
                'is_dismissible' => true,
                'sort_order' => 1,
            ],
        );

        Announcement::query()->firstOrCreate(
            ['placement' => AnnouncementPlacement::Cookie->value],
            [
                'title' => 'Kami menggunakan cookie',
                'message' => 'Kami memakai cookie untuk memahami cara pengunjung menggunakan situs ini. Anda dapat menerima atau menolak.',
                'frequency' => AnnouncementFrequency::Always->value,
                'is_active' => true,
                'is_dismissible' => false,
                'sort_order' => 1,
            ],
        );
    }
}
