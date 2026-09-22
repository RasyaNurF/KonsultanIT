@props(['announcement' => null])

@if ($announcement)
    <div data-announcement-popup
        data-popup-cookie="nusakode_popup_seen"
        data-popup-value="{{ $announcement->id }}"
        data-popup-scope="{{ $announcement->frequency->value }}"
        data-popup-days="{{ $announcement->frequency_days }}"
        data-popup-dismissible="{{ $announcement->is_dismissible ? '1' : '0' }}"
        class="fixed inset-0 z-[80] hidden items-start justify-center overflow-y-auto p-4 sm:p-6">
        <div data-popup-backdrop class="announcement-backdrop-in fixed inset-0 bg-navy-950/60 backdrop-blur-sm"></div>

        <div role="dialog" aria-modal="true" aria-label="{{ $announcement->title ?: 'Pengumuman' }}"
            class="announcement-in relative my-auto w-full {{ filled($announcement->image_path) ? 'max-w-4xl' : 'max-w-md' }} overflow-hidden rounded-2xl bg-white shadow-2xl shadow-navy-950/25 ring-1 ring-neutral-950/5">
            @unless (filled($announcement->image_path))
                <span class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-navy-900 via-brand-600 to-brand-400" aria-hidden="true"></span>
            @endunless

            <div class="{{ filled($announcement->image_path) ? 'sm:grid sm:grid-cols-[minmax(0,1fr)_minmax(0,1.1fr)]' : '' }}">
                @if (filled($announcement->image_path))
                    <div class="relative h-44 sm:h-auto sm:min-h-[320px]">
                        <img src="{{ $announcement->imageUrl() }}" alt="" class="absolute inset-0 h-full w-full object-cover">
                    </div>
                @endif

                <div class="{{ filled($announcement->image_path) ? 'p-7 sm:p-9' : 'p-7 pt-9 sm:p-8 sm:pt-10' }}">
                    <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-brand-600">Pengumuman</p>

                    @if ($announcement->title)
                        <h2 class="mt-2.5 text-xl font-bold leading-snug tracking-tight text-navy-950 sm:text-2xl">{{ $announcement->title }}</h2>
                    @endif

                    <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-neutral-500">{{ $announcement->message }}</p>

                    <div class="mt-7 flex flex-wrap items-center gap-x-6 gap-y-3">
                        @if ($announcement->link_label && $announcement->link_url)
                            <a href="{{ $announcement->link_url }}" data-popup-dismiss
                                class="inline-flex items-center gap-2 rounded-full bg-navy-950 px-6 py-3 text-sm font-bold text-white transition hover:bg-navy-800">
                                {{ $announcement->link_label }}
                                <svg class="h-4 w-4 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </a>
                        @endif
                        <button type="button" data-popup-close class="text-sm font-semibold text-neutral-500 transition hover:text-navy-950">Tutup</button>
                    </div>
                </div>
            </div>

            @if ($announcement->is_dismissible)
                <button type="button" data-popup-close aria-label="Tutup"
                    class="absolute right-3 top-3 z-10 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/85 text-neutral-500 ring-1 ring-black/5 backdrop-blur transition hover:bg-white hover:text-navy-950">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
            @endif
        </div>
    </div>
@endif
