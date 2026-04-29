<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOC - Manajemen Laporan Gangguan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
@php($isHomePage = request()->routeIs('home'))
<body class="bg-gray-100 font-sans leading-normal tracking-normal text-gray-800">

    @if($isHomePage)
        {{ $slot }}
    @elseif(auth()->check())
        <nav class="bg-blue-800 p-4 shadow-md text-white flex justify-between items-center">
            <div class="font-bold text-xl">
                NOC Panel 
                <span class="text-sm font-normal ml-2 px-2 py-1 bg-blue-700 rounded-lg">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
            <div class="flex items-center gap-4">
                <details class="relative">
                    <summary class="flex cursor-pointer list-none items-center gap-2 rounded-full bg-blue-700 px-3 py-2 text-sm">
                        Notifikasi
                        @php($unreadCount = auth()->user()->unreadNotifications()->count())
                        @if($unreadCount > 0)
                            <span class="rounded-full bg-white px-2 py-0.5 text-xs font-bold text-blue-700">{{ $unreadCount }}</span>
                        @endif
                    </summary>
                    <div class="absolute right-0 z-50 mt-3 w-96 rounded-xl border border-gray-200 bg-white p-4 text-gray-800 shadow-2xl">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-sm font-bold text-gray-900">Notifikasi Aktivitas</div>
                                <div class="text-xs text-gray-500">Update tiket terbaru untuk akun Anda.</div>
                            </div>
                            <form method="POST" action="{{ route('notifications.read-all') }}">
                                @csrf
                                <button type="submit" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Tandai semua dibaca</button>
                            </form>
                        </div>
                        <div class="mt-4 max-h-96 space-y-3 overflow-y-auto">
                            @forelse(auth()->user()->notifications()->latest()->take(8)->get() as $notification)
                                <div class="rounded-lg border {{ $notification->read_at ? 'border-gray-200 bg-gray-50' : 'border-blue-200 bg-blue-50' }} p-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="text-sm font-bold text-gray-900">{{ $notification->data['title'] ?? 'Notifikasi' }}</div>
                                            <div class="mt-1 text-sm text-gray-600">{{ $notification->data['message'] ?? '-' }}</div>
                                            @if(!empty($notification->data['ticket_code']))
                                                <div class="mt-2 text-xs font-mono text-blue-700">{{ $notification->data['ticket_code'] }}</div>
                                            @endif
                                        </div>
                                        @if(!$notification->read_at)
                                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                                @csrf
                                                <button type="submit" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Baca</button>
                                            </form>
                                        @endif
                                    </div>
                                    @if(!empty($notification->data['url']))
                                        <a href="{{ $notification->data['url'] }}" class="mt-3 inline-flex text-xs font-semibold text-blue-600 hover:text-blue-700">
                                            Buka tiket
                                        </a>
                                    @endif
                                </div>
                            @empty
                                <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-4 py-8 text-center text-sm text-gray-500">
                                    Belum ada notifikasi untuk akun Anda.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </details>
                <span>{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-sm">
                        Logout
                    </button>
                </form>
            </div>
        </nav>
        
        <div class="flex flex-col md:flex-row min-h-screen">
            <!-- Sidebar -->
            <div class="bg-white w-full md:w-64 border-r border-gray-200">
                <ul class="flex flex-col py-4">
                    @if(auth()->user()->role === 'admin')
                        <li class="px-5 py-2 hover:bg-gray-100">
                            <a href="{{ route('admin.dashboard') }}" class="block">Dashboard</a>
                        </li>
                        <li class="px-5 py-2 hover:bg-gray-100">
                            <a href="{{ route('admin.perangkat') }}" class="block">Manajemen Perangkat</a>
                        </li>
                        <li class="px-5 py-2 hover:bg-gray-100">
                            <a href="{{ route('admin.gangguan') }}" class="block">Laporan Masuk</a>
                        </li>
                    @elseif(auth()->user()->role === 'operator')
                        <li class="px-5 py-2 hover:bg-gray-100">
                            <a href="{{ route('user.gangguan') }}" class="block">Buat Laporan</a>
                        </li>
                    @elseif(auth()->user()->role === 'teknisi')
                        <li class="px-5 py-2 hover:bg-gray-100">
                            <a href="{{ route('teknisi.task') }}" class="block">Daftar Tugas</a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Content -->
            <div class="flex-1 p-6">
                {{ $slot }}
            </div>
        </div>
    @else
        {{ $slot }}
    @endif

    @livewireScripts
</body>
</html>
