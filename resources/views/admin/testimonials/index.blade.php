@extends('admin.layouts.app')

@section('title', 'Testimonial — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Testimonial" description="Kutipan dan ulasan dari klien KIT Konsultan IT." eyebrow="Konten">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.testimonials.create')" variant="primary">
            <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Testimonial
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari nama, perusahaan, atau kutipan…"
        :selects="[
            ['name' => 'status', 'label' => 'Semua status', 'options' => collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()],
        ]" />

    @if ($testimonials->isEmpty())
        <x-admin.empty-state title="Belum ada testimonial." description="Tambahkan testimonial pertama Anda." icon="quote">
            <x-slot:action>
                <x-admin.partials.button :href="route('admin.testimonials.create')" variant="primary">
                    <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Testimonial
                </x-admin.partials.button>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Nama</th>
                <th class="px-5 py-2.5 font-bold">Perusahaan</th>
                <th class="px-5 py-2.5 font-bold">Testimonial</th>
                <th class="px-5 py-2.5 font-bold">Unggulan</th>
                <th class="px-5 py-2.5 font-bold">Status</th>
                <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
            </x-slot:head>

            @foreach ($testimonials as $testimonial)
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            @if ($testimonial->photo_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($testimonial->photo_path) }}" alt="{{ $testimonial->name }}" class="h-8 w-8 shrink-0 rounded-full border border-neutral-200 bg-white object-cover">
                            @else
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-neutral-100 text-[11px] font-bold text-neutral-500">{{ $testimonial->initials() }}</span>
                            @endif
                            <span class="min-w-0">
                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="block truncate font-semibold text-neutral-900 transition hover:text-brand-600">{{ $testimonial->name }}</a>
                                @if ($testimonial->position)
                                    <span class="block truncate text-xs text-neutral-500">{{ $testimonial->position }}</span>
                                @endif
                            </span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-neutral-600">{{ $testimonial->company ?: '—' }}</td>
                    <td class="px-5 py-3.5 text-neutral-500 italic">{{ Str::limit($testimonial->quote, 90) }}</td>
                    <td class="px-5 py-3.5">
                        @if ($testimonial->is_featured)
                            <x-admin.badge tone="violet">Unggulan</x-admin.badge>
                        @else
                            <span class="text-neutral-400">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5"><x-admin.status-badge :status="$testimonial->status" /></td>
                    <td class="px-5 py-3.5">
                        <x-admin.partials.row-actions
                            :edit="route('admin.testimonials.edit', $testimonial)"
                            :delete="route('admin.testimonials.destroy', $testimonial)"
                            delete-confirm="Hapus testimonial ini? Tindakan tidak dapat dibatalkan." />
                    </td>
                </tr>
            @endforeach
        </x-admin.partials.table>

        <x-admin.partials.pagination :paginator="$testimonials" />
    @endif
</div>
@endsection
