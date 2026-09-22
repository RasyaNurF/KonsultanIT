<?php

namespace App\Http\Controllers\Public;

use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $categories = BlogCategory::query()
            ->where('status', PublishStatus::Published->value)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $articles = Article::query()
            ->where('status', PublishStatus::Published->value)
            ->when($request->filled('kategori'), fn ($query) => $query->whereHas('blogCategory', fn ($q) => $q->where('slug', $request->string('kategori')->toString())))
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->string('q')->toString();
                $query->where(fn ($q) => $q->where('title', 'like', "%{$term}%")->orWhere('excerpt', 'like', "%{$term}%"));
            })
            ->with(['blogCategory', 'author'])
            ->orderByDesc('published_at')
            ->latest('id')
            ->paginate(9)
            ->withQueryString();

        return view('blog.index', [
            'articles' => $articles,
            'categories' => $categories,
            'activeCategory' => $request->string('kategori')->toString() ?: null,
        ]);
    }

    public function show(Article $article): View
    {
        abort_unless($article->status === PublishStatus::Published, 404);

        $article->load(['blogCategory', 'author']);

        $related = Article::query()
            ->where('status', PublishStatus::Published->value)
            ->whereKeyNot($article->id)
            ->when($article->blog_category_id, fn ($query) => $query->where('blog_category_id', $article->blog_category_id))
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        $sections = $this->articleSections((string) $article->body);

        return view('blog.show', [
            'article' => $article,
            'related' => $related,
            'sections' => $sections,
            'keyPoints' => $this->articleKeyPoints($sections, (string) $article->excerpt),
        ]);
    }

    /**
     * @return list<array{id: string, heading: string, body: string}>
     */
    private function articleSections(string $body): array
    {
        $paragraphs = preg_split('/\R{2,}/', trim($body)) ?: [];
        $sections = [];
        $usedIds = [];

        foreach ($paragraphs as $index => $paragraph) {
            $paragraph = trim(preg_replace('/\s+/', ' ', $paragraph) ?? '');

            if ($paragraph === '') {
                continue;
            }

            [$heading, $rest] = $this->splitHeading($paragraph, $index);

            $id = Str::slug($heading) ?: 'bagian-'.($index + 1);
            $suffix = 2;

            while (in_array($id, $usedIds, true)) {
                $id = Str::slug($heading).'-'.$suffix++;
            }

            $usedIds[] = $id;
            $sections[] = ['id' => $id, 'heading' => $heading, 'body' => $rest];
        }

        return $sections;
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function splitHeading(string $paragraph, int $index): array
    {
        if (preg_match('/^(.{12,140}?[.?!])\s+(.+)$/us', $paragraph, $matches) === 1) {
            return [trim($matches[1]), trim($matches[2])];
        }

        return ['Bagian '.($index + 1), $paragraph];
    }

    /**
     * @param  list<array{id: string, heading: string, body: string}>  $sections
     * @return list<string>
     */
    private function articleKeyPoints(array $sections, string $excerpt): array
    {
        $points = [];

        foreach (array_slice($sections, 0, 3) as $section) {
            if (preg_match('/^(.{12,160}?[.?!])(\s+|$)/us', $section['body'], $matches) === 1) {
                $points[] = trim($matches[1]);
            }
        }

        if ($excerpt !== '' && count($points) < 3) {
            $points[] = trim($excerpt);
        }

        return array_values(array_unique(array_filter($points)));
    }
}
