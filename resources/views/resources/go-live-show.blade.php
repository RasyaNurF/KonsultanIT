@extends('layouts.site')
@section('title', $resource->title.' — KIT Konsultan IT')
@section('meta-description', \Illuminate\Support\Str::limit($resource->excerpt ?: $resource->title, 160))
@php($imagePath = $resource->cover_image_path ?: data_get($resource->gallery, '0'))
@php($image = $imagePath ? (str_starts_with($imagePath, 'img/') ? asset($imagePath) : \Illuminate\Support\Facades\Storage::disk('public')->url($imagePath)) : asset('img/editorial/news-workspace.png'))
@if ($imagePath)
    @section('og-image', $image)
@endif
@section('content')
<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16">
    <div class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_300px] lg:gap-16">
        <article class="min-w-0">
            <nav class="text-xs font-bold uppercase tracking-[0.16em] text-brand-700" aria-label="Breadcrumb"><a href="{{ route('resources.go-live') }}" class="hover:underline">Go-Live</a><span class="mx-2 text-neutral-300">/</span><span class="text-neutral-500">{{ $resource->industry ?: 'Implementasi' }}</span></nav>
            <h1 class="mt-5 text-3xl font-extrabold leading-[1.14] tracking-tight text-navy-950 sm:text-5xl">{{ $resource->title }}</h1>
            <p class="mt-5 text-sm text-neutral-500">{{ ($resource->published_at ?? $resource->created_at)?->translatedFormat('d F Y') }} <span class="mx-2">·</span> {{ $resource->industry ?: 'Solusi digital' }}</p>
            @if (str_starts_with($resource->title, 'Contoh '))
                <p class="mt-5 border-l-2 border-brand-500 pl-4 text-sm leading-6 text-neutral-500">Skenario ilustratif untuk pratinjau. Cerita ini bukan dokumentasi klien atau laporan hasil proyek nyata.</p>
            @endif
            <figure class="mt-8">
                <img src="{{ $image }}" alt="{{ $resource->title }}" class="aspect-[16/9] w-full rounded-xl object-cover" fetchpriority="high">
                @if (!$imagePath || str_starts_with($imagePath, 'img/'))<figcaption class="mt-2 text-xs text-neutral-500">Gambar ilustrasi</figcaption>@endif
            </figure>
            @if ($resource->excerpt)<p class="mt-10 text-xl font-medium leading-8 text-navy-950">{{ $resource->excerpt }}</p>@endif

            @if ($resource->body)
                <section class="mt-10" aria-labelledby="cerita-implementasi">
                    <h2 id="cerita-implementasi" class="text-2xl font-bold tracking-tight text-navy-950">Cerita implementasi</h2>
                    <div class="mt-5 space-y-6 text-[16px] leading-8 text-neutral-700">
                        @foreach (preg_split('/\R{2,}/', trim($resource->body)) ?: [] as $paragraph)
                            @if (trim($paragraph) !== '')<p>{{ trim($paragraph) }}</p>@endif
                        @endforeach
                    </div>
                </section>
            @endif

            @if (!empty($resource->metrics))
                <section class="mt-12 border-t border-neutral-200 pt-9" aria-labelledby="hasil-proyek">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-brand-700">Hasil implementasi</p>
                    <h2 id="hasil-proyek" class="mt-2 text-2xl font-bold text-navy-950">Dampak yang dicatat</h2>
                    <div class="mt-6 grid gap-4 sm:grid-cols-3">
                        @foreach ($resource->metrics as $metric)
                            <div class="border-l-2 border-brand-600 bg-[#f7f9fb] px-5 py-5">
                                <p class="text-2xl font-extrabold text-navy-950">{{ $metric['value'] ?? '—' }}</p>
                                <p class="mt-2 text-xs font-semibold leading-5 text-neutral-600">{{ $metric['label'] ?? '' }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if (count($resource->gallery ?? []) > ($resource->cover_image_path ? 0 : 1))
                <section class="mt-12 border-t border-neutral-200 pt-9" aria-labelledby="visual-proyek">
                    <h2 id="visual-proyek" class="text-2xl font-bold text-navy-950">Galeri visual</h2>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @foreach (array_slice($resource->gallery, $resource->cover_image_path ? 0 : 1) as $photo)
                            @php($photoUrl = str_starts_with($photo, 'img/') ? asset($photo) : \Illuminate\Support\Facades\Storage::disk('public')->url($photo))
                            <img src="{{ $photoUrl }}" alt="Visual pendukung {{ $resource->title }}" class="aspect-[4/3] w-full rounded-lg object-cover" loading="lazy">
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="mt-12 rounded-xl bg-[#f2f6fa] p-7 sm:p-9">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-brand-700">Langkah berikutnya</p>
                <h2 class="mt-2 text-2xl font-bold text-navy-950">Ingin membangun solusi serupa?</h2>
                <p class="mt-3 text-sm leading-6 text-neutral-600">Ceritakan proses dan tujuan bisnis Anda. Tim kami siap membantu menyusun pendekatan yang sesuai.</p>
                <a href="{{ route('kontak') }}" class="mt-6 inline-flex rounded-full bg-navy-950 px-6 py-3 text-sm font-bold text-white hover:bg-brand-700">Diskusikan proyek →</a>
            </section>
        </article>
        <aside class="border-t border-neutral-200 pt-8 lg:border-l lg:border-t-0 lg:pl-8 lg:pt-0">
            <div class="lg:sticky lg:top-28">
                <h2 class="text-sm font-bold uppercase tracking-[0.14em] text-navy-950">Sekilas proyek</h2>
                <dl class="mt-5 divide-y divide-neutral-200 border-y border-neutral-200 text-sm">
                    <div class="py-4"><dt class="text-neutral-500">Sektor</dt><dd class="mt-1 font-semibold text-navy-950">{{ $resource->industry ?: 'Lintas industri' }}</dd></div>
                    <div class="py-4"><dt class="text-neutral-500">Publikasi</dt><dd class="mt-1 font-semibold text-navy-950">{{ ($resource->published_at ?? $resource->created_at)?->translatedFormat('d F Y') }}</dd></div>
                </dl>
                @if ($related->isNotEmpty())
                    <div class="mt-10">
                        <h2 class="text-sm font-bold uppercase tracking-[0.14em] text-navy-950">Cerita lainnya</h2>
                        <div class="mt-5 space-y-5">
                            @foreach ($related as $item)
                                <a href="{{ route('resources.go-live.show', $item->slug) }}" class="group block border-b border-neutral-200 pb-5">
                                    <span class="text-xs text-brand-700">{{ $item->industry ?: 'Implementasi' }}</span>
                                    <span class="mt-2 block text-[15px] font-bold leading-snug text-navy-950 group-hover:text-brand-700">{{ $item->title }}</span>
                                </a>
                            @endforeach
                        </div>
                        <a href="{{ route('resources.go-live') }}" class="mt-5 inline-block text-sm font-bold text-brand-700">Lihat semua Go-Live →</a>
                    </div>
                @endif
            </div>
        </aside>
    </div>
</div>
@endsection
