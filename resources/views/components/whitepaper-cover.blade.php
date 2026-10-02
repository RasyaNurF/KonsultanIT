@props(['resource', 'compact' => false, 'priority' => false])

@php
    $accents = ['bg-[#b5c8d5]', 'bg-[#bacbbb]', 'bg-[#d3c3b5]', 'bg-[#c7c2d6]'];
    $accent = $accents[abs(crc32($resource->slug)) % count($accents)];
    $coverUrl = $resource->cover_image_path
        ? (str_starts_with($resource->cover_image_path, 'img/')
            ? asset($resource->cover_image_path)
            : \Illuminate\Support\Facades\Storage::disk('public')->url($resource->cover_image_path))
        : null;
@endphp

<div {{ $attributes->class(['relative isolate aspect-[4/5] overflow-hidden bg-[#f6f6f2] shadow-[0_18px_38px_-28px_rgba(12,29,45,0.5)]']) }}>
    @if ($coverUrl)
        <img src="{{ $coverUrl }}" alt="Sampul {{ $resource->title }}" class="h-full w-full object-cover" @if ($priority) fetchpriority="high" @else loading="lazy" @endif>
    @else
        <div class="absolute inset-x-0 top-0 h-[7%] bg-[#112b3d]" aria-hidden="true"></div>
        <div class="absolute inset-y-[7%] right-0 w-[14%] {{ $accent }}" aria-hidden="true"></div>
        <svg class="pointer-events-none absolute bottom-[14%] right-[3%] h-[38%] w-[62%] text-[#18354b]/20" viewBox="0 0 240 160" fill="none" aria-hidden="true">
            <path d="M0 20h240M0 55h240M0 90h240M0 125h240M35 0v160M95 0v160M155 0v160M215 0v160" stroke="currentColor" stroke-width=".8"/>
            <path d="M5 134c38-8 50-38 77-43 33-6 37 28 77 13 27-10 34-53 74-72" stroke="#294b5f" stroke-width="2.4"/>
            <circle cx="159" cy="104" r="4" fill="#294b5f"/>
        </svg>
        <div class="relative flex h-full flex-col justify-between {{ $compact ? 'p-3 pt-5' : 'p-6 pt-10 sm:p-8 sm:pt-12' }}">
            <div>
                <p class="font-bold uppercase text-[#39536a] {{ $compact ? 'text-[7px] tracking-[0.12em]' : 'text-[10px] tracking-[0.2em]' }}">KIT / Research Notes</p>
                <div class="mt-3 h-px w-8 bg-[#39536a]/40"></div>
            </div>
            <div class="relative z-10 pr-[10%]">
                <p class="font-bold uppercase text-[#526d81] {{ $compact ? 'text-[7px] tracking-[0.12em]' : 'text-[10px] tracking-[0.18em]' }}">Whitepaper</p>
                <p class="mt-2 line-clamp-5 font-bold leading-[1.15] tracking-tight text-[#102b3f] {{ $compact ? 'text-[12px]' : 'text-xl sm:text-[27px]' }}">{{ $resource->title }}</p>
            </div>
            <div class="relative z-10 flex items-end justify-between gap-2 text-[#102b3f]">
                <span class="font-extrabold tracking-tighter {{ $compact ? 'text-base' : 'text-2xl' }}">KiT<span class="text-[#7593a3]">.</span></span>
                <span class="font-semibold {{ $compact ? 'text-[7px]' : 'text-[10px]' }}">{{ ($resource->published_at ?? $resource->created_at)?->format('Y') }}</span>
            </div>
        </div>
    @endif
</div>
