@extends('admin.layouts.app')

@section('title', 'Pengumuman — Admin Nusakode')

@section('content')
<x-admin.page-header title="Pengumuman" description="Bilah atas, pop-up masuk, dan banner persetujuan cookie." eyebrow="Website">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.announcements.create')" variant="primary">
            <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Pengumuman
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari judul atau isi pengumuman…"
        :selects="[
            ['name' => 'placement', 'label' => 'Semua jenis', 'options' => collect($placements)->mapWithKeys(fn ($p) => [$p->value => $p->label()])->all()],
            ['name' => 'active', 'label' => 'Semua status', 'options' => ['1' => 'Aktif', '0' => 'Nonaktif']],
        ]" />

    @if ($announcements->isEmpty())
        <x-admin.empty-state title="Belum ada pengumuman." description="Tambah bilah atas, pop-up, atau banner cookie." icon="megaphone">
            <x-slot:action>
                <x-admin.partials.button :href="route('admin.announcements.create')" variant="primary">
                    <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Pengumuman
                </x-admin.partials.button>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Pengumuman</th>
                <th class="px-5 py-2.5 font-bold">Jenis</th>
                <th class="px-5 py-2.5 font-bold">Frekuensi</th>
                <th class="px-5 py-2.5 font-bold">Jadwal</th>
                <th class="px-5 py-2.5 font-bold">Status</th>
                <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
            </x-slot:head>

            @foreach ($announcements as $announcement)
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            @if ($announcement->image_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($announcement->image_path) }}" alt="" class="h-9 w-12 shrink-0 rounded border border-neutral-200 bg-white object-cover">
                            @else
                                <span class="flex h-9 w-12 shrink-0 items-center justify-center rounded border border-neutral-200 bg-neutral-50 text-neutral-300">
                                    <x-admin.icon name="megaphone" class="h-4 w-4" />
                                </span>
                            @endif
                            <span class="min-w-0">
                                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="block max-w-md truncate font-semibold text-neutral-900 transition hover:text-brand-600">
                                    {{ $announcement->title ?: \Illuminate\Support\Str::limit($announcement->message, 60) }}
                                </a>
                                <span class="block max-w-md truncate text-xs text-neutral-500">{{ \Illuminate\Support\Str::limit($announcement->message, 90) }}</span>
                            </span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5"><x-admin.badge :tone="$announcement->placement->tone()">{{ $announcement->placement->label() }}</x-admin.badge></td>
                    <td class="px-5 py-3.5 text-neutral-600">
                        {{ $announcement->frequency->label() }}
                        @if ($announcement->frequency_days)
                            <span class="text-xs text-neutral-400">({{ $announcement->frequency_days }} hari)</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-5 py-3.5 text-neutral-600">
                        @if ($announcement->starts_at || $announcement->ends_at)
                            {{ $announcement->starts_at?->translatedFormat('d M Y') ?? '—' }} – {{ $announcement->ends_at?->translatedFormat('d M Y') ?? '—' }}
                        @else
                            <span class="text-neutral-400">Selalu</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        @if ($announcement->is_active)
                            <x-admin.badge tone="emerald">Aktif</x-admin.badge>
                        @else
                            <x-admin.badge tone="neutral">Nonaktif</x-admin.badge>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <x-admin.partials.row-actions
                            :edit="route('admin.announcements.edit', $announcement)"
                            :delete="route('admin.announcements.destroy', $announcement)"
                            delete-confirm="Hapus pengumuman ini?" />
                    </td>
                </tr>
            @endforeach
        </x-admin.partials.table>

        <x-admin.partials.pagination :paginator="$announcements" />
    @endif
</div>
@endsection
