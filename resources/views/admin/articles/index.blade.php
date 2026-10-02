@extends('admin.layouts.app')

@section('title', 'Artikel — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Artikel" description="Tulisan dan insight yang dipublikasikan di website." eyebrow="Konten">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.articles.create')" variant="primary">
            <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Artikel
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari judul, kategori, atau tag…"
        :selects="[
            ['name' => 'status', 'label' => 'Semua status', 'options' => collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()],
            ['name' => 'category', 'label' => 'Semua kategori', 'options' => $categories->mapWithKeys(fn ($c) => [$c->id => $c->name])->all()],
        ]" />

    @if ($articles->isEmpty())
        <x-admin.empty-state title="Belum ada artikel." description="Tulis artikel pertama Anda." icon="article">
            <x-slot:action>
                <x-admin.partials.button :href="route('admin.articles.create')" variant="primary">
                    <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Artikel
                </x-admin.partials.button>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Artikel</th>
                <th class="px-5 py-2.5 font-bold">Kategori</th>
                <th class="px-5 py-2.5 font-bold">Penulis</th>
                <th class="px-5 py-2.5 font-bold">Status</th>
                <th class="px-5 py-2.5 font-bold">Publikasi</th>
                <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
            </x-slot:head>

            @foreach ($articles as $article)
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            @if ($article->featured_image_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($article->featured_image_path) }}" alt="{{ $article->title }}" class="h-7 w-10 shrink-0 rounded border border-neutral-200 bg-white object-cover">
                            @else
                                <span class="flex h-7 w-10 shrink-0 items-center justify-center rounded border border-neutral-200 bg-neutral-50 text-neutral-400">
                                    <x-admin.icon name="article" class="h-3.5 w-3.5" />
                                </span>
                            @endif
                            <span class="min-w-0">
                                <a href="{{ route('admin.articles.edit', $article) }}" class="block truncate font-semibold text-neutral-900 transition hover:text-brand-600">{{ $article->title }}</a>
                                @if ($article->excerpt)
                                    <span class="block truncate text-xs text-neutral-500">{{ Str::limit($article->excerpt, 70) }}</span>
                                @endif
                            </span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-neutral-600">{{ $article->blogCategory?->name ?? $article->category ?: '—' }}</td>
                    <td class="px-5 py-3.5 text-neutral-600">{{ $article->author?->name ?? '—' }}</td>
                    <td class="px-5 py-3.5"><x-admin.status-badge :status="$article->status" /></td>
                    <td class="whitespace-nowrap px-5 py-3.5 text-neutral-500 tabular-nums">{{ $article->published_at?->translatedFormat('d M Y') ?? '—' }}</td>
                    <td class="px-5 py-3.5">
                        <x-admin.partials.row-actions
                            :edit="route('admin.articles.edit', $article)"
                            :delete="route('admin.articles.destroy', $article)"
                            delete-confirm="Hapus artikel ini? Tindakan tidak dapat dibatalkan." />
                    </td>
                </tr>
            @endforeach
        </x-admin.partials.table>

        <x-admin.partials.pagination :paginator="$articles" />
    @endif
</div>
@endsection
