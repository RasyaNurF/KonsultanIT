@extends('admin.layouts.app')

@section('title', 'Resources — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Resources" description="Kelola Event, Whitepaper, E-book, News, dan Go-Live." eyebrow="Konten">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.resources.create')" variant="primary">
            <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Resource
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari judul resources…"
        :selects="[
            ['name' => 'type', 'label' => 'Semua tipe', 'options' => collect($types)->mapWithKeys(fn ($t) => [$t->value => $t->label()])->all()],
            ['name' => 'status', 'label' => 'Semua status', 'options' => collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()],
        ]" />
    @if($resources->isEmpty())
        <x-admin.empty-state title="Belum ada resource." description="Tambahkan resource pertama Anda." icon="article">
            <x-slot:action>
                <x-admin.partials.button :href="route('admin.resources.create')" variant="primary">
                    <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Resource
                </x-admin.partials.button>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Judul</th>
                <th class="px-5 py-2.5 font-bold">Tipe</th>
                <th class="px-5 py-2.5 font-bold">Publikasi</th>
                <th class="px-5 py-2.5 font-bold">Status</th>
                <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
            </x-slot:head>
            @foreach($resources as $resource)
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <a href="{{route('admin.resources.edit',$resource)}}" class="block min-w-0 truncate font-semibold text-neutral-900 transition hover:text-brand-600">{{$resource->title}}</a>
                        <span class="block truncate text-xs text-neutral-400">{{$resource->slug}}</span>
                    </td>
                    <td class="px-5 py-3.5 text-neutral-600">{{$resource->type->label()}}</td>
                    <td class="px-5 py-3.5 text-neutral-600 tabular-nums">{{$resource->published_at?->format('d M Y')?:'—'}}</td>
                    <td class="px-5 py-3.5"><x-admin.status-badge :status="$resource->status" /></td>
                    <td class="px-5 py-3.5">
                        <x-admin.partials.row-actions
                            :edit="route('admin.resources.edit',$resource)"
                            :delete="route('admin.resources.destroy',$resource)"
                            delete-confirm="Hapus resource ini?" /></td>
                </tr>
            @endforeach
        </x-admin.partials.table>
        <x-admin.partials.pagination :paginator="$resources" />

    @endif
</div>
@endsection
