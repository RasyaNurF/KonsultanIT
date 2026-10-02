@extends('admin.layouts.app')

@section('title', 'Industri — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Industri" description="Industri yang dilayani KIT Konsultan IT beserta studi kasusnya." eyebrow="Konten">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.industries.create')" variant="primary">
            <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Industri
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari nama atau deskripsi industri…"
        :selects="[
            ['name' => 'status', 'label' => 'Semua status', 'options' => collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()],
        ]" />

    @if ($industries->isEmpty())
        <x-admin.empty-state title="Belum ada industri." description="Tambahkan industri pertama Anda." icon="factory">
            <x-slot:action>
                <x-admin.partials.button :href="route('admin.industries.create')" variant="primary">
                    <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Industri
                </x-admin.partials.button>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Industri</th>
                <th class="px-5 py-2.5 font-bold">Deskripsi</th>
                <th class="px-5 py-2.5 font-bold">Layanan</th>
                <th class="px-5 py-2.5 font-bold">Urutan</th>
                <th class="px-5 py-2.5 font-bold">Status</th>
                <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
            </x-slot:head>

            @foreach ($industries as $industry)
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            @if ($industry->image_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($industry->image_path) }}" alt="{{ $industry->name }}" class="h-8 w-8 shrink-0 rounded border border-neutral-200 bg-white object-cover">
                            @else
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded border border-neutral-200 bg-neutral-100 text-[11px] font-bold text-neutral-500">{{ mb_strtoupper(mb_substr($industry->name, 0, 2)) }}</span>
                            @endif
                            <a href="{{ route('admin.industries.edit', $industry) }}" class="min-w-0 truncate font-semibold text-neutral-900 transition hover:text-brand-600">{{ $industry->name }}</a>
                        </div>
                    </td>
                    <td class="max-w-sm px-5 py-3.5 text-neutral-600">
                        <span class="block truncate">{{ $industry->description ? \Illuminate\Support\Str::limit($industry->description, 90) : '—' }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-neutral-600 tabular-nums">{{ $industry->services_count }} layanan</td>
                    <td class="px-5 py-3.5 text-neutral-600 tabular-nums">{{ $industry->sort_order }}</td>
                    <td class="px-5 py-3.5"><x-admin.status-badge :status="$industry->status" /></td>
                    <td class="px-5 py-3.5">
                        <x-admin.partials.row-actions
                            :edit="route('admin.industries.edit', $industry)"
                            :delete="route('admin.industries.destroy', $industry)"
                            delete-confirm="Hapus industri ini? Relasi dengan layanan akan dilepas." />
                    </td>
                </tr>
            @endforeach
        </x-admin.partials.table>

        <x-admin.partials.pagination :paginator="$industries" />
    @endif
</div>
@endsection
