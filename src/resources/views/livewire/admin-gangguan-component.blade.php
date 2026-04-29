<div class="bg-white rounded shadow p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Manajemen Laporan Masuk</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-7 gap-4 mb-6">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari tiket, perangkat, deskripsi..." class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">
        <select wire:model.live="filterJenis" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">
            <option value="">Semua Jenis</option>
            @foreach($jenisOptions as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterWilayah" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">
            <option value="">Semua Wilayah</option>
            @foreach($wilayahOptions as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterStatus" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">
            <option value="">Semua Status</option>
            @foreach($statusOptions as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterPrioritas" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">
            <option value="">Semua Prioritas</option>
            @foreach($prioritasOptions as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        <input type="date" wire:model.live="filterTanggalMulai" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">
        <input type="date" wire:model.live="filterTanggalSelesai" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-max">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="py-3 px-4 border-b font-semibold">Tiket</th>
                    <th class="py-3 px-4 border-b font-semibold">Tgl</th>
                    <th class="py-3 px-4 border-b font-semibold">Perangkat</th>
                    <th class="py-3 px-4 border-b font-semibold">Deskripsi</th>
                    <th class="py-3 px-4 border-b font-semibold">Prioritas</th>
                    <th class="py-3 px-4 border-b font-semibold">Status</th>
                    <th class="py-3 px-4 border-b font-semibold">Assign Teknisi</th>
                    <th class="py-3 px-4 border-b font-semibold">Ubah Status</th>
                    <th class="py-3 px-4 border-b font-semibold">Riwayat Singkat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gangguans as $g)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4 font-mono text-xs text-blue-700 font-semibold">{{ $g->kode_tiket }}</td>
                    <td class="py-3 px-4">{{ $g->tanggal->format('d/m/Y') }}</td>
                    <td class="py-3 px-4 font-medium">
                        {{ $g->perangkat->nama_perangkat }}
                        <br><span class="text-xs text-gray-500">{{ $g->perangkat->jenis }} | {{ $g->perangkat->wilayah ?: '-' }}</span>
                        <br><span class="text-xs text-gray-400">{{ $g->perangkat->lokasi }}</span>
                    </td>
                    <td class="py-3 px-4 text-sm">{{ Str::limit($g->deskripsi, 40) }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded text-xs font-semibold 
                            @if($g->prioritas == 'Tinggi') bg-red-100 text-red-800 
                            @elseif($g->prioritas == 'Sedang') bg-yellow-100 text-yellow-800 
                            @else bg-green-100 text-green-800 @endif">
                            {{ $g->prioritas }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($g->status == 'Selesai') bg-green-500 text-white
                            @elseif($g->status == 'Proses') bg-yellow-400 text-white
                            @elseif($g->status == 'Diverifikasi') bg-blue-500 text-white
                            @elseif($g->status == 'Menunggu') bg-orange-400 text-white
                            @elseif($g->status == 'Ditolak') bg-gray-500 text-white
                            @else bg-red-500 text-white @endif">
                            {{ $g->status }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <select wire:change="assignTeknisi({{ $g->id }}, $event.target.value)" class="text-sm shadow appearance-none border rounded w-full py-1 px-2 text-gray-700 focus:outline-none">
                            <option value="">-- Belum Diassign --</option>
                            @foreach($teknisis as $teknisi)
                                <option value="{{ $teknisi->id }}" {{ $g->teknisi_id == $teknisi->id ? 'selected' : '' }}>
                                    {{ $teknisi->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="py-3 px-4">
                        <select wire:change="updateStatus({{ $g->id }}, $event.target.value)" class="text-sm shadow appearance-none border rounded w-full py-1 px-2 text-gray-700 focus:outline-none">
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}" {{ $g->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="py-3 px-4 text-xs text-gray-600 min-w-72">
                        @forelse($g->proses->sortByDesc('created_at')->take(3) as $proses)
                            <div class="mb-2 rounded border bg-gray-50 p-2">
                                <div class="font-semibold text-gray-700">{{ $proses->actor_name }} • {{ $proses->tanggal_update->format('d/m/Y') }}</div>
                                <div>{{ $proses->keterangan_proses }}</div>
                            </div>
                        @empty
                            <span class="text-gray-400">Belum ada riwayat.</span>
                        @endforelse
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="py-6 text-center text-gray-500">Belum ada laporan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $gangguans->links() }}
    </div>
</div>
