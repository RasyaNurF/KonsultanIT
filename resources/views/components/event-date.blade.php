@props(['resource', 'compact' => false])

<div {{ $attributes->class(['flex aspect-square flex-col items-center justify-center border border-neutral-200 bg-white text-center text-navy-950']) }}>
    @if ($resource->starts_at)
        <span class="font-bold uppercase tracking-[0.16em] text-neutral-500 {{ $compact ? 'text-[9px]' : 'text-xs' }}">{{ $resource->starts_at->locale('id')->translatedFormat('M') }}</span>
        <span class="font-extrabold leading-none tabular-nums {{ $compact ? 'mt-1 text-3xl' : 'mt-2 text-7xl' }}">{{ $resource->starts_at->format('d') }}</span>
        <span class="font-medium text-neutral-500 {{ $compact ? 'mt-1 text-[10px]' : 'mt-3 text-sm' }}">{{ $resource->starts_at->format('Y') }}</span>
    @else
        <span class="font-bold uppercase tracking-[0.12em] text-neutral-600 {{ $compact ? 'text-[9px]' : 'text-sm' }}">Jadwal<br>menyusul</span>
    @endif
</div>
