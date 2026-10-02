@extends('admin.layouts.app')

@section('title', 'Klien — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Klien" description="Perusahaan dan organisasi yang bekerja sama dengan KIT Konsultan IT." eyebrow="CRM">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.clients.create')" variant="primary">
            <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Klien
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari nama, industri, atau email…"
        :selects="[
            ['name' => 'status', 'label' => 'Semua status', 'options' => collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()],
        ]" />

    @if ($clients->isEmpty())
        <x-admin.empty-state title="Belum ada klien." description="Tambahkan klien pertama Anda." icon="building">
            <x-slot:action>
                <x-admin.partials.button :href="route('admin.clients.create')" variant="primary">
                    <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Klien
                </x-admin.partials.button>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Klien</th>
                <th class="px-5 py-2.5 font-bold">Website</th>
                <th class="px-5 py-2.5 font-bold">Kontak</th>
                <th class="px-5 py-2.5 font-bold">Proyek</th>
                <th class="px-5 py-2.5 font-bold">Status</th>
                <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
            </x-slot:head>

            @foreach ($clients as $client)
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            @if ($client->logo_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($client->logo_path) }}" alt="{{ $client->name }}" class="h-8 w-8 shrink-0 rounded border border-neutral-200 bg-white object-contain p-0.5">
                            @else
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded bg-neutral-100 text-[11px] font-bold text-neutral-500">{{ mb_strtoupper(mb_substr($client->name, 0, 1)) }}</span>
                            @endif
                            <span class="min-w-0">
                                <a href="{{ route('admin.clients.edit', $client) }}" class="block truncate font-semibold text-neutral-900 transition hover:text-brand-600">{{ $client->name }}</a>
                                <span class="block truncate text-xs text-neutral-500">{{ $client->industry ?: '—' }}</span>
                            </span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        @if ($client->website)
                            <a href="{{ $client->website }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-neutral-600 transition hover:text-brand-600">
                                {{ $client->website }} <x-admin.icon name="external" class="h-3.5 w-3.5" />
                            </a>
                        @else
                            <span class="text-neutral-400">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        @if ($client->contact_name || $client->contact_email)
                            <span class="block text-neutral-900">{{ $client->contact_name ?: '—' }}</span>
                            @if ($client->contact_email)
                                <span class="block text-xs text-neutral-500">{{ $client->contact_email }}</span>
                            @endif
                        @else
                            <span class="text-neutral-400">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-neutral-600 tabular-nums">{{ $client->projects_count }}</td>
                    <td class="px-5 py-3.5"><x-admin.status-badge :status="$client->status" /></td>
                    <td class="px-5 py-3.5">
                        <x-admin.partials.row-actions
                            :edit="route('admin.clients.edit', $client)"
                            :delete="route('admin.clients.destroy', $client)"
                            delete-confirm="Hapus klien ini? Proyek dan portfolio terkait akan dilepas." />
                    </td>
                </tr>
            @endforeach
        </x-admin.partials.table>

        <x-admin.partials.pagination :paginator="$clients" />
    @endif
</div>
@endsection
