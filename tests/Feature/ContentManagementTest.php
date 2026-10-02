<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\Article;
use App\Models\BlogCategory;
use App\Models\Career;
use App\Models\CompanyProfile;
use App\Models\Hero;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_team_member_and_it_appears_on_about_page(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.teams.store'), [
                'name' => 'Andi Pratama',
                'position' => 'CTO',
                'status' => PublishStatus::Published->value,
            ])
            ->assertRedirect(route('admin.teams.index'));

        $this->assertDatabaseHas('teams', ['slug' => 'andi-pratama']);

        $this->get(route('tentang'))
            ->assertOk()
            ->assertSee('Andi Pratama');
    }

    public function test_admin_can_create_career_and_public_can_apply(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.careers.store'), [
                'title' => 'Backend Engineer',
                'department' => 'Engineering',
                'location' => 'Jakarta',
                'employment_type' => 'Full-time',
                'status' => PublishStatus::Published->value,
            ])
            ->assertRedirect(route('admin.careers.index'));

        $career = Career::query()->firstWhere('slug', 'backend-engineer');
        $this->assertNotNull($career);

        $this->get(route('karier.index'))->assertOk()->assertSee('Backend Engineer');
        $this->get(route('karier.show', $career->slug))->assertOk();

        $this->post(route('karier.apply', $career->slug), [
            'name' => 'Pelamar Hebat',
            'email' => 'pelamar@example.com',
            'cover_letter' => 'Saya tertarik.',
        ])->assertRedirect();

        $this->assertDatabaseHas('career_applications', [
            'career_id' => $career->id,
            'email' => 'pelamar@example.com',
            'status' => 'new',
        ]);
    }

    public function test_service_created_in_admin_appears_on_public_pages(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.services.store'), [
                'title' => 'Web Development',
                'description' => 'Jasa pembuatan website perusahaan.',
                'status' => PublishStatus::Published->value,
            ])
            ->assertRedirect(route('admin.services.index'));

        $service = Service::query()->firstWhere('slug', 'web-development');
        $this->assertNotNull($service);

        $this->get(route('layanan.index'))->assertOk()->assertSee('Web Development');
        $this->get(route('layanan.show', $service->slug))->assertOk()->assertSee('Jasa pembuatan website');
    }

    public function test_portfolio_with_details_renders_problem_solution_result(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.portfolios.store'), [
                'title' => 'Dashboard Keuangan',
                'category' => 'Perbankan',
                'description' => 'Deskripsi.',
                'challenge' => 'Pencatatan manual.',
                'solution' => 'Portal terpusat.',
                'result' => 'Laporan sehari selesai.',
                'status' => PublishStatus::Published->value,
            ])
            ->assertRedirect(route('admin.portfolios.index'));

        $portfolio = Portfolio::query()->firstWhere('slug', 'dashboard-keuangan');
        $this->assertNotNull($portfolio);

        $this->get(route('portfolio.show', $portfolio->slug))
            ->assertOk()
            ->assertSee('Pencatatan manual')
            ->assertSee('Portal terpusat')
            ->assertSee('Laporan sehari selesai');
    }

    public function test_blog_category_links_article_to_public_blog(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.blog-categories.store'), [
                'name' => 'Panduan',
                'status' => PublishStatus::Published->value,
            ])
            ->assertRedirect(route('admin.blog-categories.index'));

        $category = BlogCategory::query()->firstWhere('slug', 'panduan');
        $this->assertNotNull($category);

        $this->actingAs($admin)
            ->post(route('admin.articles.store'), [
                'title' => 'Panduan Memilih Vendor',
                'blog_category_id' => $category->id,
                'status' => PublishStatus::Published->value,
            ])
            ->assertRedirect(route('admin.articles.index'));

        $article = Article::query()->firstWhere('slug', 'panduan-memilih-vendor');
        $this->assertNotNull($article);
        $this->assertSame($category->id, $article->blog_category_id);

        $this->get(route('blog.index'))->assertOk()->assertSee('Panduan Memilih Vendor');
        $this->get(route('blog.show', $article->slug))->assertOk();
    }

    public function test_hero_and_company_profile_can_be_managed(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.heroes.store'), [
                'title' => 'Teknologi yang merapikan bisnis',
                'highlight' => 'bisnis',
                'description' => 'Deskripsi hero.',
                'status' => PublishStatus::Published->value,
            ])
            ->assertRedirect(route('admin.heroes.index'));

        $this->assertDatabaseHas('heroes', ['title' => 'Teknologi yang merapikan bisnis']);

        $this->actingAs($admin)
            ->put(route('admin.company.update'), [
                'name' => 'KIT Konsultan IT',
                'vision' => 'Visi baru.',
            ])
            ->assertRedirect(route('admin.company.edit'));

        $this->assertDatabaseHas('company_profiles', ['vision' => 'Visi baru.']);
        $this->assertSame('Visi baru.', CompanyProfile::active()?->vision);

        $this->get('/')->assertOk()->assertSee('Teknologi yang merapikan');
        $this->get(route('tentang'))->assertOk()->assertSee('Visi baru.');
    }

    public function test_sitemap_includes_published_content(): void
    {
        Service::create(['title' => 'Web Development', 'status' => PublishStatus::Published]);
        Hero::create(['title' => 'Hero', 'status' => PublishStatus::Published]);

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('/layanan/web-development', false)
            ->assertSee('/tentang', false);
    }

    public function test_unknown_page_returns_custom_404(): void
    {
        $this->get('/halaman-yang-tidak-ada-xyz')->assertNotFound()->assertSee('404');
    }

    public function test_editor_permissions_follow_role_matrix(): void
    {
        $editor = User::factory()->editor()->create();

        $this->actingAs($editor)->get(route('admin.teams.index'))->assertOk();
        $this->actingAs($editor)->get(route('admin.heroes.index'))->assertOk();
        $this->actingAs($editor)->get(route('admin.company.edit'))->assertOk();
        $this->actingAs($editor)->get(route('admin.careers.index'))->assertOk();
        $this->actingAs($editor)->get(route('admin.users.index'))->assertForbidden();
    }
}
