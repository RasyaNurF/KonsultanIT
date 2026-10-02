<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\Article;
use App\Models\BlogCategory;
use App\Models\Career;
use App\Models\Portfolio;
use App\Models\Resource;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_public_pages_render_without_errors(): void
    {
        $service = Service::create(['title' => 'Layanan A', 'status' => PublishStatus::Published]);
        $portfolio = Portfolio::create(['title' => 'Portofolio A', 'status' => PublishStatus::Published]);
        $article = Article::create(['title' => 'Artikel A', 'status' => PublishStatus::Published, 'published_at' => now()]);
        $career = Career::create(['title' => 'Karier A', 'status' => PublishStatus::Published]);

        $files = [
            Resource::create(['type' => 'event', 'title' => 'Event A', 'starts_at' => now()->addWeek(), 'status' => PublishStatus::Published]),
            Resource::create(['type' => 'go-live', 'title' => 'GoLive A', 'status' => PublishStatus::Published]),
            Resource::create(['type' => 'whitepaper', 'title' => 'WP A', 'status' => PublishStatus::Published]),
            Resource::create(['type' => 'ebook', 'title' => 'EB A', 'status' => PublishStatus::Published]),
            Resource::create(['type' => 'news', 'title' => 'News A', 'status' => PublishStatus::Published]),
        ];

        $paths = [
            '/',
            '/tentang',
            '/solusi',
            '/solusi/'.$service->slug,
            '/portfolio',
            '/portfolio/'.$portfolio->slug,
            '/blog',
            '/blog/'.$article->slug,
            '/karier',
            '/karier/'.$career->slug,
            '/kontak',
            '/events',
            '/go-live',
            '/whitepaper',
            '/e-book',
            '/news',
            '/sitemap.xml',
        ];

        foreach ($paths as $path) {
            $this->get($path)->assertOk();
        }

        $this->get('/resources')->assertNotFound();

        $this->get('/layanan')->assertStatus(301)->assertRedirect('/solusi');
        $this->get('/layanan/'.$service->slug)->assertStatus(301)->assertRedirect('/solusi/'.$service->slug);

        foreach ($files as $file) {
            $this->get('/resources/'.$file->type->value.'/'.$file->slug)->assertOk();
        }

        $this->get('/events/'.$files[0]->slug)->assertOk();
        $this->get('/go-live/'.$files[1]->slug)->assertOk();
        $this->get('/whitepaper/'.$files[2]->slug)->assertOk();
        $this->get('/e-book/'.$files[3]->slug)->assertOk();
        $this->get('/news/'.$files[4]->slug)->assertOk();
    }

    public function test_all_admin_pages_render_without_errors(): void
    {
        $admin = User::factory()->superAdmin()->create();

        $paths = [
            '/admin',
            '/admin/artikel',
            '/admin/artikel/tambah',
            '/admin/hero',
            '/admin/hero/tambah',
            '/admin/industri',
            '/admin/karier',
            '/admin/karier/tambah',
            '/admin/kategori-blog',
            '/admin/kategori-blog/tambah',
            '/admin/kategori-solusi',
            '/admin/kategori-solusi/tambah',
            '/admin/klien',
            '/admin/klien/tambah',
            '/admin/lamaran',
            '/admin/layanan',
            '/admin/layanan/tambah',
            '/admin/leads',
            '/admin/media',
            '/admin/pengaturan',
            '/admin/pengguna',
            '/admin/pengguna/tambah',
            '/admin/pesan',
            '/admin/portfolio',
            '/admin/portfolio/tambah',
            '/admin/profil',
            '/admin/profil-perusahaan',
            '/admin/proyek',
            '/admin/proyek/tambah',
            '/admin/resources',
            '/admin/resources/tambah',
            '/admin/seo',
            '/admin/seo/tambah',
            '/admin/solusi',
            '/admin/solusi/tambah',
            '/admin/testimonial',
            '/admin/testimonial/tambah',
            '/admin/tim',
            '/admin/tim/tambah',
        ];

        foreach ($paths as $path) {
            $this->actingAs($admin)->get($path)->assertOk();
        }
    }

    public function test_blog_search_and_category_filter_work(): void
    {
        $category = BlogCategory::create(['name' => 'Keamanan', 'status' => PublishStatus::Published]);

        Article::create(['title' => 'Panduan Pentest Aplikasi', 'excerpt' => 'Langkah pengujian penetrasi.', 'blog_category_id' => $category->id, 'status' => PublishStatus::Published, 'published_at' => now()]);
        Article::create(['title' => 'Checklist Serah Terima', 'blog_category_id' => $category->id, 'status' => PublishStatus::Published, 'published_at' => now()]);

        $this->get(route('blog.index', ['q' => 'Pentest']))
            ->assertOk()
            ->assertSee('Panduan Pentest Aplikasi')
            ->assertDontSee('Checklist Serah Terima');

        $this->get(route('blog.index', ['kategori' => $category->slug]))
            ->assertOk()
            ->assertSee('Panduan Pentest Aplikasi');
    }
}
