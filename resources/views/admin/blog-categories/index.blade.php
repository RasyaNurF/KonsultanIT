@extends('admin.layouts.app')

@section('title', 'Kategori Blog — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Kategori Blog" description="Kelompok artikel agar mudah dijelajahi." eyebrow="Konten">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.blog-categories.create')" variant="primary">
            <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Kategori
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari nama kategori…"
        :selects="[
            ['name' => 'status', 'label' => 'Semua status', 'options' => collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()],
        ]" />

    @if ($categories->isEmpty())
        <x-admin.empty-state title="Belum ada kategori." description="Tambahkan kategori pertama Anda." icon="article">
            <x-slot:action>
                <x-admin.partials.button :href="route('admin.blog-categories.create')" variant="primary">
                    <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Kategori
                </x-admin.partials.button>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Kategori</th>
                <th class="px-5 py-2.5 font-bold">Artikel</th>
                <th class="px-5 py-2.5 font-bold">Urutan</th>
                <th class="px-5 py-2.5 font-bold">Status</th>
                <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
            </x-slot:head>

            @foreach ($categories as $category)
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <a href="{{ route('admin.blog-categories.edit', $category) }}" class="block font-semibold text-neutral-900 transition hover:text-brand-600">{{ $category->name }}</a>
                        @if ($category->description)
                            <span class="block max-w-md truncate text-xs text-neutral-500">{{ \Illuminate\Support\Str::limit($category->description, 80) }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-neutral-600 tabular-nums">{{ $category->articles_count }}</td>
                    <td class="px-5 py-3.5 text-neutral-600 tabular-nums">{{ $category->sort_order }}</td>
                    <td class="px-5 py-3.5"><x-admin.status-badge :status="$category->status" /></td>
                    <td class="px-5 py-3.5">
                        <x-admin.partials.row-actions
                            :edit="route('admin.blog-categories.edit', $category)"
                            :delete="route('admin.blog-categories.destroy', $category)"
                            delete-confirm="Hapus kategori ini? Artikel terkait tidak ikut terhapus." />
                    </td>
                </tr>
            @endforeach
        </x-admin.partials.table>

        <x-admin.partials.pagination :paginator="$categories" />
    @endif
</div>
@endsection
