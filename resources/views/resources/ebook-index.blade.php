@extends('layouts.site')
@section('title', 'E-book — KIT Konsultan IT')
@section('meta-description', 'Kumpulan panduan praktis tentang software, integrasi sistem, keamanan, dan transformasi digital.')
@section('content')
<section class="border-b border-neutral-200 bg-[#fafbfc]">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-700">Resources / E-book</p>
        <h1 class="mt-4 max-w-4xl text-4xl font-extrabold leading-[1.08] tracking-tight text-navy-950 sm:text-6xl">Panduan yang bisa dibawa ke langkah berikutnya.</h1>
        <p class="mt-5 max-w-2xl text-base leading-8 text-neutral-600">Jelajahi bacaan praktis untuk merencanakan sistem, menata proses, dan membuat keputusan teknologi dengan lebih percaya diri.</p>
        <p class="mt-6 text-sm text-neutral-500">{{ $resources->total() }} panduan tersedia untuk dijelajahi</p>
    </div>
</section>

<div class="mx-auto max-w-7xl px-4 pb-20 sm:px-6">
    @if ($resources->isEmpty())
        <div class="py-24 text-center">
            <h2 class="text-2xl font-bold text-navy-950">E-book segera hadir.</h2>
            <p class="mt-3 text-neutral-500">Sementara itu, Anda dapat membaca artikel praktis dari tim kami.</p>
            <a href="{{ route('blog.index') }}" class="mt-6 inline-block text-sm font-bold text-brand-700">Jelajahi Blog →</a>
        </div>
    @else
        @if ($resources->currentPage() === 1)
            @php($featured = $resources->first())
            <article class="grid gap-8 border-b border-neutral-200 py-12 lg:grid-cols-[minmax(0,0.95fr)_minmax(0,1.05fr)] lg:items-center lg:gap-14">
                <a href="{{ route('resources.e-book.show', $featured->slug) }}" class="flex min-h-[360px] items-center justify-center rounded-xl bg-[#f1f4f6] px-8 py-10 sm:min-h-[440px]">
                    <x-ebook-cover :resource="$featured" :priority="true" class="w-[205px] sm:w-[255px]" />
                </a>
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-brand-700">Pilihan Pustaka <span class="mx-2 text-neutral-300">/</span> E-book</p>
                    <h2 class="mt-4 text-3xl font-bold leading-tight tracking-tight text-navy-950 sm:text-4xl"><a href="{{ route('resources.e-book.show', $featured->slug) }}" class="hover:text-brand-700">{{ $featured->title }}</a></h2>
                    @if ($featured->excerpt)<p class="mt-5 max-w-xl text-[15px] leading-7 text-neutral-600">{{ $featured->excerpt }}</p>@endif
                    <p class="mt-6 text-sm text-neutral-500">{{ ($featured->published_at ?? $featured->created_at)?->translatedFormat('d F Y') }} @if ($featured->page_count && $featured->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($featured->file_path)) · {{ $featured->page_count }} halaman @endif</p>
                    @if (!empty($featured->chapters))
                        <div class="mt-7 border-t border-neutral-200 pt-5">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-navy-950">Di dalam panduan</p>
                            <p class="mt-2 text-sm leading-6 text-neutral-600">{{ collect($featured->chapters)->pluck('title')->filter()->take(2)->implode(' · ') }}</p>
                        </div>
                    @endif
                    <a href="{{ route('resources.e-book.show', $featured->slug) }}" class="mt-7 inline-block text-sm font-bold text-brand-700">Jelajahi e-book →</a>
                </div>
            </article>
        @endif

        @php($items = $resources->currentPage() === 1 ? $resources->getCollection()->skip(1) : $resources->getCollection())
        @if ($items->isNotEmpty())
            <div class="flex flex-wrap items-end justify-between gap-3 pt-12">
                <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-brand-700">Koleksi</p><h2 class="mt-2 text-2xl font-bold text-navy-950 sm:text-3xl">E-book lainnya</h2></div>
                <span class="text-sm text-neutral-500">Topik untuk tim bisnis dan teknologi</span>
            </div>
            <div class="mt-7 grid gap-x-10 sm:grid-cols-2">
                @foreach ($items as $resource)
                    <article class="grid grid-cols-[96px_minmax(0,1fr)] gap-5 border-t border-neutral-200 py-7 sm:grid-cols-[116px_minmax(0,1fr)] sm:gap-6">
                        <a href="{{ route('resources.e-book.show', $resource->slug) }}" aria-label="Lihat {{ $resource->title }}"><x-ebook-cover :resource="$resource" :compact="true" class="w-full" /></a>
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-brand-700">E-book <span class="mx-1 text-neutral-300">·</span> <span class="font-medium tracking-normal text-neutral-500">{{ ($resource->published_at ?? $resource->created_at)?->translatedFormat('d M Y') }}</span></p>
                            <h3 class="mt-2 text-lg font-bold leading-snug text-navy-950 sm:text-xl"><a href="{{ route('resources.e-book.show', $resource->slug) }}" class="hover:text-brand-700">{{ $resource->title }}</a></h3>
                            @if ($resource->excerpt)<p class="mt-2 text-sm leading-6 text-neutral-600">{{ \Illuminate\Support\Str::limit($resource->excerpt, 110) }}</p>@endif
                            <a href="{{ route('resources.e-book.show', $resource->slug) }}" class="mt-4 inline-block text-sm font-bold text-brand-700">Lihat isi →</a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
        <div class="mt-10">{{ $resources->links() }}</div>
    @endif
</div>
<section class="bg-[#f2f6fa] py-14">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-6 px-4 sm:px-6">
        <div><p class="text-xs font-bold uppercase tracking-[0.16em] text-brand-700">Butuh panduan khusus?</p><h2 class="mt-2 text-2xl font-bold text-navy-950 sm:text-3xl">Diskusikan kebutuhan tim Anda.</h2><p class="mt-2 text-sm text-neutral-600">Kami siap membantu memetakan tantangan dan langkah digital yang paling relevan.</p></div>
        <a href="{{ route('kontak') }}" class="inline-flex rounded-full bg-navy-950 px-7 py-3 text-sm font-bold text-white hover:bg-brand-700">Mulai diskusi →</a>
    </div>
</section>
@endsection
