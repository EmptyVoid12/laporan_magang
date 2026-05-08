<div class="relative min-h-screen overflow-hidden bg-slate-50 text-slate-700">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.08),_transparent_26%),radial-gradient(circle_at_top_right,_rgba(56,189,248,0.08),_transparent_22%),linear-gradient(180deg,_#f8fafc_0%,_#f1f5f9_100%)]"></div>
        <div class="absolute inset-0 opacity-40" style="background-image: linear-gradient(rgba(148,163,184,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(148,163,184,0.1) 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="absolute -left-20 top-20 h-72 w-72 rounded-full bg-indigo-400/10 blur-3xl"></div>
        <div class="absolute right-0 top-0 h-80 w-80 rounded-full bg-sky-400/10 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto flex w-full max-w-7xl flex-col gap-6 px-5 py-5 sm:px-6 lg:px-10 lg:py-8">
        <header class="rounded-2xl border border-slate-200 bg-white/80 px-5 py-4 shadow-sm backdrop-blur-xl">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-600 text-sm font-black text-white shadow-sm">
                        NOC
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-indigo-600">Network Operation Center</p>
                        <h1 class="mt-1 text-base font-black text-slate-900 sm:text-lg">Laporan Gangguan Perangkat</h1>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    @auth
                        @if(in_array(auth()->user()->role, ['admin', 'super_admin']) || auth()->user()->hasRole('super_admin'))
                            <a href="{{ url('/admin') }}" class="inline-flex items-center justify-center rounded-lg bg-white border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 shadow-sm">
                                Panel Admin
                            </a>
                        @elseif(auth()->user()->role === 'teknisi')
                            <a href="{{ route('teknisi.task') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 shadow-sm">
                                Daftar Tugas
                            </a>
                        @else
                            <a href="{{ route('user.gangguan') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 shadow-sm">
                                Buat Laporan
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-lg bg-white border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 shadow-sm">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 shadow-sm">
                            Daftar Baru
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-[11px] font-bold uppercase tracking-[0.24em] text-slate-500">Total Tiket</div>
                <div class="mt-4 text-4xl font-black text-slate-900">{{ $total }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-[11px] font-bold uppercase tracking-[0.24em] text-red-500">Butuh Respon</div>
                <div class="mt-4 text-4xl font-black text-slate-900">{{ $open }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-[11px] font-bold uppercase tracking-[0.24em] text-amber-500">Diproses</div>
                <div class="mt-4 text-4xl font-black text-slate-900">{{ $proses + $menunggu }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-[11px] font-bold uppercase tracking-[0.24em] text-emerald-500">Terverifikasi</div>
                <div class="mt-4 text-4xl font-black text-slate-900">{{ $diverifikasi }}</div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-[0.28em] text-indigo-600">Ticket Lookup</div>
                        <h3 class="mt-2 text-2xl font-black text-slate-900">Cek status tiket</h3>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-4.65a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                        </svg>
                    </div>
                </div>

                <p class="mt-3 text-sm leading-6 text-slate-500">
                    Masukkan kode tiket untuk melihat progres penanganan terbaru.
                </p>

                <form wire:submit.prevent="searchTicket" class="mt-5 space-y-4">
                    <div>
                        <label class="mb-2 block text-[11px] font-bold uppercase tracking-[0.24em] text-slate-500">Kode Tiket</label>
                        <div class="relative">
                            <input
                                type="text"
                                wire:model="ticketCode"
                                placeholder="NOC-20260421-0001"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 pr-12 text-sm font-semibold tracking-[0.05em] text-slate-900 placeholder:text-slate-400 focus:border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-100"
                            >
                            <span class="absolute inset-y-0 right-4 flex items-center text-slate-400">#</span>
                        </div>
                        @error('ticketCode')
                            <span class="mt-2 block text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3.5 text-sm font-bold text-white transition hover:bg-indigo-700 shadow-sm">
                        Cek status
                        <span>+</span>
                    </button>
                </form>

                <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    @if($ticketResult)
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <div class="text-[11px] font-bold uppercase tracking-[0.26em] text-indigo-600">{{ $ticketResult->kode_tiket }}</div>
                                <div class="mt-2 text-xl font-bold text-slate-900">{{ $ticketResult->perangkat->nama_perangkat }}</div>
                                <div class="mt-2 text-sm leading-6 text-slate-500">
                                    {{ $ticketResult->perangkat->jenis }} • {{ $ticketResult->perangkat->wilayah ?: '-' }} • {{ $ticketResult->perangkat->lokasi }}
                                </div>
                            </div>
                            <div class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                                {{ $ticketResult->workflow_status_label }}
                            </div>
                        </div>

                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm">
                                <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Prioritas</div>
                                <div class="mt-2 text-sm font-bold text-slate-900">{{ $ticketResult->prioritas }}</div>
                            </div>
                            <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm">
                                <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Teknisi</div>
                                <div class="mt-2 text-sm font-bold text-slate-900">{{ $ticketResult->teknisi?->name ?: 'Belum diassign' }}</div>
                            </div>
                        </div>

                        <div class="mt-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Timeline Penanganan</div>
                            <div class="mt-4 space-y-3">
                                @forelse($ticketResult->proses->sortByDesc('id') as $proses)
                                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <div class="text-sm font-bold text-slate-900">{{ $proses->actor_name }}</div>
                                                <div class="mt-1 text-[11px] uppercase tracking-[0.18em] text-indigo-600">{{ $proses->tipe_update }}</div>
                                            </div>
                                            <div class="text-xs text-slate-400">{{ optional($proses->tanggal_update)->format('d M Y') }}</div>
                                        </div>
                                        <div class="mt-3 text-sm text-slate-600">{{ $proses->keterangan_proses }}</div>
                                        @if($proses->kendala)
                                            <div class="mt-2 text-xs text-amber-600">Kendala: {{ $proses->kendala }}</div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-sm text-slate-500 text-center">
                                        Belum ada progres teknis yang dicatat untuk tiket ini.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @elseif($ticketLookupAttempted)
                        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm leading-6 text-red-600">
                            Tiket belum ditemukan. Coba cek lagi kode tiket yang Anda masukkan.
                        </div>
                    @else
                        <div class="grid gap-3">
                            <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white p-3 shadow-sm">
                                <span class="text-sm text-slate-500">Format tiket</span>
                                <span class="font-mono text-xs font-bold text-indigo-600">NOC-YYYYMMDD-XXXX</span>
                            </div>
                            <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white p-3 shadow-sm">
                                <span class="text-sm text-slate-500">Informasi tampil</span>
                                <span class="text-xs font-bold text-slate-900">status, prioritas, teknisi, timeline</span>
                            </div>
                        </div>
                    @endif
                </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-[0.28em] text-indigo-600">Live Feed</div>
                    <h3 class="mt-2 text-3xl font-bold text-slate-900">Tiket terbaru</h3>
                </div>
                <p class="max-w-xl text-sm leading-6 text-slate-500">
                    Ringkasan singkat aktivitas laporan terbaru.
                </p>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @forelse($recentReports as $report)
                    <div class="flex h-full flex-col rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-indigo-300/50 hover:bg-white shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div class="font-mono text-[11px] font-bold tracking-[0.18em] text-indigo-600">{{ $report->kode_tiket }}</div>
                            <span class="rounded-full px-3 py-1 text-[11px] font-bold
                                @if($report->isFinallyVerified()) bg-emerald-100 text-emerald-700
                                @elseif($report->isAwaitingFinalVerification()) bg-lime-100 text-lime-700
                                @elseif($report->status === 'Selesai') bg-green-100 text-green-700
                                @elseif($report->status === 'Proses') bg-sky-100 text-sky-700
                                @elseif($report->status === 'Diverifikasi') bg-indigo-100 text-indigo-700
                                @elseif($report->status === 'Menunggu') bg-orange-100 text-orange-700
                                @elseif($report->status === 'Ditolak') bg-slate-200 text-slate-600
                                @else bg-red-100 text-red-700
                                @endif">
                                {{ $report->workflow_status_label }}
                            </span>
                        </div>

                        <div class="mt-4">
                            <h4 class="text-lg font-bold text-slate-900">{{ $report->perangkat->nama_perangkat }}</h4>
                            <div class="mt-2 text-sm leading-6 text-slate-500">
                                {{ $report->perangkat->jenis }}<br>
                                {{ $report->perangkat->wilayah ?: '-' }}<br>
                                {{ $report->perangkat->lokasi }}
                            </div>
                        </div>

                        <div class="mt-5 grid gap-3 text-sm">
                            <div class="rounded-xl border border-slate-200 bg-white px-3 py-3 shadow-sm">
                                <div class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Prioritas</div>
                                <div class="mt-2 font-bold text-slate-900">{{ $report->prioritas }}</div>
                            </div>
                            <div class="rounded-xl border border-slate-200 bg-white px-3 py-3 shadow-sm">
                                <div class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Teknisi</div>
                                <div class="mt-2 font-bold text-slate-900">{{ $report->teknisi?->name ?: 'Belum diassign' }}</div>
                            </div>
                        </div>

                        <div class="mt-5 border-t border-slate-200 pt-4 text-xs text-slate-400">
                            Dibuat {{ optional($report->tanggal)->format('d M Y') ?: '-' }}
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-xl border border-dashed border-slate-300 bg-slate-50 px-6 py-14 text-center">
                        <h4 class="text-xl font-bold text-slate-900">Belum ada laporan terbaru</h4>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Saat tiket pertama masuk, daftar aktivitas terbaru akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <footer class="pb-2 text-center text-sm text-slate-500">
            <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 shadow-sm">
                <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                &copy; {{ date('Y') }} Network Operation Center
            </span>
        </footer>
    </div>
</div>
