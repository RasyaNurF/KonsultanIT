@extends('layouts.site')

@section('title', 'Layanan — Nusakode')
@section('meta-description', 'Layanan Nusakode: pengembangan web & aplikasi, sistem informasi bisnis, integrasi API, aplikasi mobile, UI/UX, dan maintenance.')

@section('content')
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="max-w-3xl py-20 sm:py-28">
                <p class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.24em] text-navy-950"><span class="h-px w-10 bg-brand-600" aria-hidden="true"></span>Layanan</p>
                <h1 class="mt-6 text-4xl font-extrabold leading-[1.05] tracking-tight text-balance text-navy-950 sm:text-6xl">Dibangun dan dirawat langsung oleh tim kami.</h1>
                <p class="mt-6 max-w-xl text-base leading-relaxed text-neutral-500">Layanan adalah jasa yang dikerjakan engineer Nusakode — dari pengembangan aplikasi hingga pemeliharaan sistem. Butuh produk mitra seperti HRIS, CRM, cloud, atau ERP? Lihat katalog <a href="{{ route('solusi.index') }}" class="font-semibold text-brand-700 underline underline-offset-2 transition hover:text-brand-600">Solusi</a>.</p>
                <a href="{{ route('solusi.index') }}" class="group mt-8 inline-flex items-center gap-2 text-sm font-bold text-navy-950 transition hover:text-brand-700">Lihat produk mitra kami<svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
            </div>
        </div>
    </section>

    @if ($services->isEmpty())
        <section class="border-t border-neutral-100">
            <div class="mx-auto max-w-3xl px-4 py-20 text-center sm:px-6 sm:py-28">
                <p class="text-lg font-bold text-navy-950">Daftar layanan segera hadir.</p>
                <p class="mt-2 text-sm text-neutral-500">Silakan hubungi kami untuk informasi layanan.</p>
                <a href="{{ route('kontak') }}" class="mt-8 inline-flex items-center gap-2 rounded-full bg-brand-600 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-brand-500">Hubungi Kami</a>
            </div>
        </section>
    @else
        <section class="border-t border-neutral-100">
            <div data-no-reveal>
                @foreach ($services as $service)
                    <div class="grid items-stretch lg:grid-cols-2 {{ $loop->first ? 'border-t border-neutral-100' : 'border-t border-neutral-100' }}">
                        <div class="flex items-center {{ $loop->even ? 'lg:order-2' : '' }}">
                            <div class="w-full px-4 py-12 sm:px-6 lg:py-20 {{ $loop->even ? 'lg:pl-12 lg:pr-[max(1.5rem,calc((100vw-80rem)/2+1.5rem))]' : 'lg:pl-[max(1.5rem,calc((100vw-80rem)/2+1.5rem))] lg:pr-12' }}">
                                <p class="flex items-center gap-3 text-sm font-extrabold tabular-nums text-neutral-400"><span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span class="h-px w-10 bg-neutral-300" aria-hidden="true"></span></p>
                                <h2 class="mt-4 text-xl font-extrabold tracking-tight text-navy-950 sm:text-2xl">{{ $service->title }}</h2>
                                @if ($service->description)
                                    <p class="mt-3 max-w-md text-[15px] leading-relaxed text-neutral-500">{{ \Illuminate\Support\Str::limit($service->description, 220) }}</p>
                                @endif
                                @if ($service->technologyList())
                                    <p class="mt-4 text-[13px] font-medium text-neutral-400">{!! implode(' &nbsp;·&nbsp; ', array_map('e', $service->technologyList())) !!}</p>
                                @endif
                                <a href="{{ route('layanan.show', $service->slug) }}" class="group mt-8 inline-flex items-center gap-2 text-[13px] font-bold text-navy-950">
                                    Lihat detail layanan
                                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                                </a>
                            </div>
                        </div>
                        <div class="group relative min-h-72 overflow-hidden bg-neutral-100 lg:min-h-[420px]">
                            @if ($service->image_path)
                                <img src="{{ str_starts_with($service->image_path, 'img/') ? asset($service->image_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($service->image_path) }}" alt="{{ $service->title }}" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]" loading="lazy">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center bg-navy-950">
                                    <span class="text-4xl font-extrabold text-white/20">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    @if ($industries->isNotEmpty())
        <section class="border-t border-white/5 bg-navy-950 py-20 text-white sm:py-28" aria-label="Solusi per industri">
            <div class="mx-auto max-w-7xl px-4 sm:px-6">
                <div class="flex flex-wrap items-end justify-between gap-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-brand-400">Solusi per industri</p>
                        <h2 class="mt-4 max-w-lg text-3xl font-extrabold tracking-tight text-balance sm:text-4xl">Disesuaikan dengan cara kerja setiap sektor</h2>
                    </div>
                    <p class="max-w-sm text-[15px] leading-relaxed text-neutral-400">Satu tim, pola yang sama: pahami prosesnya dulu, bangun sistemnya kemudian.</p>
                </div>

                <div class="mt-14 divide-y divide-white/10 border-t border-white/10">
                    @foreach ($industries as $industry)
                        <div class="group grid gap-6 py-8 lg:grid-cols-12 lg:items-center lg:gap-8">
                            <div class="lg:col-span-1">
                                <span class="text-sm font-extrabold tabular-nums text-white/25 transition group-hover:text-brand-400">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="lg:col-span-4">
                                <h3 class="text-xl font-bold tracking-tight sm:text-2xl">{{ $industry->name }}</h3>
                                @if ($industry->technologyList())
                                    <p class="mt-2 text-[13px] font-semibold text-neutral-500">{{ implode(' · ', $industry->technologyList()) }}</p>
                                @endif
                            </div>
                            <div class="lg:col-span-6">
                                <p class="text-[15px] leading-relaxed text-neutral-400">{{ $industry->description }}</p>
                            </div>
                            <div class="lg:col-span-1 lg:justify-self-end">
                                <a href="{{ route('kontak') }}" aria-label="Konsultasi untuk {{ $industry->name }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-neutral-400 transition group-hover:border-brand-500 group-hover:text-brand-400">
                                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="bg-navy-900 py-16 text-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-6 px-4 sm:px-6">
            <div class="max-w-xl">
                <h2 class="text-2xl font-extrabold tracking-tight text-balance sm:text-3xl">Butuh solusi yang disesuaikan?</h2>
                <p class="mt-2 text-[15px] text-neutral-300">Ceritakan proses bisnis Anda — kami susun penawaran tertulis.</p>
            </div>
            <a href="{{ route('kontak') }}" class="rounded-full bg-brand-600 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-brand-500">Diskusikan Kebutuhan</a>
        </div>
    </section>
@endsection
