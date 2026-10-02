<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\Article;
use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EditorialPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_archive_and_article_display_editorial_image_and_body(): void
    {
        $article = Article::create([
            'title' => 'Panduan Sistem Internal',
            'excerpt' => 'Langkah awal pengembangan.',
            'body' => "Kenali proses kerja yang ada. Catat hambatan tim sebelum merancang fitur.\n\nUji rancangan bersama pengguna. Perbaiki alur sebelum sistem diluncurkan.",
            'featured_image_path' => 'img/editorial/insight-team.png',
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);

        $this->get(route('blog.index'))
            ->assertOk()
            ->assertSee('Panduan Sistem Internal')
            ->assertSee('img/editorial/insight-team.png');

        $this->get(route('blog.show', $article->slug))
            ->assertOk()
            ->assertSee('Tim Redaksi KIT')
            ->assertSee('Kenali proses kerja yang ada.')
            ->assertSee('img/editorial/insight-team.png');
    }

    public function test_news_archive_and_detail_use_news_editorial_layout(): void
    {
        $resource = Resource::create([
            'type' => 'news',
            'title' => 'Catatan Pengembangan Produk',
            'excerpt' => 'Proses yang lebih terarah.',
            'body' => "Mulai dari kebutuhan pengguna. Susun prioritas bersama tim.\n\nUkur hasil setelah peluncuran.",
            'cover_image_path' => 'img/editorial/news-workspace.png',
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);

        $this->get(route('resources.news'))
            ->assertOk()
            ->assertSee('Sorotan News')
            ->assertSee('img/editorial/news-workspace.png');

        $this->get(route('resources.news.show', $resource->slug))
            ->assertOk()
            ->assertSee('Berita terbaru')
            ->assertSee('Mulai dari kebutuhan pengguna.')
            ->assertSee('img/editorial/news-workspace.png');
    }

    public function test_event_archive_and_detail_show_schedule_and_agenda_without_unavailable_registration(): void
    {
        $event = Resource::create([
            'type' => 'event',
            'title' => 'Contoh: Forum Integrasi Sistem',
            'excerpt' => 'Diskusi tentang integrasi data.',
            'body' => 'Pemetaan sistem menjadi langkah awal.',
            'starts_at' => now()->addWeek()->setTime(9, 0),
            'ends_at' => now()->addWeek()->setTime(11, 0),
            'register_url' => 'https://example.com/pendaftaran-palsu',
            'agenda' => [['time' => '09:00', 'title' => 'Pemetaan data', 'description' => 'Menentukan sumber informasi.']],
            'status' => PublishStatus::Published,
        ]);

        $this->get(route('resources.events'))
            ->assertOk()
            ->assertSee('Contoh kegiatan')
            ->assertSee('Forum Integrasi Sistem');

        $this->get(route('resources.events.show', $event->slug))
            ->assertOk()
            ->assertSee('Agenda acara')
            ->assertSee('Pemetaan data')
            ->assertSee('pendaftaran belum dikonfirmasi')
            ->assertDontSee('https://example.com/pendaftaran-palsu');
    }

    public function test_event_detail_shows_a_real_registration_or_recording_link_when_available(): void
    {
        $upcoming = Resource::create([
            'type' => 'event',
            'title' => 'Webinar Terbuka',
            'starts_at' => now()->addWeek(),
            'register_url' => 'https://acara.kit.test/daftar',
            'status' => PublishStatus::Published,
        ]);
        $past = Resource::create([
            'type' => 'event',
            'title' => 'Diskusi Terdahulu',
            'starts_at' => now()->subWeek(),
            'recording_url' => 'https://acara.kit.test/rekaman',
            'status' => PublishStatus::Published,
        ]);

        $this->get(route('resources.events.show', $upcoming->slug))
            ->assertOk()
            ->assertSee('Daftar acara')
            ->assertSee('https://acara.kit.test/daftar')
            ->assertSee('Pendaftaran tersedia');

        $this->get(route('resources.events.show', $past->slug))
            ->assertOk()
            ->assertSee('Tonton rekaman')
            ->assertSee('https://acara.kit.test/rekaman')
            ->assertSee('Rekaman tersedia');
    }

    public function test_go_live_archive_and_detail_show_project_story_and_visuals(): void
    {
        $resource = Resource::create([
            'type' => 'go-live',
            'title' => 'Implementasi Sistem Operasional',
            'industry' => 'Manufaktur',
            'excerpt' => 'Menyatukan proses di beberapa lokasi.',
            'body' => "Tim memetakan alur kerja yang tersebar.\n\nSistem diterapkan secara bertahap.",
            'gallery' => ['img/work-1.jpg', 'img/work-2.jpg'],
            'metrics' => [['label' => 'Lokasi', 'value' => '4']],
            'status' => PublishStatus::Published,
            'published_at' => now(),
        ]);

        $this->get(route('resources.go-live'))
            ->assertOk()
            ->assertSee('Implementasi Sistem Operasional')
            ->assertSee('img/work-1.jpg');

        $this->get(route('resources.go-live.show', $resource->slug))
            ->assertOk()
            ->assertSee('Cerita implementasi')
            ->assertSee('Tim memetakan alur kerja yang tersebar.')
            ->assertSee('img/work-2.jpg')
            ->assertSee('Lokasi');
    }

    public function test_go_live_sample_story_is_clearly_labeled_as_illustrative(): void
    {
        $resource = Resource::create([
            'type' => 'go-live',
            'title' => 'Contoh Implementasi HRIS',
            'body' => 'Skenario ini menggambarkan pendekatan bertahap.',
            'status' => PublishStatus::Published,
        ]);

        $this->get(route('resources.go-live'))
            ->assertOk()
            ->assertSee('bukan dokumentasi klien');

        $this->get(route('resources.go-live.show', $resource->slug))
            ->assertOk()
            ->assertSee('bukan dokumentasi klien');
    }

    public function test_ebook_pages_show_readable_preview_when_download_is_unavailable(): void
    {
        $resource = Resource::create([
            'type' => 'ebook',
            'title' => 'Panduan Integrasi Sistem',
            'excerpt' => 'Memahami alur pertukaran data.',
            'body' => "Mulai dari pemilik data.\n\nTentukan cara menangani kegagalan.",
            'chapters' => [['title' => 'Pemetaan data', 'description' => 'Menentukan sumber informasi.']],
            'file_path' => 'resources/files/belum-ada.pdf',
            'status' => PublishStatus::Published,
        ]);

        $this->get(route('resources.e-book'))
            ->assertOk()
            ->assertSee('Panduan Integrasi Sistem')
            ->assertSee('Pustaka Digital');

        $this->get(route('resources.e-book.show', $resource->slug))
            ->assertOk()
            ->assertSee('Materi unduhan sedang disiapkan')
            ->assertSee('Pemetaan data')
            ->assertDontSee('/storage/resources/files/belum-ada.pdf');
    }

    public function test_ebook_detail_links_to_an_existing_pdf(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('resources/files/panduan.pdf', '%PDF-1.4');

        $resource = Resource::create([
            'type' => 'ebook',
            'title' => 'Panduan Tersedia',
            'file_path' => 'resources/files/panduan.pdf',
            'page_count' => 12,
            'status' => PublishStatus::Published,
        ]);

        $this->get(route('resources.e-book.show', $resource->slug))
            ->assertOk()
            ->assertSee('12 halaman')
            ->assertSee('Materi tersedia')
            ->assertSee(Storage::disk('public')->url('resources/files/panduan.pdf'), false);
    }

    public function test_whitepaper_pages_show_a_readable_summary_when_download_is_unavailable(): void
    {
        $resource = Resource::create([
            'type' => 'whitepaper',
            'title' => 'Kajian Integrasi Data',
            'excerpt' => 'Menentukan sumber data yang dapat dipercaya.',
            'body' => "Mulai dari inventaris sistem.\n\nTinjau alur perubahan data.",
            'toc' => ['Pemetaan sumber data', 'Pengelolaan perubahan'],
            'file_path' => 'resources/files/belum-ada.pdf',
            'status' => PublishStatus::Published,
        ]);

        $this->get(route('resources.whitepaper'))
            ->assertOk()
            ->assertSee('Kajian Integrasi Data')
            ->assertSee('Sorotan / Whitepaper terbaru');

        $this->get(route('resources.whitepaper.show', $resource->slug))
            ->assertOk()
            ->assertSee('Dokumen lengkap sedang disiapkan')
            ->assertSee('Pemetaan sumber data')
            ->assertSee('Ringkasan tersedia')
            ->assertDontSee('/storage/resources/files/belum-ada.pdf');
    }

    public function test_whitepaper_detail_links_to_an_existing_pdf(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('resources/files/kajian.pdf', '%PDF-1.4');

        $resource = Resource::create([
            'type' => 'whitepaper',
            'title' => 'Kajian Tersedia',
            'file_path' => 'resources/files/kajian.pdf',
            'page_count' => 24,
            'status' => PublishStatus::Published,
        ]);

        $this->get(route('resources.whitepaper.show', $resource->slug))
            ->assertOk()
            ->assertSee('24 halaman')
            ->assertSee('Dokumen tersedia')
            ->assertSee(Storage::disk('public')->url('resources/files/kajian.pdf'), false);
    }

    public function test_whitepaper_detail_uses_an_external_document_when_provided(): void
    {
        $resource = Resource::create([
            'type' => 'whitepaper',
            'title' => 'Kajian Eksternal',
            'external_url' => 'https://documents.example.org/kajian.pdf',
            'status' => PublishStatus::Published,
        ]);

        $this->get(route('resources.whitepaper.show', $resource->slug))
            ->assertOk()
            ->assertSee('https://documents.example.org/kajian.pdf')
            ->assertSee('Dokumen tersedia')
            ->assertDontSee('Dokumen lengkap sedang disiapkan');
    }
}
