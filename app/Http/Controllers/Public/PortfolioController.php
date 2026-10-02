<?php

namespace App\Http\Controllers\Public;

use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(Request $request): View
    {
        $publishedPortfolios = Portfolio::query()
            ->where('status', PublishStatus::Published->value);

        $categories = (clone $publishedPortfolios)
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $portfolios = (clone $publishedPortfolios)
            ->when($request->filled('kategori'), fn ($query) => $query->where('category', $request->string('kategori')->toString()))
            ->with(['client'])
            ->orderBy('sort_order')
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('portfolios.index', [
            'portfolios' => $portfolios,
            'categories' => $categories,
            'activeCategory' => $request->string('kategori')->toString() ?: null,
        ]);
    }

    public function show(Portfolio $portfolio): View
    {
        abort_unless($portfolio->status === PublishStatus::Published, 404);

        $portfolio->load(['client', 'images']);

        $related = Portfolio::query()
            ->where('status', PublishStatus::Published->value)
            ->whereKeyNot($portfolio->id)
            ->when($portfolio->category, fn ($query) => $query->where('category', $portfolio->category))
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        return view('portfolios.show', [
            'portfolio' => $portfolio,
            'related' => $related,
        ]);
    }
}
