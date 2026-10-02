@if ($participants->isEmpty())
    <x-admin.empty-state title="Belum ada percakapan." description="Pesan dari pengunjung dan pengguna terdaftar akan muncul di sini." icon="message" />
@else
    <x-admin.partials.table>
        <x-slot:head>
            <th class="px-5 py-2.5 font-bold">Pengunjung</th>
            <th class="px-5 py-2.5 font-bold">Subjek</th>
            <th class="px-5 py-2.5 font-bold">Pesan</th>
            <th class="px-5 py-2.5 font-bold">Status</th>
            <th class="px-5 py-2.5 font-bold">Terakhir</th>
            <th class="px-5 py-2.5 text-right font-bold">Aksi</th>
        </x-slot:head>

        @foreach ($participants as $p)
            <tr class="transition hover:bg-neutral-50/70">
                <td class="px-5 py-3.5">
                    <a href="{{ route('admin.messages.show', $p) }}" class="block font-semibold text-neutral-900 transition hover:text-brand-600">{{ $p->name ?: 'Tanpa nama' }}</a>
                    <span class="text-xs text-neutral-500">{{ $p->email }}</span>
                </td>
                <td class="px-5 py-3.5 text-neutral-600">{{ $p->subject ? \Illuminate\Support\Str::limit($p->subject, 60) : '—' }}</td>
                <td class="px-5 py-3.5 text-neutral-600 tabular-nums">{{ $p->messages_count }} pesan</td>
                <td class="px-5 py-3.5"><x-admin.status-badge :status="\App\Enums\MessageStatus::from($p->status)" /></td>
                <td class="whitespace-nowrap px-5 py-3.5 text-neutral-500">{{ $p->last_message_at?->locale('id')->diffForHumans(short: true) ?? '—' }}</td>
                <td class="px-5 py-3.5">
                    <x-admin.partials.row-actions
                        :view="route('admin.messages.show', $p)"
                        :delete="route('admin.messages.destroy', $p)"
                        delete-confirm="Hapus percakapan ini beserta seluruh pesannya?" />
                </td>
            </tr>
        @endforeach
    </x-admin.partials.table>

    <x-admin.partials.pagination :paginator="$participants" />
@endif
