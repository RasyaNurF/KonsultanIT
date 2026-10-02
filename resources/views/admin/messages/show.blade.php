@extends('admin.layouts.app')

@section('title', ($participant->name ?: 'Tanpa nama').' — Percakapan')

@section('content')
<x-admin.page-header
    :title="$participant->name ?: 'Tanpa nama'"
    eyebrow="Percakapan"
    :description="trim(($participant->email ?: '—').($participant->subject ? ' · '.$participant->subject : ''))">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.messages.index')" variant="secondary">
            <x-admin.icon name="chevron-left" class="h-4 w-4" /> Kembali
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-8 grid gap-8 lg:grid-cols-[minmax(0,1.6fr)_minmax(0,1fr)]">
    {{-- Kiri: thread + form balasan --}}
    <x-admin.card class="overflow-hidden">
        <div data-admin-thread data-poll-url="{{ route('admin.messages.poll', $participant) }}" data-typing-url="{{ route('admin.messages.typing', $participant) }}" data-last-id="{{ $messages->last()?->id ?? 0 }}"
            class="flex max-h-[560px] flex-col gap-3 overflow-y-auto p-5">
            @forelse ($messages as $message)
                @php($isInbound = $message->sender !== 'admin')
                <div data-message-id="{{ $message->id }}" class="flex flex-col {{ $isInbound ? 'items-start' : 'items-end' }}">
                    <div class="max-w-[85%] rounded-lg px-4 py-2.5 text-sm leading-relaxed {{ $isInbound ? 'bg-neutral-100 text-neutral-800' : 'bg-navy-800 text-white' }}">
                        {{ $message->body }}
                    </div>
                    <span class="mt-1 text-[11px] text-neutral-400">
                        {{ $message->name ?: ($isInbound ? 'Pengunjung' : 'Admin') }} · {{ $message->timestampWib('H:i d M') }}
                    </span>
                </div>
            @empty
                <x-admin.empty-state title="Belum ada pesan." icon="message" />
            @endforelse
        </div>

        <form data-admin-reply-form method="post" action="{{ route('admin.messages.reply', $participant) }}" class="border-t border-neutral-200 p-5">
            @csrf
            <x-admin.field label="Balasan" name="body" :required="true">
                <textarea data-admin-reply-body name="body" rows="3" required maxlength="4000" placeholder="Tulis balasan…"
                    class="h-auto w-full resize-y rounded-md border border-neutral-200 px-3 py-2.5 text-sm text-neutral-900 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-100">{{ old('body') }}</textarea>
            </x-admin.field>
            <div class="mt-4 flex justify-end">
                <x-admin.partials.button type="submit" variant="primary">Kirim Balasan</x-admin.partials.button>
            </div>
        </form>
    </x-admin.card>

    {{-- Kanan: status + informasi --}}
    <div class="space-y-6">
        <x-admin.card title="Status">
            <form method="post" action="{{ route('admin.messages.update', $participant) }}" class="space-y-5 px-5 py-5">
                @csrf
                @method('put')

                <x-admin.field label="Status" name="status" :required="true">
                    <select name="status" data-admin-status class="h-10 w-full rounded-md border border-neutral-200 bg-white pl-3 pr-8 text-sm text-neutral-900 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}" @selected(old('status', $participant->status) === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </x-admin.field>

                <div class="flex justify-end border-t border-neutral-200 pt-5">
                    <x-admin.partials.button type="submit" variant="primary">Simpan</x-admin.partials.button>
                </div>
            </form>
        </x-admin.card>

        <x-admin.card title="Informasi">
            <dl class="divide-y divide-neutral-100 px-5">
                @foreach (['Email' => $participant->email, 'Telepon' => $participant->phone, 'Perusahaan' => $participant->company, 'Subjek' => $participant->subject] as $label => $value)
                    <div class="grid grid-cols-[100px_1fr] gap-4 py-3">
                        <dt class="text-[13px] font-medium text-neutral-500">{{ $label }}</dt>
                        <dd class="text-[13px] text-neutral-900">{{ $value ?: '—' }}</dd>
                    </div>
                @endforeach
                <div class="grid grid-cols-[100px_1fr] gap-4 py-3">
                    <dt class="text-[13px] font-medium text-neutral-500">Tipe</dt>
                    <dd class="text-[13px] text-neutral-900">
                        @if ($participant->isFromRegisteredUser())
                            <x-admin.badge tone="brand">Pengguna terdaftar</x-admin.badge>
                        @else
                            <x-admin.badge tone="neutral">Pengunjung</x-admin.badge>
                        @endif
                    </dd>
                </div>
                <div class="grid grid-cols-[100px_1fr] gap-4 py-3">
                    <dt class="text-[13px] font-medium text-neutral-500">Dibuat</dt>
                    <dd class="text-[13px] text-neutral-900">{{ $participant->created_at->copy()->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i') }} WIB</dd>
                </div>
            </dl>
        </x-admin.card>
    </div>
</div>
@endsection
