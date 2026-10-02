<?php

namespace App\Providers;

use App\Enums\AnnouncementPlacement;
use App\Enums\ClientStatus;
use App\Enums\MessageStatus;
use App\Enums\PublishStatus;
use App\Models\Announcement;
use App\Models\Article;
use App\Models\ChatParticipant;
use App\Models\Client;
use App\Models\Hero;
use App\Models\Industry;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->isProduction() && config('app.debug')) {
            throw new \RuntimeException('Konfigurasi tidak aman: APP_DEBUG harus false saat APP_ENV=production.');
        }

        Gate::before(function (?User $user, string $ability) {
            if ($user === null) {
                return null;
            }

            return $user->hasAbility($ability) ? true : null;
        });

        View::composer('layouts.site', function ($view) {
            try {
                $navServices = Service::query()
                    ->where('status', PublishStatus::Published->value)
                    ->orderBy('sort_order')
                    ->orderBy('title')
                    ->get(['slug', 'title']);
            } catch (Throwable) {
                $navServices = collect();
            }

            $view->with('navServices', $navServices);

            $chatUnread = 0;
            $user = auth()->user();

            if ($user !== null) {
                try {
                    $chatUnread = ChatParticipant::query()
                        ->where('user_id', $user->id)
                        ->first()
                        ?->unreadForUser() ?? 0;
                } catch (Throwable) {
                    $chatUnread = 0;
                }
            }

            $view->with('chatUnread', $chatUnread);

            try {
                $announcements = Announcement::query()
                    ->active()
                    ->orderBy('sort_order')
                    ->get();
            } catch (Throwable) {
                $announcements = collect();
            }

            $view->with('announcementBar', $announcements->first(fn (Announcement $item) => $item->placement === AnnouncementPlacement::Bar));
            $view->with('announcementPopup', $announcements->first(fn (Announcement $item) => $item->placement === AnnouncementPlacement::Popup));
            $view->with('cookieNotice', $announcements->first(fn (Announcement $item) => $item->placement === AnnouncementPlacement::Cookie));
        });

        View::composer('admin.layouts.app', function ($view) {
            try {
                $view->with('unreadMessages', ChatParticipant::query()
                    ->where('status', MessageStatus::Unread->value)
                    ->count());
            } catch (Throwable) {
                $view->with('unreadMessages', 0);
            }
        });

        View::composer('welcome', function ($view) {
            try {
                $view->with($this->landingContent());
                $view->with('heroStats', [
                    'experience' => $this->safeSetting('stat_experience') ?: '10+',
                    'projects' => $this->safeSetting('stat_projects') ?: '120+',
                    'retention' => $this->safeSetting('stat_retention') ?: '98%',
                ]);

                $media = function (string $key, string $fallback): string {
                    $path = $this->safeSetting($key);

                    if (! $path) {
                        return asset($fallback);
                    }

                    if (str_starts_with($path, 'img/')) {
                        return asset($path);
                    }

                    return Storage::disk('public')->url($path);
                };

                $view->with('heroImage', $media('hero_image_path', 'img/hero.jpg'));
                $view->with('aboutImage', $media('about_image_path', 'img/about.jpg'));
                $view->with('cmsHero', $this->cmsHero($media('hero_image_path', 'img/hero.jpg')));
            } catch (Throwable) {
                $view->with([
                    'dbServices' => [],
                    'dbIndustries' => [],
                    'dbWorks' => [],
                    'dbQuotes' => [],
                    'dbInsights' => [],
                    'dbClients' => [],
                    'heroStats' => ['experience' => '10+', 'projects' => '120+', 'retention' => '98%'],
                    'heroImage' => asset('img/hero.jpg'),
                    'aboutImage' => asset('img/about.jpg'),
                    'cmsHero' => null,
                ]);
            }
        });
    }

    private function safeSetting(string $key, ?string $default = null): ?string
    {
        try {
            return SiteSetting::value($key) ?? $default;
        } catch (Throwable) {
            return $default;
        }
    }

    /**
     * Hero aktif untuk homepage: baris published dengan urutan terkecil.
     *
     * @return array{title: string, highlight: ?string, description: ?string, cta_label: ?string, cta_url: ?string, secondary_cta_label: ?string, secondary_cta_url: ?string, image: string}|null
     */
    private function cmsHero(string $fallbackImage): ?array
    {
        try {
            $hero = Hero::query()
                ->where('status', PublishStatus::Published->value)
                ->orderBy('sort_order')
                ->orderByDesc('updated_at')
                ->first();
        } catch (Throwable) {
            return null;
        }

        if (! $hero) {
            return null;
        }

        $image = match (true) {
            $hero->image_path === null => $fallbackImage,
            str_starts_with($hero->image_path, 'img/') => asset($hero->image_path),
            default => Storage::disk('public')->url($hero->image_path),
        };

        return [
            'title' => $hero->title,
            'highlight' => $hero->highlight,
            'description' => $hero->description,
            'cta_label' => $hero->cta_label,
            'cta_url' => $hero->cta_url,
            'secondary_cta_label' => $hero->secondary_cta_label,
            'secondary_cta_url' => $hero->secondary_cta_url,
            'image' => $image,
        ];
    }

    /**
     * Konten landing page dari basis data, dengan fallback statis sebelum admin mengisi data.
     *
     * @return array<string, mixed>
     */
    private function landingContent(): array
    {
        $media = fn (?string $path, string $fallback) => match (true) {
            $path === null => asset($fallback),
            // Path bawaan seeder ('img/...') menunjuk aset statis di public/, bukan berkas unggahan.
            str_starts_with($path, 'img/') => asset($path),
            default => Storage::disk('public')->url($path),
        };

        try {
            $services = Service::query()
                ->where('status', PublishStatus::Published->value)
                ->orderBy('sort_order')
                ->orderBy('title')
                ->limit(6)
                ->get();

            $industries = Industry::query()
                ->where('status', PublishStatus::Published->value)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->limit(4)
                ->get();

            $portfolios = Portfolio::query()
                ->where('status', PublishStatus::Published->value)
                ->orderBy('sort_order')
                ->latest()
                ->limit(8)
                ->get();

            $testimonials = Testimonial::query()
                ->where('status', PublishStatus::Published->value)
                ->orderByDesc('is_featured')
                ->latest()
                ->limit(6)
                ->get();

            $articles = Article::query()
                ->where('status', PublishStatus::Published->value)
                ->with('blogCategory')
                ->orderByDesc('published_at')
                ->limit(3)
                ->get();

            $clients = Client::query()
                ->where('status', ClientStatus::Active->value)
                ->orderBy('name')
                ->get();
        } catch (Throwable) {
            $services = $industries = $portfolios = $testimonials = $articles = $clients = collect();
        }

        return [
            'dbServices' => $services->map(fn (Service $service, int $index) => [
                'no' => str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                'title' => $service->title,
                'desc' => (string) $service->description,
                'tags' => $service->technologyList(),
                'img' => $media($service->image_path, 'img/work-1.jpg'),
                'alt' => $service->title,
                'diagram' => $service->image_path === null && $index === 2,
                'url' => route('solusi.show', $service->slug),
            ])->all(),

            'dbIndustries' => $industries->map(fn (Industry $industry) => [
                'name' => $industry->name,
                'desc' => (string) $industry->description,
                'tags' => $industry->technologyList(),
            ])->all(),

            'dbWorks' => $portfolios->map(fn (Portfolio $portfolio) => [
                'img' => $media($portfolio->thumbnail_path, 'img/work-1.jpg'),
                'tag' => (string) ($portfolio->category ?: ($portfolio->client?->industry ?? 'Portfolio')),
                'title' => $portfolio->title,
                'url' => route('portfolio.show', $portfolio->slug),
            ])->all(),

            'dbQuotes' => $testimonials->map(fn (Testimonial $testimonial) => [
                'text' => $testimonial->quote,
                'name' => $testimonial->name,
                'role' => collect([$testimonial->position, $testimonial->company])->filter()->implode(', '),
                'initials' => $testimonial->initials(),
                'photo' => $testimonial->photo_path ? $media($testimonial->photo_path, 'img/about.jpg') : null,
            ])->all(),

            'dbInsights' => $articles->map(fn (Article $article) => [
                'cat' => (string) ($article->blogCategory?->name ?? $article->category ?: 'Insight'),
                'img' => $media($article->featured_image_path, 'img/work-1.jpg'),
                'title' => $article->title,
                'date' => ($article->published_at ?? $article->created_at)?->translatedFormat('d F Y') ?? '',
                'url' => route('blog.show', $article->slug),
            ])->all(),

            'dbClients' => $clients->map(fn (Client $client) => [
                'name' => $client->name,
                'logo' => $client->logo_path ? $media($client->logo_path, 'img/hero.jpg') : null,
                'website' => $client->website,
            ])->all(),
        ];
    }
}
