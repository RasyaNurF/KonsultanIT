<?php

namespace Tests\Feature;

use App\Models\ChatParticipant;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_editor_cannot_write_restricted_resources(): void
    {
        $editor = User::factory()->editor()->create();

        $this->actingAs($editor)->post(route('admin.services.store'), [])->assertForbidden();
        $this->actingAs($editor)->post(route('admin.industries.store'), [])->assertForbidden();
        $this->actingAs($editor)->post(route('admin.resources.store'), [])->assertForbidden();
        $this->actingAs($editor)->post(route('admin.careers.store'), [])->assertForbidden();
        $this->actingAs($editor)->put(route('admin.company.update'), [])->assertForbidden();
    }

    public function test_editor_cannot_write_every_restricted_admin_area(): void
    {
        $editor = User::factory()->editor()->create();

        $writes = [
            'admin.clients.store',
            'admin.projects.store',
            'admin.services.store',
            'admin.solution-categories.store',
            'admin.solutions.store',
            'admin.resources.store',
            'admin.industries.store',
            'admin.blog-categories.store',
            'admin.careers.store',
            'admin.users.store',
        ];

        foreach ($writes as $route) {
            $this->actingAs($editor)->post(route($route), [])->assertForbidden();
        }

        $this->actingAs($editor)->put(route('admin.company.update'), [])->assertForbidden();
        $this->actingAs($editor)->put(route('admin.settings.update'), [])->assertForbidden();
        $this->actingAs($editor)->put(route('admin.messages.update', ChatParticipant::create([
            'guest_token' => 'token-editor',
            'status' => 'unread',
        ])), ['status' => 'archived'])->assertForbidden();
    }

    public function test_editor_can_still_write_the_resources_they_own(): void
    {
        $editor = User::factory()->editor()->create();

        $this->actingAs($editor)
            ->post(route('admin.articles.store'), ['title' => 'Artikel Editor', 'status' => 'published'])
            ->assertRedirect(route('admin.articles.index'));

        $this->actingAs($editor)
            ->post(route('admin.portfolios.store'), ['title' => 'Portfolio Editor', 'status' => 'published'])
            ->assertRedirect(route('admin.portfolios.index'));

        $this->actingAs($editor)
            ->post(route('admin.announcements.store'), ['placement' => 'bar', 'message' => 'Pengumuman editor', 'frequency' => 'always'])
            ->assertRedirect(route('admin.announcements.index'));

        $this->assertDatabaseHas('articles', ['title' => 'Artikel Editor']);
        $this->assertDatabaseHas('portfolios', ['title' => 'Portfolio Editor']);
    }

    public function test_editor_can_still_view_restricted_resources(): void
    {
        $editor = User::factory()->editor()->create();

        $this->actingAs($editor)->get(route('admin.services.index'))->assertOk();
        $this->actingAs($editor)->get(route('admin.careers.index'))->assertOk();
        $this->actingAs($editor)->get(route('admin.company.edit'))->assertOk();
    }

    public function test_admin_can_still_write_restricted_resources(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.services.store'), [
                'title' => 'Layanan Uji',
                'status' => 'published',
            ])
            ->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseHas('services', ['title' => 'Layanan Uji']);
    }

    public function test_last_active_super_admin_cannot_be_demoted_or_deactivated(): void
    {
        $super = User::factory()->superAdmin()->create();

        $this->actingAs($super)
            ->put(route('admin.users.update', $super), [
                'name' => $super->name,
                'email' => $super->email,
                'role' => 'admin',
                'is_active' => '1',
            ])
            ->assertForbidden();

        $this->actingAs($super)
            ->put(route('admin.users.update', $super), [
                'name' => $super->name,
                'email' => $super->email,
                'role' => 'super_admin',
            ])
            ->assertForbidden();
    }

    public function test_demoting_a_super_admin_is_allowed_when_another_active_one_exists(): void
    {
        $first = User::factory()->superAdmin()->create();
        User::factory()->superAdmin()->create();

        $this->actingAs($first)
            ->put(route('admin.users.update', $first), [
                'name' => $first->name,
                'email' => $first->email,
                'role' => 'admin',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.users.index'));
    }

    public function test_inactive_user_cannot_log_in(): void
    {
        $user = User::factory()->create(['password' => 'password', 'is_active' => false]);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_deactivated_user_is_signed_out_before_sending_a_message(): void
    {
        $user = User::factory()->user()->create();
        $this->actingAs($user)->get(route('dashboard'))->assertOk();
        $user->update(['is_active' => false]);

        $this->postJson(route('pesan.store'), ['body' => 'Pesan akun nonaktif'])
            ->assertForbidden();

        $this->assertGuest();
        $this->assertDatabaseMissing('chat_messages', ['body' => 'Pesan akun nonaktif']);
    }

    public function test_session_with_an_old_password_is_rejected(): void
    {
        $user = User::factory()->user()->create();
        $oldPasswordHash = $user->password;
        $user->update(['password' => 'kata-sandi-baru-123']);

        $this->actingAs($user)
            ->withSession(['password_hash_web' => $oldPasswordHash])
            ->getJson(route('pesan.index'))
            ->assertUnauthorized();

        $this->assertGuest();
    }

    public function test_javascript_url_is_rejected_for_announcements(): void
    {
        $this->actingAs(User::factory()->create())
            ->from(route('admin.announcements.create'))
            ->post(route('admin.announcements.store'), [
                'placement' => 'bar',
                'message' => 'Promo',
                'frequency' => 'always',
                'link_label' => 'Klik',
                'link_url' => 'javascript:alert(1)',
            ])
            ->assertSessionHasErrors('link_url');
    }

    public function test_uploaded_svg_is_sanitized(): void
    {
        Storage::fake('public');

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)">'
            .'<script>alert(1)</script><circle cx="5" cy="5" r="4"/></svg>';

        $path = tempnam(sys_get_temp_dir(), 'svg').'.svg';
        file_put_contents($path, $svg);

        try {
            $file = new UploadedFile($path, 'logo.svg', 'image/svg+xml', null, true);

            $this->actingAs(User::factory()->create())
                ->post(route('admin.media.store'), ['file' => $file])
                ->assertRedirect();

            $medium = Media::query()->firstOrFail();
            $stored = Storage::disk('public')->get($medium->path);

            $this->assertStringNotContainsString('<script', $stored);
            $this->assertStringNotContainsString('onload', $stored);
            $this->assertStringContainsString('<circle', $stored);
        } finally {
            @unlink($path);
        }
    }

    public function test_security_headers_are_sent(): void
    {
        $this->get(route('home'))
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }
}
