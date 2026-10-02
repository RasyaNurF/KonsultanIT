<?php

namespace Tests\Feature;

use App\Enums\ClientStatus;
use App\Enums\PublishStatus;
use App\Models\Announcement;
use App\Models\Article;
use App\Models\BlogCategory;
use App\Models\Career;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\Client;
use App\Models\Hero;
use App\Models\Industry;
use App\Models\Media;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\ProjectInquiry;
use App\Models\SeoMeta;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Solution;
use App\Models\SolutionCategory;
use App\Models\Team;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCrudMatrixTest extends TestCase
{
    use RefreshDatabase;

    public function test_simple_resources_support_full_crud(): void
    {
        $admin = User::factory()->superAdmin()->create();
        $this->actingAs($admin);

        $cases = [
            ['admin.clients', ['name' => 'Klien CRUD', 'status' => ClientStatus::Active->value], ['name' => 'Klien Ubah', 'status' => ClientStatus::Prospect->value], Client::class, ['name' => 'Klien Ubah']],
            ['admin.projects', ['name' => 'Proyek CRUD', 'status' => 'planning'], ['name' => 'Proyek Ubah', 'status' => 'development'], Project::class, ['name' => 'Proyek Ubah']],
            ['admin.services', ['title' => 'Layanan CRUD', 'status' => PublishStatus::Published->value], ['title' => 'Layanan Ubah', 'status' => PublishStatus::Draft->value], Service::class, ['title' => 'Layanan Ubah']],
            ['admin.industries', ['name' => 'Industri CRUD', 'status' => PublishStatus::Published->value], ['name' => 'Industri Ubah', 'status' => PublishStatus::Published->value], Industry::class, ['name' => 'Industri Ubah']],
            ['admin.solution-categories', ['name' => 'Kategori CRUD', 'status' => PublishStatus::Published->value], ['name' => 'Kategori Ubah', 'status' => PublishStatus::Published->value], SolutionCategory::class, ['name' => 'Kategori Ubah']],
            ['admin.blog-categories', ['name' => 'Blog Kategori CRUD', 'status' => PublishStatus::Published->value], ['name' => 'Blog Kategori Ubah', 'status' => PublishStatus::Published->value], BlogCategory::class, ['name' => 'Blog Kategori Ubah']],
            ['admin.teams', ['name' => 'Anggota CRUD', 'status' => PublishStatus::Published->value], ['name' => 'Anggota Ubah', 'status' => PublishStatus::Published->value], Team::class, ['name' => 'Anggota Ubah']],
            ['admin.heroes', ['title' => 'Hero CRUD', 'status' => PublishStatus::Published->value], ['title' => 'Hero Ubah', 'status' => PublishStatus::Draft->value], Hero::class, ['title' => 'Hero Ubah']],
            ['admin.testimonials', ['name' => 'Testi CRUD', 'quote' => 'Kutipan uji.', 'status' => PublishStatus::Published->value], ['name' => 'Testi Ubah', 'quote' => 'Kutipan ubah.', 'status' => PublishStatus::Published->value], Testimonial::class, ['name' => 'Testi Ubah']],
            ['admin.articles', ['title' => 'Artikel CRUD', 'status' => PublishStatus::Published->value], ['title' => 'Artikel Ubah', 'status' => PublishStatus::Draft->value], Article::class, ['title' => 'Artikel Ubah']],
            ['admin.careers', ['title' => 'Karier CRUD', 'status' => PublishStatus::Published->value], ['title' => 'Karier Ubah', 'status' => PublishStatus::Draft->value], Career::class, ['title' => 'Karier Ubah']],
            ['admin.portfolios', ['title' => 'Portfolio CRUD', 'status' => PublishStatus::Published->value], ['title' => 'Portfolio Ubah', 'status' => PublishStatus::Published->value], Portfolio::class, ['title' => 'Portfolio Ubah']],
            ['admin.announcements', ['placement' => 'bar', 'message' => 'Pengumuman CRUD', 'frequency' => 'always'], ['placement' => 'bar', 'message' => 'Pengumuman Ubah', 'frequency' => 'always'], Announcement::class, ['message' => 'Pengumuman Ubah']],
            ['admin.seo', ['path' => '/uji-crud-'.uniqid(), 'label' => 'SEO CRUD'], ['path' => '/uji-crud-'.uniqid(), 'label' => 'SEO Ubah'], SeoMeta::class, ['label' => 'SEO Ubah']],
        ];

        foreach ($cases as [$base, $store, $update, $modelClass, $expect]) {
            $this->assertCrud($base, $store, $update, $modelClass, $expect);
        }
    }

    public function test_solutions_support_full_crud(): void
    {
        $category = SolutionCategory::create(['name' => 'Kategori Induk', 'status' => PublishStatus::Published->value]);

        $this->actingAs(User::factory()->superAdmin()->create());

        $this->assertCrud(
            'admin.solutions',
            ['solution_category_id' => $category->id, 'title' => 'Solusi CRUD', 'status' => PublishStatus::Published->value],
            ['solution_category_id' => $category->id, 'title' => 'Solusi Ubah', 'status' => PublishStatus::Published->value],
            Solution::class,
            ['title' => 'Solusi Ubah'],
        );
    }

    public function test_resources_support_full_crud(): void
    {
        $this->actingAs(User::factory()->superAdmin()->create());

        $this->assertCrud(
            'admin.resources',
            ['type' => 'whitepaper', 'title' => 'Resource CRUD', 'status' => PublishStatus::Published->value],
            ['type' => 'whitepaper', 'title' => 'Resource Ubah', 'status' => PublishStatus::Published->value],
            \App\Models\Resource::class,
            ['title' => 'Resource Ubah'],
        );
    }

    public function test_users_support_full_crud(): void
    {
        $this->actingAs(User::factory()->superAdmin()->create());

        $this->assertCrud(
            'admin.users',
            ['name' => 'Staf CRUD', 'email' => 'staf.crud@example.com', 'role' => 'admin', 'password' => 'rahasia-aman-123', 'password_confirmation' => 'rahasia-aman-123', 'is_active' => '1'],
            ['name' => 'Staf Ubah', 'email' => 'staf.crud@example.com', 'role' => 'editor', 'is_active' => '1'],
            User::class,
            ['name' => 'Staf Ubah'],
        );
    }

    public function test_media_supports_full_crud(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->superAdmin()->create());

        $this->post(route('admin.media.store'), ['file' => UploadedFile::fake()->image('media.png')])
            ->assertRedirect();

        $media = Media::query()->latest('id')->firstOrFail();

        $this->put(route('admin.media.update', $media), ['alt_text' => 'Alt ubah', 'title' => 'Judul ubah'])
            ->assertRedirect();

        $this->assertSame('Alt ubah', $media->fresh()->alt_text);

        $this->delete(route('admin.media.destroy', $media))->assertRedirect();
        $this->assertDatabaseMissing('media', ['id' => $media->id]);
    }

    public function test_leads_support_update_destroy_and_export(): void
    {
        $this->actingAs(User::factory()->superAdmin()->create());

        $lead = ProjectInquiry::create([
            'name' => 'Lead CRUD',
            'email' => 'lead.crud@example.com',
            'project_type' => 'Aplikasi Web',
            'project_detail' => 'Detail uji.',
        ]);

        $this->put(route('admin.leads.update', $lead), ['status' => 'deal', 'admin_note' => 'Disetujui'])
            ->assertRedirect();

        $this->assertSame('deal', $lead->fresh()->status->value);
        $this->assertNotNull($lead->fresh()->handled_by);

        $this->get(route('admin.leads.export'))->assertOk();

        $this->delete(route('admin.leads.destroy', $lead))->assertRedirect();
        $this->assertDatabaseMissing('project_inquiries', ['id' => $lead->id]);
    }

    public function test_messages_support_reply_update_and_destroy(): void
    {
        $this->actingAs(User::factory()->superAdmin()->create());

        $participant = ChatParticipant::create([
            'guest_token' => 'token-crud',
            'name' => 'Pengunjung CRUD',
            'email' => 'guest@example.com',
            'status' => 'unread',
            'last_message_at' => now(),
        ]);

        ChatMessage::create([
            'chat_participant_id' => $participant->id,
            'guest_token' => $participant->guest_token,
            'sender' => 'guest',
            'name' => $participant->name,
            'body' => 'Halo, saya ingin bertanya.',
        ]);

        $this->post(route('admin.messages.reply', $participant), ['body' => 'Terima kasih atas pesannya.'])
            ->assertRedirect();

        $this->assertDatabaseHas('chat_messages', ['chat_participant_id' => $participant->id, 'sender' => 'admin']);

        $this->put(route('admin.messages.update', $participant), ['status' => 'archived'])->assertRedirect();
        $this->assertSame('archived', $participant->fresh()->status);

        $this->delete(route('admin.messages.destroy', $participant))->assertRedirect();
        $this->assertDatabaseMissing('chat_participants', ['id' => $participant->id]);
        $this->assertDatabaseMissing('chat_messages', ['chat_participant_id' => $participant->id]);
    }

    public function test_career_applications_support_update_and_destroy(): void
    {
        $this->actingAs(User::factory()->superAdmin()->create());

        $career = Career::create(['title' => 'Posisi CRUD', 'status' => PublishStatus::Published->value]);
        $application = $career->applications()->create([
            'name' => 'Pelamar CRUD',
            'email' => 'pelamar@example.com',
            'status' => 'new',
        ]);

        $this->put(route('admin.career-applications.update', $application), ['status' => 'interview'])
            ->assertRedirect();

        $this->assertSame('interview', $application->fresh()->status);

        $this->delete(route('admin.career-applications.destroy', $application))->assertRedirect();
        $this->assertDatabaseMissing('career_applications', ['id' => $application->id]);
    }

    public function test_portfolio_gallery_supports_store_and_destroy(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->superAdmin()->create());

        $portfolio = Portfolio::create(['title' => 'Portfolio Galeri', 'status' => PublishStatus::Published->value]);

        $this->post(route('admin.portfolio-images.store', $portfolio), [
            'image_file' => UploadedFile::fake()->image('galeri.png'),
        ])->assertRedirect();

        $image = $portfolio->images()->firstOrFail();
        Storage::disk('public')->assertExists($image->image_path);

        $this->delete(route('admin.portfolio-images.destroy', [$portfolio, $image]))->assertRedirect();

        $this->assertDatabaseMissing('portfolio_images', ['id' => $image->id]);
        Storage::disk('public')->assertMissing($image->image_path);
    }

    public function test_settings_company_and_profile_can_be_updated(): void
    {
        $admin = User::factory()->superAdmin()->create();
        $this->actingAs($admin);

        $this->put(route('admin.settings.update'), ['company_name' => 'PT Uji KIT Konsultan IT'])
            ->assertRedirect();
        $this->assertSame('PT Uji KIT Konsultan IT', SiteSetting::value('company_name'));

        $this->put(route('admin.company.update'), ['name' => 'PT Profil Uji'])
            ->assertRedirect();
        $this->assertDatabaseHas('company_profiles', ['name' => 'PT Profil Uji']);

        $this->put(route('admin.profile.update'), ['name' => 'Admin Ubah', 'email' => $admin->email])
            ->assertRedirect();
        $this->assertSame('Admin Ubah', $admin->fresh()->name);
    }

    public function test_every_admin_index_and_create_page_renders_for_super_admin(): void
    {
        $this->actingAs(User::factory()->superAdmin()->create());

        $bases = [
            'admin.leads', 'admin.messages', 'admin.clients', 'admin.projects', 'admin.portfolios',
            'admin.services', 'admin.solution-categories', 'admin.solutions', 'admin.resources',
            'admin.industries', 'admin.articles', 'admin.blog-categories', 'admin.testimonials',
            'admin.heroes', 'admin.teams', 'admin.careers', 'admin.career-applications',
            'admin.announcements', 'admin.users', 'admin.seo', 'admin.media',
        ];

        foreach ($bases as $base) {
            $this->get(route($base.'.index'))->assertOk();
        }

        $creatable = [
            'admin.clients', 'admin.projects', 'admin.portfolios', 'admin.services',
            'admin.solution-categories', 'admin.solutions', 'admin.resources', 'admin.industries',
            'admin.articles', 'admin.blog-categories', 'admin.testimonials', 'admin.heroes',
            'admin.teams', 'admin.careers', 'admin.announcements', 'admin.users', 'admin.seo',
        ];

        foreach ($creatable as $base) {
            $this->get(route($base.'.create'))->assertOk();
        }
    }

    /**
     * @param  array<string, mixed>  $store
     * @param  array<string, mixed>  $update
     * @param  class-string<Model>  $modelClass
     * @param  array<string, mixed>  $expect
     */
    private function assertCrud(string $base, array $store, array $update, string $modelClass, array $expect): void
    {
        $this->post(route($base.'.store'), $store)
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $model = $modelClass::query()->latest('id')->firstOrFail();

        $this->put(route($base.'.update', $model), $update)
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        foreach ($expect as $key => $value) {
            $this->assertSame($value, $model->fresh()->getAttribute($key), "Update {$base} gagal pada kolom {$key}.");
        }

        $this->delete(route($base.'.destroy', $model))->assertRedirect();
        $this->assertDatabaseMissing((new $modelClass)->getTable(), ['id' => $model->id]);
    }
}
