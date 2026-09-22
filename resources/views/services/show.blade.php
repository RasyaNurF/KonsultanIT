@extends('layouts.site')

@section('title', $service->title.' — Nusakode')
@section('meta-description', \Illuminate\Support\Str::limit($service->description ?? $service->title, 160))

@section('content')
    @php
        $imageUrl = $service->image_path
            ? (str_starts_with($service->image_path, 'img/') ? asset($service->image_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($service->image_path))
            : null;
        $technologies = $service->technologyList();
        $industries = $service->industries;
        $steps = [
            ['title' => 'Konsultasi & lingkup', 'desc' => 'Kami petakan kebutuhan, prioritas, dan ukuran keberhasilan sebelum menyusun penawaran tertulis.'],
            ['title' => 'Desain & pengembangan', 'desc' => 'Pengerjaan bertahap oleh tim tetap, dengan demonstrasi berkala yang bisa Anda nilai.'],
            ['title' => 'Uji & serah terima', 'desc' => 'Pengujian menyeluruh, pelatihan operator, dan penyerahan kode, dokumentasi, serta kredensial.'],
            ['title' => 'Rawat & kembangkan', 'desc' => 'Pemeliharaan, pemantauan, dan peningkatan fitur berkelanjutan sesuai kebutuhan bisnis.'],
        ];
    @endphp

    {{-- ============ HERO ============ --}}
    <section class="relative overflow-hidden bg-navy-950 pb-20 pt-16 text-white sm:pt-20">
        <span class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-brand-600/25 blur-3xl" aria-hidden="true"></span>
        <span class="pointer-events-none absolute -right-20 top-10 h-72 w-72 rounded-full bg-brand-500/10 blur-3xl" aria-hidden="true"></span>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6" data-no-reveal>
            <p class="flex flex-wrap items-center gap-3 text-xs font-bold uppercase tracking-[0.24em] text-white/60">
                <a href="{{ route('layanan.index') }}" class="transition hover:text-white">Layanan</a>
                <span class="text-white/25" aria-hidden="true">/</span>
                <span class="text-white/40">{{ $service->title }}</span>
            </p>

            <div class="mt-8 grid gap-12 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)] lg:items-center">
                <div class="min-w-0">
                    <span class="inline-flex w-fit items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-white/80 ring-1 ring-white/15">
                        <span class="h-1.5 w-1.5 rounded-full bg-brand-400" aria-hidden="true"></span>
                        Jasa internal
                    </span>

                    <h1 class="mt-5 text-4xl font-extrabold leading-[1.05] tracking-tight text-balance sm:text-5xl">{{ $service->title }}</h1>

                    @if ($service->description)
                        <p class="mt-6 max-w-xl text-base leading-relaxed text-neutral-300">{{ $service->description }}</p>
                    @endif

                    @if ($technologies)
                        <ul class="mt-7 flex flex-wrap gap-2">
                            @foreach ($technologies as $technology)
                                <li class="rounded-full bg-white/5 px-3.5 py-1.5 text-[13px] font-semibold text-white/80 ring-1 ring-white/10">{{ $technology }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="mt-9 flex flex-wrap items-center gap-3">
                        <a href="{{ route('kontak') }}" class="group inline-flex items-center gap-2 rounded-full bg-brand-600 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-brand-500">
                            Diskusikan kebutuhan ini
                            <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                        <a href="{{ route('solusi.index') }}" class="inline-flex items-center gap-2 rounded-full px-7 py-3.5 text-sm font-bold text-white ring-1 ring-white/25 transition hover:bg-white/10">
                            Lihat produk mitra
                        </a>
                    </div>
                </div>

                <div class="lg:justify-self-end">
                    @if ($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $service->title }}" class="aspect-[4/3] w-full rounded-2xl object-cover ring-1 ring-white/10" loading="lazy">
                    @else
                        <div class="relative flex aspect-[4/3] w-full items-center justify-center overflow-hidden rounded-2xl bg-white/5 ring-1 ring-white/10">
                            <span class="pointer-events-none absolute -left-10 -top-10 h-40 w-40 rounded-full bg-brand-600/30 blur-3xl" aria-hidden="true"></span>
                            <span class="text-7xl font-black tracking-tight text-white/10">{{ str_pad((string) $service->sort_order, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ============ NILAI / JAMINAN ============ --}}
    <section class="border-b border-neutral-100 bg-white">
        <div class="mx-auto grid max-w-7xl gap-px overflow-hidden px-4 sm:px-6 md:grid-cols-3 md:divide-x md:divide-neutral-200" data-no-reveal>
            @foreach ([
                ['title' => 'Tim tetap, bukan lepas', 'desc' => 'Engineer yang mengerjakan proyek Anda adalah karyawan kami.'],
                ['title' => 'Kontrak tertulis', 'desc' => 'Lingkup, jadwal, biaya, dan garansi tercantum jelas sejak awal.'],
                ['title' => 'Serah terima penuh', 'desc' => 'Kode sumber, dokumentasi, dan pelatihan operator menjadi milik Anda.'],
            ] as $value)
                <div class="py-10 md:px-8 md:first:pl-0 md:last:pr-0">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-100 text-brand-700">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                    </span>
                    <h2 class="mt-5 text-base font-extrabold text-navy-950">{{ $value['title'] }}</h2>
                    <p class="mt-2 text-[15px] leading-relaxed text-neutral-500">{{ $value['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ PROSES ============ --}}
    <section class="bg-white py-20 sm:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6" data-no-reveal>
            <div class="max-w-2xl">
                <p class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.22em] text-brand-700"><span class="h-px w-8 bg-brand-500" aria-hidden="true"></span>Alur kerja</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-balance text-navy-950 sm:text-4xl">Cara kami mengerjakan layanan ini</h2>
            </div>

            <ol class="mt-12 grid gap-px overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-200 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($steps as $step)
                    <li class="bg-white p-8">
                        <span class="text-sm font-extrabold tabular-nums text-brand-600">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="mt-4 text-base font-extrabold text-navy-950">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-[15px] leading-relaxed text-neutral-500">{{ $step['desc'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ============ INDUSTRI TERKAIT ============ --}}
    @if ($industries->isNotEmpty())
        <section class="border-t border-white/5 bg-navy-950 py-20 text-white sm:py-28" aria-label="Industri yang dilayani">
            <div class="mx-auto max-w-7xl px-4 sm:px-6" data-no-reveal>
                <div class="flex flex-wrap items-end justify-between gap-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-brand-400">Berpengalaman di</p>
                        <h2 class="mt-4 max-w-lg text-3xl font-extrabold tracking-tight text-balance sm:text-4xl">Industri yang cocok dengan layanan ini</h2>
                    </div>
                    <p class="max-w-sm text-[15px] leading-relaxed text-neutral-400">Pola pengerjaan kami menyesuaikan proses dan regulasi tiap sektor.</p>
                </div>

                <div class="mt-14 divide-y divide-white/10 border-t border-white/10">
                    @foreach ($industries as $industry)
                        <div class="grid gap-4 py-7 lg:grid-cols-12 lg:items-center lg:gap-8">
                            <div class="lg:col-span-1">
                                <span class="text-sm font-extrabold tabular-nums text-white/25">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="lg:col-span-4">
                                <h3 class="text-lg font-bold tracking-tight sm:text-xl">{{ $industry->name }}</h3>
                                @if ($industry->technologyList())
                                    <p class="mt-2 text-[13px] font-semibold text-neutral-500">{{ implode(' · ', $industry->technologyList()) }}</p>
                                @endif
                            </div>
                            <div class="lg:col-span-7">
                                <p class="text-[15px] leading-relaxed text-neutral-400">{{ $industry->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ LAYANAN TERKAIT ============ --}}
    @if ($related->isNotEmpty())
        <section class="border-t border-neutral-100 py-20 sm:py-28">
            <div class="mx-auto max-w-7xl px-4 sm:px-6" data-no-reveal>
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <h2 class="text-2xl font-extrabold tracking-tight text-navy-950 sm:text-3xl">Layanan lain</h2>
                    <a href="{{ route('layanan.index') }}" class="group inline-flex items-center gap-2 text-sm font-bold text-navy-800 transition hover:text-brand-700">
                        Semua layanan
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                </div>

                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        @php($itemImage = $item->image_path ? (str_starts_with($item->image_path, 'img/') ? asset($item->image_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($item->image_path)) : null)
                        <a href="{{ route('layanan.show', $item->slug) }}" class="group flex flex-col overflow-hidden rounded-2xl border border-neutral-200 bg-white transition hover:-translate-y-1 hover:border-brand-200 hover:shadow-xl hover:shadow-navy-950/5">
                            <span class="block h-40 overflow-hidden bg-neutral-100">
                                @if ($itemImage)
                                    <img src="{{ $itemImage }}" alt="{{ $item->title }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]" loading="lazy">
                                @else
                                    <span class="flex h-full w-full items-center justify-center bg-navy-950 text-lg font-black text-white/20">{{ str_pad((string) $item->sort_order, 2, '0', STR_PAD_LEFT) }}</span>
                                @endif
                            </span>
                            <span class="flex flex-1 flex-col p-6">
                                <span class="text-[11px] font-bold uppercase tracking-[0.16em] text-brand-700">Layanan</span>
                                <h3 class="mt-2 text-lg font-bold tracking-tight text-navy-950 transition group-hover:text-brand-700">{{ $item->title }}</h3>
                                @if ($item->description)
                                    <span class="mt-2 flex-1 text-sm leading-relaxed text-neutral-500">{{ \Illuminate\Support\Str::limit($item->description, 110) }}</span>
                                @endif
                                <span class="mt-5 inline-flex items-center gap-2 text-[13px] font-bold text-navy-800 transition group-hover:text-brand-700">Selengkapnya<svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ CTA ============ --}}
    <section class="bg-navy-900 py-16 text-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-6 px-4 sm:px-6">
            <div class="max-w-xl">
                <h2 class="text-2xl font-extrabold tracking-tight text-balance sm:text-3xl">Butuh layanan ini untuk bisnis Anda?</h2>
                <p class="mt-2 text-[15px] text-neutral-300">Ceritakan kebutuhan Anda — kami kirimkan penawaran tertulis sesuai lingkup. Butuh produk mitra? Lihat <a href="{{ route('solusi.index') }}" class="font-semibold text-white underline underline-offset-2 transition hover:text-brand-300">Solusi</a>.</p>
            </div>
            <a href="{{ route('kontak') }}" class="rounded-full bg-brand-600 px-8 py-3.5 text-sm font-bold text-white transition hover:bg-brand-500">Mulai Konsultasi</a>
        </div>
    </section>
@endsection
