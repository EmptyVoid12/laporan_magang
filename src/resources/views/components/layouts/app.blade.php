<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dinas Perhubungan DKI Jakarta - Sistem Manajemen Laporan Gangguan Perangkat Jaringan">
    <title>Dinas Perhubungan DKI Jakarta - Manajemen Laporan Gangguan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @livewireStyles
    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(99,102,241,0.08); color: #4f46e5; border-right: 3px solid #4f46e5; }
        .notif-panel { animation: slideDown 0.18s ease-out; }
        @keyframes slideDown { from { opacity:0; transform: translateY(-6px); } to { opacity:1; transform: translateY(0); } }
        
        /* Dark mode overrides */
        .dark .sidebar-link { color: #94a3b8; }
        .dark .sidebar-link:hover, .dark .sidebar-link.active { color: #818cf8; background: rgba(99, 102, 241, 0.15); border-right-color: #818cf8; }
    </style>
</head>
@php($isHomePage = request()->routeIs('home'))
@php($actor = Auth::guard('web')->user() ?: Auth::guard('admin')->user())
@php($actor = $actor && in_array($actor->role, ['user', 'teknisi', 'admin', 'operator'], true) ? $actor : null)
<body class="bg-slate-50 text-slate-700 antialiased dark:bg-slate-950 dark:text-slate-300">

    @if($isHomePage)
        {{ $slot }}
    @elseif($actor)
        {{-- Top Navbar --}}
        <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/80 backdrop-blur-md dark:border-slate-800 dark:bg-slate-900/80">
            <div class="flex h-16 items-center justify-between px-4 lg:px-6">
                <div class="flex items-center gap-3">
                    {{-- Mobile menu toggle --}}
                    <button onclick="document.getElementById('mobileSidebar').classList.toggle('hidden')" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden dark:text-slate-400 dark:hover:bg-slate-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('images/logo-dishub.png') }}" alt="Logo Dinas Perhubungan" class="h-9 w-auto object-contain" />
                        <div class="hidden sm:block">
                            <div class="text-sm font-bold text-slate-900 dark:text-white">Dinas Perhubungan</div>
                            <div class="text-[11px] text-slate-400 dark:text-slate-500">DKI Jakarta</div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Theme Toggle --}}
                    <button class="theme-toggle-btn rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-300" aria-label="Toggle dark mode">
                        <svg class="theme-toggle-dark-icon hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                        </svg>
                        <svg class="theme-toggle-light-icon hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                        </svg>
                    </button>

                    {{-- Notifications --}}
                    <div x-data="{ open: false }" class="relative" @click.away="open = false">
                        @php($unreadCount = $actor->unreadNotifications()->count())
                        <button @click="open = !open" class="relative rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-350">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                            @if($unreadCount > 0)
                                <span class="absolute -right-0.5 -top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                            @endif
                        </button>
                        <div x-show="open" x-cloak class="notif-panel absolute right-0 mt-2 w-96 rounded-xl border border-slate-200 bg-white p-4 shadow-xl dark:border-slate-800 dark:bg-slate-900">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3 dark:border-slate-800">
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white">Notifikasi</h4>
                                <form method="POST" action="{{ route('notifications.read-all') }}">
                                    @csrf
                                    <button type="submit" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">Tandai semua dibaca</button>
                                </form>
                            </div>
                            <div class="mt-3 max-h-80 space-y-2 overflow-y-auto">
                                @forelse($actor->notifications()->latest()->take(8)->get() as $notification)
                                    <div class="rounded-lg p-3 {{ $notification->read_at ? 'bg-slate-50 dark:bg-slate-950/40' : 'border-l-2 border-indigo-500 bg-indigo-50/50 dark:bg-indigo-950/20' }}">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="min-w-0">
                                                <div class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $notification->data['title'] ?? 'Notifikasi' }}</div>
                                                <div class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">{{ $notification->data['message'] ?? '-' }}</div>
                                                @if(!empty($notification->data['ticket_code']))
                                                    <span class="mt-1 inline-block rounded bg-slate-200 px-1.5 py-0.5 font-mono text-[10px] text-slate-600 dark:bg-slate-800 dark:text-slate-400">{{ $notification->data['ticket_code'] }}</span>
                                                @endif
                                            </div>
                                            @if(!$notification->read_at)
                                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                                    @csrf
                                                    <button type="submit" class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">Baca</button>
                                                </form>
                                            @endif
                                        </div>
                                        @if(!empty($notification->data['url']))
                                            <a href="{{ $notification->data['url'] }}" class="mt-2 inline-block text-xs font-semibold text-indigo-600 hover:underline dark:text-indigo-400">Buka tiket →</a>
                                        @endif
                                    </div>
                                @empty
                                    <div class="py-6 text-center text-sm text-slate-400 dark:text-slate-550">Belum ada notifikasi.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- User Info --}}
                    <div class="hidden items-center gap-2 border-l border-slate-200 pl-3 sm:flex dark:border-slate-800">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-200 text-xs font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ strtoupper(substr($actor->name, 0, 1)) }}</div>
                        <div>
                            <div class="text-sm font-semibold text-slate-800 dark:text-white">{{ $actor->name }}</div>
                            <div class="text-[11px] capitalize text-slate-400 dark:text-slate-500">{{ $actor->role }}</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30">Keluar</button>
                    </form>
                </div>
            </div>
        </header>

        <div class="flex min-h-[calc(100vh-4rem)]">
            {{-- Desktop Sidebar --}}
            <aside class="hidden w-56 shrink-0 border-r border-slate-200 bg-white lg:block dark:border-slate-800 dark:bg-slate-900">
                <nav class="space-y-0.5 px-2 py-4">
                    @if($actor->role === 'admin')
                        <a href="{{ route('filament.admin.pages.dashboard') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-bold text-slate-600 {{ request()->routeIs('filament.admin.pages.dashboard') ? 'active' : '' }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Dashboard Admin
                        </a>
                        <a href="{{ route('filament.admin.resources.perangkats.index') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 {{ request()->routeIs('filament.admin.resources.perangkats.*') ? 'active' : '' }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/></svg>
                            Perangkat
                        </a>
                        <a href="{{ route('filament.admin.resources.gangguans.index') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 {{ request()->routeIs('filament.admin.resources.gangguans.*') ? 'active' : '' }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Laporan Masuk
                        </a>
                        <a href="{{ route('filament.admin.pages.reports-overview') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 {{ request()->routeIs('filament.admin.pages.reports-overview') ? 'active' : '' }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M18 17V9M13 17V5M8 17v-3"/></svg>
                            Ringkasan Laporan
                        </a>
                    @elseif($actor->role === 'operator')
                        <a href="{{ route('filament.admin.pages.dashboard') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 {{ request()->routeIs('filament.admin.pages.dashboard') ? 'active' : '' }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/></svg>
                            Panel Operator
                        </a>
                    @elseif($actor->role === 'teknisi')
                        <a href="{{ route('teknisi.task') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 {{ request()->routeIs('teknisi.task') ? 'active' : '' }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Daftar Tugas
                        </a>
                    @elseif($actor->role === 'user')
                        <a href="{{ route('user.gangguan') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 {{ request()->routeIs('user.gangguan*') ? 'active' : '' }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                            Laporan Saya
                        </a>
                    @endif
                    <a href="{{ route('home') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/></svg>
                        Beranda
                    </a>
                </nav>
            </aside>

            {{-- Mobile Sidebar Overlay --}}
            <div id="mobileSidebar" class="fixed inset-0 z-50 hidden lg:hidden">
                <div class="absolute inset-0 bg-slate-900/30" onclick="document.getElementById('mobileSidebar').classList.add('hidden')"></div>
                <aside class="relative w-64 bg-white shadow-xl h-full overflow-y-auto dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b border-slate-200 px-4 py-4 dark:border-slate-800">
                        <span class="text-sm font-bold text-slate-900 dark:text-white">Menu</span>
                        <button onclick="document.getElementById('mobileSidebar').classList.add('hidden')" class="rounded p-1 text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-350">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <nav class="space-y-0.5 px-2 py-3">
                        @if($actor->role === 'admin')
                            <a href="{{ route('filament.admin.pages.dashboard') }}" class="sidebar-link block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 {{ request()->routeIs('filament.admin.pages.dashboard') ? 'active' : '' }}" onclick="document.getElementById('mobileSidebar').classList.add('hidden')">Dashboard Admin</a>
                            <a href="{{ route('filament.admin.resources.perangkats.index') }}" class="sidebar-link block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 {{ request()->routeIs('filament.admin.resources.perangkats.*') ? 'active' : '' }}" onclick="document.getElementById('mobileSidebar').classList.add('hidden')">Perangkat</a>
                            <a href="{{ route('filament.admin.resources.gangguans.index') }}" class="sidebar-link block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 {{ request()->routeIs('filament.admin.resources.gangguans.*') ? 'active' : '' }}" onclick="document.getElementById('mobileSidebar').classList.add('hidden')">Laporan Masuk</a>
                            <a href="{{ route('filament.admin.pages.reports-overview') }}" class="sidebar-link block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 {{ request()->routeIs('filament.admin.pages.reports-overview') ? 'active' : '' }}" onclick="document.getElementById('mobileSidebar').classList.add('hidden')">Ringkasan Laporan</a>
                        @elseif($actor->role === 'operator')
                            <a href="{{ route('filament.admin.pages.dashboard') }}" class="sidebar-link block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 {{ request()->routeIs('filament.admin.pages.dashboard') ? 'active' : '' }}">Panel Operator</a>
                        @elseif($actor->role === 'teknisi')
                            <a href="{{ route('teknisi.task') }}" class="sidebar-link block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600">Daftar Tugas</a>
                        @elseif($actor->role === 'user')
                            <a href="{{ route('user.gangguan') }}" class="sidebar-link block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600">Laporan Saya</a>
                        @endif
                        <a href="{{ route('home') }}" class="sidebar-link block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600">Beranda</a>
                    </nav>
                </aside>
            </div>

            {{-- Main Content --}}
            <main class="flex-1 overflow-x-hidden p-5 lg:p-7">
                {{ $slot }}
            </main>
        </div>
    @else
        {{ $slot }}
    @endif

    @livewireScripts
    <script>
        function updateToggleIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            document.querySelectorAll('.theme-toggle-btn').forEach(btn => {
                const darkIcon = btn.querySelector('.theme-toggle-dark-icon');
                const lightIcon = btn.querySelector('.theme-toggle-light-icon');
                if (isDark) {
                    lightIcon?.classList.remove('hidden');
                    darkIcon?.classList.add('hidden');
                } else {
                    darkIcon?.classList.remove('hidden');
                    lightIcon?.classList.add('hidden');
                }
            });
        }

        // Global click listener for theme toggle buttons (safe from Livewire updates)
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.theme-toggle-btn');
            if (!btn) return;

            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            updateToggleIcons();
        });

        // Run on initial load
        document.addEventListener('DOMContentLoaded', updateToggleIcons);
        // Run on Livewire page transitions/updates
        document.addEventListener('livewire:navigated', updateToggleIcons);
        document.addEventListener('livewire:update', updateToggleIcons);
    </script>
</body>
</html>
