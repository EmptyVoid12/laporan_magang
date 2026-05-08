<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Manajemen Laporan Masuk</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola laporan gangguan, assign teknisi, dan ubah status tiket.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('message') }}</div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-4">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari tiket..." class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100">
                <select wire:model.live="filterJenis" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm outline-none focus:border-indigo-300">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisOptions as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach
                </select>
                <select wire:model.live="filterWilayah" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm outline-none focus:border-indigo-300">
                    <option value="">Semua Wilayah</option>
                    @foreach($wilayahOptions as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach
                </select>
                <select wire:model.live="filterStatus" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm outline-none focus:border-indigo-300">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach
                </select>
                <select wire:model.live="filterPrioritas" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm outline-none focus:border-indigo-300">
                    <option value="">Semua Prioritas</option>
                    @foreach($prioritasOptions as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach
                </select>
                <input type="date" wire:model.live="filterTanggalMulai" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm outline-none focus:border-indigo-300">
                <input type="date" wire:model.live="filterTanggalSelesai" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm outline-none focus:border-indigo-300">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[1100px]">
                <thead>
                    <tr class="border-b border-slate-100 text-xs uppercase tracking-wide text-slate-400">
                        <th class="px-4 py-3 font-semibold">Tiket</th>
                        <th class="px-4 py-3 font-semibold">Tgl</th>
                        <th class="px-4 py-3 font-semibold">Perangkat</th>
                        <th class="px-4 py-3 font-semibold">Deskripsi</th>
                        <th class="px-4 py-3 font-semibold">Prioritas</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Teknisi</th>
                        <th class="px-4 py-3 font-semibold">Ubah Status</th>
                        <th class="px-4 py-3 font-semibold">Riwayat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($gangguans as $g)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-4 py-3 font-mono text-xs font-semibold text-indigo-600">{{ $g->kode_tiket }}</td>
                        <td class="px-4 py-3 text-xs text-slate-600">{{ $g->tanggal->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-slate-800">{{ $g->perangkat->nama_perangkat }}</div>
                            <div class="text-xs text-slate-400">{{ $g->perangkat->jenis }} · {{ $g->perangkat->wilayah ?: '-' }}</div>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-600 max-w-[160px] truncate">{{ Str::limit($g->deskripsi, 40) }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold @if($g->prioritas == 'Tinggi') bg-red-50 text-red-600 @elseif($g->prioritas == 'Sedang') bg-amber-50 text-amber-600 @else bg-emerald-50 text-emerald-600 @endif">{{ $g->prioritas }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold @if($g->status == 'Selesai') bg-emerald-100 text-emerald-700 @elseif($g->status == 'Proses') bg-sky-100 text-sky-700 @elseif($g->status == 'Diverifikasi') bg-indigo-100 text-indigo-700 @elseif($g->status == 'Menunggu') bg-orange-100 text-orange-700 @elseif($g->status == 'Ditolak') bg-slate-100 text-slate-500 @else bg-red-100 text-red-700 @endif">{{ $g->status }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <select wire:change="assignTeknisi({{ $g->id }}, $event.target.value)" class="w-full rounded-md border border-slate-200 bg-slate-50 px-2 py-1.5 text-xs outline-none focus:border-indigo-300">
                                <option value="">-- Belum --</option>
                                @foreach($teknisis as $teknisi)
                                    <option value="{{ $teknisi->id }}" {{ $g->teknisi_id == $teknisi->id ? 'selected' : '' }}>{{ $teknisi->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-3">
                            <select wire:change="updateStatus({{ $g->id }}, $event.target.value)" class="w-full rounded-md border border-slate-200 bg-slate-50 px-2 py-1.5 text-xs outline-none focus:border-indigo-300">
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}" {{ $g->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500 min-w-[200px]">
                            @forelse($g->proses->sortByDesc('created_at')->take(2) as $proses)
                                <div class="mb-1.5 rounded-md border border-slate-100 bg-slate-50 p-2">
                                    <div class="font-semibold text-slate-700">{{ $proses->actor_name }} · {{ $proses->tanggal_update->format('d/m') }}</div>
                                    <div class="text-slate-500">{{ Str::limit($proses->keterangan_proses, 50) }}</div>
                                </div>
                            @empty
                                <span class="text-slate-300">—</span>
                            @endforelse
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="px-5 py-10 text-center text-sm text-slate-400">Belum ada laporan masuk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 px-5 py-3">{{ $gangguans->links() }}</div>
    </div>
</div>
