<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\Career;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CareerApplicationPrivacyTest extends TestCase
{
    use RefreshDatabase;

    public function test_uploaded_cv_is_stored_privately(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        $career = Career::create(['title' => 'Web Developer', 'status' => PublishStatus::Published]);

        $this->post(route('karier.apply', $career->slug), [
            'name' => 'Pelamar',
            'email' => 'pelamar@example.com',
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ])->assertRedirect()->assertSessionHasNoErrors();

        $application = $career->applications()->firstOrFail();
        Storage::disk('local')->assertExists($application->cv_path);
        Storage::disk('public')->assertMissing($application->cv_path);
    }

    public function test_guest_and_regular_user_cannot_download_a_cv(): void
    {
        $career = Career::create(['title' => 'Web Developer']);
        $application = $career->applications()->create(['name' => 'Pelamar', 'email' => 'pelamar@example.com']);
        $url = route('admin.career-applications.download', $application);

        $this->get($url)->assertRedirect(route('login'));
        $this->actingAs(User::factory()->user()->create())->get($url)->assertForbidden();
    }

    public function test_authorized_admin_can_download_a_private_cv(): void
    {
        Storage::fake('local');
        $career = Career::create(['title' => 'Web Developer']);
        $application = $career->applications()->create([
            'name' => 'Pelamar', 'email' => 'pelamar@example.com', 'cv_path' => 'career-cvs/cv.pdf',
        ]);
        Storage::disk('local')->put($application->cv_path, 'CV privat');

        $this->actingAs(User::factory()->superAdmin()->create())
            ->get(route('admin.career-applications.download', $application))
            ->assertOk()->assertDownload('cv.pdf')->assertStreamedContent('CV privat');
    }

    public function test_deleting_an_application_removes_its_private_cv(): void
    {
        Storage::fake('local');
        $career = Career::create(['title' => 'Web Developer']);
        $application = $career->applications()->create([
            'name' => 'Pelamar', 'email' => 'pelamar@example.com', 'cv_path' => 'career-cvs/cv.pdf',
        ]);
        Storage::disk('local')->put($application->cv_path, 'CV privat');

        $this->actingAs(User::factory()->superAdmin()->create())
            ->delete(route('admin.career-applications.destroy', $application))->assertRedirect();

        Storage::disk('local')->assertMissing($application->cv_path);
        $this->assertDatabaseMissing('career_applications', ['id' => $application->id]);
    }

    public function test_missing_cv_returns_404_to_an_authorized_admin(): void
    {
        Storage::fake('local');
        $career = Career::create(['title' => 'Web Developer']);
        $this->actingAs(User::factory()->superAdmin()->create());

        foreach ([null, 'career-cvs/missing.pdf'] as $path) {
            $application = $career->applications()->create([
                'name' => 'Pelamar', 'email' => 'pelamar@example.com', 'cv_path' => $path,
            ]);

            $this->get(route('admin.career-applications.download', $application))->assertNotFound();
        }
    }
}
