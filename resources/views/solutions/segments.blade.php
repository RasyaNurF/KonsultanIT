@extends('layouts.site')

@section('title', 'Solusi per Segmen — KIT Konsultan IT')
@section('meta-description', 'Jelajahi solusi digital untuk perguruan tinggi, sekolah, dan industri. Sistem dirancang mengikuti kebutuhan dan alur kerja institusi Anda.')

@section('content')
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24">
            <div class="max-w-3xl">
                <p class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.24em] text-brand-700"><span class="h-px w-10 bg-brand-600" aria-hidden="true"></span>Solusi sesuai kebutuhan Anda</p>
                <h1 class="mt-6 text-4xl font-extrabold leading-[1.05] tracking-tight text-balance text-navy-950 sm:text-6xl">Teknologi yang mengikuti cara kerja institusi Anda.</h1>
                <p class="mt-6 max-w-2xl text-base leading-relaxed text-neutral-500">Setiap institusi punya tantangan berbeda. Pilih segmen Anda untuk melihat masalah yang bisa dibantu, pilihan solusi, dan contoh pekerjaan kami.</p>
            </div>

            <div class="mt-12 grid gap-4 md:grid-cols-3">
                @foreach ($segments as $segment)
                    <a href="#{{ $segment['id'] }}" class="group rounded-2xl border border-neutral-200 p-6 transition hover:-translate-y-1 hover:border-brand-300 hover:shadow-lg hover:shadow-navy-950/5 sm:p-8">
                        <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-brand-700">0{{ $loop->iteration }}</span>
                        <h2 class="mt-4 text-xl font-extrabold tracking-tight text-navy-950 transition group-hover:text-brand-700">{{ $segment['title'] }}</h2>
                        <p class="mt-2 text-sm leading-relaxed text-neutral-500">{{ $segment['summary'] }}</p>
                        <span class="mt-6 inline-flex items-center gap-2 text-sm font-bold text-navy-900">Lihat solusi
                            <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @foreach ($segments as $segment)
        <section id="{{ $segment['id'] }}" class="scroll-mt-24 border-t border-neutral-100 {{ $loop->even ? 'bg-neutral-50/70' : 'bg-white' }}">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24">
                <div class="max-w-3xl">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-700">Solusi untuk {{ $segment['title'] }}</p>
                    <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-balance text-navy-950 sm:text-4xl">{{ $segment['summary'] }}</h2>
                    <p class="mt-4 text-[15px] leading-relaxed text-neutral-500">{{ $segment['description'] }}</p>
                </div>

                <div class="mt-10 grid gap-5 lg:grid-cols-3">
                    <div class="rounded-2xl border border-neutral-200 bg-white p-6 sm:p-7">
                        <h3 class="text-sm font-extrabold uppercase tracking-[0.12em] text-neutral-400">Tantangan yang sering ditemui</h3>
                        <ul class="mt-5 space-y-4">
                            @foreach ($segment['challenges'] as $challenge)
                                <li class="flex gap-3 text-sm leading-relaxed text-neutral-600"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-500" aria-hidden="true"></span>{{ $challenge }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="rounded-2xl border border-neutral-200 bg-white p-6 sm:p-7">
                        <h3 class="text-sm font-extrabold uppercase tracking-[0.12em] text-neutral-400">Solusi yang dapat dibangun</h3>
                        <ul class="mt-5 space-y-4">
                            @foreach ($segment['solutions'] as $solution)
                                <li class="flex gap-3 text-sm leading-relaxed text-neutral-600"><span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-700" aria-hidden="true">
                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m5 12 4 4L19 6"/></svg>
                                </span>{{ $solution }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="rounded-2xl border border-neutral-200 bg-white p-6 sm:p-7">
                        <h3 class="text-sm font-extrabold uppercase tracking-[0.12em] text-neutral-400">Manfaat yang dituju</h3>
                        <ul class="mt-5 space-y-4">
                            @foreach ($segment['benefits'] as $benefit)
                                <li class="flex gap-3 text-sm leading-relaxed text-neutral-600"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-navy-900" aria-hidden="true"></span>{{ $benefit }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                @if ($segment['case_studies']->isNotEmpty())
                    <div class="mt-12">
                        <div class="flex flex-wrap items-end justify-between gap-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.18em] text-brand-700">Dari portfolio kami</p>
                                <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-navy-950">Contoh pekerjaan terkait</h3>
                            </div>
                            <a href="{{ route('portfolio.index') }}" class="text-sm font-bold text-navy-800 transition hover:text-brand-700">Lihat semua portfolio <span aria-hidden="true">→</span></a>
                        </div>
                        <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($segment['case_studies'] as $portfolio)
                                @php($portfolioImage = $portfolio->thumbnail_path ? (str_starts_with($portfolio->thumbnail_path, 'img/') ? asset($portfolio->thumbnail_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($portfolio->thumbnail_path)) : asset('img/work-1.jpg'))
                                <a href="{{ route('portfolio.show', $portfolio->slug) }}" class="group overflow-hidden rounded-2xl border border-neutral-200 bg-white transition hover:-translate-y-1 hover:shadow-lg hover:shadow-navy-950/5">
                                    <img src="{{ $portfolioImage }}" alt="{{ $portfolio->title }}" class="h-48 w-full object-cover" loading="lazy">
                                    <span class="block p-6">
                                        <span class="text-[11px] font-bold uppercase tracking-[0.16em] text-brand-700">{{ $portfolio->category ?: 'Portfolio' }}</span>
                                        <span class="mt-2 block text-lg font-extrabold text-navy-950 transition group-hover:text-brand-700">{{ $portfolio->title }}</span>
                                        @if ($portfolio->client)
                                            <span class="mt-1 block text-sm text-neutral-500">{{ $portfolio->client->name }}</span>
                                        @endif
                                        @if ($portfolio->description)
                                            <span class="mt-3 block text-sm leading-relaxed text-neutral-500">{{ \Illuminate\Support\Str::limit($portfolio->description, 120) }}</span>
                                        @endif
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-10 flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-navy-950 p-6 text-white sm:p-8">
                    <div>
                        <h3 class="text-xl font-extrabold">Punya kebutuhan untuk {{ strtolower($segment['title']) }}?</h3>
                        <p class="mt-2 text-sm text-neutral-300">Diskusikan tantangan dan proses yang ingin Anda benahi bersama tim kami.</p>
                    </div>
                    <a href="{{ route('kontak') }}" class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-brand-500">Diskusikan Kebutuhan
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                </div>
            </div>
        </section>
    @endforeach

    @if ($industries->isNotEmpty())
        <section class="border-t border-neutral-100 bg-neutral-50/70">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20">
                <div class="max-w-2xl">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-700">Industri</p>
                    <h2 class="mt-3 text-2xl font-extrabold tracking-tight text-navy-950 sm:text-3xl">Mengenal kebutuhan tiap sektor</h2>
                    <p class="mt-3 text-[15px] leading-relaxed text-neutral-500">Sistem dapat disesuaikan dengan proses dan kebutuhan tiap sektor.</p>
                </div>
                <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($industries as $industry)
                        <a href="{{ $industry->slug === 'pendidikan' ? '#perguruan-tinggi' : '#industri' }}" class="rounded-xl border border-neutral-200 bg-white p-5 transition hover:border-brand-300">
                            <h3 class="font-bold text-navy-950">{{ $industry->name }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-neutral-500">{{ \Illuminate\Support\Str::limit($industry->description, 110) }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($solutionCategories->isNotEmpty())
        <section class="border-t border-neutral-100 bg-neutral-50/70">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20">
                <div class="max-w-2xl">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-700">Solusi digital</p>
                    <h2 class="mt-3 text-2xl font-extrabold tracking-tight text-navy-950 sm:text-3xl">Jelajahi produk dan solusi terkelola</h2>
                    <p class="mt-3 text-[15px] leading-relaxed text-neutral-500">Pilihan solusi berdasarkan kebutuhan institusi dan bisnis Anda.</p>
                </div>
                <div class="mt-8 divide-y divide-neutral-200 border-y border-neutral-200">
                    @foreach ($solutionCategories as $category)
                        <div class="grid gap-3 py-6 md:grid-cols-[minmax(0,1fr)_minmax(0,2fr)] md:gap-8">
                            <div>
                                <a href="{{ route('solusi.category', $category->slug) }}" class="text-lg font-bold text-navy-950 transition hover:text-brand-700">{{ $category->name }} <span aria-hidden="true">↗</span></a>
                                @if ($category->tagline)
                                    <p class="mt-1 text-sm text-neutral-500">{{ $category->tagline }}</p>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-x-6 gap-y-2 md:items-center">
                                @foreach ($category->solutions as $solution)
                                    <a href="{{ route('solusi.solution', [$category->slug, $solution->slug]) }}" class="text-sm font-medium text-neutral-700 transition hover:text-brand-700">{{ $solution->title }} <span aria-hidden="true">→</span></a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="border-t border-neutral-100 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-700">Kemampuan tim</p>
                <h2 class="mt-3 text-2xl font-extrabold tracking-tight text-navy-950 sm:text-3xl">Solusi dibangun dari kebutuhan nyata</h2>
                <p class="mt-3 text-[15px] leading-relaxed text-neutral-500">Kami dapat menggabungkan beberapa kemampuan berikut agar sesuai dengan proses dan sistem yang sudah digunakan.</p>
            </div>
            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($services as $service)
                    <a href="{{ route('solusi.show', $service->slug) }}" class="group rounded-xl border border-neutral-200 p-5 transition hover:border-brand-300 hover:shadow-md">
                        <h3 class="font-bold text-navy-950 transition group-hover:text-brand-700">{{ $service->title }}</h3>
                        @if ($service->description)
                            <p class="mt-2 text-sm leading-relaxed text-neutral-500">{{ \Illuminate\Support\Str::limit($service->description, 130) }}</p>
                        @endif
                        <span class="mt-4 inline-flex items-center gap-2 text-xs font-bold text-navy-800">Pelajari solusi <span aria-hidden="true">→</span></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
