@extends('admin.layouts.app')

@section('title', 'Lamaran '.$application->name.' — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header :title="$application->name" :description="$application->email" eyebrow="Lamaran">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.career-applications.index')" variant="secondary">Kembali</x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-8 grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_minmax(0,1fr)]">
    <div class="rounded-lg border border-neutral-200 bg-white p-6">
        <dl class="grid gap-5 text-sm">
            <div>
                <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-neutral-400">Posisi dilamar</dt>
                <dd class="mt-1 font-semibold text-neutral-900">{{ $application->career?->title ?? '—' }}</dd>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-neutral-400">Telepon</dt>
                    <dd class="mt-1 text-neutral-700">{{ $application->phone ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-neutral-400">Portfolio</dt>
                    <dd class="mt-1">
                        @if ($application->portfolio_url)
                            <a href="{{ $application->portfolio_url }}" target="_blank" rel="noopener" class="font-medium text-brand-600 hover:text-brand-700">{{ $application->portfolio_url }}</a>
                        @else
                            <span class="text-neutral-500">—</span>
                        @endif
                    </dd>
                </div>
            </div>
            <div>
                <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-neutral-400">Surat pengantar</dt>
                <dd class="mt-1 whitespace-pre-line leading-relaxed text-neutral-700">{{ $application->cover_letter ?: '—' }}</dd>
            </div>
            @if ($application->cv_path)
                <div>
                    <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-neutral-400">CV</dt>
                    <dd class="mt-1">
                        <a href="{{ route('admin.career-applications.download', $application) }}" class="font-medium text-brand-600 hover:text-brand-700">Unduh CV</a>
                    </dd>
                </div>
            @endif
        </dl>

        <form method="post" action="{{ route('admin.career-applications.destroy', $application) }}" class="mt-6 border-t border-neutral-200 pt-5"
            onsubmit="return confirm('Hapus lamaran ini? Tindakan tidak dapat dibatalkan.')">
            @csrf
            @method('delete')
            <button type="submit" class="text-[13px] font-semibold text-red-600 transition hover:text-red-700">Hapus lamaran</button>
        </form>
    </div>

    <form method="post" action="{{ route('admin.career-applications.update', $application) }}" class="h-fit rounded-lg border border-neutral-200 bg-white p-6">
        @csrf
        @method('put')
        <h2 class="text-[13px] font-semibold text-neutral-900">Status lamaran</h2>
        <div class="mt-3 space-y-2">
            @foreach ($statuses as $status)
                <label class="flex cursor-pointer items-center gap-2.5 rounded-md border border-neutral-200 px-3 py-2.5 text-sm transition hover:border-brand-300 {{ $application->status === $status ? 'border-brand-500 bg-brand-50/50' : '' }}">
                    <input type="radio" name="status" value="{{ $status }}" @checked($application->status === $status) class="h-4 w-4 accent-brand-600">
                    <span class="font-medium text-neutral-800">{{ ucfirst($status) }}</span>
                </label>
            @endforeach
        </div>
        @error('status')
            <p class="mt-2 text-xs font-medium text-red-700">{{ $message }}</p>
        @enderror
        <x-admin.partials.button type="submit" variant="primary" class="mt-4 w-full justify-center">Simpan Status</x-admin.partials.button>
    </form>
</div>
@endsection
