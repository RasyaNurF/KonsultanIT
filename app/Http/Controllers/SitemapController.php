<?php

namespace App\Http\Controllers;

use App\Enums\PublishStatus;
use App\Models\Article;
use App\Models\Career;
use App\Models\Portfolio;
use App\Models\Resource;
use App\Models\Service;
use App\Models\Solution;
use App\Models\SolutionCategory;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = collect([
            '/',
            '/tentang',
            '/solusi',
            '/portfolio',
            '/blog',
            '/karier',
            '/kontak',
        ]);

        $urls = $urls
            ->merge(Service::query()->where('status', PublishStatus::Published->value)->pluck('slug')->map(fn ($slug) => "/solusi/{$slug}"))
            ->merge(SolutionCategory::query()->where('status', PublishStatus::Published->value)->pluck('slug')->map(fn ($slug) => "/solusi/kategori/{$slug}"))
            ->merge(Solution::query()->where('status', PublishStatus::Published->value)
                ->whereHas('category', fn ($query) => $query->where('status', PublishStatus::Published->value))
                ->with('category')
                ->get()
                ->map(fn (Solution $solution) => "/solusi/kategori/{$solution->category->slug}/{$solution->slug}"))
            ->merge(Portfolio::query()->where('status', PublishStatus::Published->value)->pluck('slug')->map(fn ($slug) => "/portfolio/{$slug}"))
            ->merge(Article::query()->where('status', PublishStatus::Published->value)->pluck('slug')->map(fn ($slug) => "/blog/{$slug}"))
            ->merge(Resource::query()->where('status', PublishStatus::Published->value)->get(['type', 'slug'])
                ->map(fn (Resource $resource) => "/resources/{$resource->type->value}/{$resource->slug}"))
            ->merge(Career::query()->where('status', PublishStatus::Published->value)->pluck('slug')->map(fn ($slug) => "/karier/{$slug}"))
            ->unique()
            ->values();

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
