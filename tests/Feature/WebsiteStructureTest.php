<?php

namespace Tests\Feature;

use App\Enums\AnnouncementPlacement;
use App\Enums\PublishStatus;
use App\Models\Announcement;
use App\Models\Client;
use App\Models\Industry;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteStructureTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_links_to_dedicated_pages_instead_of_duplicating_them(): void
    {
        Service::create(['title' => 'Web Development', 'status' => PublishStatus::Published]);

        $this->get('/')
            ->assertOk()
            ->assertSee(route('layanan.index'), false)
            ->assertSee(route('portfolio.index'), false)
            ->assertSee(route('tentang'), false)
            ->assertSee(route('blog.index'), false)
            ->assertSee('Web Development');
    }

    public function test_homepage_has_promo_banner_solution_menu_and_interactive_tabs(): void
    {
        Announcement::create([
            'placement' => AnnouncementPlacement::Bar,
            'message' => 'Butuh software untuk bisnis Anda?',
            'link_label' => 'Konsultasi gratis',
            'link_url' => '/kontak',
            'is_active' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Butuh software untuk bisnis Anda?')
            ->assertSee('Konsultasi gratis')
            ->assertSee('Pilih solusi sesuai kebutuhan Anda')
            ->assertSee('Pelajari Selengkapnya')
            ->assertSee('data-solusi-tab', false)
            ->assertSee('GitHub')
            ->assertSee('Perusahaan')
            ->assertSee('Resources')
            ->assertSee('Karier')

            ->assertSee('Tentang Kami')
            ->assertSee(route('login'), false)
            ->assertSee(route('register'), false);

    }

    public function test_homepage_brand_strip_renders_icon_and_name(): void
    {
        Client::create(['name' => 'Bank Arta', 'status' => 'active']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Docker')
            ->assertSee('Laravel');
    }

    public function test_layanan_page_shows_industry_solutions(): void
    {
        Industry::create([
            'name' => 'Pendidikan',
            'description' => 'Sistem akademik terpadu.',
            'status' => PublishStatus::Published,
        ]);

        $this->get(route('layanan.index'))
            ->assertOk()
            ->assertSee('Solusi per industri')
            ->assertSee('Pendidikan');
    }

    public function test_tentang_page_shows_work_process(): void
    {
        $this->get(route('tentang'))
            ->assertOk()
            ->assertSee('Tahapan yang bisa Anda audit')
            ->assertSee('Penemuan &amp; Analisis', false);
    }
}
