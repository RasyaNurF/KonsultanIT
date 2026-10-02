@extends('layouts.site')

@section('title', 'Portofolio — KIT Konsultan IT')
@section('meta-description', 'Jelajahi portofolio proyek digital KIT Konsultan IT untuk berbagai kebutuhan bisnis dan industri.')

@section('content')
    <section class="relative isolate overflow-hidden bg-[#f8fafc]">
        <img src="{{ asset('img/KiT Konsultan IT Hero Mockup.png') }}" alt="" class="pointer-events-none absolute bottom-0 right-0 -z-10 h-[250px] w-full object-cover object-right lg:inset-0 lg:h-full lg:object-[center_20%]" loading="eager" fetchpriority="high">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-gradient-to-b from-[#f8fafc] via-[#f8fafc] to-transparent lg:bg-gradient-to-r lg:from-white/20 lg:via-transparent lg:to-transparent" aria-hidden="true"></div>

        <div class="mx-auto flex min-h-[650px] max-w-6xl items-start px-4 pt-14 sm:px-6 lg:min-h-[540px] lg:items-center lg:py-14" data-no-reveal>
            <div class="max-w-xl">
                <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-brand-700">Portofolio</p>
                <h1 class="mt-4 max-w-xl text-4xl font-extrabold leading-[1.1] tracking-tight text-balance text-navy-950 sm:text-[2.75rem]">Karya Digital untuk Berbagai Industri</h1>
                <p class="mt-5 max-w-lg text-sm leading-6 text-neutral-700">Jelajahi proyek digital yang kami kerjakan untuk beragam kebutuhan bisnis. Setiap solusi dirancang dengan memperhatikan pengguna, proses kerja, dan tujuan proyek.</p>
            </div>
        </div>
    </section>

    <section class="bg-white pb-20 pt-9 sm:pb-24 sm:pt-11">
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            @if ($categories->isNotEmpty())
                <nav aria-label="Filter kategori portofolio" class="-mx-4 overflow-x-auto px-4 sm:mx-0 sm:px-0">
                    <div class="flex w-max min-w-full gap-2 pb-2">
                        <a href="{{ route('portfolio.index') }}" @if (! $activeCategory) aria-current="page" @endif class="rounded-full px-5 py-2.5 text-xs font-bold transition {{ $activeCategory ? 'bg-neutral-50 text-navy-800 hover:bg-brand-50 hover:text-brand-700' : 'bg-navy-950 text-white' }}">Semua</a>
                        @foreach ($categories as $category)
                            <a href="{{ route('portfolio.index', ['kategori' => $category]) }}" @if ($activeCategory === $category) aria-current="page" @endif class="rounded-full px-5 py-2.5 text-xs font-bold transition {{ $activeCategory === $category ? 'bg-navy-950 text-white' : 'bg-neutral-50 text-navy-800 hover:bg-brand-50 hover:text-brand-700' }}">{{ $category }}</a>
                        @endforeach
                    </div>
                </nav>
            @endif

            @if ($portfolios->isEmpty())
                <div class="mt-7 rounded-xl border border-neutral-200 bg-neutral-50 px-6 py-16 text-center sm:px-10">
                    <h2 class="text-xl font-bold text-navy-950">Belum ada proyek di kategori ini.</h2>
                    <p class="mt-2 text-sm text-neutral-600">Pilih kategori lain untuk melihat portofolio kami.</p>
                    <a href="{{ route('portfolio.index') }}" class="mt-6 inline-flex rounded-full bg-navy-950 px-6 py-3 text-sm font-bold text-white transition hover:bg-navy-800">Lihat semua proyek</a>
                </div>
            @else
                <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($portfolios as $portfolio)
                        @php($thumbnail = $portfolio->thumbnail_path ? (str_starts_with($portfolio->thumbnail_path, 'img/') ? asset($portfolio->thumbnail_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($portfolio->thumbnail_path)) : null)
                        <article class="min-w-0">
                            <a href="{{ route('portfolio.show', $portfolio->slug) }}" class="group flex h-full flex-col overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-[0_8px_24px_-20px_rgba(5,24,41,0.35)] transition hover:-translate-y-0.5 hover:border-brand-200 hover:shadow-[0_18px_32px_-22px_rgba(5,24,41,0.3)] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
                                <div class="relative aspect-[2.55/1] overflow-hidden bg-[#edf2f7]">
                                    @if ($thumbnail)
                                        <img src="{{ $thumbnail }}" alt="Visual proyek {{ $portfolio->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]" loading="lazy">
                                    @else
                                        <div class="flex h-full items-end p-6 text-xl font-bold text-navy-800">{{ $portfolio->title }}</div>
                                    @endif
                                    <span class="absolute left-4 top-4 max-w-[calc(100%-2rem)] truncate rounded-full bg-white px-3 py-1.5 text-[11px] font-semibold text-navy-950 shadow-sm">{{ $portfolio->category ?: $portfolio->client?->industry ?: 'Proyek Digital' }}</span>
                                </div>

                                <div class="flex flex-1 flex-col p-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <h2 class="text-sm font-bold leading-snug text-navy-950 transition group-hover:text-brand-700">{{ $portfolio->title }}</h2>
                                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-brand-600 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                    <p class="mt-2 min-h-10 text-xs leading-5 text-neutral-600">{{ $portfolio->description ? \Illuminate\Support\Str::limit($portfolio->description, 120) : ($portfolio->client ? 'Proyek digital untuk '.$portfolio->client->name.'.' : 'Lihat detail proyek dan ruang lingkup pekerjaannya.') }}</p>
                                    <div class="mt-4 flex items-center justify-between gap-4 border-t border-neutral-100 pt-3 text-[11px] text-neutral-500">
                                        <span class="flex min-w-0 items-center gap-2"><svg class="h-4 w-4 shrink-0 text-navy-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 20V7l8-4 8 4v13M3 20h18M9 20v-6h6v6M8 9h.01M12 9h.01M16 9h.01" stroke-linecap="round" stroke-linejoin="round"/></svg><span class="truncate">{{ $portfolio->client?->name ?: $portfolio->category ?: 'Proyek Digital' }}</span></span>
                                        @if ($portfolio->year)
                                            <span class="shrink-0 tabular-nums">{{ $portfolio->year }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                @if ($portfolios->hasPages())
                    <nav aria-label="Halaman portofolio" class="mt-9 flex items-center justify-end gap-2 text-sm">
                        <span class="mr-2 text-xs font-medium text-neutral-500">Halaman {{ $portfolios->currentPage() }} dari {{ $portfolios->lastPage() }}</span>
                        @if ($portfolios->onFirstPage())
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg border border-neutral-200 text-neutral-300" aria-hidden="true">←</span>
                        @else
                            <a href="{{ $portfolios->previousPageUrl() }}" aria-label="Halaman sebelumnya" class="flex h-9 w-9 items-center justify-center rounded-lg border border-neutral-200 text-navy-950 transition hover:border-brand-300 hover:text-brand-700">←</a>
                        @endif
                        @if ($portfolios->hasMorePages())
                            <a href="{{ $portfolios->nextPageUrl() }}" aria-label="Halaman berikutnya" class="flex h-9 w-9 items-center justify-center rounded-lg border border-neutral-200 text-navy-950 transition hover:border-brand-300 hover:text-brand-700">→</a>
                        @else
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg border border-neutral-200 text-neutral-300" aria-hidden="true">→</span>
                        @endif
                    </nav>
                @endif
            @endif

            <div class="mt-12 grid gap-6 rounded-xl bg-[#eaf2ff] px-6 py-7 sm:px-8 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.1fr)_auto] lg:items-center lg:gap-10">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.17em] text-brand-700">Punya ide proyek?</p>
                    <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-navy-950 sm:text-3xl">Mari wujudkan bersama.</h2>
                </div>
                <p class="max-w-lg text-sm leading-7 text-navy-800/80">Diskusikan kebutuhan digital Anda dengan tim kami. Kami bantu menyusun langkah dari perencanaan hingga pengembangan.</p>
                <a href="{{ route('kontak') }}#proyek" class="inline-flex w-fit items-center justify-center gap-2 rounded-lg bg-navy-950 px-6 py-3.5 text-sm font-bold text-white transition hover:bg-navy-800">Hubungi Kami <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
            </div>
        </div>
    </section>
@endsection
