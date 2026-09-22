@extends('admin.layouts.app')

@section('title', $lead->name.' — Lead')

@section('content')
<x-admin.page-header :title="$lead->name" eyebrow="Lead" :description="trim(($lead->company ?: 'Tanpa perusahaan').' · '.$lead->email)">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.leads.index')" variant="secondary">
            <x-admin.icon name="chevron-left" class="h-4 w-4" /> Kembali
        </x-admin.partials.button>
        <form method="post" action="{{ route('admin.leads.destroy', $lead) }}" data-confirm="Hapus lead ini? Tindakan tidak dapat dibatalkan.">
            @csrf
            @method('delete')
            <x-admin.partials.button type="submit" variant="danger">
                <x-admin.icon name="trash" class="h-4 w-4" /> Hapus
            </x-admin.partials.button>
        </form>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-8 grid gap-8 lg:grid-cols-[minmax(0,1.5fr)_minmax(0,1fr)]">
    {{-- Kiri: detail permintaan --}}
    <div class="min-w-0 space-y-6">
        <x-admin.card title="Detail Permintaan">
            @php
                $rows = [
                    'Nama' => $lead->name,
                    'Email' => $lead->email,
                    'Perusahaan' => $lead->company,
                    'Telepon' => $lead->phone,
                    'Jenis Proyek' => $lead->project_type,
                    'Budget' => $lead->budget_range,
                ];
            @endphp
            <dl class="divide-y divide-neutral-100 px-5">
                @foreach ($rows as $label => $value)
                    <div class="grid grid-cols-[140px_1fr] gap-4 py-3">
                        <dt class="text-[13px] font-medium text-neutral-500">{{ $label }}</dt>
                        <dd class="text-[13px] text-neutral-900">{{ $value ?: '—' }}</dd>
                    </div>
                @endforeach
            </dl>
        </x-admin.card>

        <x-admin.card title="Keterangan Proyek">
            <div class="px-5 py-5">
                @if ($lead->project_detail)
                    <p class="whitespace-pre-line text-sm leading-relaxed text-neutral-700">{{ $lead->project_detail }}</p>
                @else
                    <p class="text-sm text-neutral-400">Tidak ada keterangan.</p>
                @endif
            </div>
        </x-admin.card>
    </div>

    {{-- Kanan: status --}}
    <div class="space-y-6">
        <x-admin.card title="Status Lead">
            <form method="post" action="{{ route('admin.leads.update', $lead) }}" class="space-y-5 px-5 py-5">
                @csrf
                @method('put')

                <x-admin.field label="Status" name="status" :required="true">
                    <select name="status" class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}" @selected(old('status', $lead->status->value) === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </x-admin.field>

                <x-admin.field label="Catatan Admin" name="admin_note" hint="Catatan internal, tidak terlihat oleh pengunjung.">
                    <textarea name="admin_note" rows="5" class="h-auto w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100" placeholder="Tulis catatan…">{{ old('admin_note', $lead->admin_note) }}</textarea>
                </x-admin.field>

                <div class="flex justify-end border-t border-neutral-200 pt-5">
                    <x-admin.partials.button type="submit" variant="primary">Simpan Perubahan</x-admin.partials.button>
                </div>
            </form>
        </x-admin.card>

        <div class="rounded-lg border border-neutral-200 bg-white p-5">
            <h2 class="text-[13px] font-semibold text-neutral-900">Informasi</h2>
            <dl class="mt-3.5 space-y-2.5 text-[13px]">
                <div class="flex items-start justify-between gap-4">
                    <dt class="text-neutral-500">Dibuat</dt>
                    <dd class="text-right text-neutral-900">{{ $lead->created_at->translatedFormat('d F Y H:i') }}</dd>
                </div>
                <div class="flex items-start justify-between gap-4">
                    <dt class="text-neutral-500">Diubah</dt>
                    <dd class="text-right text-neutral-900">{{ $lead->updated_at->translatedFormat('d F Y H:i') }}</dd>
                </div>
                <div class="flex items-start justify-between gap-4">
                    <dt class="text-neutral-500">Ditangani</dt>
                    <dd class="text-right text-neutral-900">{{ $lead->handler?->name ?? '—' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
