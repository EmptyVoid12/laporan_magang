<div class="space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('user.gangguan') }}" class="rounded-lg border border-slate-200 bg-white p-2 text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-805 dark:hover:text-slate-200">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Detail Tiket <span class="font-mono text-indigo-600 dark:text-indigo-400">{{ $gangguan->kode_tiket }}</span></h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Informasi lengkap dan riwayat penanganan laporan Anda.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('message') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Detail Informasi Laporan --}}
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="mb-4 flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">Informasi Laporan</h2>
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold @if($gangguan->isFinallyVerified()) bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 @elseif($gangguan->isAwaitingFinalVerification()) bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400 @elseif($gangguan->status == 'Selesai') bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400 @elseif($gangguan->status == 'Proses') bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-400 @elseif($gangguan->status == 'Diterima') bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-400 @elseif($gangguan->status == 'Menunggu') bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-450 @elseif($gangguan->status == 'Ditolak') bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400 @else bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400 @endif">{{ $gangguan->workflow_status_label }}</span>
                </div>

                <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Tanggal Lapor</p>
                        <p class="mt-1 text-sm font-medium text-slate-800 dark:text-slate-200">{{ $gangguan->tanggal->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Prioritas</p>
                        <p class="mt-1 text-sm font-medium text-slate-800 dark:text-slate-250">
                            <span class="inline-flex rounded px-1.5 py-0.5 text-xs font-semibold @if($gangguan->prioritas == 'Tinggi') bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400 @elseif($gangguan->prioritas == 'Sedang') bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 @else bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 @endif">{{ $gangguan->prioritas }}</span>
                        </p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Perangkat</p>
                        <p class="mt-1 text-sm font-medium text-slate-800 dark:text-slate-200">{{ $gangguan->perangkat->nama_perangkat }} <span class="text-slate-400 dark:text-slate-500">({{ $gangguan->perangkat->jenis }})</span></p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $gangguan->perangkat->lokasi }} · {{ $gangguan->perangkat->wilayah }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Deskripsi Gangguan</p>
                        <div class="mt-2 rounded-lg bg-slate-50 p-3 text-sm text-slate-700 dark:bg-slate-950 dark:text-slate-350">{{ $gangguan->deskripsi }}</div>
                    </div>
                    @if($gangguan->foto)
                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Foto Bukti</p>
                        <img src="{{ Storage::url($gangguan->foto) }}" class="mt-2 max-h-64 rounded-lg border border-slate-200 object-cover shadow-sm dark:border-slate-800">
                    </div>
                    @endif
                </div>

                @if($gangguan->teknisi_id)
                <div class="mt-6 border-t border-slate-100 pt-5 dark:border-slate-800">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Ditangani Oleh</p>
                    <div class="mt-2 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-400">{{ strtoupper(substr($gangguan->teknisi->name, 0, 1)) }}</div>
                        <div>
                            <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $gangguan->teknisi->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Teknisi Lapangan</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Verifikasi Admin (Hanya tampil jika status Selesai & user = admin) --}}
            @if($gangguan->status === 'Selesai' && auth()->user()->role === 'admin' && !$gangguan->isFinallyVerified())
            <div class="rounded-xl border border-indigo-200 bg-indigo-50 p-6 shadow-sm">
                <h3 class="text-lg font-bold text-indigo-900">Verifikasi Penyelesaian</h3>
                <p class="mt-1 text-sm text-indigo-700">Teknisi telah menyelesaikan tugas ini. Silakan verifikasi hasil perbaikan.</p>
                <div class="mt-4 flex gap-3">
                    <button wire:click="verifyCompletion(true)" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">Verifikasi (Selesai Valid)</button>
                    <button wire:click="verifyCompletion(false)" onclick="confirm('Tolak penyelesaian? Tiket akan dikembalikan ke Proses.') || event.stopImmediatePropagation()" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-red-600 shadow-sm border border-red-200 hover:bg-red-50">Tolak (Kembali Proses)</button>
                </div>
            </div>
            @endif
        </div>

        {{-- Timeline Log Proses --}}
        <div class="lg:col-span-1">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm sticky top-24 dark:border-slate-800 dark:bg-slate-900">
                <h3 class="mb-5 text-base font-bold text-slate-800 dark:text-white">Riwayat Penanganan</h3>
                <div class="relative space-y-6 before:absolute before:inset-0 before:ml-2 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent dark:before:via-slate-800">
                    @forelse($prosesLogs as $log)
                        <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                            <div class="flex items-center justify-center w-5 h-5 rounded-full border-2 border-white bg-indigo-500 text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 dark:border-slate-900"></div>
                            <div class="w-[calc(100%-2rem)] md:w-[calc(50%-1.5rem)] rounded-lg border border-slate-100 bg-slate-50 p-4 shadow-sm dark:border-slate-850 dark:bg-slate-950">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="font-bold text-slate-800 text-sm dark:text-slate-200">{{ $log->status_berubah_menjadi }}</div>
                                    <time class="text-[10px] font-medium text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full dark:bg-indigo-950/30 dark:text-indigo-400">{{ $log->tanggal_update->format('d/m H:i') }}</time>
                                </div>
                                <div class="text-xs text-slate-600 mb-2 dark:text-slate-400">{{ $log->keterangan_proses }}</div>
                                <div class="text-[10px] text-slate-400 font-medium border-t border-slate-200 pt-2 mt-2 dark:border-slate-800 dark:text-slate-500">Oleh: {{ $log->actor_name }}</div>
                                @if($log->foto_bukti)
                                    <a href="{{ Storage::url($log->foto_bukti) }}" target="_blank" class="mt-2 inline-flex items-center gap-1 text-[11px] font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        Lihat Bukti Lampiran
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-sm text-slate-400 py-4 dark:text-slate-550">Belum ada riwayat proses.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
