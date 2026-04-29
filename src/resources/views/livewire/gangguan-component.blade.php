<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Form Laporan -->
    <div class="md:col-span-1 bg-white rounded shadow p-6 h-fit">
        <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Buat Laporan Baru</h2>
        
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="store">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Perangkat</label>
                <select wire:model.live="jenis" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Wilayah Jakarta</label>
                <select wire:model.live="wilayah" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                    <option value="">Semua Wilayah</option>
                    @foreach($wilayahOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Perangkat</label>
                <select wire:key="perangkat-select-{{ $jenis ?: 'semua' }}-{{ $wilayah ?: 'semua' }}" wire:model="perangkat_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                    <option value="">-- Pilih --</option>
                    @foreach($perangkats as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_perangkat }} - {{ $p->jenis }} - {{ $p->wilayah ?: 'Tanpa Wilayah' }} ({{ $p->lokasi }})</option>
                    @endforeach
                </select>
                @error('perangkat_id') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                @if($perangkats->isEmpty())
                    <span class="text-gray-500 text-xs">Belum ada perangkat yang cocok dengan jenis dan wilayah yang dipilih.</span>
                @endif
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
                <input type="date" wire:model="tanggal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                @error('tanggal') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Prioritas</label>
                <select wire:model="prioritas" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                    <option value="Rendah">Rendah</option>
                    <option value="Sedang">Sedang</option>
                    <option value="Tinggi">Tinggi</option>
                </select>
                @error('prioritas') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Kerusakan</label>
                <textarea wire:model="deskripsi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" rows="4"></textarea>
                @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Upload Foto (Opsional)</label>
                <input type="file" wire:model="foto" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('foto') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                @if ($foto)
                    <div class="mt-2 text-sm text-gray-500">Preview:</div>
                    <img src="{{ $foto->temporaryUrl() }}" class="mt-1 h-32 w-auto object-cover rounded shadow-sm">
                @endif
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition duration-200" wire:loading.attr="disabled">
                <span wire:loading.remove>Kirim Laporan</span>
                <span wire:loading>Memproses...</span>
            </button>
        </form>
    </div>

    <!-- Riwayat Laporan -->
    <div class="md:col-span-2 bg-white rounded shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Riwayat Laporan Anda</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="py-3 px-4 border-b font-semibold">Tiket</th>
                        <th class="py-3 px-4 border-b font-semibold">Tanggal</th>
                        <th class="py-3 px-4 border-b font-semibold">Perangkat</th>
                        <th class="py-3 px-4 border-b font-semibold">Prioritas</th>
                        <th class="py-3 px-4 border-b font-semibold">Status</th>
                        <th class="py-3 px-4 border-b font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatLaporan as $laporan)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4 font-mono text-xs text-blue-700 font-semibold">{{ $laporan->kode_tiket }}</td>
                        <td class="py-3 px-4">{{ $laporan->tanggal->format('d M Y') }}</td>
                        <td class="py-3 px-4">
                            {{ $laporan->perangkat->nama_perangkat }}
                            <div class="text-xs text-gray-500">{{ $laporan->perangkat->jenis }} | {{ $laporan->perangkat->wilayah ?: '-' }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded text-xs font-semibold 
                                @if($laporan->prioritas == 'Tinggi') bg-red-100 text-red-800 
                                @elseif($laporan->prioritas == 'Sedang') bg-yellow-100 text-yellow-800 
                                @else bg-green-100 text-green-800 @endif">
                                {{ $laporan->prioritas }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($laporan->status == 'Selesai') bg-green-500 text-white
                                @elseif($laporan->status == 'Proses') bg-yellow-400 text-white
                                @elseif($laporan->status == 'Diverifikasi') bg-blue-500 text-white
                                @elseif($laporan->status == 'Menunggu') bg-orange-400 text-white
                                @elseif($laporan->status == 'Ditolak') bg-gray-500 text-white
                                @else bg-red-500 text-white @endif">
                                {{ $laporan->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($laporan->status == 'Open')
                            <button wire:click="deleteLaporan({{ $laporan->id }})" class="text-red-500 hover:text-red-700 text-sm font-semibold" onclick="confirm('Yakin ingin menghapus laporan ini?') || event.stopImmediatePropagation()">Hapus</button>
                            @else
                            <span class="text-xs text-gray-400">Terkunci</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-gray-500">Anda belum pernah membuat laporan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
