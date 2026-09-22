@props(['unread' => 0])

@auth
<div data-chat-widget
    data-messages-url="{{ route('pesan.index') }}"
    data-store-url="{{ route('pesan.store') }}"
    class="pointer-events-none fixed bottom-10 left-10 z-50">
    {{-- Panel percakapan --}}
    <section data-chat-panel hidden
        class="pointer-events-auto absolute bottom-20 left-0 flex max-h-[calc(100dvh-7rem)] w-[min(360px,calc(100vw-3rem))] flex-col overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-2xl shadow-black/20">
        <header class="flex items-start justify-between gap-3 bg-navy-950 px-5 py-4 text-white">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20.5 11.6a8.4 8.4 0 0 1-12.2 7.5L3.5 20.5l1.5-5.2a8.4 8.4 0 1 1 15.5-3.7Z"/>
                    </svg>
                </span>
                <span>
                    <span class="block text-sm font-bold">Tim Nusakode</span>
                    <span class="mt-0.5 flex items-center gap-1.5 text-[11px] text-white/60">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400" aria-hidden="true"></span>
                        Biasanya membalas cepat
                    </span>
                </span>
            </div>
            <button type="button" data-chat-close aria-label="Tutup percakapan"
                class="rounded-full p-1.5 text-white/70 transition hover:bg-white/10 hover:text-white">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
            </button>
        </header>

        <div data-chat-messages role="log" aria-live="polite" class="flex max-h-[min(380px,50dvh)] min-h-[200px] flex-col gap-3 overflow-y-auto bg-neutral-50 p-4">
            <p data-chat-empty class="m-auto px-6 text-center text-[13px] text-neutral-500">Memuat percakapan…</p>
        </div>

        <form data-chat-form method="post" action="{{ route('pesan.store') }}" class="border-t border-neutral-200 bg-white p-4">
            @csrf

            <p data-chat-error class="mb-3 hidden border-l-2 border-red-600 bg-red-50 px-3 py-2 text-[13px] text-red-700" role="alert"></p>

            <div class="flex items-end gap-2">
                <textarea data-chat-body name="body" rows="1" required maxlength="2000" placeholder="Tulis pesan…"
                    class="max-h-28 min-h-[42px] w-full resize-none border border-neutral-200 px-3 py-2.5 text-sm text-navy-950 outline-none transition placeholder:text-neutral-400 focus:border-brand-600 focus:ring-2 focus:ring-brand-100"></textarea>
                <button type="submit" aria-label="Kirim pesan"
                    class="flex h-[42px] w-[42px] shrink-0 items-center justify-center rounded-full bg-brand-600 text-white transition hover:bg-brand-700 disabled:cursor-not-allowed disabled:opacity-60">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.5 19.5 21 12 4.5 4.5l2.6 6.4L21 12l-13.9 1.1-2.6 6.4Z"/></svg>
                </button>
            </div>
        </form>
    </section>

    {{-- Bubble --}}
    <button type="button" data-chat-toggle aria-expanded="false" aria-label="Buka pesan"
        class="pointer-events-auto group relative flex h-16 w-16 items-center justify-center rounded-full bg-navy-950 text-white shadow-xl shadow-black/25 transition duration-300 hover:scale-105 hover:bg-navy-800">
        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-navy-950 opacity-20" aria-hidden="true"></span>
        <svg class="relative h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M20.5 11.6a8.4 8.4 0 0 1-12.2 7.5L3.5 20.5l1.5-5.2a8.4 8.4 0 1 1 15.5-3.7Z"/>
            <circle cx="9" cy="11.6" r="1" fill="currentColor" stroke="none"/>
            <circle cx="12.5" cy="11.6" r="1" fill="currentColor" stroke="none"/>
            <circle cx="16" cy="11.6" r="1" fill="currentColor" stroke="none"/>
        </svg>
        <span data-chat-badge @class([
            'absolute -right-0.5 -top-0.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-[11px] font-bold text-white',
            'hidden' => $unread === 0,
        ])>{{ $unread > 9 ? '9+' : $unread }}</span>
    </button>
</div>
@endauth
