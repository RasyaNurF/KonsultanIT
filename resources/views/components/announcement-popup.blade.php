@props(['announcement' => null])

@if ($announcement)
    @php($hasImage = filled($announcement->image_path))
    <div data-announcement-popup
        data-popup-cookie="kit_popup_seen"
        data-popup-value="{{ $announcement->id }}"
        data-popup-scope="{{ $announcement->frequency->value }}"
        data-popup-days="{{ $announcement->frequency_days }}"
        data-popup-dismissible="{{ $announcement->is_dismissible ? '1' : '0' }}"
        class="fixed inset-0 z-[80] hidden items-center justify-center overflow-y-auto p-4 sm:p-8">
        <div data-popup-backdrop class="announcement-backdrop-in fixed inset-0 bg-[#101c2b]/55"></div>

        <div role="dialog" aria-modal="true" aria-labelledby="announcement-title" aria-describedby="announcement-message"
            class="announcement-in relative my-auto w-full {{ $hasImage ? 'max-w-[860px]' : 'max-w-[540px]' }} overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-[0_24px_72px_-24px_rgba(10,22,38,0.28)]">
            <div class="{{ $hasImage ? 'flex flex-col lg:grid lg:grid-cols-[1fr_0.9fr]' : '' }}">
                <div class="flex min-w-0 flex-col justify-center p-7 sm:p-10 lg:p-12">
                    <div class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.14em] text-neutral-500">
                        <span class="h-px w-8 bg-brand-600" aria-hidden="true"></span>
                        Informasi dari KIT
                    </div>

                    <h2 id="announcement-title" class="mt-6 max-w-[20ch] text-[26px] font-semibold leading-[1.2] tracking-tight text-navy-950 sm:text-[32px]">{{ $announcement->title ?: 'Pengumuman' }}</h2>
                    <p id="announcement-message" class="mt-4 whitespace-pre-line text-[15px] leading-7 text-neutral-600">{{ $announcement->message }}</p>

                    @if (($announcement->link_label && $announcement->link_url) || $announcement->is_dismissible)
                        <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-4 border-t border-neutral-200 pt-6">
                            @if ($announcement->link_label && $announcement->link_url)
                                <a href="{{ $announcement->link_url }}" data-popup-dismiss
                                    class="inline-flex min-h-11 items-center justify-center gap-3 rounded-md bg-navy-950 px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-brand-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
                                    {{ $announcement->link_label }}
                                    <span aria-hidden="true">→</span>
                                </a>
                            @endif
                            @if ($announcement->is_dismissible)
                                <button type="button" data-popup-close class="min-h-11 text-sm font-medium text-neutral-600 underline decoration-neutral-300 underline-offset-4 transition-colors hover:text-navy-950 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">{{ $announcement->link_label && $announcement->link_url ? 'Nanti saja' : 'Tutup' }}</button>
                            @endif
                        </div>
                    @endif
                </div>

                @if ($hasImage)
                    <div class="order-first h-48 bg-neutral-100 lg:order-last lg:h-full lg:min-h-[390px]">
                        <img src="{{ $announcement->imageUrl() }}" alt="" class="h-full w-full object-cover">
                    </div>
                @endif
            </div>

            @if ($announcement->is_dismissible)
                <button type="button" data-popup-close aria-label="Tutup pengumuman"
                    class="absolute right-4 top-4 z-10 flex h-9 w-9 items-center justify-center rounded-md border border-neutral-200 bg-white text-neutral-600 transition-colors hover:border-neutral-300 hover:text-navy-950 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M5 5l14 14M19 5 5 19"/></svg>
                </button>
            @endif
        </div>
    </div>
@endif
