@extends('admin.layouts.app')

@section('title', 'Proyek — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Proyek" description="Proyek yang sedang dan telah dikerjakan KIT Konsultan IT." eyebrow="Proyek">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.projects.create')" variant="primary">
            <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Proyek
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari nama, kategori, atau klien…"
        :selects="[
            ['name' => 'status', 'label' => 'Semua status', 'options' => collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()],
            ['name' => 'category', 'label' => 'Semua kategori', 'options' => $categories->mapWithKeys(fn ($c) => [$c => $c])->all()],
        ]" />

    @if ($projects->isEmpty())
        <x-admin.empty-state title="Belum ada proyek." description="Tambahkan proyek pertama Anda." icon="kanban">
            <x-slot:action>
                <x-admin.partials.button :href="route('admin.projects.create')" variant="primary">
                    <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Proyek
                </x-admin.partials.button>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Proyek</th>
                <th class="px-5 py-2.5 font-bold">Klien</th>
                <th class="px-5 py-2.5 font-bold">Teknologi</th>
                <th class="px-5 py-2.5 font-bold">Periode</th>
                <th class="px-5 py-2.5 font-bold">Status</th>
                <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
            </x-slot:head>

            @foreach ($projects as $project)
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <a href="{{ route('admin.projects.edit', $project) }}" class="block font-semibold text-neutral-900 transition hover:text-brand-600">{{ $project->name }}</a>
                        <span class="text-xs text-neutral-500">{{ $project->category ?: '—' }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-neutral-600">{{ $project->client?->name ?? '—' }}</td>
                    <td class="px-5 py-3.5">
                        @php($techs = $project->technologyList())
                        @if ($techs)
                            <div class="flex flex-wrap items-center gap-1">
                                @foreach (array_slice($techs, 0, 2) as $tech)
                                    <x-admin.badge tone="neutral">{{ $tech }}</x-admin.badge>
                                @endforeach
                                @if (count($techs) > 2)
                                    <span class="text-xs text-neutral-400">+{{ count($techs) - 2 }}</span>
                                @endif
                            </div>
                        @else
                            <span class="text-neutral-400">—</span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-5 py-3.5 text-neutral-600 tabular-nums">
                        {{ $project->started_at?->format('M Y') ?? '—' }} â†’ {{ $project->finished_at?->format('M Y') ?? 'Sekarang' }}
                    </td>
                    <td class="px-5 py-3.5"><x-admin.status-badge :status="$project->status" /></td>
                    <td class="px-5 py-3.5">
                        <x-admin.partials.row-actions
                            :edit="route('admin.projects.edit', $project)"
                            :delete="route('admin.projects.destroy', $project)"
                            delete-confirm="Hapus proyek ini? Portfolio terkait akan dilepas." />
                    </td>
                </tr>
            @endforeach
        </x-admin.partials.table>

        <x-admin.partials.pagination :paginator="$projects" />
    @endif
</div>
@endsection
