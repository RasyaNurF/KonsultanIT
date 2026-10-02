@extends('admin.layouts.app')

@section('title', 'Karier — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Karier" description="Lowongan yang tampil di halaman karier." eyebrow="Konten">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.career-applications.index')" variant="secondary">Lamaran Masuk</x-admin.partials.button>
        <x-admin.partials.button :href="route('admin.careers.create')" variant="primary">
            <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Lowongan
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari posisi, departemen, atau lokasi…"
        :selects="[
            ['name' => 'status', 'label' => 'Semua status', 'options' => collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()],
        ]" />

    @if ($careers->isEmpty())
        <x-admin.empty-state title="Belum ada lowongan." description="Tambahkan lowongan pertama Anda." icon="briefcase">
            <x-slot:action>
                <x-admin.partials.button :href="route('admin.careers.create')" variant="primary">
                    <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Lowongan
                </x-admin.partials.button>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Posisi</th>
                <th class="px-5 py-2.5 font-bold">Departemen</th>
                <th class="px-5 py-2.5 font-bold">Lamaran</th>
                <th class="px-5 py-2.5 font-bold">Deadline</th>
                <th class="px-5 py-2.5 font-bold">Status</th>
                <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
            </x-slot:head>

            @foreach ($careers as $career)
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <a href="{{ route('admin.careers.edit', $career) }}" class="block font-semibold text-neutral-900 transition hover:text-brand-600">{{ $career->title }}</a>
                        <span class="text-xs text-neutral-500">{{ $career->location ?: 'Lokasi menyusul' }}{{ $career->is_remote ? ' · Remote' : '' }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-neutral-600">{{ $career->department ?: '—' }}</td>
                    <td class="px-5 py-3.5 text-neutral-600 tabular-nums">{{ $career->applications_count }}</td>
                    <td class="whitespace-nowrap px-5 py-3.5 text-neutral-500 tabular-nums">{{ $career->deadline?->translatedFormat('d M Y') ?? '—' }}</td>
                    <td class="px-5 py-3.5"><x-admin.status-badge :status="$career->status" /></td>
                    <td class="px-5 py-3.5">
                        <x-admin.partials.row-actions
                            :edit="route('admin.careers.edit', $career)"
                            :delete="route('admin.careers.destroy', $career)"
                            delete-confirm="Hapus lowongan ini beserta lamarannya?" />
                    </td>
                </tr>
            @endforeach
        </x-admin.partials.table>

        <x-admin.partials.pagination :paginator="$careers" />
    @endif
</div>
@endsection
