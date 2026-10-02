@extends('admin.layouts.app')

@section('title', 'Pesan — Admin KIT Konsultan IT')

@section('content')
<x-admin.page-header title="Pesan" description="Percakapan dari pengunjung dan pengguna terdaftar." eyebrow="CRM" />

<div class="mt-6 rounded-lg border border-neutral-200 bg-white">
    <x-admin.partials.filters
        :action="$searchAction"
        search-placeholder="Cari nama, email, atau subjek…"
        :selects="[
            ['name' => 'status', 'label' => 'Semua status', 'options' => collect($statuses)->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()],
        ]" />

    <div data-messages-live data-live-url="{{ route('admin.messages.live') }}">
        @include('admin.messages.partials.list', ['participants' => $participants])
    </div>
</div>
@endsection
