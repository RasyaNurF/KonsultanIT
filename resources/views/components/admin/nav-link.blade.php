@php
$badgeCount = is_numeric($badge) ? (int) $badge : 0;
@endphp

<a href="{{ $href }}" @if ($active) aria-current="page" @endif
    class="group flex items-center gap-2.5 rounded-md px-3 py-2 transition {{ $active ? 'bg-white/[0.09] font-semibold text-white' : 'font-medium text-white/60 hover:bg-white/[0.05] hover:text-white/90' }}">
    <x-admin.icon :name="$icon" class="h-4 w-4 {{ $active ? 'text-brand-400' : 'text-white/40 group-hover:text-white/70' }}" />
    <span class="flex-1 truncate">{{ $slot }}</span>
    @if ($badgeCount > 0 || $badgeTarget)
        <span @if ($badgeTarget) data-admin-badge="{{ $badgeTarget }}" @endif
            class="rounded-full bg-brand-600 px-1.5 py-0.5 text-[10px] font-bold leading-none text-white {{ $badgeCount > 0 ? '' : 'hidden' }}">{{ $badgeCount > 99 ? '99+' : $badgeCount }}</span>
    @endif
</a>
