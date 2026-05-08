<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Daftar Tugas Teknisi</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola dan perbarui status tugas perbaikan perangkat.</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="exportTaskBulanan" class="inline-flex items-center gap-2 rounded-lg bg-indigo-50 px-4 py-2.5 text-sm font-semibold text-indigo-700 shadow-sm transition hover:bg-indigo-100">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export PDF Bulanan
            </button>
            <button wire:click="exportTaskBulananExcel" class="inline-flex items-center gap-2 rounded-lg bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-700 shadow-sm transition hover:bg-emerald-100">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                Export Excel
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('message') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 p-4">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <select wire:model.live="filterStatus" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm outline-none focus:border-indigo-300">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach
                </select>
                <select wire:model.live="filterBulan" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm outline-none focus:border-indigo-300">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterTahun" class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm outline-none focus:border-indigo-300">
                    @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[1000px]">
                <thead>
                    <tr class="border-b border-slate-100 text-xs uppercase tracking-wide text-slate-400">
                        <th class="px-5 py-3 font-semibold">Tiket</th>
                        <th class="px-5 py-3 font-semibold">Tanggal</th>
                        <th class="px-5 py-3 font-semibold">Perangkat & Lokasi</th>
                        <th class="px-5 py-3 font-semibold">Deskripsi Awal</th>
                        <th class="px-5 py-3 font-semibold">Prioritas</th>
                        <th class="px-5 py-3 font-semibold">Status Tiket</th>
                        <th class="px-5 py-3 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($tasks as $t)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-5 py-3 font-mono text-xs font-semibold text-indigo-600">{{ $t->kode_tiket }}</td>
                        <td class="px-5 py-3 text-xs text-slate-600">{{ $t->tanggal->format('d/m/Y') }}</td>
                        <td class="px-5 py-3">
                            <div class="font-medium text-slate-800">{{ $t->perangkat->nama_perangkat }}</div>
                            <div class="text-xs text-slate-400">{{ $t->perangkat->lokasi }}</div>
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-600 max-w-[160px] truncate">{{ Str::limit($t->deskripsi, 40) }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold @if($t->prioritas == 'Tinggi') bg-red-50 text-red-600 @elseif($t->prioritas == 'Sedang') bg-amber-50 text-amber-600 @else bg-emerald-50 text-emerald-600 @endif">{{ $t->prioritas }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold @if($t->status == 'Selesai') bg-emerald-100 text-emerald-700 @elseif($t->status == 'Proses') bg-sky-100 text-sky-700 @elseif($t->status == 'Diverifikasi') bg-indigo-100 text-indigo-700 @elseif($t->status == 'Menunggu') bg-orange-100 text-orange-700 @elseif($t->status == 'Ditolak') bg-slate-100 text-slate-500 @else bg-red-100 text-red-700 @endif">{{ $t->status }}</span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <button wire:click="openUpdateModal({{ $t->id }})" class="rounded-md bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-100">Update Progres</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-sm text-slate-400">Tidak ada tugas pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 px-5 py-3">{{ $tasks->links() }}</div>
    </div>

    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
        <div class="w-full max-w-2xl rounded-xl border border-slate-200 bg-white p-6 shadow-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <h3 class="text-lg font-bold text-slate-900">Update Progres Tiket: <span class="font-mono text-indigo-600">{{ $selectedGangguan->kode_tiket }}</span></h3>
                <button wire:click="closeUpdateModal" class="text-slate-400 hover:text-slate-600"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>

            <div class="mt-4 rounded-lg bg-slate-50 p-4">
                <div class="mb-2 text-sm font-semibold text-slate-700">Detail Gangguan:</div>
                <p class="text-sm text-slate-600">{{ $selectedGangguan->deskripsi }}</p>
                <div class="mt-3 flex gap-4 text-xs text-slate-500">
                    <div><strong>Perangkat:</strong> {{ $selectedGangguan->perangkat->nama_perangkat }}</div>
                    <div><strong>Pelapor:</strong> {{ $selectedGangguan->operator->name }}</div>
                </div>
            </div>

            <form wire:submit.prevent="updateProgress" class="mt-5 space-y-4">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">Ubah Status Tiket</label>
                    <select wire:model="statusUpdate" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-indigo-300">
                        @foreach($statusOptions as $val => $lbl)
                            <option value="{{ $val }}">{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">Keterangan Update (Wajib)</label>
                    <textarea wire:model="keterangan" rows="3" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-indigo-300" placeholder="Jelaskan tindakan perbaikan yang dilakukan..."></textarea>
                    @error('keterangan') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">Bukti Foto (Opsional)</label>
                    <input type="file" wire:model="foto_bukti" class="block w-full text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-xs file:font-semibold file:text-indigo-600 hover:file:bg-indigo-100">
                    @error('foto_bukti') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                    @if ($foto_bukti)
                        <div class="mt-3"><img src="{{ $foto_bukti->temporaryUrl() }}" class="h-28 w-auto rounded-lg border border-slate-200 object-cover"></div>
                    @endif
                </div>

                <div class="mt-6 border-t border-slate-100 pt-4">
                    <h4 class="mb-3 text-sm font-bold text-slate-800">Riwayat Progres</h4>
                    <div class="space-y-3">
                        @forelse($riwayatProses as $rp)
                            <div class="flex gap-3 text-sm">
                                <div class="flex-none">
                                    <div class="h-2 w-2 mt-1.5 rounded-full bg-indigo-500"></div>
                                </div>
                                <div class="flex-1 rounded-lg border border-slate-100 bg-slate-50 p-3">
                                    <div class="flex justify-between font-semibold text-slate-700">
                                        <span>{{ $rp->actor_name }} ({{ $rp->status_berubah_menjadi }})</span>
                                        <span class="text-xs text-slate-400">{{ $rp->tanggal_update->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="mt-1 text-slate-600">{{ $rp->keterangan_proses }}</p>
                                    @if($rp->foto_bukti)
                                        <a href="{{ Storage::url($rp->foto_bukti) }}" target="_blank" class="mt-2 inline-block text-xs text-indigo-600 hover:underline">Lihat Lampiran</a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400">Belum ada progres dicatat.</p>
                        @endforelse
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700" wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan Update</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
