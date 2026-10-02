@extends('admin.layouts.app')

@section('title', 'Solusi — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Solusi" description="Produk dan solusi di bawah setiap kategori." eyebrow="Konten">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.solutions.create')" variant="primary">
            <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Solusi
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari nama solusi atau partner…"
        :selects="[
            ['name' => 'status', 'label' => 'Semua status', 'options' => collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()],
            ['name' => 'category', 'label' => 'Semua kategori', 'options' => $categories->pluck('name','id')->all()],
        ]" />
    @if($solutions->isEmpty())
        <x-admin.empty-state title="Belum ada solusi." description="Tambahkan solusi pertama Anda." icon="layers">
            <x-slot:action>
                <x-admin.partials.button :href="route('admin.solutions.create')" variant="primary">
                    <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Solusi
                </x-admin.partials.button>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Solusi</th>
                <th class="px-5 py-2.5 font-bold">Kategori</th>
                <th class="px-5 py-2.5 font-bold">Partner</th>
                <th class="px-5 py-2.5 font-bold">Urutan</th>
                <th class="px-5 py-2.5 font-bold">Status</th>
                <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
            </x-slot:head>
            @foreach($solutions as $solution)
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            @if($solution->logo_path)
                                <img src="{{\Illuminate\Support\Facades\Storage::disk('public')->url($solution->logo_path)}}" alt="{{$solution->title}}" class="h-8 w-8 shrink-0 rounded border border-neutral-200 bg-white object-contain">
                            @else
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded border border-neutral-200 bg-neutral-100 text-[11px] font-bold text-neutral-500">{{mb_strtoupper(mb_substr($solution->title,0,2))}}</span>
                            @endif
                            <a href="{{route('admin.solutions.edit',$solution)}}" class="min-w-0 truncate font-semibold text-neutral-900 transition hover:text-brand-600">{{$solution->title}}</a>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-neutral-600">{{$solution->category?->name?:'—'}}</td>
                    <td class="px-5 py-3.5 text-neutral-600">{{$solution->partner_name?:'—'}}</td>
                    <td class="px-5 py-3.5 text-neutral-600 tabular-nums">{{$solution->sort_order}}</td>
                    <td class="px-5 py-3.5"><x-admin.status-badge :status="$solution->status" /></td>
                    <td class="px-5 py-3.5">
                        <x-admin.partials.row-actions
                            :edit="route('admin.solutions.edit',$solution)"
                            :delete="route('admin.solutions.destroy',$solution)"
                            delete-confirm="Hapus solusi ini?" /></td>
                </tr>
            @endforeach
        </x-admin.partials.table>
        <x-admin.partials.pagination :paginator="$solutions" />

    @endif
</div>
@endsection
