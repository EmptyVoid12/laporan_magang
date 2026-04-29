<div class="bg-white rounded shadow p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Daftar Tugas Anda</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tasks as $task)
            <div class="border rounded-lg shadow-sm {{ $task->status == 'Selesai' ? 'border-green-300 bg-green-50' : 'border-blue-300 bg-blue-50' }} flex flex-col h-full">
                <div class="p-4 border-b {{ $task->status == 'Selesai' ? 'border-green-200' : 'border-blue-200' }}">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-bold text-lg text-gray-800">{{ $task->perangkat->nama_perangkat }}</h3>
                            <div class="text-xs font-mono text-blue-700">{{ $task->kode_tiket }}</div>
                        </div>
                        <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($task->status == 'Selesai') bg-green-500 text-white
                                @elseif($task->status == 'Proses') bg-yellow-400 text-white
                                @elseif($task->status == 'Diverifikasi') bg-blue-500 text-white
                                @elseif($task->status == 'Menunggu') bg-orange-400 text-white
                                @elseif($task->status == 'Ditolak') bg-gray-500 text-white
                                @else bg-red-500 text-white @endif">
                                {{ $task->status }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-1"><span class="font-semibold">Lokasi:</span> {{ $task->perangkat->lokasi }}</p>
                    <p class="text-sm text-gray-600"><span class="font-semibold">Lap. Operator:</span> {{ $task->deskripsi }}</p>
                </div>
                
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-sm text-gray-700 mb-2">Riwayat Progress:</h4>
                        @if($task->proses->count() > 0)
                            <ul class="text-sm space-y-2 mb-4">
                                @foreach($task->proses as $p)
                                    <li class="bg-white p-2 rounded shadow-sm text-xs border">
                                        <div class="font-semibold text-blue-800">{{ $p->actor_name }} • {{ $p->tanggal_update->format('d/m/Y') }}</div>
                                        <div>{{ $p->keterangan_proses }}</div>
                                        @if($p->kendala)
                                            <div class="text-red-600 italic">Kendala: {{ $p->kendala }}</div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-xs text-gray-500 mb-4 italic">Belum ada progress.</p>
                        @endif
                    </div>
                    
                    <div class="mt-4 flex gap-2">
                        @if(!in_array($task->status, ['Selesai', 'Ditolak']))
                            <button wire:click="openModal({{ $task->id }})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-3 rounded shadow">
                                + Progress
                            </button>
                            <button wire:click="updateTaskStatus({{ $task->id }}, 'Menunggu')" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold py-2 px-3 rounded shadow">
                                Menunggu
                            </button>
                            <button wire:click="updateTaskStatus({{ $task->id }}, 'Selesai')" onclick="confirm('Pastikan perangkat benar-benar sudah normal. Selesaikan tugas?') || event.stopImmediatePropagation()" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-3 rounded shadow">
                                √ Selesai
                            </button>
                        @else
                            <div class="w-full text-center {{ $task->status == 'Selesai' ? 'text-green-700 bg-green-100' : 'text-gray-700 bg-gray-100' }} font-bold py-2 rounded">
                                {{ $task->status == 'Selesai' ? 'Tugas Selesai' : 'Tugas Ditutup' }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-8 text-center text-gray-500 border rounded-lg bg-gray-50 border-dashed">
                Tidak ada tugas yang diassign ke Anda saat ini.
            </div>
        @endforelse
    </div>

    <!-- Modal Progress -->
    @if($isOpen)
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 mx-4">
            <h3 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">Tambah Progress Pekerjaan</h3>
            
            <form wire:submit.prevent="storeProses">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tindakan / Keterangan</label>
                    <textarea wire:model="keterangan_proses" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3" placeholder="Apa yang telah dilakukan?"></textarea>
                    @error('keterangan_proses') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Kendala (Opsional)</label>
                    <textarea wire:model="kendala" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2" placeholder="Apakah ada kesulitan/sparepart kurang?"></textarea>
                </div>

                <div class="flex justify-end gap-3 border-t pt-4">
                    <button type="button" wire:click="closeModal" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded transition">Batal</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition">Simpan Progress</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
