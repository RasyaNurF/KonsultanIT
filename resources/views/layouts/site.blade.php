<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'KIT Konsultan IT — Perusahaan Jasa Teknologi Informasi Indonesia')</title>
    <meta name="description" content="@yield('meta-description', 'KIT Konsultan IT: jasa pengembangan aplikasi web, sistem informasi, integrasi sistem, infrastruktur cloud, dan keamanan informasi untuk perusahaan di Indonesia.')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta property="og:type" content="@yield('og-type', 'website')">
    <meta property="og:site_name" content="KIT Konsultan IT">
    <meta property="og:title" content="@yield('og-title', trim($__env->yieldContent('title', 'KIT Konsultan IT — Perusahaan Jasa Teknologi Informasi Indonesia')))">
    <meta property="og:description" content="@yield('og-description', trim($__env->yieldContent('meta-description', 'KIT Konsultan IT: jasa pengembangan aplikasi web, sistem informasi, integrasi sistem, infrastruktur cloud, dan keamanan informasi untuk perusahaan di Indonesia.')))">
    <meta property="og:url" content="{{ url()->current() }}">
    @hasSection('og-image')
        <meta property="og:image" content="@yield('og-image')">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og-title', trim($__env->yieldContent('title', 'KIT Konsultan IT — Perusahaan Jasa Teknologi Informasi Indonesia')))">
    <meta name="twitter:description" content="@yield('og-description', trim($__env->yieldContent('meta-description', 'KIT Konsultan IT: jasa pengembangan aplikasi web, sistem informasi, integrasi sistem, infrastruktur cloud, dan keamanan informasi untuk perusahaan di Indonesia.')))">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-neutral-700">

<a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:left-3 focus:top-3 focus:z-[100] focus:bg-white focus:px-4 focus:py-2 focus:text-sm">Lewati ke konten</a>

{{-- ============ NAVIGASI KACA (global) ============ --}}
@php
    $isHome = request()->is('/');

    $activeBar = $announcementBar ?? null;
    $showBar = $activeBar !== null
        && ($activeBar->is_dismissible === false || (string) request()->cookie('kit_bar_dismissed') !== (string) $activeBar->id);

    $activePopup = $announcementPopup ?? null;
    $showPopup = $activePopup !== null
        && ($activePopup->frequency === \App\Enums\AnnouncementFrequency::Always
            || (string) request()->cookie('kit_popup_seen') !== (string) $activePopup->id);

    $showCookieNotice = ($cookieNotice ?? null) !== null && ! request()->cookie('kit_cookie_consent');

    $explorerSolutions = [
        'web' => ['title' => 'Website & Web Application', 'description' => 'Pengalaman web yang cepat, jelas, dan siap berkembang.', 'slug' => 'pengembangan-web-aplikasi'],
        'software' => ['title' => 'Custom Software', 'description' => 'Perangkat lunak yang mengikuti alur kerja bisnis.', 'slug' => 'sistem-informasi-bisnis'],
        'mobile' => ['title' => 'Mobile Application', 'description' => 'Layanan dan operasional dalam genggaman.', 'slug' => 'aplikasi-mobile'],
        'system' => ['title' => 'Sistem Informasi', 'description' => 'Data dan proses kerja dalam satu sistem.', 'slug' => 'sistem-informasi-bisnis'],
        'integration' => ['title' => 'API & System Integration', 'description' => 'Hubungkan aplikasi dan data tanpa kerja berulang.', 'slug' => 'integrasi-api-sistem'],
        'design' => ['title' => 'UI/UX & Product Design', 'description' => 'Rancang produk yang mudah dipahami dan digunakan.', 'slug' => 'uiux-product-design'],
    ];

    $explorerNeeds = [
        ['title' => 'Digitalisasi Bisnis', 'description' => 'Bangun kanal digital yang relevan untuk pelanggan.', 'solutions' => ['web', 'software', 'mobile', 'system', 'integration', 'design']],
        ['title' => 'Otomasi Proses', 'description' => 'Sederhanakan pekerjaan rutin dan aliran data.', 'solutions' => ['software', 'system', 'integration']],
        ['title' => 'Pengembangan Sistem', 'description' => 'Wujudkan platform sesuai kebutuhan tim Anda.', 'solutions' => ['web', 'software', 'mobile', 'system']],
        ['title' => 'Integrasi Sistem', 'description' => 'Satukan sistem yang sudah Anda gunakan.', 'solutions' => ['integration', 'system', 'software']],
        ['title' => 'Transformasi Digital', 'description' => 'Rancang langkah digital dari ide hingga peluncuran.', 'solutions' => ['design', 'web', 'mobile', 'integration']],
    ];

    $explorerUrl = function (string $key) use ($explorerSolutions, $navServices): string {
        $service = ($navServices ?? collect())->firstWhere('slug', $explorerSolutions[$key]['slug']);

        return $service ? route('solusi.show', $service->slug) : route('solusi.index');
    };
@endphp
<header id="glass-header" class="fixed inset-x-0 top-0 z-50 border-b border-neutral-200 bg-white/95 shadow-sm backdrop-blur transition duration-300">
    @if ($showBar)
        <x-announcement-bar :announcement="$activeBar" />
    @endif
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6">
        <a href="{{ url('/') }}" class="flex shrink-0 items-center" aria-label="KIT Konsultan IT">
            <img src="{{ asset('img/logo-company-trimmed.png') }}" alt="KIT Konsultan IT" class="h-11 w-[210px] object-contain object-left sm:w-[230px] lg:w-[210px] xl:w-[230px]">
        </a>
        <nav class="hidden items-center gap-3 text-sm font-semibold text-neutral-600 lg:flex xl:gap-7 xl:text-[15px]" aria-label="Navigasi utama">
            <a href="{{ url('/') }}" class="relative pb-1.5 transition hover:text-navy-950 {{ $isHome ? 'text-navy-950' : '' }}">Beranda @if ($isHome)<span class="absolute inset-x-0 -bottom-0.5 h-0.5 bg-brand-500" aria-hidden="true"></span>@endif</a>
            <button type="button" data-solution-trigger aria-expanded="false" aria-controls="solution-explorer" class="flex items-center gap-1 pb-1.5 transition hover:text-navy-950 {{ request()->routeIs('solusi*') ? 'text-navy-950' : '' }}">Solusi
                <svg class="h-3.5 w-3.5 transition-transform duration-200" data-solution-chevron viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <a href="{{ route('portfolio.index') }}" class="pb-1.5 transition hover:text-navy-950 {{ request()->routeIs('portfolio*') ? 'text-navy-950' : '' }}">Portfolio</a>
            <div class="group relative">
                <a href="{{ route('tentang') }}" class="flex items-center gap-1 pb-1.5 transition hover:text-navy-950 {{ request()->routeIs('tentang', 'karier*') ? 'text-navy-950' : '' }}" aria-haspopup="true">Perusahaan
                    <svg class="h-3.5 w-3.5 transition-transform group-hover:rotate-180 group-focus-within:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </a>
                <div class="pointer-events-none invisible absolute left-1/2 top-full z-50 w-80 -translate-x-1/2 translate-y-1 pt-4 opacity-0 transition duration-150 group-hover:pointer-events-auto group-hover:visible group-hover:translate-y-0 group-hover:opacity-100 group-focus-within:pointer-events-auto group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:opacity-100">
                    <div class="rounded-xl border border-neutral-200 bg-white p-2 text-left shadow-[0_18px_45px_-22px_rgba(5,24,41,0.28)]">
                        <div class="border-b border-neutral-100 px-3 pb-3 pt-2">
                            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-brand-700">Perusahaan</p>
                            <p class="mt-1 text-xs font-normal leading-5 text-neutral-500">Kenali tim dan perjalanan KIT.</p>
                        </div>
                        <div class="pt-1">
                            <a href="{{ route('tentang') }}" class="group/item flex items-center justify-between gap-3 rounded-lg px-3 py-3 transition hover:bg-neutral-50 focus-visible:bg-neutral-50">
                                <span><span class="block text-sm font-bold text-navy-950">Tentang Kami</span><span class="mt-0.5 block text-xs font-normal text-neutral-500">Profil, pendekatan, dan cara kerja.</span></span>
                                <span class="text-brand-600 transition-transform group-hover/item:translate-x-1" aria-hidden="true">→</span>
                            </a>
                            <a href="{{ route('karier.index') }}" class="group/item flex items-center justify-between gap-3 rounded-lg px-3 py-3 transition hover:bg-neutral-50 focus-visible:bg-neutral-50">
                                <span><span class="block text-sm font-bold text-navy-950">Karier</span><span class="mt-0.5 block text-xs font-normal text-neutral-500">Peluang berkembang bersama tim.</span></span>
                                <span class="text-brand-600 transition-transform group-hover/item:translate-x-1" aria-hidden="true">→</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <button type="button" data-resource-trigger aria-expanded="false" aria-controls="resource-menu" class="flex items-center gap-1 pb-1.5 transition hover:text-navy-950 focus-visible:text-navy-950 {{ request()->routeIs('resources*') ? 'text-navy-950' : '' }}">Resources
                    <svg class="h-3.5 w-3.5 transition-transform duration-200" data-resource-chevron viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div id="resource-menu" data-resource-menu data-open="false" class="pointer-events-none invisible absolute left-1/2 top-full z-50 w-[440px] -translate-x-1/2 translate-y-1 pt-4 opacity-0 transition duration-150 data-[open=true]:pointer-events-auto data-[open=true]:visible data-[open=true]:translate-y-0 data-[open=true]:opacity-100">
                    <div class="rounded-xl border border-neutral-200 bg-white p-2 text-left shadow-[0_18px_45px_-22px_rgba(5,24,41,0.28)]">
                        <div class="border-b border-neutral-100 px-3 pb-3 pt-2">
                            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-brand-700">Resources</p>
                            <p class="mt-1 text-xs font-normal leading-5 text-neutral-500">Wawasan dan kabar terbaru dari KIT.</p>
                        </div>
                        <div class="grid grid-cols-2 gap-1 pt-1">
                            @foreach ([
                                ['title' => 'Blog', 'description' => 'Artikel dan panduan praktis.', 'url' => route('blog.index')],
                                ['title' => 'Event', 'description' => 'Agenda dan kegiatan terbaru.', 'url' => route('resources.events')],
                                ['title' => 'Whitepaper', 'description' => 'Kajian teknologi untuk bisnis.', 'url' => route('resources.whitepaper')],
                                ['title' => 'E-book', 'description' => 'Materi untuk dipelajari mandiri.', 'url' => route('resources.e-book')],
                                ['title' => 'News', 'description' => 'Berita dan pembaruan KIT.', 'url' => route('resources.news')],
                                ['title' => 'Go-Live', 'description' => 'Cerita peluncuran solusi.', 'url' => route('resources.go-live')],
                            ] as $resourceLink)
                                <a href="{{ $resourceLink['url'] }}" class="group/item flex min-h-16 items-start justify-between gap-2 rounded-lg px-3 py-3 transition hover:bg-neutral-50 focus-visible:bg-neutral-50">
                                    <span><span class="block text-sm font-bold text-navy-950">{{ $resourceLink['title'] }}</span><span class="mt-0.5 block text-[11px] font-normal leading-4 text-neutral-500">{{ $resourceLink['description'] }}</span></span>
                                    <span class="shrink-0 text-brand-600 transition-transform group-hover/item:translate-x-1" aria-hidden="true">→</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex items-center gap-2">
            @auth
                <div class="relative hidden sm:block" data-dropdown>
                    <button type="button" data-dropdown-toggle aria-expanded="false" aria-haspopup="true" aria-label="Menu akun"
                        class="flex items-center gap-2 rounded-full py-1 pl-1 pr-2.5 text-navy-950 ring-1 ring-neutral-300 transition hover:bg-neutral-100">
                        <span class="flex h-8 w-8 items-center justify-center overflow-hidden rounded-full bg-neutral-900 text-[11px] font-bold text-white ring-1 ring-neutral-900">
                            @if (auth()->user()->avatarUrl())
                                <img src="{{ auth()->user()->avatarUrl() }}" alt="" class="h-full w-full object-cover">
                            @else
                                {{ auth()->user()->initials() }}
                            @endif
                        </span>
                        <x-admin.icon name="chevron-down" class="h-3.5 w-3.5 text-navy-950" />
                    </button>

                    <div data-dropdown-menu class="absolute right-0 z-50 mt-2 hidden w-64 overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-xl shadow-navy-950/10">
                        <div class="border-b border-neutral-100 px-4 py-3">
                            <p class="truncate text-sm font-bold text-navy-950">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-neutral-500">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="p-1.5">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-navy-950 transition hover:bg-neutral-50">
                                <x-admin.icon name="dashboard" class="h-4 w-4 text-neutral-400" /> Dashboard
                            </a>
                            <button type="button" data-chat-toggle class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-semibold text-navy-950 transition hover:bg-neutral-50">
                                <x-admin.icon name="message" class="h-4 w-4 text-neutral-400" /> Pesan Saya
                            </button>
                            <a href="{{ route('kontak') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-navy-950 transition hover:bg-neutral-50">
                                <x-admin.icon name="inbox" class="h-4 w-4 text-neutral-400" /> Hubungi Kami
                            </a>
                        </div>
                        <form method="post" action="{{ route('logout') }}" class="border-t border-neutral-100 p-1.5">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-semibold text-red-600 transition hover:bg-red-50">
                                <x-admin.icon name="logout" class="h-4 w-4" /> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
            @guest
                <a href="{{ route('login') }}" class="hidden px-3 py-2.5 text-sm font-semibold text-neutral-600 transition hover:text-navy-950 sm:inline">Masuk</a>
                <a href="{{ route('register') }}" class="hidden items-center gap-2 rounded-full bg-brand-600 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-brand-500 sm:inline-flex">Daftar</a>
            @endguest
            <a href="{{ route('kontak') }}" class="group hidden items-center gap-2 rounded-full px-6 py-2.5 text-sm font-bold text-navy-800 ring-1 ring-neutral-300 transition hover:bg-brand-50 hover:ring-brand-300 md:inline-flex">
                Hubungi Kami

                <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
            <button type="button" data-toggle aria-expanded="false" aria-controls="drawer" aria-label="Buka menu" class="inline-flex h-10 w-10 items-center justify-center rounded-full text-navy-800 hover:bg-neutral-100 lg:hidden">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        </div>
    </div>
    <div id="solution-explorer" data-solution-menu data-open="false" class="pointer-events-none invisible absolute left-1/2 top-[calc(100%+8px)] z-50 hidden w-[calc(100vw-2rem)] max-w-[900px] -translate-x-1/2 -translate-y-1 rounded-2xl border border-neutral-200 bg-white opacity-0 shadow-[0_18px_42px_-22px_rgba(5,24,41,0.3)] transition-all duration-200 data-[open=true]:pointer-events-auto data-[open=true]:visible data-[open=true]:translate-y-0 data-[open=true]:opacity-100 lg:block" aria-label="Solution Explorer">
        <div class="p-5">
            <div class="flex items-end justify-between gap-6 border-b border-neutral-200 pb-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-brand-700">Solution Explorer</p>
                    <h2 class="mt-1 text-xl font-bold tracking-tight text-navy-950">Solusi</h2>
                    <p class="mt-1 text-xs font-normal text-neutral-500">Temukan solusi digital yang sesuai dengan kebutuhan bisnis Anda.</p>
                </div>
                <a href="{{ route('solusi.index') }}" class="shrink-0 text-xs font-bold text-brand-700 transition hover:text-navy-950">Lihat semua solusi
                    1
                </a>
            </div>

            <div class="grid grid-cols-[minmax(0,0.36fr)_minmax(0,0.64fr)] gap-8 py-5">
                <div>
                    <p class="mb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-500">Berdasarkan kebutuhan</p>
                    <div role="tablist" aria-label="Pilih kebutuhan bisnis" aria-orientation="vertical" class="space-y-0.5">
                        @foreach ($explorerNeeds as $need)
                            <button type="button" role="tab" id="solution-need-{{ $loop->index }}" data-explorer-need="{{ $loop->index }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-controls="solution-list-{{ $loop->index }}" tabindex="{{ $loop->first ? '0' : '-1' }}" class="group flex w-full items-start gap-3 border-l-2 border-transparent py-2 pl-3 pr-2 text-left transition-colors duration-150 hover:border-brand-300 hover:bg-neutral-50/60 aria-selected:border-brand-600 aria-selected:bg-brand-50/60">
                                <span class="min-w-0 flex-1">
                                    <span class="block text-[13px] font-bold text-navy-900 group-hover:text-brand-700">{{ $need['title'] }}</span>
                                    <span class="mt-0.5 block text-[11px] font-normal leading-4 text-neutral-500">{{ $need['description'] }}</span>
                                </span>
                                <span class="pt-1 text-neutral-300 transition-colors group-hover:text-brand-600" aria-hidden="true">→</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="min-h-[250px]">
                    <p class="mb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-neutral-500">Solusi digital</p>
                    @foreach ($explorerNeeds as $need)
                        <div id="solution-list-{{ $loop->index }}" role="tabpanel" aria-labelledby="solution-need-{{ $loop->index }}" data-explorer-panel="{{ $loop->index }}" @if (! $loop->first) hidden @endif class="grid grid-cols-2 gap-x-6">
                            @foreach ($need['solutions'] as $key)
                                <a href="{{ $explorerUrl($key) }}" class="group flex min-h-[72px] items-start gap-3 border-b border-neutral-100 py-3 text-left transition-colors hover:border-brand-300">
                                    <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-neutral-100 text-brand-700 transition-colors group-hover:bg-brand-50" aria-hidden="true">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14m-6-6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </span>
                                    <span class="min-w-0">
                                        <span class="block text-[13px] font-bold text-navy-950 transition-colors group-hover:text-brand-700">{{ $explorerSolutions[$key]['title'] }}</span>
                                        <span class="mt-1 block text-[11px] font-normal leading-4 text-neutral-500">{{ $explorerSolutions[$key]['description'] }}</span>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-between gap-6 rounded-lg bg-navy-950 px-5 py-3 text-white">
                <div>
                    <p class="text-sm font-bold">Konsultasikan Proyek Anda</p>
                    <p class="mt-0.5 text-xs font-normal text-white/65">Ceritakan kebutuhan digital bisnis Anda kepada tim kami.</p>
                </div>
                <a href="{{ route('kontak') }}#proyek" class="shrink-0 rounded-md bg-white px-4 py-2.5 text-xs font-bold text-navy-950 transition hover:bg-brand-50">Mulai Konsultasi <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </div>
    <div id="drawer" data-drawer class="hidden max-h-[calc(100dvh-4rem)] overflow-y-auto overscroll-contain border-t border-neutral-200 bg-white/95 text-navy-950 backdrop-blur lg:hidden">
        <nav class="space-y-1 px-4 py-4 text-base font-semibold text-neutral-700" aria-label="Navigasi seluler">
            <a href="{{ url('/') }}" class="block rounded-xl px-3 py-2.5 hover:bg-neutral-100">Beranda</a>
            <div class="px-3 pb-2 pt-3">
                <div class="flex items-center justify-between gap-3">
                    <span class="font-bold text-navy-950">Solusi</span>
                    <a href="{{ route('solusi.index') }}" class="text-xs font-bold text-brand-700">Lihat semua</a>
                </div>
                <p class="mt-1 text-xs font-normal leading-5 text-neutral-500">Temukan solusi digital yang sesuai dengan kebutuhan bisnis Anda.</p>
            </div>
            <div class="divide-y divide-neutral-100 px-3">
                @foreach ($explorerNeeds as $need)
                    <details class="group py-1">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 py-2.5 text-left text-navy-950 [&::-webkit-details-marker]:hidden">
                            <span>
                                <span class="block text-sm font-bold">{{ $need['title'] }}</span>
                                <span class="mt-0.5 block text-xs font-normal text-neutral-500">{{ $need['description'] }}</span>
                            </span>
                            <svg class="h-4 w-4 shrink-0 text-brand-700 transition-transform group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        </summary>
                        <div class="pb-3 pl-3">
                            <p class="pb-1 text-[10px] font-bold uppercase tracking-[0.15em] text-neutral-400">Solusi digital</p>
                            @foreach ($need['solutions'] as $key)
                                <a href="{{ $explorerUrl($key) }}" class="block py-2 text-xs font-semibold text-navy-800 hover:text-brand-700">{{ $explorerSolutions[$key]['title'] }} <span aria-hidden="true">↗</span></a>
                            @endforeach
                        </div>
                    </details>
                @endforeach
            </div>
            <div class="mx-3 mt-4 rounded-lg bg-navy-950 p-4 text-white">
                <p class="text-sm font-bold">Konsultasikan Proyek Anda</p>
                <p class="mt-1 text-xs font-normal text-white/70">Ceritakan kebutuhan digital bisnis Anda kepada tim kami.</p>
                <a href="{{ route('kontak') }}#proyek" class="mt-3 inline-flex rounded-md bg-white px-3 py-2 text-xs font-bold text-navy-950">Mulai Konsultasi <span class="ml-1" aria-hidden="true">→</span></a>
            </div>
            <a href="{{ route('portfolio.index') }}" class="block rounded-xl px-3 py-2.5 hover:bg-neutral-100">Portfolio</a>
            <div class="mx-3 mt-2 border-t border-neutral-100">
                <details class="group border-b border-neutral-100">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-3 py-3 text-base font-bold text-navy-950 [&::-webkit-details-marker]:hidden">
                        Perusahaan
                        <svg class="h-4 w-4 text-neutral-500 transition-transform group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                    </summary>
                    <div class="space-y-1 pb-3">
                        <a href="{{ route('tentang') }}" class="block rounded-lg px-3 py-2.5 text-[13px] font-semibold text-navy-800 hover:bg-neutral-50">Tentang Kami</a>
                        <a href="{{ route('karier.index') }}" class="block rounded-lg px-3 py-2.5 text-[13px] font-semibold text-navy-800 hover:bg-neutral-50">Karier</a>
                    </div>
                </details>
                <details class="group border-b border-neutral-100">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-3 py-3 text-base font-bold text-navy-950 [&::-webkit-details-marker]:hidden">
                        Resources
                        <svg class="h-4 w-4 text-neutral-500 transition-transform group-open:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                    </summary>
                    <div class="space-y-1 pb-3">
                        <a href="{{ route('blog.index') }}" class="block rounded-lg px-3 py-2.5 text-[13px] font-semibold text-navy-800 hover:bg-neutral-50">Blog</a>
                        @foreach (\App\Enums\ResourceType::cases() as $resourceType)
                            <a href="{{ route('resources.type', $resourceType->value) }}" class="block rounded-lg px-3 py-2.5 text-[13px] font-semibold text-navy-800 hover:bg-neutral-50">{{ $resourceType->label() }}</a>
                        @endforeach
                    </div>
                </details>
            </div>

            @auth
                <div class="mt-3 flex items-center gap-3 rounded-xl bg-neutral-50 px-3 py-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-neutral-900 text-xs font-bold text-white ring-1 ring-neutral-900">
                        @if (auth()->user()->avatarUrl())
                            <img src="{{ auth()->user()->avatarUrl() }}" alt="" class="h-full w-full object-cover">
                        @else
                            {{ auth()->user()->initials() }}
                        @endif
                    </span>
                    <span class="min-w-0">
                        <span class="block truncate text-sm font-bold text-navy-950">{{ auth()->user()->name }}</span>
                        <span class="block truncate text-xs text-neutral-500">{{ auth()->user()->email }}</span>
                    </span>
                </div>
                <a href="{{ route('dashboard') }}" class="mt-1 block rounded-xl px-3 py-2.5 hover:bg-neutral-100">Dashboard</a>
                <button type="button" data-chat-toggle class="block w-full rounded-xl px-3 py-2.5 text-left hover:bg-neutral-100">Pesan Saya</button>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full rounded-xl px-3 py-2.5 text-left font-semibold text-red-600 hover:bg-red-50">Keluar</button>
                </form>
            @endauth
            @guest
                <a href="{{ route('login') }}" class="block rounded-xl px-3 py-2.5 hover:bg-neutral-100">Masuk</a>
                <a href="{{ route('register') }}" class="block rounded-xl px-3 py-2.5 hover:bg-neutral-100">Daftar</a>
            @endguest
            <a href="{{ route('kontak') }}" class="mt-2 flex items-center justify-center gap-2 rounded-full bg-navy-950 px-3 py-2.5 font-bold text-white transition hover:bg-navy-800">Mulai Proyek</a>

        </nav>
    </div>
</header>

<main id="main" class="{{ $isHome ? '' : ($showBar ? 'pt-[112px]' : 'pt-[72px]') }}">
    @yield('content')
</main>

{{-- ============ FOOTER ============ --}}
<footer class="relative overflow-hidden bg-navy-950 pb-10 pt-16 text-sm text-neutral-400">
    <div class="relative w-full px-4 sm:px-4 lg:px-4">
        <div class="grid gap-x-10 gap-y-12 pb-14 md:grid-cols-2 lg:grid-cols-[1.35fr_repeat(4,minmax(0,1fr))]">
            <div class="pl-4 sm:pl-8 lg:pl-[6vw]">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-start gap-2 leading-tight">
                    <img src="{{ asset('img/logo-company-white-trimmed.png') }}" alt="KIT Konsultan IT" class="h-10 w-[210px] object-contain object-left">
                </a>
                <div class="mt-8">
                    <h3 class="text-xs font-bold uppercase tracking-[0.18em] text-white">Hubungi Kami</h3>
                    <ul class="mt-4 space-y-3 text-[13px]">
                        <li><a href="mailto:halo@nusakode.id" class="transition hover:text-white">halo@nusakode.id</a></li>
                        <li><a href="tel:+622150001234" class="transition hover:text-white">+62 21 5000 1234</a></li>
                    </ul>
                </div>
                <div class="mt-7">
                    <h3 class="text-xs font-bold uppercase tracking-[0.18em] text-white">Kantor</h3>
                    <p class="mt-3 max-w-xs text-[13px] leading-relaxed">Jakarta Selatan, Indonesia</p>
                </div>
            </div>
            <nav aria-label="Tentang KIT Konsultan IT">
                <h3 class="text-xs font-bold uppercase tracking-[0.18em] text-white">Seputar KIT Konsultan IT</h3>
                <ul class="mt-5 space-y-3 text-[13px]">
                    <li><a href="{{ route('tentang') }}" class="transition hover:text-white">Tentang Kami</a></li>
                    <li><a href="{{ route('karier.index') }}" class="transition hover:text-white">Karier</a></li>
                    <li><a href="{{ route('portfolio.index') }}" class="transition hover:text-white">Portfolio</a></li>
                </ul>
            </nav>
            <nav aria-label="Solusi">
                <h3 class="text-xs font-bold uppercase tracking-[0.18em] text-white">Solusi</h3>
                <ul class="mt-5 space-y-3 text-[13px]">
                    @forelse (($navServices ?? collect())->take(4) as $navService)
                        <li><a href="{{ route('solusi.show', $navService->slug) }}" class="transition hover:text-white">{{ $navService->title }}</a></li>
                    @empty
                        <li><a href="{{ route('solusi.index') }}" class="transition hover:text-white">Semua Solusi</a></li>
                    @endforelse
                    <li><a href="{{ route('solusi.index') }}" class="transition hover:text-white">Semua Solusi</a></li>
                </ul>
            </nav>
            <nav aria-label="Informasi dan bantuan">
                <h3 class="text-xs font-bold uppercase tracking-[0.18em] text-white">Informasi &amp; Bantuan</h3>
                <ul class="mt-5 space-y-3 text-[13px]">
                    <li><a href="{{ route('kontak') }}" class="transition hover:text-white">Kontak</a></li>
                    <li><a href="{{ route('kontak') }}#proyek" class="transition hover:text-white">Minta Penawaran</a></li>
                    <li><a href="https://wa.me/622150001234" target="_blank" rel="noopener" class="transition hover:text-white">WhatsApp</a></li>
                </ul>
            </nav>
            <nav aria-label="Informasi">
                <h3 class="text-xs font-bold uppercase tracking-[0.18em] text-white">Informasi</h3>
                <ul class="mt-5 space-y-3 text-[13px]">
                    <li><a href="{{ route('blog.index') }}" class="transition hover:text-white">Blog</a></li>
                    @foreach(\App\Enums\ResourceType::cases() as $resourceType)
                        <li><a href="{{ route('resources.type', $resourceType->value) }}" class="transition hover:text-white">{{ $resourceType->label() }}</a></li>
                    @endforeach
                </ul>
            </nav>
        </div>
        <div class="grid gap-3 border-t border-white/10 pt-6 text-xs text-neutral-500 sm:grid-cols-3 sm:items-center">
            <span class="hidden sm:block" aria-hidden="true"></span>
            <p class="text-left sm:text-center">&copy; {{ date('Y') }} KIT Konsultan IT. Seluruh hak cipta dilindungi.</p>
            <nav class="flex items-center gap-5 sm:justify-end" aria-label="Tautan legal">
                <a href="{{ url('/') }}" class="transition hover:text-white">Kebijakan Privasi</a>
                <a href="{{ url('/') }}" class="transition hover:text-white">Syarat &amp; Ketentuan</a>
            </nav>
        </div>
    </div>

</footer>

{{-- Ganti nomor di bawah dengan nomor WhatsApp admin (format: kode negara + nomor, tanpa +/spasi) --}}
<a href="https://wa.me/622150001234?text=Halo%20KIT%20Konsultan%20IT%2C%20saya%20ingin%20bertanya." target="_blank" rel="noopener" aria-label="Chat via WhatsApp"
    class="group fixed bottom-10 right-10 z-50 flex h-16 w-16 items-center justify-center rounded-full bg-[#25D366] text-white shadow-xl shadow-emerald-900/25 transition duration-300 hover:scale-105 hover:bg-[#1faa53]">
    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#25D366] opacity-20" aria-hidden="true"></span>
    <svg class="relative h-9 w-9" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
</a>

{{-- Widget pesan live (bubble kiri bawah) untuk semua pengguna yang masuk. --}}
<x-chat-widget :unread="$chatUnread ?? 0" />

@if ($showCookieNotice)
    <x-cookie-notice :announcement="$cookieNotice" />
@endif

@if ($showPopup)
    <x-announcement-popup :announcement="$activePopup" />
@endif

</body>
</html>
