<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\Solution;
use App\Models\SolutionCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SolutionHierarchyTest extends TestCase
{
    use RefreshDatabase;

    private function makeCategory(array $attributes = []): SolutionCategory
    {
        return SolutionCategory::create(array_merge([
            'name' => 'Human Capital Management',
            'status' => PublishStatus::Published,
        ], $attributes));
    }

    public function test_solution_index_lists_published_categories_with_their_solutions(): void
    {
        $category = $this->makeCategory();
        Solution::create([
            'solution_category_id' => $category->id,
            'title' => 'SAP SuccessFactors',
            'status' => PublishStatus::Published,
        ]);

        $this->get(route('solusi.index'))
            ->assertOk()
            ->assertSee('Human Capital Management')
            ->assertSee('SAP SuccessFactors');
    }

    public function test_solution_category_page_shows_only_its_solutions(): void
    {
        $category = $this->makeCategory();
        $other = $this->makeCategory(['name' => 'IT Security']);

        Solution::create([
            'solution_category_id' => $category->id,
            'title' => 'Haermes HRIS',
            'status' => PublishStatus::Published,
        ]);
        $otherSolution = Solution::create([
            'solution_category_id' => $other->id,
            'title' => 'Managed Detection',
            'status' => PublishStatus::Published,
        ]);

        $response = $this->get(route('solusi.category', $category->slug))
            ->assertOk()
            ->assertSee('Haermes HRIS')
            ->assertSee(route('solusi.show', [$other->slug, $otherSolution->slug]));

        $previousLibxmlErrorSetting = libxml_use_internal_errors(true);
        $document = new \DOMDocument;

        try {
            $document->loadHTML($response->getContent());
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousLibxmlErrorSetting);
        }

        $mainContent = $document->getElementById('main');

        $this->assertNotNull($mainContent);
        $this->assertStringContainsString('Haermes HRIS', $mainContent->textContent);
        $this->assertStringNotContainsString('Managed Detection', $mainContent->textContent);
    }

    public function test_solution_detail_is_reachable_and_scoped_to_category(): void
    {
        $category = $this->makeCategory();
        $solution = Solution::create([
            'solution_category_id' => $category->id,
            'title' => 'Freshdesk',
            'features' => ['Ticketing', 'Knowledge base'],
            'benefits' => ['SLA terukur'],
            'status' => PublishStatus::Published,
        ]);

        $this->get(route('solusi.show', [$category->slug, $solution->slug]))
            ->assertOk()
            ->assertSee('Freshdesk')
            ->assertSee('Ticketing')
            ->assertSee('SLA terukur');
    }

    public function test_draft_solution_and_category_return_404(): void
    {
        $category = $this->makeCategory();
        $draft = Solution::create([
            'solution_category_id' => $category->id,
            'title' => 'Draft Solution',
            'status' => PublishStatus::Draft,
        ]);

        $this->get(route('solusi.show', [$category->slug, $draft->slug]))->assertNotFound();

        $hidden = $this->makeCategory(['name' => 'Hidden', 'status' => PublishStatus::Draft]);
        $this->get(route('solusi.category', $hidden->slug))->assertNotFound();
    }

    public function test_featured_solutions_surface_on_the_index(): void
    {
        $category = $this->makeCategory();

        Solution::create([
            'solution_category_id' => $category->id,
            'title' => 'Solusi Unggulan',
            'is_featured' => true,
            'status' => PublishStatus::Published,
        ]);
        Solution::create([
            'solution_category_id' => $category->id,
            'title' => 'Solusi Biasa',
            'status' => PublishStatus::Published,
        ]);

        $response = $this->get(route('solusi.index'))->assertOk();

        $response->assertSee('Solusi unggulan')->assertSee('Solusi Unggulan')->assertSee('Solusi Biasa');
    }

    public function test_detail_links_to_solutions_from_other_categories(): void
    {
        $category = $this->makeCategory();
        $other = $this->makeCategory(['name' => 'IT Security']);

        $solution = Solution::create([
            'solution_category_id' => $category->id,
            'title' => 'Haermes HRIS',
            'status' => PublishStatus::Published,
        ]);
        Solution::create([
            'solution_category_id' => $other->id,
            'title' => 'Managed Detection',
            'status' => PublishStatus::Published,
        ]);

        $this->get(route('solusi.show', [$category->slug, $solution->slug]))
            ->assertOk()
            ->assertSee('Jelajahi solusi lainnya')
            ->assertSee('Managed Detection');
    }
}
