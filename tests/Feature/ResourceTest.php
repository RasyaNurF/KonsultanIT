<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Enums\ResourceType;
use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_index_is_removed_while_category_pages_remain_available(): void
    {
        Resource::create([
            'type' => ResourceType::Whitepaper,
            'title' => 'Panduan Keamanan',
            'status' => PublishStatus::Published,
        ]);
        Resource::create([
            'type' => ResourceType::News,
            'title' => 'Kabar Draft',
            'status' => PublishStatus::Draft,
        ]);

        $this->get('/resources')->assertNotFound();

        $this->get(route('resources.whitepaper'))
            ->assertOk()
            ->assertSee('Panduan Keamanan')
            ->assertDontSee('Kabar Draft');
    }

    public function test_each_type_has_its_own_page(): void
    {
        Resource::create(['type' => ResourceType::Event, 'title' => 'Tech Summit', 'status' => PublishStatus::Published]);
        Resource::create(['type' => ResourceType::Ebook, 'title' => 'Ebook ERP', 'status' => PublishStatus::Published]);

        $this->get(route('resources.type', 'event'))
            ->assertOk()
            ->assertSee('Tech Summit')
            ->assertDontSee('Ebook ERP');

        $this->get(route('resources.type', 'ebook'))
            ->assertOk()
            ->assertSee('Ebook ERP')
            ->assertDontSee('Tech Summit');
    }

    public function test_unknown_type_returns_404(): void
    {
        $this->get(route('resources.type', 'tidak-ada'))->assertNotFound();
    }

    public function test_clean_url_aliases_render_type_pages_and_details(): void
    {
        $event = Resource::create(['type' => ResourceType::Event, 'title' => 'Tech Summit', 'status' => PublishStatus::Published]);
        Resource::create(['type' => ResourceType::Whitepaper, 'title' => 'Panduan Keamanan', 'status' => PublishStatus::Published]);

        $this->get(route('resources.events'))->assertOk()->assertSee('Tech Summit')->assertDontSee('Panduan Keamanan');
        $this->get(route('resources.whitepaper'))->assertOk()->assertSee('Panduan Keamanan');
        $this->get(route('resources.events.show', $event->slug))->assertOk()->assertSee('Tech Summit');
    }

    public function test_resource_detail_is_reachable_and_drafts_404(): void
    {
        $resource = Resource::create([
            'type' => ResourceType::GoLive,
            'title' => 'Go-Live HRIS',
            'body' => 'Implementasi berjalan lancar.',
            'status' => PublishStatus::Published,
        ]);

        $this->get(route('resources.show', ['go-live', $resource->slug]))
            ->assertOk()
            ->assertSee('Go-Live HRIS')
            ->assertSee('Implementasi berjalan lancar.');

        $draft = Resource::create([
            'type' => ResourceType::News,
            'title' => 'Draft Resource',
            'status' => PublishStatus::Draft,
        ]);

        $this->get(route('resources.show', ['news', $draft->slug]))->assertNotFound();
    }

    public function test_resource_detail_404s_when_type_does_not_match(): void
    {
        $resource = Resource::create([
            'type' => ResourceType::Event,
            'title' => 'Mismatch Event',
            'status' => PublishStatus::Published,
        ]);

        $this->get(route('resources.show', ['news', $resource->slug]))->assertNotFound();
    }
}
