@extends('admin.layouts.app')

@section('title', 'Lamaran Masuk — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Lamaran Masuk" description="Lamaran yang dikirim dari halaman karier." eyebrow="Karier" />

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari nama atau email pelamar…"
        :selects="[
            ['name' => 'status', 'label' => 'Semua status', 'options' => collect($statuses)->mapWithKeys(fn ($s) => [$s => ucfirst($s)])->all()],
        ]" />

    @if ($applications->isEmpty())
        <x-admin.empty-state title="Belum ada lamaran." description="Lamaran dari halaman karier akan muncul di sini." icon="inbox" />
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Pelamar</th>
                <th class="px-5 py-2.5 font-bold">Posisi</th>
                <th class="px-5 py-2.5 font-bold">Status</th>
                <th class="px-5 py-2.5 text-right font-bold">Tanggal</th>
            </x-slot:head>

            @foreach ($applications as $application)
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <a href="{{ route('admin.career-applications.show', $application) }}" class="block font-semibold text-neutral-900 transition hover:text-brand-600">{{ $application->name }}</a>
                        <span class="text-xs text-neutral-500">{{ $application->email }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-neutral-600">{{ $application->career?->title ?? '—' }}</td>
                    <td class="px-5 py-3.5"><x-admin.badge :tone="$application->statusTone()">{{ $application->statusLabel() }}</x-admin.badge></td>
                    <td class="whitespace-nowrap px-5 py-3.5 text-right text-neutral-500">{{ $application->created_at->translatedFormat('d M Y') }}</td>
                </tr>
            @endforeach
        </x-admin.partials.table>

        <x-admin.partials.pagination :paginator="$applications" />
    @endif
</div>
@endsection
