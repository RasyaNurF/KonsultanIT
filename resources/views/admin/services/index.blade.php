@extends('admin.layouts.app')

@section('title', 'Layanan — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Layanan" description="Layanan yang ditawarkan KIT Konsultan IT." eyebrow="Konten">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.services.create')" variant="primary">
            <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Layanan
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari nama atau deskripsi layanan…"
        :selects="[
            ['name' => 'status', 'label' => 'Semua status', 'options' => collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()],
        ]" />

    @if ($services->isEmpty())
        <x-admin.empty-state title="Belum ada layanan." description="Tambahkan layanan pertama Anda." icon="layers">
            <x-slot:action>
                <x-admin.partials.button :href="route('admin.services.create')" variant="primary">
                    <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Layanan
                </x-admin.partials.button>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Layanan</th>
                <th class="px-5 py-2.5 font-bold">Teknologi</th>
                <th class="px-5 py-2.5 font-bold">Urutan</th>
                <th class="px-5 py-2.5 font-bold">Status</th>
                <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
            </x-slot:head>

            @foreach ($services as $service)
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            @if ($service->image_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($service->image_path) }}" alt="{{ $service->title }}" class="h-9 w-12 shrink-0 rounded border border-neutral-200 bg-white object-cover">
                            @else
                                <span class="flex h-9 w-12 shrink-0 items-center justify-center rounded border border-neutral-200 bg-neutral-50 text-neutral-300">
                                    <x-admin.icon name="image" class="h-4 w-4" />
                                </span>
                            @endif
                            <span class="min-w-0">
                                <a href="{{ route('admin.services.edit', $service) }}" class="block truncate font-semibold text-neutral-900 transition hover:text-brand-600">{{ $service->title }}</a>
                                @if ($service->description)
                                    <span class="block max-w-md truncate text-xs text-neutral-500">{{ \Illuminate\Support\Str::limit($service->description, 80) }}</span>
                                @endif
                            </span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        @php($techs = $service->technologyList())
                        @if ($techs)
                            <div class="flex flex-wrap items-center gap-1">
                                @foreach (array_slice($techs, 0, 3) as $tech)
                                    <x-admin.badge tone="neutral">{{ $tech }}</x-admin.badge>
                                @endforeach
                                @if (count($techs) > 3)
                                    <span class="text-xs text-neutral-400">+{{ count($techs) - 3 }}</span>
                                @endif
                            </div>
                        @else
                            <span class="text-neutral-400">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-neutral-600 tabular-nums">{{ $service->sort_order }}</td>
                    <td class="px-5 py-3.5"><x-admin.status-badge :status="$service->status" /></td>
                    <td class="px-5 py-3.5">
                        <x-admin.partials.row-actions
                            :edit="route('admin.services.edit', $service)"
                            :delete="route('admin.services.destroy', $service)"
                            delete-confirm="Hapus layanan ini? Relasi dengan industri akan dilepas." />
                    </td>
                </tr>
            @endforeach
        </x-admin.partials.table>

        <x-admin.partials.pagination :paginator="$services" />
    @endif
</div>
@endsection
