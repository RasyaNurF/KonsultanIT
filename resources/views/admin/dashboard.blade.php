@extends('admin.layouts.app')

@section('title', 'Dashboard — Admin KIT Konsultan IT')

@section('content')
@php
$hour = (int) now()->format('H');
$greeting = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 18 ? 'Selamat sore' : 'Selamat malam'));

$statCards = [
    ['label' => 'Total Proyek', 'value' => $stats['active_projects'], 'note' => 'Proyek aktif', 'href' => route('admin.projects.index'), 'accent' => false],
    ['label' => 'Total Layanan', 'value' => $stats['services'], 'note' => 'Layanan terdaftar', 'href' => route('admin.services.index'), 'accent' => false],
    ['label' => 'Total Artikel', 'value' => $stats['articles'], 'note' => 'Artikel terbit & draft', 'href' => route('admin.articles.index'), 'accent' => false],
    ['label' => 'Lowongan Aktif', 'value' => $stats['careers'], 'note' => $stats['applications'].' lamaran baru', 'href' => route('admin.careers.index'), 'accent' => $stats['applications'] > 0],
    ['label' => 'Total Leads', 'value' => $stats['leads'], 'note' => 'Sepanjang waktu', 'href' => route('admin.leads.index'), 'accent' => false],
    ['label' => 'Pesan Baru', 'value' => $stats['unread_messages'], 'note' => 'Belum dibaca', 'href' => route('admin.messages.index', ['status' => 'unread']), 'accent' => $stats['unread_messages'] > 0],
];
@endphp

<header class="border-b border-neutral-200 pb-6">
    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-brand-600">Dashboard</p>
    <h1 class="mt-2 text-[22px] font-bold tracking-tight text-neutral-900 sm:text-2xl">{{ $greeting }}, {{ auth()->user()->name }}.</h1>
    <p class="mt-1.5 text-sm text-neutral-500">Berikut ringkasan aktivitas KIT Konsultan IT.</p>
</header>

{{-- Statistik: angka besar, label kecil, pemisah tipis konsisten. --}}
<section class="mt-8 grid grid-cols-1 gap-px overflow-hidden rounded-lg border border-neutral-200 bg-neutral-200 sm:grid-cols-2 lg:grid-cols-3" aria-label="Statistik utama">
    @foreach ($statCards as $stat)
        <a href="{{ $stat['href'] }}" class="flex flex-col gap-1 bg-white px-6 py-5 transition hover:bg-neutral-50/60">
            <span class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.1em] text-neutral-500">
                {{ $stat['label'] }}
                @if ($stat['accent'])
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-600" aria-hidden="true"></span>
                @endif
            </span>
            <span class="text-3xl font-bold tabular-nums tracking-tight {{ $stat['accent'] ? 'text-brand-600' : 'text-neutral-900' }}">{{ number_format($stat['value'], 0, ',', '.') }}</span>
            <span class="text-xs text-neutral-400">{{ $stat['note'] }}</span>
        </a>
    @endforeach
</section>

<div class="mt-10 grid gap-8 lg:grid-cols-[minmax(0,1.6fr)_minmax(0,1fr)]">
    {{-- Lead terbaru --}}
    <section class="min-w-0" aria-labelledby="lead-terbaru">
        <div class="flex items-end justify-between gap-4 pb-4">
            <h2 id="lead-terbaru" class="truncate text-[15px] font-semibold text-neutral-900">Lead Terbaru</h2>
            <a href="{{ route('admin.leads.index') }}" class="shrink-0 text-[13px] font-medium text-brand-600 transition hover:text-brand-700">Lihat semua</a>
        </div>

        <div class="rounded-lg border border-neutral-200 bg-white">
            @if ($leads->isEmpty())
                <x-admin.empty-state title="Belum ada lead masuk." description="Permintaan proyek dari formulir kontak akan muncul di sini." icon="inbox" />
            @else
                <x-admin.partials.table>
                    <x-slot:head>
                        <th class="px-5 py-2.5 font-bold">Nama</th>
                        <th class="px-5 py-2.5 font-bold">Jenis Proyek</th>
                        <th class="px-5 py-2.5 font-bold">Status</th>
                        <th class="px-5 py-2.5 text-right font-bold">Tanggal</th>
                    </x-slot:head>
                    @foreach ($leads as $lead)
                        <tr class="transition hover:bg-neutral-50/70">
                            <td class="px-5 py-3.5">
                                <a href="{{ route('admin.leads.show', $lead) }}" class="block font-semibold text-neutral-900 transition hover:text-brand-600">{{ $lead->name }}</a>
                                <span class="text-xs text-neutral-500">{{ $lead->company ?: $lead->email }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-neutral-600">{{ $lead->project_type }}</td>
                            <td class="px-5 py-3.5"><x-admin.status-badge :status="$lead->status" /></td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-right text-neutral-500">{{ $lead->created_at->translatedFormat('d M Y') }}</td>
                        </tr>
                    @endforeach
                </x-admin.partials.table>
            @endif
        </div>
    </section>

    {{-- Aktivitas terbaru --}}
    <section class="min-w-0" aria-labelledby="aktivitas-terbaru">
        <div class="flex items-end justify-between gap-4 pb-4">
            <h2 id="aktivitas-terbaru" class="truncate text-[15px] font-semibold text-neutral-900">Aktivitas Terbaru</h2>
        </div>

        <div class="rounded-lg border border-neutral-200 bg-white">
            @if ($activities->isEmpty())
                <x-admin.empty-state title="Belum ada aktivitas." description="Perubahan portfolio, lead, artikel, dan proyek akan tercatat di sini." icon="info" />
            @else
                <ul class="divide-y divide-neutral-100">
                    @foreach ($activities as $activity)
                        <li>
                            <a href="{{ $activity['url'] }}" class="flex gap-3 px-5 py-3.5 transition hover:bg-neutral-50/70">
                                <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-md bg-neutral-100 text-neutral-500">
                                    <x-admin.icon :name="match ($activity['type']) { 'portfolio' => 'briefcase', 'lead' => 'inbox', 'article' => 'article', default => 'kanban' }" class="h-3.5 w-3.5" />
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-[13px] font-semibold text-neutral-900">{{ $activity['title'] }}</span>
                                    <span class="mt-0.5 block truncate text-xs text-neutral-500">{{ $activity['description'] }}</span>
                                </span>
                                <span class="shrink-0 whitespace-nowrap text-[11px] text-neutral-400">{{ $activity['at'] ? $activity['at']->diffForHumans(short: true) : '' }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Ringkasan status lead --}}
        <div class="mt-6 rounded-lg border border-neutral-200 bg-white p-5">
            <h3 class="text-[13px] font-semibold text-neutral-900">Status Lead</h3>
            <dl class="mt-3.5 space-y-2.5">
                @foreach (\App\Enums\LeadStatus::cases() as $status)
                    <div class="flex items-center justify-between text-[13px]">
                        <dt class="flex items-center gap-2 text-neutral-600">
                            <span class="h-1.5 w-1.5 rounded-full {{ match ($status->tone()) {
                                'brand' => 'bg-brand-500',
                                'sky' => 'bg-sky-500',
                                'amber' => 'bg-amber-500',
                                'violet' => 'bg-violet-500',
                                'emerald' => 'bg-emerald-500',
                                default => 'bg-neutral-300',
                            } }}" aria-hidden="true"></span>
                            {{ $status->label() }}
                        </dt>
                        <dd class="font-semibold tabular-nums text-neutral-900">{{ (int) ($leadStatusSummary[$status->value] ?? 0) }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>
</div>
@endsection
