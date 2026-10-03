<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\Article;
use App\Models\Career;
use App\Models\Industry;
use App\Models\Portfolio;
use App\Models\SeoMeta;
use App\Models\Service;
use App\Models\SolutionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCrudEdgeCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_solution_category_can_be_edited_with_its_existing_slug(): void
    {
        $this->actingAs(User::factory()->superAdmin()->create());

        $this->post(route('admin.solution-categories.store'), ['name' => 'Kategori Slug', 'status' => PublishStatus::Published->value])
            ->assertRedirect();

        $category = SolutionCategory::query()->latest('id')->firstOrFail();

        // Sebelum diperbaiki, ini gagal karena rule unique membaca route param yang salah.
        $this->put(route('admin.solution-categories.update', $category), [
            'name' => 'Kategori Slug Diubah',
            'slug' => $category->slug,
            'status' => PublishStatus::Published->value,
        ])->assertRedirect()->assertSessionHasNoErrors();

        $this->assertSame('Kategori Slug Diubah', $category->fresh()->name);
    }

    public function test_manual_slug_is_saved_for_article_service_and_industry(): void
    {
        $this->actingAs(User::factory()->superAdmin()->create());

        $this->post(route('admin.services.store'), ['title' => 'Layanan Manual', 'slug' => 'layanan-manual-ku', 'status' => PublishStatus::Published->value])
            ->assertRedirect();
        $this->assertSame('layanan-manual-ku', Service::query()->latest('id')->firstOrFail()->slug);

        $this->post(route('admin.industries.store'), ['name' => 'Industri Manual', 'slug' => 'industri-manual-ku', 'status' => PublishStatus::Published->value])
            ->assertRedirect();
        $this->assertSame('industri-manual-ku', Industry::query()->latest('id')->firstOrFail()->slug);

        $this->post(route('admin.articles.store'), ['title' => 'Artikel Manual', 'slug' => 'artikel-manual-ku', 'status' => PublishStatus::Published->value])
            ->assertRedirect();
        $this->assertSame('artikel-manual-ku', Article::query()->latest('id')->firstOrFail()->slug);
    }

    public function test_clearing_slug_on_update_regenerates_instead_of_erroring(): void
    {
        $this->actingAs(User::factory()->superAdmin()->create());

        $service = Service::create(['title' => 'Layanan Awal', 'status' => PublishStatus::Published->value]);
        $originalSlug = $service->slug;

        $this->put(route('admin.services.update', $service), [
            'title' => 'Layanan Setelah Dikosongkan',
            'slug' => '',
            'status' => PublishStatus::Published->value,
        ])->assertRedirect()->assertSessionHasNoErrors();

        $updated = $service->fresh();
        $this->assertNotNull($updated->slug);
        $this->assertNotSame('', $updated->slug);
        $this->assertNotSame($originalSlug, $updated->slug);
    }

    public function test_duplicate_client_name_is_rejected(): void
    {
        $this->actingAs(User::factory()->superAdmin()->create());

        $this->post(route('admin.clients.store'), ['name' => 'Bank Arta', 'status' => 'active'])->assertRedirect();
        $this->post(route('admin.clients.store'), ['name' => 'Bank Arta', 'status' => 'active'])->assertSessionHasErrors('name');
    }

    public function test_duplicate_seo_path_is_rejected(): void
    {
        $this->actingAs(User::factory()->superAdmin()->create());

        SeoMeta::create(['path' => '/duplikat', 'label' => 'Duplikat']);

        $this->post(route('admin.seo.store'), ['path' => '/duplikat', 'label' => 'Lain'])->assertSessionHasErrors('path');
    }

    public function test_career_with_past_deadline_can_be_updated(): void
    {
        $this->actingAs(User::factory()->superAdmin()->create());

        $career = Career::create([
            'title' => 'Posisi Lama',
            'status' => PublishStatus::Published->value,
            'deadline' => now()->subMonth()->toDateString(),
        ]);

        $this->put(route('admin.careers.update', $career), [
            'title' => 'Posisi Lama Diubah',
            'status' => PublishStatus::Published->value,
            'deadline' => $career->deadline->toDateString(),
        ])->assertRedirect()->assertSessionHasNoErrors();

        $this->assertSame('Posisi Lama Diubah', $career->fresh()->title);
    }

    public function test_deleting_a_career_removes_application_cv_files(): void
    {
        Storage::fake('local');
        $this->actingAs(User::factory()->superAdmin()->create());

        $career = Career::create(['title' => 'Posisi CV', 'status' => PublishStatus::Published->value]);

        Storage::disk('local')->put('career-cvs/cv-uji.pdf', 'PDF');
        $career->applications()->create([
            'name' => 'Pelamar',
            'email' => 'pelamar@example.com',
            'cv_path' => 'career-cvs/cv-uji.pdf',
            'status' => 'new',
        ]);

        $this->delete(route('admin.careers.destroy', $career))->assertRedirect();

        Storage::disk('local')->assertMissing('career-cvs/cv-uji.pdf');
        $this->assertDatabaseMissing('careers', ['id' => $career->id]);
    }

    public function test_portfolio_gallery_svg_is_sanitized(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->superAdmin()->create());

        $portfolio = Portfolio::create(['title' => 'Portfolio SVG', 'status' => PublishStatus::Published->value]);

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)"><script>alert(1)</script><rect width="4" height="4"/></svg>';
        $path = tempnam(sys_get_temp_dir(), 'svg').'.svg';
        file_put_contents($path, $svg);

        try {
            $this->post(route('admin.portfolio-images.store', $portfolio), [
                'image_file' => new UploadedFile($path, 'galeri.svg', 'image/svg+xml', null, true),
            ])->assertRedirect();

            $image = $portfolio->images()->firstOrFail();
            $stored = Storage::disk('public')->get($image->image_path);

            $this->assertStringNotContainsString('<script', $stored);
            $this->assertStringNotContainsString('onload', $stored);
            $this->assertStringContainsString('<rect', $stored);
        } finally {
            @unlink($path);
        }
    }
}
