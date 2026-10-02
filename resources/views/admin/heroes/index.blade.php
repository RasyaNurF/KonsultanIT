@extends('admin.layouts.app')

@section('title', 'Hero — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Hero" description="Bagian pembuka homepage. Urutan terkecil tampil pertama." eyebrow="Konten">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.heroes.create')" variant="primary">
            <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Hero
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari judul hero…"
        :selects="[
            ['name' => 'status', 'label' => 'Semua status', 'options' => collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()],
        ]" />

    @if ($heroes->isEmpty())
        <x-admin.empty-state title="Belum ada hero." description="Tambahkan hero pertama untuk homepage." icon="image">
            <x-slot:action>
                <x-admin.partials.button :href="route('admin.heroes.create')" variant="primary">
                    <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Hero
                </x-admin.partials.button>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Hero</th>
                <th class="px-5 py-2.5 font-bold">CTA</th>
                <th class="px-5 py-2.5 font-bold">Urutan</th>
                <th class="px-5 py-2.5 font-bold">Status</th>
                <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
            </x-slot:head>

            @foreach ($heroes as $hero)
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <a href="{{ route('admin.heroes.edit', $hero) }}" class="block font-semibold text-neutral-900 transition hover:text-brand-600">{{ $hero->title }}</a>
                        @if ($hero->description)
                            <span class="block max-w-md truncate text-xs text-neutral-500">{{ \Illuminate\Support\Str::limit($hero->description, 80) }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-neutral-600">{{ $hero->cta_label ?: '—' }}</td>
                    <td class="px-5 py-3.5 text-neutral-600 tabular-nums">{{ $hero->sort_order }}</td>
                    <td class="px-5 py-3.5"><x-admin.status-badge :status="$hero->status" /></td>
                    <td class="px-5 py-3.5">
                        <x-admin.partials.row-actions
                            :edit="route('admin.heroes.edit', $hero)"
                            :delete="route('admin.heroes.destroy', $hero)"
                            delete-confirm="Hapus hero ini?" />
                    </td>
                </tr>
            @endforeach
        </x-admin.partials.table>

        <x-admin.partials.pagination :paginator="$heroes" />
    @endif
</div>
@endsection
