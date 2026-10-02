@extends('admin.layouts.app')

@section('title', 'Portfolio — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Portfolio" description="Karya yang ditampilkan di halaman portofolio website." eyebrow="Proyek">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.portfolios.create')" variant="primary">
            <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Portfolio
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari judul, kategori, atau klien…"
        :selects="[
            ['name' => 'status', 'label' => 'Semua status', 'options' => collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()],
            ['name' => 'client_id', 'label' => 'Semua klien', 'options' => $clients->mapWithKeys(fn ($c) => [$c->id => $c->name])->all()],
        ]" />

    @if ($portfolios->isEmpty())
        <x-admin.empty-state title="Belum ada portfolio." description="Tambahkan portfolio pertama Anda." icon="briefcase">
            <x-slot:action>
                <x-admin.partials.button :href="route('admin.portfolios.create')" variant="primary">
                    <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Portfolio
                </x-admin.partials.button>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Portfolio</th>
                <th class="px-5 py-2.5 font-bold">Klien</th>
                <th class="px-5 py-2.5 font-bold">Kategori</th>
                <th class="px-5 py-2.5 font-bold">Status</th>
                <th class="px-5 py-2.5 font-bold">Tanggal</th>
                <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
            </x-slot:head>

            @foreach ($portfolios as $portfolio)
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            @if ($portfolio->thumbnail_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($portfolio->thumbnail_path) }}" alt="{{ $portfolio->title }}" class="h-8 w-11 shrink-0 rounded border border-neutral-200 bg-white object-cover">
                            @else
                                <span class="flex h-8 w-11 shrink-0 items-center justify-center rounded border border-neutral-200 bg-neutral-100 text-neutral-400">
                                    <x-admin.icon name="image" class="h-3.5 w-3.5" />
                                </span>
                            @endif
                            <span class="min-w-0">
                                <a href="{{ route('admin.portfolios.show', $portfolio) }}" class="block truncate font-semibold text-neutral-900 transition hover:text-brand-600">{{ $portfolio->title }}</a>
                                <span class="block truncate text-xs text-neutral-500">{{ $portfolio->category ?: '—' }}</span>
                            </span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-neutral-600">{{ $portfolio->client?->name ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-neutral-600">{{ $portfolio->category ?: '—' }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-1.5">
                            <x-admin.status-badge :status="$portfolio->status" />
                            @if ($portfolio->is_featured)
                                <x-admin.badge tone="violet">Unggulan</x-admin.badge>
                            @endif
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-5 py-3.5 text-neutral-500 tabular-nums">{{ $portfolio->updated_at->translatedFormat('d M Y') }}</td>
                    <td class="px-5 py-3.5">
                        <x-admin.partials.row-actions
                            :view="route('admin.portfolios.show', $portfolio)"
                            :edit="route('admin.portfolios.edit', $portfolio)"
                            :delete="route('admin.portfolios.destroy', $portfolio)"
                            delete-confirm="Hapus portfolio ini? Tindakan tidak dapat dibatalkan." />
                    </td>
                </tr>
            @endforeach
        </x-admin.partials.table>

        <x-admin.partials.pagination :paginator="$portfolios" />
    @endif
</div>
@endsection
