<?php

namespace App\Http\Controllers\Public;

use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Models\Solution;
use App\Models\SolutionCategory;
use Illuminate\View\View;

class SolutionController extends Controller
{
    public function index(): View
    {
        $categories = SolutionCategory::query()
            ->where('status', PublishStatus::Published->value)
            ->with(['solutions' => fn ($query) => $query->where('status', PublishStatus::Published->value)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $featured = Solution::query()
            ->where('status', PublishStatus::Published->value)
            ->where('is_featured', true)
            ->with('category')
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('solutions.index', [
            'categories' => $categories,
            'featured' => $featured,
            'solutionCount' => $categories->sum(fn ($category) => $category->solutions->count()),
        ]);
    }

    public function category(SolutionCategory $category): View
    {
        abort_unless($category->status === PublishStatus::Published, 404);

        $solutions = $category->solutions()
            ->where('status', PublishStatus::Published->value)
            ->orderBy('sort_order')
            ->get();

        return view('solutions.category', [
            'category' => $category,
            'solutions' => $solutions,
        ]);
    }

    public function show(SolutionCategory $category, Solution $solution): View
    {
        abort_unless($category->status === PublishStatus::Published, 404);
        abort_unless($solution->status === PublishStatus::Published, 404);
        abort_unless($solution->solution_category_id === $category->id, 404);

        $related = $category->solutions()
            ->where('status', PublishStatus::Published->value)
            ->whereKeyNot($solution->id)
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        $more = Solution::query()
            ->where('status', PublishStatus::Published->value)
            ->where('solution_category_id', '!=', $category->id)
            ->whereHas('category', fn ($query) => $query->where('status', PublishStatus::Published->value))
            ->with('category')
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('solutions.show', [
            'category' => $category,
            'solution' => $solution,
            'related' => $related,
            'more' => $more,
        ]);
    }
}
