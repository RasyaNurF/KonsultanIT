@extends('admin.layouts.app')

@section('title', 'Media Library — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Media Library" description="Gambar dan dokumen yang dipakai di seluruh website." eyebrow="Konten" />

<x-admin.card class="mt-6">
    <form method="post" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" data-resize-upload class="p-5">
        @csrf
        <div class="grid gap-4 sm:grid-cols-[1fr_1fr_auto]">
            <input type="file" name="file" accept="image/*,.pdf" required
                class="h-10 w-full rounded-md border border-neutral-200 px-3 py-2 text-sm text-neutral-900 outline-none transition file:mr-3 file:rounded file:border-0 file:bg-neutral-100 file:px-3 file:py-1.5 file:text-[13px] file:font-semibold file:text-neutral-700 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
            <input type="text" name="alt_text" placeholder="Alt text (opsional)" maxlength="255"
                class="h-10 w-full rounded-md border border-neutral-200 px-3 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
            <x-admin.partials.button type="submit" variant="primary">
                <x-admin.icon name="upload" class="h-4 w-4" /> Unggah
            </x-admin.partials.button>
        </div>
        <p data-upload-note class="mt-2 text-xs text-neutral-400">PNG, JPG, WebP, SVG, atau PDF. Maks 20 MB.</p>
    </form>
</x-admin.card>

<x-admin.card class="mt-6">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari nama file, judul, atau alt text…"
        :selects="[]" />

    @if ($media->isEmpty())
        <x-admin.empty-state title="Belum ada media." description="Unggah gambar pertama Anda." icon="image" />
    @else
        <div class="grid grid-cols-2 gap-4 p-5 sm:grid-cols-3 lg:grid-cols-4">
            @foreach ($media as $item)
                <div class="group overflow-hidden rounded-lg border border-neutral-200">
                    <div class="flex aspect-[4/3] items-center justify-center bg-neutral-50">
                        @if ($item->isImage())
                            <img src="{{ $item->url() }}" alt="{{ $item->alt_text ?: $item->original_name }}" class="h-full w-full object-cover">
                        @else
                            <x-admin.icon name="article" class="h-8 w-8 text-neutral-300" />
                        @endif
                    </div>
                    <div class="p-3">
                        <p class="truncate text-[12px] font-semibold text-neutral-800">{{ $item->original_name }}</p>
                        <p class="mt-0.5 truncate text-[11px] text-neutral-400">{{ $item->humanSize() }}@if ($item->alt_text) · {{ $item->alt_text }}@endif</p>

                        <div class="mt-2 flex items-center gap-1.5">
                            <form method="post" action="{{ route('admin.media.update', $item) }}" class="flex flex-1 items-center gap-1.5">
                                @csrf
                                @method('put')
                                <input type="text" name="alt_text" value="{{ $item->alt_text }}" placeholder="Alt text" maxlength="255"
                                    class="h-8 w-full rounded-md border border-neutral-200 px-2 text-[12px] text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                                <button type="submit" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md text-neutral-500 transition hover:bg-neutral-100 hover:text-emerald-600" title="Simpan alt text" aria-label="Simpan alt text">
                                    <x-admin.icon name="check" class="h-4 w-4" />
                                </button>
                            </form>
                            <form method="post" action="{{ route('admin.media.destroy', $item) }}" data-confirm="Hapus media ini? Tindakan tidak dapat dibatalkan.">
                                @csrf
                                @method('delete')
                                <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-md text-neutral-400 transition hover:bg-red-50 hover:text-red-600" title="Hapus" aria-label="Hapus">
                                    <x-admin.icon name="trash" class="h-4 w-4" />
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <x-admin.partials.pagination :paginator="$media" />
    @endif
</x-admin.card>
@endsection
