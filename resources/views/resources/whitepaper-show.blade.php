@extends('layouts.site')
@section('title', $resource->title.' — KIT Konsultan IT')
@section('meta-description', \Illuminate\Support\Str::limit($resource->excerpt ?: $resource->title, 160))
@php
    $hasFile = $resource->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($resource->file_path);
    $downloadUrl = $hasFile
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($resource->file_path)
        : ($resource->cta_url ?: $resource->external_url);
@endphp
@if ($resource->cover_image_path)
    @section('og-image', str_starts_with($resource->cover_image_path, 'img/') ? asset($resource->cover_image_path) : \Illuminate\Support\Facades\Storage::disk('public')->url($resource->cover_image_path))
@endif
@section('content')
<section class="border-b border-neutral-200 bg-[#f8f9f7]">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 sm:py-16 lg:grid-cols-[minmax(0,1fr)_350px] lg:items-center lg:gap-16">
        <div>
            <nav class="text-xs font-bold uppercase tracking-[0.16em] text-navy-950" aria-label="Breadcrumb"><a href="{{ route('resources.whitepaper') }}" class="transition-colors hover:text-brand-700 focus-visible:text-brand-700">Whitepaper</a><span class="mx-2 text-neutral-300">/</span><span class="text-neutral-500">Kajian</span></nav>
            <p class="mt-10 text-xs font-bold uppercase tracking-[0.16em] text-neutral-500">KIT / Research Notes</p>
            <h1 class="mt-3 max-w-3xl text-3xl font-extrabold leading-[1.12] tracking-tight text-navy-950 sm:text-5xl">{{ $resource->title }}</h1>
            @if ($resource->excerpt)<p class="mt-6 max-w-2xl text-lg leading-8 text-neutral-600">{{ $resource->excerpt }}</p>@endif
            <p class="mt-6 text-sm text-neutral-500">{{ ($resource->published_at ?? $resource->created_at)?->translatedFormat('d F Y') }}@if ($resource->page_count && $hasFile) <span class="mx-2">·</span> {{ $resource->page_count }} halaman @endif</p>
            <div class="mt-9 flex flex-wrap items-center gap-4">
                @if ($downloadUrl)
                    <a href="{{ $downloadUrl }}" target="_blank" rel="noopener" class="inline-flex rounded-full bg-navy-950 px-7 py-3.5 text-sm font-bold text-white transition hover:bg-brand-700">{{ $resource->cta_label ?: ($hasFile ? 'Buka Whitepaper' : 'Buka Materi') }} →</a>
                @else
                    <a href="#ringkasan" class="inline-flex rounded-full bg-navy-950 px-7 py-3.5 text-sm font-bold text-white transition hover:bg-brand-700">Baca ringkasan →</a>
                @endif
                <a href="{{ route('resources.whitepaper') }}" class="text-sm font-semibold text-navy-950 hover:text-brand-700">Kembali ke koleksi</a>
            </div>
            @if (!$downloadUrl)<p class="mt-4 text-xs leading-5 text-neutral-500">Dokumen lengkap sedang disiapkan. Ringkasan topik dan daftar isi dapat dibaca di bawah.</p>@endif
        </div>
        <div class="flex justify-center bg-[#e9edef] px-6 py-10 sm:px-10 sm:py-12">
            <x-whitepaper-cover :resource="$resource" :priority="true" class="w-[220px] sm:w-[265px]" />
        </div>
    </div>
</section>

<div id="ringkasan" class="mx-auto grid max-w-7xl scroll-mt-28 gap-12 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-[minmax(0,1fr)_280px] lg:gap-16">
    <article class="min-w-0">
        <p class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-600">Ringkasan kajian</p>
        <h2 class="mt-3 text-3xl font-bold tracking-tight text-navy-950">Gambaran topik</h2>
        @if ($resource->body)
            <div class="mt-6 space-y-5 text-[16px] leading-8 text-neutral-700">
                @foreach (preg_split('/\R{2,}/', trim($resource->body)) ?: [] as $paragraph)
                    @if (trim($paragraph) !== '')<p>{{ trim($paragraph) }}</p>@endif
                @endforeach
            </div>
        @elseif ($resource->excerpt)
            <p class="mt-6 text-[16px] leading-8 text-neutral-700">{{ $resource->excerpt }}</p>
        @endif

        @if (!empty($resource->toc))
            <section class="mt-14 border-t border-neutral-200 pt-10" aria-labelledby="pokok-bahasan">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-600">Struktur Whitepaper</p>
                <h2 id="pokok-bahasan" class="mt-3 text-3xl font-bold tracking-tight text-navy-950">Pokok bahasan</h2>
                <ol class="mt-7 divide-y divide-neutral-200 border-y border-neutral-200">
                    @foreach ($resource->toc as $index => $topic)
                        <li class="grid grid-cols-[42px_minmax(0,1fr)] gap-4 py-5">
                            <span class="font-bold tabular-nums text-[#547082]">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="font-semibold leading-snug text-navy-950">{{ $topic }}</span>
                        </li>
                    @endforeach
                </ol>
            </section>
        @endif

        <div class="mt-12 border-l-2 border-[#a9bdc8] bg-[#f5f7f8] p-7 sm:p-9">
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-neutral-600">Bahas lebih lanjut</p>
            <h2 class="mt-2 text-2xl font-bold text-navy-950">Perlu perspektif untuk keputusan berikutnya?</h2>
            <p class="mt-3 text-sm leading-6 text-neutral-600">Ceritakan tantangan bisnis dan teknologi Anda kepada tim KIT Konsultan IT.</p>
            <a href="{{ route('kontak') }}" class="mt-6 inline-flex rounded-full bg-navy-950 px-6 py-3 text-sm font-bold text-white hover:bg-brand-700">Mulai konsultasi →</a>
        </div>
    </article>
    <aside class="border-t border-neutral-200 pt-8 lg:border-l lg:border-t-0 lg:pl-8 lg:pt-0">
        <div class="lg:sticky lg:top-28">
            <h2 class="text-sm font-bold uppercase tracking-[0.14em] text-navy-950">Tentang publikasi</h2>
            <dl class="mt-5 divide-y divide-neutral-200 border-y border-neutral-200 text-sm">
                <div class="py-4"><dt class="text-neutral-500">Jenis materi</dt><dd class="mt-1 font-semibold text-navy-950">Whitepaper</dd></div>
                <div class="py-4"><dt class="text-neutral-500">Diterbitkan</dt><dd class="mt-1 font-semibold text-navy-950">{{ ($resource->published_at ?? $resource->created_at)?->translatedFormat('d F Y') }}</dd></div>
                @if ($resource->page_count && $hasFile)<div class="py-4"><dt class="text-neutral-500">Panjang</dt><dd class="mt-1 font-semibold text-navy-950">{{ $resource->page_count }} halaman</dd></div>@endif
                <div class="py-4"><dt class="text-neutral-500">Akses</dt><dd class="mt-1 font-semibold text-navy-950">{{ $downloadUrl ? 'Dokumen tersedia' : 'Ringkasan tersedia' }}</dd></div>
            </dl>
            <a href="{{ route('resources.whitepaper') }}" class="mt-6 inline-block text-sm font-bold text-navy-950 transition-colors hover:text-brand-700 focus-visible:text-brand-700">Lihat semua Whitepaper →</a>
        </div>
    </aside>
</div>

@if ($related->isNotEmpty())
    <section class="border-t border-neutral-200 bg-[#f8f9f7] py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <h2 class="text-2xl font-bold text-navy-950 sm:text-3xl">Kajian terkait</h2>
            <div class="mt-8 grid gap-x-8 gap-y-7 md:grid-cols-3">
                @foreach ($related as $item)
                    <a href="{{ route('resources.whitepaper.show', $item->slug) }}" class="group grid grid-cols-[78px_minmax(0,1fr)] items-center gap-4 border-t border-neutral-200 pt-5">
                        <x-whitepaper-cover :resource="$item" :compact="true" class="w-full" />
                        <span><span class="block text-xs font-bold uppercase tracking-[0.14em] text-neutral-600">Whitepaper</span><span class="mt-2 block text-sm font-bold leading-snug text-navy-950 group-hover:text-brand-700 group-focus-visible:text-brand-700">{{ $item->title }}</span></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection
