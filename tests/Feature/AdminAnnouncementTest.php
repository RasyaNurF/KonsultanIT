<?php

namespace Tests\Feature;

use App\Enums\AnnouncementPlacement;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAnnouncementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('admin.announcements.index'))->assertRedirect(route('login'));
    }

    public function test_user_role_cannot_access_announcements(): void
    {
        $this->actingAs(User::factory()->user()->create())
            ->get(route('admin.announcements.index'))
            ->assertForbidden();
    }

    public function test_editor_can_view_announcements(): void
    {
        $this->actingAs(User::factory()->editor()->create())
            ->get(route('admin.announcements.index'))
            ->assertOk()
            ->assertSee('Pengumuman');
    }

    public function test_admin_can_create_a_bar_announcement(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->post(route('admin.announcements.store'), [
                'placement' => AnnouncementPlacement::Bar->value,
                'message' => 'Promo perayaan ulang tahun.',
                'link_label' => 'Lihat promo',
                'link_url' => '/kontak',
                'frequency' => 'always',
                'is_active' => '1',
                'is_dismissible' => '1',
            ]);

        $response->assertRedirect(route('admin.announcements.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('announcements', [
            'placement' => AnnouncementPlacement::Bar->value,
            'message' => 'Promo perayaan ulang tahun.',
            'is_active' => true,
        ]);
    }

    public function test_message_is_required(): void
    {
        $this->actingAs(User::factory()->create())
            ->from(route('admin.announcements.create'))
            ->post(route('admin.announcements.store'), [
                'placement' => AnnouncementPlacement::Bar->value,
                'message' => '',
                'frequency' => 'always',
            ])
            ->assertSessionHasErrors('message');
    }

    public function test_frequency_days_is_required_for_days_frequency(): void
    {
        $this->actingAs(User::factory()->create())
            ->from(route('admin.announcements.create'))
            ->post(route('admin.announcements.store'), [
                'placement' => AnnouncementPlacement::Popup->value,
                'message' => 'Promo pop-up.',
                'frequency' => 'days',
                'frequency_days' => '',
            ])
            ->assertSessionHasErrors('frequency_days');
    }

    public function test_admin_can_update_and_delete_an_announcement_with_its_image(): void
    {
        Storage::fake('public');

        $announcement = Announcement::create([
            'placement' => AnnouncementPlacement::Popup,
            'title' => 'Judul lama',
            'message' => 'Pesan lama.',
            'frequency' => 'always',
            'is_active' => true,
        ]);

        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->put(route('admin.announcements.update', $announcement), [
                'placement' => AnnouncementPlacement::Popup->value,
                'title' => 'Judul baru',
                'message' => 'Pesan baru.',
                'frequency' => 'always',
                'is_active' => '1',
                'image_path_file' => UploadedFile::fake()->image('promo.png'),
            ])
            ->assertRedirect(route('admin.announcements.index'));

        $announcement->refresh();
        $this->assertSame('Pesan baru.', $announcement->message);
        $this->assertNotNull($announcement->image_path);
        Storage::disk('public')->assertExists($announcement->image_path);

        $storedPath = $announcement->image_path;

        $this->actingAs($admin)
            ->delete(route('admin.announcements.destroy', $announcement))
            ->assertRedirect(route('admin.announcements.index'));

        $this->assertDatabaseMissing('announcements', ['id' => $announcement->id]);
        Storage::disk('public')->assertMissing($storedPath);
    }
}
