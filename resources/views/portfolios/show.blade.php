@extends('layouts.site')

@section('title', $portfolio->title.' — KIT Konsultan IT')
@section('meta-description', \Illuminate\Support\Str::limit($portfolio->description ?? $portfolio->title, 160))

@section('content')
    @php
        $thumbnail = $portfolio->thumbnail_path
            ? (str_starts_with($portfolio->thumbnail_path, 'img/') ? asset($portfolio->thumbnail_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($portfolio->thumbnail_path))
            : null;
        $technologies = $portfolio->technologyList();
        $storySections = collect([
            ['title' => 'Tantangan', 'content' => $portfolio->challenge],
            ['title' => 'Solusi', 'content' => $portfolio->solution],
            ['title' => 'Hasil', 'content' => $portfolio->result],
        ])->filter(fn (array $section) => filled($section['content']))->values();
    @endphp

    <section class="bg-[#f6f5f1]">
        <div class="mx-auto max-w-7xl px-4 pb-12 pt-10 sm:px-6 sm:pb-16 sm:pt-14" data-no-reveal>
            <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-2 text-xs font-semibold text-neutral-500">
                <a href="{{ route('portfolio.index') }}" class="transition hover:text-brand-700">Portofolio</a>
                <span class="text-neutral-300" aria-hidden="true">/</span>
                <span class="text-navy-950" aria-current="page">{{ $portfolio->title }}</span>
            </nav>

            <div class="mt-12 max-w-5xl sm:mt-16">
                <p class="flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.2em] text-brand-700"><span class="h-px w-9 bg-brand-600" aria-hidden="true"></span>{{ $portfolio->category ?: 'Studi kasus' }}</p>
                <h1 class="mt-6 max-w-4xl text-4xl font-extrabold leading-[1.07] tracking-tight text-balance text-navy-950 sm:text-5xl xl:text-6xl">{{ $portfolio->title }}</h1>
                @if ($portfolio->description)
                    <p class="mt-7 max-w-2xl text-base leading-8 text-neutral-600 sm:text-lg">{{ $portfolio->description }}</p>
                @endif
            </div>
        </div>
    </section>

    @if ($thumbnail)
        <figure class="bg-white" aria-label="Visual proyek {{ $portfolio->title }}" data-no-reveal>
            <img src="{{ $thumbnail }}" alt="Visual proyek {{ $portfolio->title }}" class="block h-auto w-full" loading="eager" fetchpriority="high">
        </figure>
    @endif

    @if ($portfolio->client || $portfolio->year || $portfolio->url)
        <section class="border-b border-neutral-200 bg-white" aria-label="Informasi proyek">
            <dl class="mx-auto grid max-w-7xl gap-8 px-4 py-9 sm:grid-cols-2 sm:px-6 lg:grid-cols-3 lg:gap-12">
                @if ($portfolio->client)
                    <div>
                        <dt class="text-[11px] font-bold uppercase tracking-[0.16em] text-neutral-500">Klien</dt>
                        <dd class="mt-2 text-base font-bold text-navy-950">{{ $portfolio->client->name }}</dd>
                    </div>
                @endif
                @if ($portfolio->year)
                    <div>
                        <dt class="text-[11px] font-bold uppercase tracking-[0.16em] text-neutral-500">Tahun proyek</dt>
                        <dd class="mt-2 text-base font-bold tabular-nums text-navy-950">{{ $portfolio->year }}</dd>
                    </div>
                @endif
                @if ($portfolio->url)
                    <div>
                        <dt class="text-[11px] font-bold uppercase tracking-[0.16em] text-neutral-500">Lihat langsung</dt>
                        <dd class="mt-2"><a href="{{ $portfolio->url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-base font-bold text-brand-700 transition hover:text-brand-800">Kunjungi situs <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M7 17 17 7M8 7h9v9" stroke-linecap="round" stroke-linejoin="round"/></svg></a></dd>
                    </div>
                @endif
            </dl>
        </section>
    @endif

    @if ($storySections->isNotEmpty())
        <section class="bg-[#f7f9fc] py-20 sm:py-28" aria-labelledby="story-heading">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[minmax(0,0.38fr)_minmax(0,0.62fr)] lg:gap-20" data-no-reveal>
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-brand-700">Cerita proyek</p>
                    <h2 id="story-heading" class="mt-4 max-w-md text-3xl font-extrabold leading-tight tracking-tight text-balance text-navy-950 sm:text-4xl">Dari kebutuhan sampai hasil.</h2>
                </div>
                <ol class="border-t border-neutral-300">
                    @foreach ($storySections as $section)
                        <li class="grid gap-3 border-b border-neutral-300 py-7 sm:grid-cols-[48px_minmax(0,1fr)] sm:gap-6">
                            <span class="pt-1 text-xs font-bold tabular-nums text-brand-700">0{{ $loop->iteration }}</span>
                            <div>
                                <h3 class="text-lg font-bold text-navy-950">{{ $section['title'] }}</h3>
                                <p class="mt-3 max-w-2xl whitespace-pre-line text-[15px] leading-8 text-neutral-600">{{ $section['content'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    @if ($technologies)
        <section class="border-b border-neutral-200 bg-white py-12 sm:py-16" aria-labelledby="technology-heading">
            <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[minmax(0,0.38fr)_minmax(0,0.62fr)] lg:gap-20" data-no-reveal>
                <h2 id="technology-heading" class="text-sm font-bold text-navy-950">Teknologi yang digunakan</h2>
                <ul class="flex flex-wrap gap-2">
                    @foreach ($technologies as $technology)
                        <li class="rounded-md border border-neutral-200 px-3.5 py-2 text-sm font-semibold text-navy-800">{{ $technology }}</li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    @if ($portfolio->images->isNotEmpty())
        <section class="bg-white py-20 sm:py-28" aria-labelledby="gallery-heading">
            <div class="mx-auto max-w-7xl px-4 sm:px-6" data-no-reveal>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-brand-700">Galeri proyek</p>
                <h2 id="gallery-heading" class="mt-4 text-3xl font-extrabold tracking-tight text-navy-950 sm:text-4xl">Tampilan proyek.</h2>
                <div class="mt-10 grid gap-8 sm:grid-cols-2">
                    @foreach ($portfolio->images as $image)
                        <figure>
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image->image_path) }}" alt="{{ $image->caption ?: 'Galeri '.$portfolio->title }}" class="aspect-[4/3] w-full rounded-xl bg-neutral-100 object-cover" loading="lazy">
                            @if ($image->caption)
                                <figcaption class="mt-3 text-sm leading-6 text-neutral-500">{{ $image->caption }}</figcaption>
                            @endif
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($related->isNotEmpty())
        <section class="border-t border-neutral-200 bg-white py-20 sm:py-28" aria-labelledby="related-heading">
            <div class="mx-auto max-w-7xl px-4 sm:px-6" data-no-reveal>
                <div class="flex flex-wrap items-end justify-between gap-5">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-brand-700">Lanjut menjelajah</p>
                        <h2 id="related-heading" class="mt-4 text-3xl font-extrabold tracking-tight text-navy-950 sm:text-4xl">Proyek lainnya.</h2>
                    </div>
                    <a href="{{ route('portfolio.index') }}" class="text-sm font-bold text-navy-800 transition hover:text-brand-700">Semua portofolio</a>
                </div>
                <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        @php($relatedThumbnail = $item->thumbnail_path ? (str_starts_with($item->thumbnail_path, 'img/') ? asset($item->thumbnail_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($item->thumbnail_path)) : null)
                        <a href="{{ route('portfolio.show', $item->slug) }}" class="group block">
                            @if ($relatedThumbnail)
                                <span class="block overflow-hidden rounded-lg bg-neutral-100">
                                    <img src="{{ $relatedThumbnail }}" alt="Visual proyek {{ $item->title }}" class="aspect-[4/3] w-full object-cover transition duration-300 group-hover:scale-[1.03]" loading="lazy">
                                </span>
                            @else
                                <span class="flex aspect-[4/3] items-end rounded-lg bg-navy-900 p-6 text-sm font-semibold text-white">{{ $item->category ?: 'Studi kasus' }}</span>
                            @endif
                            <span class="mt-5 block text-[11px] font-bold uppercase tracking-[0.16em] text-brand-700">{{ $item->category ?: 'Studi kasus' }}</span>
                            <span class="mt-2 block text-lg font-bold leading-snug text-navy-950 transition group-hover:text-brand-700">{{ $item->title }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="border-t border-neutral-200 bg-[#f7f9fc] py-16 sm:py-20">
        <div class="mx-auto flex max-w-7xl flex-wrap items-end justify-between gap-8 px-4 sm:px-6">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-brand-700">Punya proyek berikutnya?</p>
                <h2 class="mt-4 text-3xl font-extrabold leading-tight tracking-tight text-balance text-navy-950 sm:text-4xl">Mari bahas kebutuhan digital Anda.</h2>
                <p class="mt-4 text-[15px] leading-7 text-neutral-600">Ceritakan proses dan tujuan bisnis Anda. Kami bantu susun langkah pengembangannya.</p>
            </div>
            <a href="{{ route('kontak') }}#proyek" class="inline-flex items-center gap-3 rounded-lg bg-brand-600 px-7 py-3.5 text-sm font-bold text-white transition hover:bg-brand-700">Diskusikan proyek <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
        </div>
    </section>
@endsection
