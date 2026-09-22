@props(['announcement' => null])

@if ($announcement)
    <div data-cookie-notice role="region" aria-label="Persetujuan cookie" class="pointer-events-none fixed inset-x-0 bottom-0 z-[70] p-4 sm:p-6">
        <div class="pointer-events-auto mx-auto mb-28 flex max-w-2xl flex-col gap-4 rounded-2xl border border-neutral-200 bg-white p-4 shadow-2xl shadow-navy-950/20 sm:mb-0 sm:flex-row sm:items-center sm:gap-5 sm:p-5">
            <div class="min-w-0 flex-1">
                @if ($announcement->title)
                    <p class="text-sm font-bold text-navy-950">{{ $announcement->title }}</p>
                @endif
                <p class="mt-0.5 text-[13px] leading-relaxed text-neutral-600">
                    {{ $announcement->message }}
                    @if ($announcement->link_label && $announcement->link_url)
                        <a href="{{ $announcement->link_url }}" class="font-semibold text-brand-700 underline underline-offset-2 transition hover:text-brand-600">{{ $announcement->link_label }}</a>
                    @endif
                </p>
            </div>

            <div class="flex shrink-0 gap-2">
                <button type="button" data-cookie-consent="declined" class="inline-flex flex-1 items-center justify-center rounded-full bg-neutral-900 px-5 py-2.5 text-[13px] font-bold text-white transition hover:bg-neutral-700 sm:flex-none">Tolak</button>
                <button type="button" data-cookie-consent="accepted" class="inline-flex flex-1 items-center justify-center rounded-full bg-brand-600 px-5 py-2.5 text-[13px] font-bold text-white transition hover:bg-brand-500 sm:flex-none">Setuju</button>
            </div>
        </div>
    </div>
@endif
