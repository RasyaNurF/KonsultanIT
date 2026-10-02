<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ServicePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_web_service_shows_project_steps_when_it_has_no_custom_image(): void
    {
        $service = Service::create([
            'title' => 'Pengembangan Web & Aplikasi',
            'description' => 'Kami membangun aplikasi web untuk bisnis Anda.',
            'image_path' => 'img/work-1.jpg',
            'status' => PublishStatus::Published,
        ]);

        $this->get(route('solusi.show', $service->slug))
            ->assertOk()
            ->assertSee('Pengembangan Web &amp; Aplikasi', false)
            ->assertSee('Website dan portal')
            ->assertSee('Konsultasikan proyek')
            ->assertSee('Dari percakapan ke rencana kerja.')
            ->assertSee(route('portfolio.index'), false)
            ->assertDontSee('img/work-1.jpg', false);
    }

    public function test_uploaded_service_image_appears_in_the_hero(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->image('layanan.jpg')->store('services', 'public');
        $service = Service::create([
            'title' => 'Pengembangan Web & Aplikasi',
            'image_path' => $path,
            'status' => PublishStatus::Published,
        ]);

        $this->get(route('solusi.show', $service->slug))
            ->assertOk()
            ->assertSee('alt="Pengembangan Web &amp; Aplikasi"', false)
            ->assertSee(Storage::disk('public')->url($path), false)
            ->assertDontSee('Dari percakapan ke rencana kerja.');

        Storage::disk('public')->assertExists($path);
    }
}
