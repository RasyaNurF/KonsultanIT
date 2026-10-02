@extends('admin.layouts.app')

@section('title', 'Leads — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Leads" description="Permintaan proyek yang masuk melalui formulir kontak." eyebrow="CRM">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.leads.export')" variant="secondary">
            <x-admin.icon name="download" class="h-4 w-4" /> Ekspor CSV
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="flex flex-wrap items-center gap-x-6 gap-y-2 border-b border-neutral-200 pb-4 pt-6 text-[13px]">
    <span class="flex items-baseline gap-2">
        <span class="text-neutral-500">Total</span>
        <span class="font-bold tabular-nums text-neutral-900">{{ number_format($counts->sum(), 0, ',', '.') }}</span>
    </span>
    @foreach ($statuses as $status)
        <a href="{{ route('admin.leads.index', ['status' => $status->value]) }}" class="flex items-baseline gap-2 transition hover:text-brand-600">
            <span class="text-neutral-500">{{ $status->label() }}</span>
            <span class="font-bold tabular-nums text-neutral-900">{{ number_format((int) ($counts[$status->value] ?? 0), 0, ',', '.') }}</span>
        </a>
    @endforeach
</div>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari nama, email, atau perusahaan…"
        :selects="[
            ['name' => 'status', 'label' => 'Semua status', 'options' => collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()],
            ['name' => 'project_type', 'label' => 'Semua jenis proyek', 'options' => array_combine($projectTypes, $projectTypes)],
        ]" />

    @if ($leads->isEmpty())
        <x-admin.empty-state title="Belum ada lead masuk." description="Permintaan dari formulir kontak akan muncul di sini." icon="inbox" />
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Nama</th>
                <th class="px-5 py-2.5 font-bold">Perusahaan</th>
                <th class="px-5 py-2.5 font-bold">Proyek</th>
                <th class="px-5 py-2.5 font-bold">Status</th>
                <th class="px-5 py-2.5 font-bold">Tanggal</th>
                <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
            </x-slot:head>

            @foreach ($leads as $lead)
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <a href="{{ route('admin.leads.show', $lead) }}" class="block font-semibold text-neutral-900 transition hover:text-brand-600">{{ $lead->name }}</a>
                        <span class="text-xs text-neutral-500">{{ $lead->email }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-neutral-600">{{ $lead->company ?: '—' }}</td>
                    <td class="px-5 py-3.5 text-neutral-600">{{ $lead->project_type ?: '—' }}</td>
                    <td class="px-5 py-3.5"><x-admin.status-badge :status="$lead->status" /></td>
                    <td class="whitespace-nowrap px-5 py-3.5 text-neutral-500 tabular-nums">{{ $lead->created_at->translatedFormat('d M Y') }}</td>
                    <td class="px-5 py-3.5">
                        <x-admin.partials.row-actions
                            :view="route('admin.leads.show', $lead)"
                            :delete="route('admin.leads.destroy', $lead)"
                            delete-confirm="Hapus lead ini? Tindakan tidak dapat dibatalkan." />
                    </td>
                </tr>
            @endforeach
        </x-admin.partials.table>

        <x-admin.partials.pagination :paginator="$leads" />
    @endif
</div>
@endsection
