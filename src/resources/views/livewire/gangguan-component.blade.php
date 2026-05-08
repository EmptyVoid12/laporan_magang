<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Laporan Gangguan</h1>
            <p class="mt-1 text-sm text-slate-500">Buat laporan baru atau pantau status laporan Anda.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        {{-- Form Laporan --}}
        <div class="xl:col-span-1">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-base font-bold text-slate-800">Buat Laporan Baru</h2>
                <p class="mt-1 text-xs text-slate-400">Isi formulir untuk melaporkan gangguan perangkat.</p>

                @if (session()->has('message'))
                    <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit.prevent="store" class="mt-5 space-y-4">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Jenis Perangkat</label>
                        <select wire:model.live="jenis" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Wilayah Jakarta</label>
                        <select wire:model.live="wilayah" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100">
                            <option value="">Semua Wilayah</option>
                            @foreach($wilayahOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Pilih Perangkat</label>
                        <select wire:key="perangkat-select-{{ $jenis ?: 'semua' }}-{{ $wilayah ?: 'semua' }}" wire:model="perangkat_id" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100">
                            <option value="">-- Pilih --</option>
                            @foreach($perangkats as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_perangkat }} - {{ $p->jenis }} - {{ $p->wilayah ?: 'Tanpa Wilayah' }} ({{ $p->lokasi }})</option>
                            @endforeach
                        </select>
                        @error('perangkat_id') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                        @if($perangkats->isEmpty())
                            <span class="mt-1 block text-xs text-slate-400">Tidak ada perangkat untuk filter ini.</span>
                        @endif
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Tanggal</label>
                        <input type="date" wire:model="tanggal" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100">
                        @error('tanggal') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Prioritas</label>
                        <select wire:model="prioritas" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100">
                            <option value="Rendah">Rendah</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                        </select>
                        @error('prioritas') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Deskripsi Kerusakan</label>
                        <textarea wire:model="deskripsi" rows="3" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100" placeholder="Jelaskan detail gangguan yang terjadi..."></textarea>
                        @error('deskripsi') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Upload Foto (Opsional)</label>
                        <input type="file" wire:model="foto" class="block w-full text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-xs file:font-semibold file:text-indigo-600 hover:file:bg-indigo-100">
                        @error('foto') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                        @if ($foto)
                            <div class="mt-3">
                                <p class="mb-1 text-xs text-slate-400">Preview:</p>
                                <img src="{{ $foto->temporaryUrl() }}" class="h-28 w-auto rounded-lg border border-slate-200 object-cover">
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700" wire:loading.attr="disabled">
                        <span wire:loading.remove>Kirim Laporan</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Riwayat Laporan --}}
        <div class="xl:col-span-2">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-base font-bold text-slate-800">Riwayat Laporan Anda</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs uppercase tracking-wide text-slate-400">
                                <th class="px-5 py-3 font-semibold">Tiket</th>
                                <th class="px-5 py-3 font-semibold">Tanggal</th>
                                <th class="px-5 py-3 font-semibold">Perangkat</th>
                                <th class="px-5 py-3 font-semibold">Prioritas</th>
                                <th class="px-5 py-3 font-semibold">Status</th>
                                <th class="px-5 py-3 text-center font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($riwayatLaporan as $laporan)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="px-5 py-3 font-mono text-xs font-semibold text-indigo-600">{{ $laporan->kode_tiket }}</td>
                                <td class="px-5 py-3 text-slate-600">{{ $laporan->tanggal->format('d M Y') }}</td>
                                <td class="px-5 py-3">
                                    <div class="font-medium text-slate-800">{{ $laporan->perangkat->nama_perangkat }}</div>
                                    <div class="text-xs text-slate-400">{{ $laporan->perangkat->jenis }} · {{ $laporan->perangkat->wilayah ?: '-' }}</div>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold
                                        @if($laporan->prioritas == 'Tinggi') bg-red-50 text-red-600
                                        @elseif($laporan->prioritas == 'Sedang') bg-amber-50 text-amber-600
                                        @else bg-emerald-50 text-emerald-600 @endif">
                                        {{ $laporan->prioritas }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold
                                        @if($laporan->isFinallyVerified()) bg-emerald-100 text-emerald-700
                                        @elseif($laporan->isAwaitingFinalVerification()) bg-amber-100 text-amber-700
                                        @elseif($laporan->status == 'Selesai') bg-green-100 text-green-700
                                        @elseif($laporan->status == 'Proses') bg-sky-100 text-sky-700
                                        @elseif($laporan->status == 'Diverifikasi') bg-indigo-100 text-indigo-700
                                        @elseif($laporan->status == 'Menunggu') bg-orange-100 text-orange-700
                                        @elseif($laporan->status == 'Ditolak') bg-slate-100 text-slate-500
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ $laporan->workflow_status_label }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('user.gangguan.show', $laporan) }}" class="rounded-md bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-100">Detail</a>
                                        @if($laporan->status == 'Open')
                                            <button wire:click="deleteLaporan({{ $laporan->id }})" class="rounded-md bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-600 transition hover:bg-red-100" onclick="confirm('Yakin ingin menghapus laporan ini?') || event.stopImmediatePropagation()">Hapus</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-400">Anda belum pernah membuat laporan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
