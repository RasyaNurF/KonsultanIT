@extends('admin.layouts.app')

@section('title', 'SEO — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="SEO" description="Judul, deskripsi, dan pengaturan indeks per halaman." eyebrow="Website">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.seo.create')" variant="primary">
            <x-admin.icon name="plus" class="h-4 w-4" /> Tambah SEO
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari path, label, atau judul…"
        :selects="[]" />

    @if ($seo->isEmpty())
        <x-admin.empty-state title="Belum ada pengaturan SEO." description="Tambahkan halaman pertama Anda." icon="search">
            <x-slot:action>
                <x-admin.partials.button :href="route('admin.seo.create')" variant="primary">
                    <x-admin.icon name="plus" class="h-4 w-4" /> Tambah SEO
                </x-admin.partials.button>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Halaman</th>
                <th class="px-5 py-2.5 font-bold">SEO Title</th>
                <th class="px-5 py-2.5 font-bold">Meta Description</th>
                <th class="px-5 py-2.5 font-bold">Canonical</th>
                <th class="px-5 py-2.5 font-bold">Indeks</th>
                <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
            </x-slot:head>

            @foreach ($seo as $item)
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <a href="{{ route('admin.seo.edit', $item) }}" class="block font-semibold text-neutral-900 transition hover:text-brand-600">{{ $item->label }}</a>
                        <span class="block font-mono text-xs text-neutral-500">{{ $item->path }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-neutral-600">{{ $item->title ? Str::limit($item->title, 60) : '—' }}</td>
                    <td class="px-5 py-3.5 text-neutral-600">{{ $item->description ? Str::limit($item->description, 70) : '—' }}</td>
                    <td class="px-5 py-3.5 text-neutral-600">{{ $item->canonical_url ? Str::limit($item->canonical_url, 40) : '—' }}</td>
                    <td class="px-5 py-3.5">
                        @if ($item->is_indexable)
                            <x-admin.badge tone="emerald">Index</x-admin.badge>
                        @else
                            <x-admin.badge tone="neutral">No Index</x-admin.badge>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <x-admin.partials.row-actions
                            :edit="route('admin.seo.edit', $item)"
                            :delete="route('admin.seo.destroy', $item)"
                            delete-confirm="Hapus pengaturan SEO ini? Tindakan tidak dapat dibatalkan." />
                    </td>
                </tr>
            @endforeach
        </x-admin.partials.table>

        <x-admin.partials.pagination :paginator="$seo" />
    @endif
</div>
@endsection
