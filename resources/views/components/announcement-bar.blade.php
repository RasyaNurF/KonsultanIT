@props(['announcement' => null])

@if ($announcement)
    <div id="promo-banner" data-promo
        data-dismiss-cookie="nusakode_bar_dismissed"
        data-dismiss-value="{{ $announcement->id }}"
        class="bg-brand-600 text-white">
        <div class="mx-auto flex max-w-7xl items-center justify-center gap-3 px-4 py-2 sm:px-6">
            <p class="line-clamp-2 text-balance text-center text-[13px] font-medium sm:line-clamp-1">
                {{ $announcement->message }}
                @if ($announcement->link_label && $announcement->link_url)
                    <a href="{{ $announcement->link_url }}" class="font-bold underline decoration-white/50 underline-offset-2 transition hover:decoration-white">{{ $announcement->link_label }}</a>
                @endif
            </p>
            @if ($announcement->is_dismissible)
                <button type="button" data-promo-close aria-label="Tutup pengumuman" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-white/80 transition hover:bg-white/15 hover:text-white">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
            @endif
        </div>
    </div>
@endif
