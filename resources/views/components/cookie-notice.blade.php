@props(['announcement' => null])

@if ($announcement)
    <div data-cookie-notice role="region" aria-label="Persetujuan cookie" class="fixed inset-x-0 bottom-0 z-[70] border-t border-neutral-200 bg-white shadow-[0_-10px_30px_-20px_rgba(10,22,38,0.2)]">
        <div class="mx-auto flex max-w-7xl flex-col gap-5 px-4 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:gap-10 lg:py-6">
            <div class="max-w-3xl">
                <h2 class="text-base font-semibold tracking-tight text-navy-950">{{ $announcement->title ?: 'Pilihan cookie Anda' }}</h2>
                <p class="mt-1 text-sm leading-6 text-neutral-600">
                    {{ $announcement->message }}
                    @if ($announcement->link_label && $announcement->link_url)
                        <a href="{{ $announcement->link_url }}" class="font-medium text-navy-950 underline decoration-neutral-400 underline-offset-4 transition-colors hover:text-brand-700">{{ $announcement->link_label }}</a>
                    @endif
                </p>
            </div>

            <div class="flex shrink-0 gap-3">
                <button type="button" data-cookie-consent="declined" class="inline-flex min-h-11 flex-1 items-center justify-center rounded-md border border-neutral-300 px-6 py-2 text-sm font-medium text-navy-950 transition-colors hover:border-navy-950 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 sm:flex-none">Tolak</button>
                <button type="button" data-cookie-consent="accepted" class="inline-flex min-h-11 flex-1 items-center justify-center rounded-md bg-navy-950 px-6 py-2 text-sm font-semibold text-white transition-colors hover:bg-brand-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600 sm:flex-none">Terima cookie</button>
            </div>
        </div>
    </div>
@endif
