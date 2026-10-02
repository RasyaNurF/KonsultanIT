@extends('admin.layouts.app')

@section('title', 'Tim — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Tim" description="Anggota tim yang tampil di halaman tentang." eyebrow="Konten">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.teams.create')" variant="primary">
            <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Anggota
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari nama atau posisi…"
        :selects="[
            ['name' => 'status', 'label' => 'Semua status', 'options' => collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()],
        ]" />

    @if ($teams->isEmpty())
        <x-admin.empty-state title="Belum ada anggota tim." description="Tambahkan anggota tim pertama Anda." icon="users">
            <x-slot:action>
                <x-admin.partials.button :href="route('admin.teams.create')" variant="primary">
                    <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Anggota
                </x-admin.partials.button>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Anggota</th>
                <th class="px-5 py-2.5 font-bold">Posisi</th>
                <th class="px-5 py-2.5 font-bold">Urutan</th>
                <th class="px-5 py-2.5 font-bold">Status</th>
                <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
            </x-slot:head>

            @foreach ($teams as $member)
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            @if ($member->photo_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($member->photo_path) }}" alt="{{ $member->name }}" class="h-9 w-9 shrink-0 rounded-full border border-neutral-200 bg-white object-cover">
                            @else
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-navy-800 text-xs font-bold text-white">{{ $member->initials() }}</span>
                            @endif
                            <a href="{{ route('admin.teams.edit', $member) }}" class="block truncate font-semibold text-neutral-900 transition hover:text-brand-600">{{ $member->name }}</a>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-neutral-600">{{ $member->position ?: '—' }}</td>
                    <td class="px-5 py-3.5 text-neutral-600 tabular-nums">{{ $member->sort_order }}</td>
                    <td class="px-5 py-3.5"><x-admin.status-badge :status="$member->status" /></td>
                    <td class="px-5 py-3.5">
                        <x-admin.partials.row-actions
                            :edit="route('admin.teams.edit', $member)"
                            :delete="route('admin.teams.destroy', $member)"
                            delete-confirm="Hapus anggota tim ini?" />
                    </td>
                </tr>
            @endforeach
        </x-admin.partials.table>

        <x-admin.partials.pagination :paginator="$teams" />
    @endif
</div>
@endsection
