<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\Client;
use App\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_portfolio_index_filters_cards_by_category_without_stat_counters(): void
    {
        $client = Client::create(['name' => 'Sinar Niaga', 'status' => 'active']);
        Portfolio::create(['title' => 'Portal Penjualan', 'category' => 'Distribusi', 'client_id' => $client->id, 'status' => PublishStatus::Published]);
        Portfolio::create(['title' => 'Dashboard Akademik', 'category' => 'Pendidikan', 'client_id' => $client->id, 'status' => PublishStatus::Published]);
        Portfolio::create(['title' => 'Proyek Draft', 'category' => 'Internal', 'status' => PublishStatus::Draft]);

        $this->get(route('portfolio.index', ['kategori' => 'Distribusi']))
            ->assertOk()
            ->assertViewHas('categories', fn ($categories) => $categories->count() === 2)
            ->assertSee('Karya Digital untuk Berbagai Industri')
            ->assertSee(asset('img/KiT Konsultan IT Hero Mockup.png'), false)
            ->assertDontSee('>Proyek</dt>', false)
            ->assertDontSee('>Klien</dt>', false)
            ->assertDontSee('>Kategori</dt>', false)
            ->assertSee('Portal Penjualan')
            ->assertDontSee('Dashboard Akademik')
            ->assertDontSee('Proyek Draft');
    }

    public function test_portfolio_index_shows_six_projects_per_page(): void
    {
        for ($number = 1; $number <= 7; $number++) {
            Portfolio::create(['title' => 'Proyek '.$number, 'status' => PublishStatus::Published]);
        }

        $this->get(route('portfolio.index'))
            ->assertOk()
            ->assertViewHas('portfolios', fn ($portfolios) => $portfolios->count() === 6 && $portfolios->total() === 7);

        $this->get(route('portfolio.index', ['page' => 2]))
            ->assertOk()
            ->assertViewHas('portfolios', fn ($portfolios) => $portfolios->count() === 1 && $portfolios->currentPage() === 2);
    }

    public function test_portfolio_with_only_basic_information_shows_a_complete_page_without_empty_sections(): void
    {
        $portfolio = Portfolio::create([
            'title' => 'Dashboard Operasional',
            'category' => 'Bisnis',
            'year' => 2025,
            'thumbnail_path' => 'img/work-1.jpg',
            'status' => PublishStatus::Published,
        ]);

        $this->get(route('portfolio.show', $portfolio->slug))
            ->assertOk()
            ->assertSee('Dashboard Operasional')
            ->assertSee('2025')
            ->assertSee(asset('img/work-1.jpg'), false)
            ->assertSee('Diskusikan proyek')
            ->assertDontSee('Dari kebutuhan sampai hasil.')
            ->assertDontSee('Teknologi yang digunakan');
    }
}
