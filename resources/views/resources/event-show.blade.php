@extends('layouts.site')
@section('title', $resource->title.' — KIT Konsultan IT')
@section('meta-description', \Illuminate\Support\Str::limit($resource->excerpt ?: $resource->title, 160))
@php
    $isSample = str_starts_with($resource->title, 'Contoh:');
    $isPast = $resource->isPast();
    $status = $isSample ? 'Contoh kegiatan' : ($resource->starts_at ? ($resource->isUpcoming() ? 'Akan datang' : ($isPast ? 'Telah berlangsung' : 'Sedang berlangsung')) : 'Jadwal menyusul');
    $actionUrl = $isSample ? null : ($isPast ? ($resource->recording_url ?: $resource->cta_url) : ($resource->register_url ?: $resource->cta_url ?: $resource->external_url));
    $actionLabel = $isPast ? 'Tonton rekaman' : 'Daftar acara';
@endphp
@if ($resource->cover_image_path)
    @section('og-image', str_starts_with($resource->cover_image_path, 'img/') ? asset($resource->cover_image_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($resource->cover_image_path))
@endif
@section('content')
<section class="border-b border-neutral-200 bg-[#f8f9f7]">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 sm:py-16 lg:grid-cols-[minmax(0,1fr)_300px] lg:items-center lg:gap-16">
        <div>
            <nav class="text-xs font-bold uppercase tracking-[0.16em] text-navy-950" aria-label="Breadcrumb"><a href="{{ route('resources.events') }}" class="hover:text-brand-700">Event</a><span class="mx-2 text-neutral-300">/</span><span class="text-neutral-500">Detail kegiatan</span></nav>
            <p class="mt-10 text-xs font-bold uppercase tracking-[0.16em] text-neutral-600">{{ $status }}</p>
            <h1 class="mt-3 max-w-3xl text-3xl font-extrabold leading-[1.12] tracking-tight text-navy-950 sm:text-5xl">{{ $resource->title }}</h1>
            @if ($resource->excerpt)<p class="mt-6 max-w-2xl text-lg leading-8 text-neutral-600">{{ $resource->excerpt }}</p>@endif
            <div class="mt-6 flex flex-wrap gap-x-5 gap-y-2 text-sm text-neutral-600">
                @if ($resource->starts_at)<span>{{ $resource->starts_at->locale('id')->translatedFormat('l, d F Y') }}</span>@endif
                @if ($resource->location)<span>{{ $resource->location }}</span>@endif
            </div>
            @if ($isSample)<p class="mt-7 max-w-2xl border-l-2 border-[#a9bdc8] pl-4 text-sm leading-6 text-neutral-600">Ini contoh konsep kegiatan untuk menunjukkan format agenda. Jadwal, lokasi, pembicara, dan pendaftaran belum dikonfirmasi.</p>@endif
            <div class="mt-9 flex flex-wrap items-center gap-4">
                @if ($actionUrl)<a href="{{ $actionUrl }}" target="_blank" rel="noopener" class="inline-flex rounded-full bg-navy-950 px-7 py-3.5 text-sm font-bold text-white transition hover:bg-brand-700">{{ $resource->cta_label ?: $actionLabel }} →</a>@endif
                <a href="#tentang-acara" class="{{ $actionUrl ? 'text-sm font-semibold text-navy-950 hover:text-brand-700' : 'inline-flex rounded-full bg-navy-950 px-7 py-3.5 text-sm font-bold text-white transition hover:bg-brand-700' }}">Lihat detail acara {{ $actionUrl ? '↓' : '→' }}</a>
                <a href="{{ route('resources.events') }}" class="text-sm font-semibold text-navy-950 hover:text-brand-700">Kembali ke agenda</a>
            </div>
        </div>
        <div class="flex justify-center bg-[#e9edef] px-6 py-10 sm:px-10 sm:py-12">
            <x-event-date :resource="$resource" class="w-[225px] shadow-[0_18px_38px_-28px_rgba(12,29,45,0.45)]" />
        </div>
    </div>
</section>

@if ($resource->cover_image_path)
    <div class="mx-auto max-w-7xl px-4 pt-10 sm:px-6"><img src="{{ str_starts_with($resource->cover_image_path, 'img/') ? asset($resource->cover_image_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($resource->cover_image_path) }}" alt="Visual {{ $resource->title }}" class="max-h-[480px] w-full object-cover" loading="lazy"></div>
@endif

<div id="tentang-acara" class="mx-auto grid max-w-7xl scroll-mt-28 gap-12 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-[minmax(0,1fr)_280px] lg:gap-16">
    <article class="min-w-0">
        <p class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-600">Tentang kegiatan</p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight text-navy-950">Apa yang akan dibahas?</h2>
        @if ($resource->body)
            <div class="mt-6 space-y-5 text-[16px] leading-8 text-neutral-700">
                @foreach (preg_split('/\R{2,}/', trim($resource->body)) ?: [] as $paragraph)
                    @if (trim($paragraph) !== '')<p>{{ trim($paragraph) }}</p>@endif
                @endforeach
            </div>
        @elseif ($resource->excerpt)
            <p class="mt-6 text-[16px] leading-8 text-neutral-700">{{ $resource->excerpt }}</p>
        @endif

        @if (!empty($resource->agenda))
            <section class="mt-14 border-t border-neutral-200 pt-10" aria-labelledby="agenda-acara">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-600">Rangkaian kegiatan</p>
                <h2 id="agenda-acara" class="mt-3 text-3xl font-bold tracking-tight text-navy-950">Agenda acara</h2>
                <ol class="mt-7 divide-y divide-neutral-200 border-y border-neutral-200">
                    @foreach ($resource->agenda as $item)
                        <li class="grid grid-cols-[66px_minmax(0,1fr)] gap-5 py-5 sm:grid-cols-[84px_minmax(0,1fr)]">
                            <span class="font-bold tabular-nums text-[#547082]">{{ $item['time'] ?? '' }}</span>
                            <span><span class="block font-bold leading-snug text-navy-950">{{ $item['title'] ?? '' }}</span>@if (!empty($item['description']))<span class="mt-1 block text-sm leading-6 text-neutral-600">{{ $item['description'] }}</span>@endif</span>
                        </li>
                    @endforeach
                </ol>
            </section>
        @endif

        @if (!empty($resource->speakers))
            <section class="mt-14 border-t border-neutral-200 pt-10">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-600">Narasumber</p>
                <h2 class="mt-3 text-3xl font-bold tracking-tight text-navy-950">Temui pembicaranya</h2>
                <div class="mt-7 grid gap-6 sm:grid-cols-2">
                    @foreach ($resource->speakers as $speaker)
                        <div class="flex items-center gap-4">
                            @if (!empty($speaker['photo']))<img src="{{ str_starts_with($speaker['photo'], 'img/') ? asset($speaker['photo']) : \Illuminate\Support\Facades\Storage::disk('public')->url($speaker['photo']) }}" alt="{{ $speaker['name'] ?? '' }}" class="h-16 w-16 rounded-full object-cover" loading="lazy">
                            @else<span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-[#e9edef] font-bold text-navy-950">{{ mb_strtoupper(mb_substr($speaker['name'] ?? '?', 0, 2)) }}</span>@endif
                            <div><p class="font-bold text-navy-950">{{ $speaker['name'] ?? '' }}</p><p class="mt-1 text-sm text-neutral-600">{{ $speaker['position'] ?? '' }}</p></div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if (!empty($resource->gallery))
            <section class="mt-14 border-t border-neutral-200 pt-10">
                <h2 class="text-3xl font-bold tracking-tight text-navy-950">Dokumentasi kegiatan</h2>
                <div class="mt-7 grid gap-4 sm:grid-cols-2">
                    @foreach ($resource->gallery as $photo)<img src="{{ str_starts_with($photo, 'img/') ? asset($photo) : \Illuminate\Support\Facades\Storage::disk('public')->url($photo) }}" alt="Dokumentasi {{ $resource->title }}" class="aspect-[4/3] w-full object-cover" loading="lazy">@endforeach
                </div>
            </section>
        @endif

        <div class="mt-14 border-l-2 border-[#a9bdc8] bg-[#f5f7f8] p-7 sm:p-9">
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-600">Lanjutkan percakapan</p>
            <h2 class="mt-2 text-2xl font-bold text-navy-950">Ingin membahas topik ini untuk tim Anda?</h2>
            <p class="mt-3 text-sm leading-6 text-neutral-600">Diskusikan kebutuhan dan tantangan digital Anda bersama tim KIT Konsultan IT.</p>
            <a href="{{ route('kontak') }}" class="mt-6 inline-flex rounded-full bg-navy-950 px-6 py-3 text-sm font-bold text-white hover:bg-brand-700">Hubungi tim →</a>
        </div>
    </article>
    <aside class="border-t border-neutral-200 pt-8 lg:border-l lg:border-t-0 lg:pl-8 lg:pt-0">
        <div class="lg:sticky lg:top-28">
            <h2 class="text-sm font-bold uppercase tracking-[0.14em] text-navy-950">Informasi acara</h2>
            <dl class="mt-5 divide-y divide-neutral-200 border-y border-neutral-200 text-sm">
                <div class="py-4"><dt class="text-neutral-500">Status</dt><dd class="mt-1 font-semibold text-navy-950">{{ $status }}</dd></div>
                @if ($resource->starts_at)<div class="py-4"><dt class="text-neutral-500">Tanggal</dt><dd class="mt-1 font-semibold text-navy-950">{{ $resource->starts_at->locale('id')->translatedFormat('d F Y') }}</dd></div><div class="py-4"><dt class="text-neutral-500">Waktu</dt><dd class="mt-1 font-semibold text-navy-950">{{ $resource->starts_at->format('H:i') }}@if ($resource->ends_at) – {{ $resource->ends_at->format('H:i') }}@endif</dd></div>@endif
                @if ($resource->location)<div class="py-4"><dt class="text-neutral-500">Lokasi / format</dt><dd class="mt-1 font-semibold text-navy-950">{{ $resource->location }}</dd></div>@endif
                @if ($resource->organizer)<div class="py-4"><dt class="text-neutral-500">Penyelenggara</dt><dd class="mt-1 font-semibold text-navy-950">{{ $resource->organizer }}</dd></div>@endif
                <div class="py-4"><dt class="text-neutral-500">Akses</dt><dd class="mt-1 font-semibold text-navy-950">{{ $isSample ? 'Belum tersedia untuk contoh kegiatan' : ($actionUrl ? ($isPast ? 'Rekaman tersedia' : 'Pendaftaran tersedia') : ($isPast ? 'Rekaman belum tersedia' : 'Pendaftaran belum tersedia')) }}</dd></div>
            </dl>
            <a href="{{ route('resources.events') }}" class="mt-6 inline-block text-sm font-bold text-navy-950 hover:text-brand-700">Lihat semua Event →</a>
        </div>
    </aside>
</div>

@if ($related->isNotEmpty())
    <section class="border-t border-neutral-200 bg-[#f8f9f7] py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <h2 class="text-2xl font-bold text-navy-950 sm:text-3xl">Kegiatan lainnya</h2>
            <div class="mt-8 grid gap-x-8 gap-y-7 md:grid-cols-3">
                @foreach ($related as $item)
                    <a href="{{ route('resources.events.show', $item->slug) }}" class="group grid grid-cols-[76px_minmax(0,1fr)] items-center gap-4 border-t border-neutral-200 pt-5">
                        <x-event-date :resource="$item" :compact="true" class="w-full" />
                        <span><span class="block text-xs font-bold uppercase tracking-[0.14em] text-neutral-600">{{ str_starts_with($item->title, 'Contoh:') ? 'Contoh kegiatan' : 'Event' }}</span><span class="mt-2 block text-sm font-bold leading-snug text-navy-950 group-hover:text-brand-700">{{ $item->title }}</span></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection
