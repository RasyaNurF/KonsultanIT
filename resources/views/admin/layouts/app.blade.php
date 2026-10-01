<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Admin — Nusakode')</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-neutral-800 antialiased">

<div class="min-h-screen lg:pl-[256px]" data-admin-shell>

    {{-- ============ SIDEBAR ============ --}}
    <aside data-admin-sidebar
        class="fixed bottom-0 left-0 top-0 z-50 flex w-[256px] -translate-x-full flex-col border-r border-white/10 bg-[#123653] transition-transform duration-200 ease-out lg:translate-x-0"
        aria-label="Navigasi admin">
        <div class="flex h-16 shrink-0 items-center justify-between px-5">
            <a href="{{ route('admin.dashboard') }}" class="flex flex-col leading-none">
                <span class="text-[17px] font-extrabold tracking-[0.14em] text-white">NUSAKODE</span>
                <span class="mt-1 text-[9px] font-semibold uppercase tracking-[0.22em] text-white/40">Admin Panel</span>
            </a>
            <button type="button" data-admin-sidebar-close class="flex h-9 w-9 items-center justify-center rounded-md text-white/60 transition hover:bg-white/10 hover:text-white lg:hidden" aria-label="Tutup menu">
                <x-admin.icon name="close" class="h-4 w-4" />
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 pb-6 text-[13px]" aria-label="Menu utama">
            <x-admin.nav-link :href="route('admin.dashboard')" icon="dashboard" :active="request()->routeIs('admin.dashboard')">Dashboard</x-admin.nav-link>

            @if (auth()->user()->hasAbility('lead.view') || auth()->user()->hasAbility('message.view') || auth()->user()->hasAbility('client.view'))
                <x-admin.nav-section label="CRM" :routes="['admin.leads.*', 'admin.messages.*', 'admin.clients.*']" />
                @if (auth()->user()->hasAbility('lead.view'))
                    <x-admin.nav-link :href="route('admin.leads.index')" icon="inbox" :active="request()->routeIs('admin.leads.*')" badge="{{ $pendingLeads ?? 0 }}">Leads</x-admin.nav-link>
                @endif
                @if (auth()->user()->hasAbility('message.view'))
                    <x-admin.nav-link :href="route('admin.messages.index')" icon="message" :active="request()->routeIs('admin.messages.*')" badge="{{ $unreadMessages ?? 0 }}" badge-target="messages">Pesan</x-admin.nav-link>
                @endif
                @if (auth()->user()->hasAbility('client.view'))
                    <x-admin.nav-link :href="route('admin.clients.index')" icon="building" :active="request()->routeIs('admin.clients.*')">Klien</x-admin.nav-link>
                @endif
            @endif

            @if (auth()->user()->hasAbility('project.view') || auth()->user()->hasAbility('portfolio.view'))
                <x-admin.nav-section label="Operasional" :routes="['admin.projects.*', 'admin.portfolios.*']" />
                @if (auth()->user()->hasAbility('project.view'))
                    <x-admin.nav-link :href="route('admin.projects.index')" icon="kanban" :active="request()->routeIs('admin.projects.*')">Proyek</x-admin.nav-link>
                @endif
                @if (auth()->user()->hasAbility('portfolio.view'))
                    <x-admin.nav-link :href="route('admin.portfolios.index')" icon="briefcase" :active="request()->routeIs('admin.portfolios.*')">Portfolio</x-admin.nav-link>
                @endif
            @endif

            @if (auth()->user()->hasAbility('service.view') || auth()->user()->hasAbility('solution-category.view') || auth()->user()->hasAbility('solution.view') || auth()->user()->hasAbility('resource.view') || auth()->user()->hasAbility('industry.view') || auth()->user()->hasAbility('article.view') || auth()->user()->hasAbility('blog-category.view'))
                <x-admin.nav-section label="Konten Website" :routes="['admin.services.*', 'admin.solution-categories.*', 'admin.solutions.*', 'admin.resources.*', 'admin.industries.*', 'admin.articles.*', 'admin.blog-categories.*']" />
                @if (auth()->user()->hasAbility('service.view'))
                    <x-admin.nav-link :href="route('admin.services.index')" icon="layers" :active="request()->routeIs('admin.services.*')">Layanan</x-admin.nav-link>
                @endif
                @if (auth()->user()->hasAbility('solution.view'))
                    <x-admin.nav-link :href="route('admin.solutions.index')" icon="layers" :active="request()->routeIs('admin.solutions.*')">Solusi</x-admin.nav-link>
                @endif
                @if (auth()->user()->hasAbility('solution-category.view'))
                    <div class="ml-5 border-l border-white/10 pl-2">
                        <x-admin.nav-link :href="route('admin.solution-categories.index')" icon="layers" :active="request()->routeIs('admin.solution-categories.*')">Kategori Solusi</x-admin.nav-link>
                    </div>
                @endif
                @if (auth()->user()->hasAbility('resource.view'))
                    <x-admin.nav-link :href="route('admin.resources.index')" icon="article" :active="request()->routeIs('admin.resources.*')">Resources</x-admin.nav-link>
                @endif
                @if (auth()->user()->hasAbility('industry.view'))
                    <x-admin.nav-link :href="route('admin.industries.index')" icon="factory" :active="request()->routeIs('admin.industries.*')">Industri</x-admin.nav-link>
                @endif
                @if (auth()->user()->hasAbility('article.view'))
                    <x-admin.nav-link :href="route('admin.articles.index')" icon="article" :active="request()->routeIs('admin.articles.*')">Artikel</x-admin.nav-link>
                @endif
                @if (auth()->user()->hasAbility('blog-category.view'))
                    <div class="ml-5 border-l border-white/10 pl-2">
                        <x-admin.nav-link :href="route('admin.blog-categories.index')" icon="article" :active="request()->routeIs('admin.blog-categories.*')">Kategori Blog</x-admin.nav-link>
                    </div>
                @endif
            @endif

            @if (auth()->user()->hasAbility('company.view') || auth()->user()->hasAbility('team.view') || auth()->user()->hasAbility('testimonial.view') || auth()->user()->hasAbility('career.view'))
                <x-admin.nav-section label="Perusahaan" :routes="['admin.company.*', 'admin.teams.*', 'admin.testimonials.*', 'admin.careers.*', 'admin.career-applications.*']" />
                @if (auth()->user()->hasAbility('company.view'))
                    <x-admin.nav-link :href="route('admin.company.edit')" icon="building" :active="request()->routeIs('admin.company.*')">Profil Perusahaan</x-admin.nav-link>
                @endif
                @if (auth()->user()->hasAbility('team.view'))
                    <x-admin.nav-link :href="route('admin.teams.index')" icon="users" :active="request()->routeIs('admin.teams.*')">Tim</x-admin.nav-link>
                @endif
                @if (auth()->user()->hasAbility('testimonial.view'))
                    <x-admin.nav-link :href="route('admin.testimonials.index')" icon="quote" :active="request()->routeIs('admin.testimonials.*')">Testimonial</x-admin.nav-link>
                @endif
                @if (auth()->user()->hasAbility('career.view'))
                    <x-admin.nav-link :href="route('admin.careers.index')" icon="briefcase" :active="request()->routeIs('admin.careers.*')">Karier</x-admin.nav-link>
                    <x-admin.nav-link :href="route('admin.career-applications.index')" icon="inbox" :active="request()->routeIs('admin.career-applications.*')">Lamaran</x-admin.nav-link>
                @endif
            @endif

            @if (auth()->user()->hasAbility('seo.view') || auth()->user()->hasAbility('announcement.view') || auth()->user()->hasAbility('hero.view') || auth()->user()->hasAbility('media.view') || auth()->user()->hasAbility('settings.view'))
                <x-admin.nav-section label="Website & Pengaturan" :routes="['admin.seo.*', 'admin.announcements.*', 'admin.heroes.*', 'admin.media.*', 'admin.settings.*']" />
                @if (auth()->user()->hasAbility('seo.view'))
                    <x-admin.nav-link :href="route('admin.seo.index')" icon="search" :active="request()->routeIs('admin.seo.*')">SEO</x-admin.nav-link>
                @endif
                @if (auth()->user()->hasAbility('announcement.view'))
                    <x-admin.nav-link :href="route('admin.announcements.index')" icon="megaphone" :active="request()->routeIs('admin.announcements.*')">Pengumuman</x-admin.nav-link>
                @endif
                @if (auth()->user()->hasAbility('hero.view'))
                    <x-admin.nav-link :href="route('admin.heroes.index')" icon="image" :active="request()->routeIs('admin.heroes.*')">Hero</x-admin.nav-link>
                @endif
                @if (auth()->user()->hasAbility('media.view'))
                    <x-admin.nav-link :href="route('admin.media.index')" icon="image" :active="request()->routeIs('admin.media.*')">Media</x-admin.nav-link>
                @endif
                @if (auth()->user()->hasAbility('settings.view'))
                    <x-admin.nav-link :href="route('admin.settings.edit')" icon="settings" :active="request()->routeIs('admin.settings.*')">Pengaturan</x-admin.nav-link>
                @endif
            @endif

            @if (auth()->user()->hasAbility('user.view') || auth()->user()->hasAbility('profile.manage'))
                <x-admin.nav-section label="Admin" :routes="['admin.users.*', 'admin.profile.*']" />
                @if (auth()->user()->hasAbility('user.view'))
                    <x-admin.nav-link :href="route('admin.users.index')" icon="users" :active="request()->routeIs('admin.users.*')">Pengguna</x-admin.nav-link>
                @endif
                @if (auth()->user()->hasAbility('profile.manage'))
                    <x-admin.nav-link :href="route('admin.profile.edit')" icon="user" :active="request()->routeIs('admin.profile.*')">Profil</x-admin.nav-link>
                @endif
            @endif
        </nav>

        <div class="shrink-0 border-t border-white/10 p-3">
            <div class="flex items-center gap-3 rounded-lg px-2 py-2">
                <x-admin.avatar :user="auth()->user()" class="h-9 w-9" />
                <div class="min-w-0 flex-1">
                    <p class="truncate text-[13px] font-semibold text-white">{{ auth()->user()->name }}</p>
                    <p class="truncate text-[11px] text-white/45">{{ auth()->user()->role->label() }}</p>
                </div>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-md text-white/50 transition hover:bg-white/10 hover:text-white" aria-label="Keluar" title="Keluar">
                        <x-admin.icon name="logout" class="h-4 w-4" />
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div data-admin-backdrop class="fixed inset-0 z-40 hidden bg-neutral-950/50 lg:hidden" aria-hidden="true"></div>

    {{-- ============ KONTEN ============ --}}
    <div class="flex min-h-screen min-w-0 flex-col">

        {{-- Topbar --}}
        <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center gap-4 border-b border-neutral-200 bg-white px-4 sm:px-6">
            <div class="flex min-w-0 flex-1 items-center gap-4">
                <button type="button" data-admin-sidebar-open class="flex h-9 w-9 shrink-0 items-center justify-center rounded-md text-neutral-600 transition hover:bg-neutral-100 lg:hidden" aria-label="Buka menu">
                    <x-admin.icon name="menu" class="h-5 w-5" />
                </button>

                <x-admin.breadcrumb :items="$breadcrumbs ?? []" />
            </div>

            <div class="flex shrink-0 items-center gap-1.5 sm:gap-3">
                @isset($searchAction)
                    <form action="{{ $searchAction }}" method="get" class="relative hidden md:block">
                        @foreach (request()->except('q', 'page') as $key => $value)
                            @if (is_scalar($value) && ! in_array($key, ['status', 'category', 'client_id', 'project_type', 'role', 'type']))
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <x-admin.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-neutral-400" />
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ $searchPlaceholder ?? 'Cari…' }}"
                            class="h-9 w-52 rounded-md border border-neutral-200 bg-neutral-50 pl-9 pr-3 text-[13px] text-neutral-800 outline-none transition placeholder:text-neutral-400 focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-100 xl:w-64">
                    </form>
                @endisset

                <a href="{{ route('admin.messages.index', ['status' => 'unread']) }}" class="relative flex h-9 w-9 items-center justify-center rounded-md text-neutral-600 transition hover:bg-neutral-100" aria-label="Pesan belum dibaca">
                    <x-admin.icon name="bell" class="h-5 w-5" />
                    <span data-admin-badge="messages" class="absolute right-1 top-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-brand-600 px-1 text-[10px] font-bold text-white {{ ($unreadMessages ?? 0) > 0 ? '' : 'hidden' }}">{{ ($unreadMessages ?? 0) > 9 ? '9+' : ($unreadMessages ?? 0) }}</span>
                </a>

                <div class="relative" data-dropdown>
                    <button type="button" data-dropdown-toggle aria-expanded="false"
                        class="flex items-center gap-2 rounded-md py-1 pl-1 pr-2 transition hover:bg-neutral-100">
                        <x-admin.avatar :user="auth()->user()" class="h-8 w-8" />
                        <span class="hidden text-left sm:block">
                            <span class="block max-w-32 truncate text-[13px] font-semibold leading-tight text-neutral-900">{{ auth()->user()->name }}</span>
                            <span class="block text-[11px] leading-tight text-neutral-500">{{ auth()->user()->role->label() }}</span>
                        </span>
                        <x-admin.icon name="chevron-down" class="hidden h-3.5 w-3.5 text-neutral-400 sm:block" />
                    </button>
                    <div data-dropdown-menu class="absolute right-0 top-full z-40 mt-2 hidden w-56 overflow-hidden rounded-lg border border-neutral-200 bg-white shadow-lg shadow-neutral-900/5">
                        <div class="border-b border-neutral-100 px-4 py-3">
                            <p class="truncate text-[13px] font-semibold text-neutral-900">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-neutral-500">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-[13px] text-neutral-700 transition hover:bg-neutral-50">
                            <x-admin.icon name="user" class="h-4 w-4 text-neutral-400" /> Profil saya
                        </a>
                        <a href="{{ url('/') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-[13px] text-neutral-700 transition hover:bg-neutral-50">
                            <x-admin.icon name="external" class="h-4 w-4 text-neutral-400" /> Lihat website
                        </a>
                        <form method="post" action="{{ route('logout') }}" class="border-t border-neutral-100">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2.5 px-4 py-2.5 text-left text-[13px] text-red-600 transition hover:bg-red-50">
                                <x-admin.icon name="logout" class="h-4 w-4" /> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 px-4 py-6 sm:px-6 sm:py-8">
            <div class="w-full">
                @yield('content')
            </div>
        </main>

        <footer class="shrink-0 border-t border-neutral-200 px-4 py-5 sm:px-6">
            <div class="flex flex-col gap-1 text-xs text-neutral-400 sm:flex-row sm:items-center sm:justify-between">
                <p>&copy; {{ date('Y') }} PT Nusakode Teknologi</p>
                <p>Panel admin · v1.0</p>
            </div>
        </footer>
    </div>
</div>

{{-- Toast --}}
<div data-toast-container class="pointer-events-none fixed bottom-5 right-5 z-[60] flex w-[min(360px,calc(100vw-2.5rem))] flex-col gap-2"></div>

<span data-admin-unread-url="{{ route('admin.messages.unread') }}" hidden></span>

<div class="hidden" aria-hidden="true">
    @if (session('success'))<span data-flash="success" data-message="{{ session('success') }}"></span>@endif
    @if (session('error'))<span data-flash="error" data-message="{{ session('error') }}"></span>@endif
    @if (isset($errors) && $errors->any())<span data-flash="error" data-message="{{ $errors->first() }}"></span>@endif
</div>

</body>
</html>
