@extends('admin.layouts.app')

@section('title', 'Kategori Solusi — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Kategori Solusi" description="Kategori besar solusi KIT Konsultan IT seperti Human Capital Management atau IT Security." eyebrow="Konten">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.solution-categories.create')" variant="primary">
            <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Kategori
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari nama atau deskripsi kategori…"
        :selects="[
            ['name' => 'status', 'label' => 'Semua status', 'options' => collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()],
        ]" />
    @if($categories->isEmpty())
        <x-admin.empty-state title="Belum ada kategori solusi." description="Tambahkan kategori pertama Anda." icon="layers">
            <x-slot:action>
                <x-admin.partials.button :href="route('admin.solution-categories.create')" variant="primary">
                    <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Kategori
                </x-admin.partials.button>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Kategori</th>
                <th class="px-5 py-2.5 font-bold">Tagline</th>
                <th class="px-5 py-2.5 font-bold">Solusi</th>
                <th class="px-5 py-2.5 font-bold">Urutan</th>
                <th class="px-5 py-2.5 font-bold">Status</th>
                <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
            </x-slot:head>
            @foreach($categories as $category)
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            @if($category->image_path)
                                <img src="{{\Illuminate\Support\Facades\Storage::disk('public')->url($category->image_path)}}" alt="{{$category->name}}" class="h-8 w-8 shrink-0 rounded border border-neutral-200 bg-white object-cover">
                            @else
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded border border-neutral-200 bg-neutral-100 text-[11px] font-bold text-neutral-500">{{mb_strtoupper(mb_substr($category->name,0,2))}}</span>
                            @endif
                            <a href="{{route('admin.solution-categories.edit',$category)}}" class="min-w-0 truncate font-semibold text-neutral-900 transition hover:text-brand-600">{{$category->name}}</a>
                        </div>
                    </td>
                    <td class="max-w-sm px-5 py-3.5 text-neutral-600"><span class="block truncate">{{$category->tagline?:'—'}}</span></td>
                    <td class="px-5 py-3.5 text-neutral-600 tabular-nums">{{$category->solutions_count}} solusi</td>
                    <td class="px-5 py-3.5 text-neutral-600 tabular-nums">{{$category->sort_order}}</td>
                    <td class="px-5 py-3.5"><x-admin.status-badge :status="$category->status" /></td>
                    <td class="px-5 py-3.5">
                        <x-admin.partials.row-actions
                            :edit="route('admin.solution-categories.edit',$category)"
                            :delete="route('admin.solution-categories.destroy',$category)"
                            delete-confirm="Hapus kategori ini? Semua solusi di dalamnya juga akan terhapus." /></td>
                </tr>
            @endforeach
        </x-admin.partials.table>
        <x-admin.partials.pagination :paginator="$categories" />

    @endif
</div>
@endsection
