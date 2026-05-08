<div class="space-y-6">
    <div>
        <h1 class="text-xl font-bold text-slate-900">Dashboard</h1>
        <p class="mt-1 text-sm text-slate-500">Ringkasan data laporan gangguan perangkat.</p>
    </div>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 xl:grid-cols-7">
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Total</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $total }}</p>
        </div>
        <div class="rounded-xl border-l-4 border-l-red-400 border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-red-500">Open</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $open }}</p>
        </div>
        <div class="rounded-xl border-l-4 border-l-indigo-400 border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-indigo-500">Diverifikasi</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $diverifikasi }}</p>
        </div>
        <div class="rounded-xl border-l-4 border-l-amber-400 border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-amber-600">Proses</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $proses }}</p>
        </div>
        <div class="rounded-xl border-l-4 border-l-orange-400 border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-orange-500">Menunggu</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $menunggu }}</p>
        </div>
        <div class="rounded-xl border-l-4 border-l-emerald-400 border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">Selesai</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $selesai }}</p>
        </div>
        <div class="rounded-xl border-l-4 border-l-slate-400 border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Ditolak</p>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $ditolak }}</p>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-sm font-bold text-slate-800">Distribusi per Wilayah</h3>
            <div class="mt-4 space-y-2.5">
                @forelse($laporanPerWilayah as $item)
                    @php($pct = $total > 0 ? round($item->total / $total * 100) : 0)
                    <div>
                        <div class="mb-1 flex items-center justify-between text-xs">
                            <span class="font-medium text-slate-600">{{ $item->wilayah }}</span>
                            <span class="font-bold text-slate-800">{{ $item->total }}</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-indigo-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-slate-400">Belum ada data.</p>
                @endforelse
            </div>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-sm font-bold text-slate-800">Distribusi per Jenis Perangkat</h3>
            <div class="mt-4 space-y-2.5">
                @forelse($laporanPerJenis as $item)
                    @php($pct = $total > 0 ? round($item->total / $total * 100) : 0)
                    <div>
                        <div class="mb-1 flex items-center justify-between text-xs">
                            <span class="font-medium text-slate-600">{{ $item->jenis }}</span>
                            <span class="font-bold text-slate-800">{{ $item->total }}</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-violet-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-slate-400">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4">
            <h3 class="text-sm font-bold text-slate-800">Laporan Masuk Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-xs uppercase tracking-wide text-slate-400">
                        <th class="px-5 py-3 font-semibold">Tanggal</th>
                        <th class="px-5 py-3 font-semibold">Perangkat</th>
                        <th class="px-5 py-3 font-semibold">Prioritas</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold">Teknisi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recent as $r)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-5 py-3">
                            <div class="text-slate-700">{{ $r->tanggal->format('d M Y') }}</div>
                            <div class="font-mono text-[11px] text-indigo-500">{{ $r->kode_tiket }}</div>
                        </td>
                        <td class="px-5 py-3">
                            <div class="font-medium text-slate-800">{{ $r->perangkat->nama_perangkat }}</div>
                            <div class="text-xs text-slate-400">{{ $r->perangkat->jenis }} · {{ $r->perangkat->wilayah ?: '-' }}</div>
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold @if($r->prioritas == 'Tinggi') bg-red-50 text-red-600 @elseif($r->prioritas == 'Sedang') bg-amber-50 text-amber-600 @else bg-emerald-50 text-emerald-600 @endif">{{ $r->prioritas }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold @if($r->status == 'Selesai') bg-emerald-100 text-emerald-700 @elseif($r->status == 'Proses') bg-sky-100 text-sky-700 @elseif($r->status == 'Diverifikasi') bg-indigo-100 text-indigo-700 @elseif($r->status == 'Menunggu') bg-orange-100 text-orange-700 @elseif($r->status == 'Ditolak') bg-slate-100 text-slate-500 @else bg-red-100 text-red-700 @endif">{{ $r->status }}</span>
                        </td>
                        <td class="px-5 py-3 text-slate-600">{{ $r->teknisi?->name ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-sm text-slate-400">Belum ada laporan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
