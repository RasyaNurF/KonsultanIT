@props(['resource', 'compact' => false, 'priority' => false])

@php
    $palettes = [
        ['bg' => 'bg-[#183345]', 'accent' => 'text-[#abd9de]'],
        ['bg' => 'bg-[#24413a]', 'accent' => 'text-[#c5d8b0]'],
        ['bg' => 'bg-[#303450]', 'accent' => 'text-[#c7c9ed]'],
        ['bg' => 'bg-[#3d3542]', 'accent' => 'text-[#e4c8d5]'],
    ];
    $palette = $palettes[abs(crc32($resource->slug)) % count($palettes)];
    $coverUrl = $resource->cover_image_path
        ? (str_starts_with($resource->cover_image_path, 'img/')
            ? asset($resource->cover_image_path)
            : \Illuminate\Support\Facades\Storage::disk('public')->url($resource->cover_image_path))
        : null;
@endphp

<div {{ $attributes->class(['relative isolate aspect-[3/4] overflow-hidden rounded-md shadow-[0_18px_38px_-24px_rgba(7,24,39,0.65)]', $palette['bg']]) }}>
    @if ($coverUrl)
        <img src="{{ $coverUrl }}" alt="Sampul {{ $resource->title }}" class="h-full w-full object-cover" @if ($priority) fetchpriority="high" @else loading="lazy" @endif>
    @else
        <svg class="pointer-events-none absolute -right-1/3 top-[12%] h-[72%] w-[120%] text-white/12" viewBox="0 0 300 300" fill="none" aria-hidden="true">
            <circle cx="180" cy="150" r="126" stroke="currentColor" stroke-width="1"/>
            <circle cx="180" cy="150" r="94" stroke="currentColor" stroke-width="1"/>
            <circle cx="180" cy="150" r="62" stroke="currentColor" stroke-width="1"/>
            <path d="M26 237c58-75 110-85 160-36 31 30 58 35 89 19" stroke="currentColor" stroke-width="1.2"/>
            <path d="M29 260c55-72 108-83 158-35 31 30 58 35 89 19" stroke="currentColor" stroke-width="1.2"/>
        </svg>
        <div class="relative flex h-full flex-col justify-between text-white {{ $compact ? 'p-3' : 'p-6 sm:p-8' }}">
            <div>
                <p class="font-bold uppercase {{ $palette['accent'] }} {{ $compact ? 'text-[7px] tracking-[0.15em]' : 'text-[10px] tracking-[0.22em]' }}">KIT · Pustaka Digital</p>
                <div class="mt-4 h-px w-10 bg-white/50"></div>
            </div>
            <div>
                <p class="font-bold uppercase {{ $palette['accent'] }} {{ $compact ? 'text-[8px] tracking-[0.14em]' : 'text-[11px] tracking-[0.2em]' }}">E-book / Panduan</p>
                <p class="mt-2 line-clamp-4 font-bold leading-[1.15] tracking-tight {{ $compact ? 'text-[13px]' : 'text-2xl sm:text-3xl' }}">{{ $resource->title }}</p>
            </div>
            <p class="font-extrabold tracking-tighter {{ $compact ? 'text-lg' : 'text-3xl' }}">KiT<span class="{{ $palette['accent'] }}">.</span></p>
        </div>
        <div class="pointer-events-none absolute inset-y-0 left-0 w-2 bg-black/15" aria-hidden="true"></div>
    @endif
</div>
