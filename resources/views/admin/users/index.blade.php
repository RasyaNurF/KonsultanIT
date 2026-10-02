@extends('admin.layouts.app')

@section('title', 'Pengguna — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Pengguna" description="Akun admin yang dapat mengakses panel." eyebrow="Admin">
    <x-slot:actions>
        <x-admin.partials.button :href="route('admin.users.create')" variant="primary">
            <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Pengguna
        </x-admin.partials.button>
    </x-slot:actions>
</x-admin.page-header>

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari nama atau email…"
        :selects="[
            ['name' => 'role', 'label' => 'Semua peran', 'options' => collect($roles)->mapWithKeys(fn ($r) => [$r->value => $r->label()])->all()],
        ]" />

    @if ($users->isEmpty())
        <x-admin.empty-state title="Belum ada pengguna." description="Tambahkan akun admin pertama Anda." icon="users">
            <x-slot:action>
                <x-admin.partials.button :href="route('admin.users.create')" variant="primary">
                    <x-admin.icon name="plus" class="h-4 w-4" /> Tambah Pengguna
                </x-admin.partials.button>
            </x-slot:action>
        </x-admin.empty-state>
    @else
        <x-admin.partials.table>
            <x-slot:head>
                <th class="px-5 py-2.5 font-bold">Pengguna</th>
                <th class="px-5 py-2.5 font-bold">Peran</th>
                <th class="px-5 py-2.5 font-bold">Status</th>
                <th class="px-5 py-2.5 font-bold">Dibuat</th>
                <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
            </x-slot:head>

            @foreach ($users as $user)
                @php($tone = match ($user->role->value) {
                    'super_admin' => 'violet',
                    'admin' => 'brand',
                    default => 'neutral',
                })
                <tr class="transition hover:bg-neutral-50/70">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <x-admin.avatar :user="$user" class="h-8 w-8 shrink-0" />
                            <span class="min-w-0">
                                <span class="flex items-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="truncate font-semibold text-neutral-900 transition hover:text-brand-600">{{ $user->name }}</a>
                                    @if ($user->is(auth()->user()))
                                        <x-admin.badge tone="brand">Anda</x-admin.badge>
                                    @endif
                                </span>
                                <span class="block truncate text-xs text-neutral-500">{{ $user->email }}</span>
                            </span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5"><x-admin.badge :tone="$tone">{{ $user->role->label() }}</x-admin.badge></td>
                    <td class="px-5 py-3.5">
                        @if ($user->is_active)
                            <x-admin.badge tone="emerald">Aktif</x-admin.badge>
                        @else
                            <x-admin.badge tone="red">Nonaktif</x-admin.badge>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-5 py-3.5 text-neutral-500 tabular-nums">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                    <td class="px-5 py-3.5">
                        <x-admin.partials.row-actions
                            :edit="route('admin.users.edit', $user)"
                            :delete="$user->is(auth()->user()) ? null : route('admin.users.destroy', $user)"
                            delete-confirm="Hapus pengguna ini? Tindakan tidak dapat dibatalkan." />
                    </td>
                </tr>
            @endforeach
        </x-admin.partials.table>

        <x-admin.partials.pagination :paginator="$users" />
    @endif
</div>
@endsection
