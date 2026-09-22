@extends('admin.layouts.app')

@section('title', $portfolio->title.' — Admin Nusakode')

@section('content')
<x-admin.page-header
    :title="$portfolio->title"
    eyebrow="Portfolio"
    :description="trim(($portfolio->client?->name ?? '').($portfolio->category ? ' · '.$portfolio->category : ''), ' ·') ?: 'Detail portfolio.'">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.portfolios.edit', $portfolio)" variant="secondary">
            <x-admin.icon name="edit" class="h-4 w-4" /> Ubah
        </x-admin.partials.button>
        <form method="post" action="{{ route('admin.portfolios.destroy', $portfolio) }}" data-confirm="Hapus portfolio ini? Tindakan tidak dapat dibatalkan.">
            @csrf
            @method('delete')
            <x-admin.partials.button type="submit" variant="danger">
                <x-admin.icon name="trash" class="h-4 w-4" /> Hapus
            </x-admin.partials.button>
        </form>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-8 grid gap-8 lg:grid-cols-[minmax(0,1.6fr)_minmax(0,1fr)]">
    <div class="min-w-0 space-y-8">
        @if ($portfolio->thumbnail_path)
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($portfolio->thumbnail_path) }}" alt="{{ $portfolio->title }}" class="aspect-video w-full rounded-lg border border-neutral-200 object-cover">
        @endif

        @if ($portfolio->description)
            <section aria-labelledby="deskripsi">
                <h2 id="deskripsi" class="border-b border-neutral-200 pb-3 text-[13px] font-semibold text-neutral-900">Deskripsi</h2>
                <p class="mt-4 whitespace-pre-line text-sm leading-relaxed text-neutral-600">{{ $portfolio->description }}</p>
            </section>
        @endif

        @foreach (['challenge' => 'Tantangan', 'solution' => 'Solusi', 'result' => 'Hasil'] as $field => $label)
            @if ($portfolio->$field)
                <section aria-labelledby="portfolio-{{ $field }}">
                    <h2 id="portfolio-{{ $field }}" class="border-b border-neutral-200 pb-3 text-[13px] font-semibold text-neutral-900">{{ $label }}</h2>
                    <p class="mt-4 whitespace-pre-line text-sm leading-relaxed text-neutral-600">{{ $portfolio->$field }}</p>
                </section>
            @endif
        @endforeach

        <section aria-labelledby="gallery-baru">
            <h2 id="gallery-baru" class="border-b border-neutral-200 pb-3 text-[13px] font-semibold text-neutral-900">Galeri</h2>
            @if ($portfolio->images->isEmpty())
                <p class="mt-4 text-sm text-neutral-500">Belum ada gambar galeri. Unggah gambar pertama di bawah.</p>
            @else
                <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                    @foreach ($portfolio->images as $image)
                        <figure class="overflow-hidden rounded-lg border border-neutral-200 bg-white">
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image->image_path) }}" alt="{{ $image->caption ?: 'Galeri '.$portfolio->title }}" class="aspect-video w-full object-cover">
                            <figcaption class="flex items-center justify-between gap-2 px-3 py-2">
                                <span class="truncate text-xs text-neutral-500">{{ $image->caption ?: 'Tanpa keterangan' }}</span>
                                <form method="post" action="{{ route('admin.portfolio-images.destroy', [$portfolio, $image]) }}" data-confirm="Hapus gambar galeri ini?">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="text-xs font-semibold text-red-600 transition hover:text-red-700">Hapus</button>
                                </form>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            @endif

            <form method="post" enctype="multipart/form-data" action="{{ route('admin.portfolio-images.store', $portfolio) }}" class="mt-4 rounded-lg border border-dashed border-neutral-300 bg-neutral-50/60 p-4">
                @csrf
                <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto] sm:items-end">
                    <div>
                        <label for="gallery-image" class="text-xs font-bold uppercase tracking-[0.12em] text-neutral-500">Gambar</label>
                        <input id="gallery-image" type="file" name="image_file" accept="image/*" required
                            class="mt-1.5 block w-full text-sm text-neutral-700 file:mr-3 file:rounded-md file:border file:border-neutral-200 file:bg-white file:px-3 file:py-2 file:text-[13px] file:font-semibold file:text-neutral-800">
                        @error('image_file')
                            <p class="mt-1.5 text-xs font-medium text-red-700">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="gallery-caption" class="text-xs font-bold uppercase tracking-[0.12em] text-neutral-500">Keterangan</label>
                        <input id="gallery-caption" type="text" name="caption" maxlength="255" value="{{ old('caption') }}"
                            class="mt-1.5 h-10 w-full rounded-md border border-neutral-200 bg-white px-3 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                    </div>
                    <x-admin.partials.button type="submit" variant="secondary">Unggah</x-admin.partials.button>
                </div>
            </form>
        </section>

        @if (! empty($portfolio->gallery))
            <section aria-labelledby="gallery">
                <h2 id="gallery" class="border-b border-neutral-200 pb-3 text-[13px] font-semibold text-neutral-900">Gallery</h2>
                <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                    @foreach ($portfolio->gallery as $image)
                        @continue(! $image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image) }}" alt="Gallery {{ $portfolio->title }}" class="aspect-video w-full rounded-lg border border-neutral-200 bg-white object-cover">
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    <aside class="min-w-0">
        <x-admin.card title="Informasi">
            <dl class="divide-y divide-neutral-100 text-[13px]">
                <div class="flex items-start justify-between gap-4 px-5 py-3">
                    <dt class="text-neutral-500">Klien</dt>
                    <dd class="text-right font-medium text-neutral-900">{{ $portfolio->client?->name ?? '—' }}</dd>
                </div>
                <div class="flex items-start justify-between gap-4 px-5 py-3">
                    <dt class="text-neutral-500">Proyek</dt>
                    <dd class="text-right font-medium text-neutral-900">{{ $portfolio->project?->name ?? '—' }}</dd>
                </div>
                <div class="flex items-start justify-between gap-4 px-5 py-3">
                    <dt class="text-neutral-500">Kategori</dt>
                    <dd class="text-right font-medium text-neutral-900">{{ $portfolio->category ?: '—' }}</dd>
                </div>
                <div class="flex items-start justify-between gap-4 px-5 py-3">
                    <dt class="text-neutral-500">Tahun</dt>
                    <dd class="text-right font-medium text-neutral-900 tabular-nums">{{ $portfolio->year ?: '—' }}</dd>
                </div>
                <div class="flex items-start justify-between gap-4 px-5 py-3">
                    <dt class="text-neutral-500">URL</dt>
                    <dd class="min-w-0 text-right font-medium">
                        @if ($portfolio->url)
                            <a href="{{ $portfolio->url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-brand-600 transition hover:text-brand-700">
                                {{ $portfolio->url }} <x-admin.icon name="external" class="h-3.5 w-3.5" />
                            </a>
                        @else
                            <span class="text-neutral-400">—</span>
                        @endif
                    </dd>
                </div>
                <div class="flex items-start justify-between gap-4 px-5 py-3">
                    <dt class="text-neutral-500">Teknologi</dt>
                    <dd class="flex flex-wrap justify-end gap-1">
                        @forelse ($portfolio->technologyList() as $tech)
                            <x-admin.badge tone="neutral">{{ $tech }}</x-admin.badge>
                        @empty
                            <span class="text-neutral-400">—</span>
                        @endforelse
                    </dd>
                </div>
                <div class="flex items-start justify-between gap-4 px-5 py-3">
                    <dt class="text-neutral-500">Status</dt>
                    <dd><x-admin.status-badge :status="$portfolio->status" /></dd>
                </div>
                <div class="flex items-start justify-between gap-4 px-5 py-3">
                    <dt class="text-neutral-500">Unggulan</dt>
                    <dd>
                        @if ($portfolio->is_featured)
                            <x-admin.badge tone="violet">Unggulan</x-admin.badge>
                        @else
                            <span class="text-neutral-400">—</span>
                        @endif
                    </dd>
                </div>
                <div class="flex items-start justify-between gap-4 px-5 py-3">
                    <dt class="text-neutral-500">Dibuat</dt>
                    <dd class="text-right font-medium text-neutral-900 tabular-nums">{{ $portfolio->created_at->translatedFormat('d M Y H:i') }}</dd>
                </div>
                <div class="flex items-start justify-between gap-4 px-5 py-3">
                    <dt class="text-neutral-500">Diubah</dt>
                    <dd class="text-right font-medium text-neutral-900 tabular-nums">{{ $portfolio->updated_at->translatedFormat('d M Y H:i') }}</dd>
                </div>
            </dl>
        </x-admin.card>
    </aside>
</div>
@endsection
