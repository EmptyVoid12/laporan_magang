<div class="bg-white rounded shadow p-6">
    <div class="mb-6 overflow-hidden rounded-[28px] border border-sky-100 bg-gradient-to-r from-sky-50 via-white to-cyan-50 shadow-sm">
        <div class="flex flex-col gap-6 p-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl">
                <span class="inline-flex items-center rounded-full border border-sky-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-sky-700 shadow-sm">
                    Riwayat Kerja Teknisi
                </span>
                <h2 class="mt-4 text-2xl font-black tracking-tight text-slate-900">Daftar Tugas Anda</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Unduh rekap kerja bulanan Anda dalam format CSV yang rapi, siap dibuka di Excel, dan menampilkan ringkasan tiket yang ditangani sepanjang bulan terpilih.
                </p>
                <div class="mt-4 flex flex-wrap gap-2 text-xs font-semibold text-slate-600">
                    <span class="rounded-full bg-white px-3 py-1 shadow-sm ring-1 ring-slate-200">Format CSV</span>
                    <span class="rounded-full bg-white px-3 py-1 shadow-sm ring-1 ring-slate-200">Ringkasan per tiket</span>
                    <span class="rounded-full bg-white px-3 py-1 shadow-sm ring-1 ring-slate-200">Mudah dibuka di Excel</span>
                </div>
            </div>

            <form
                method="GET"
                action="{{ route('technician.monthly-history.export') }}"
                class="relative z-10 w-full max-w-xl rounded-2xl p-5 text-white shadow-xl pointer-events-auto"
                style="background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #0891b2 100%); box-shadow: 0 20px 45px rgba(14, 116, 144, 0.22);"
            >
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end">
                    <div class="flex-1">
                        <label class="mb-2 block text-xs font-bold uppercase tracking-[0.2em]" style="color: rgba(224, 242, 254, 0.96);">Pilih Bulan</label>
                        <input
                            type="month"
                            name="month"
                            value="{{ $exportMonth }}"
                            required
                            class="w-full rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 shadow-inner outline-none transition focus:ring-2 pointer-events-auto"
                            style="border: 1px solid rgba(255, 255, 255, 0.28); background-color: #ffffff; color: #0f172a;"
                        >
                        <p class="mt-2 text-xs" style="color: rgba(226, 232, 240, 0.94);">File akan berisi ringkasan pekerjaan Anda selama bulan yang dipilih.</p>
                    </div>
                    <button
                        type="submit"
                        class="group relative z-20 inline-flex min-h-[56px] w-full cursor-pointer items-center justify-center gap-3 rounded-2xl px-5 py-3 text-sm font-black transition duration-200 hover:-translate-y-0.5 lg:w-auto pointer-events-auto"
                        style="background: linear-gradient(135deg, #facc15 0%, #f59e0b 100%); color: #0f172a; border: 1px solid rgba(255, 255, 255, 0.22); box-shadow: 0 14px 28px rgba(15, 23, 42, 0.28);"
                    >
                        <svg class="h-5 w-5 transition group-hover:translate-y-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 2.75a.75.75 0 0 1 .75.75v6.69l1.72-1.72a.75.75 0 1 1 1.06 1.06l-3 3a.75.75 0 0 1-1.06 0l-3-3a.75.75 0 1 1 1.06-1.06l1.72 1.72V3.5A.75.75 0 0 1 10 2.75ZM3.5 13.75A.75.75 0 0 1 4.25 13h11.5a.75.75 0 0 1 0 1.5H4.25a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                        </svg>
                        <span>Unduh Riwayat Bulanan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

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
                    @if($task->foto)
                        <a href="{{ asset('storage/' . $task->foto) }}" target="_blank" class="mt-2 inline-flex text-xs font-semibold text-blue-700 hover:text-blue-800">
                            Lihat foto laporan awal
                        </a>
                    @endif
                </div>
                
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-sm text-gray-700 mb-2">Riwayat Progress:</h4>
                        @if($task->proses->count() > 0)
                            <ul class="text-sm space-y-2 mb-4">
                                @foreach($task->proses->sortByDesc('id') as $p)
                                    <li class="bg-white p-2 rounded shadow-sm text-xs border">
                                        <div class="font-semibold text-blue-800">{{ $p->actor_name }} • {{ $p->tanggal_update->format('d/m/Y') }}</div>
                                        <div class="text-[11px] uppercase tracking-wide text-gray-500">{{ $p->tipe_update }}</div>
                                        <div>{{ $p->keterangan_proses }}</div>
                                        @if($p->kendala)
                                            <div class="text-red-600 italic">Kendala: {{ $p->kendala }}</div>
                                        @endif
                                        @if($p->has_attachment)
                                            <a href="{{ $p->attachment_url }}" target="_blank" class="mt-1 inline-flex font-semibold text-blue-700 hover:text-blue-800">
                                                Lihat lampiran
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-xs text-gray-500 mb-4 italic">Belum ada progress.</p>
                        @endif
                    </div>
                    
                    @if($task->isAwaitingFinalVerification())
                        <div class="mt-3 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-900">
                            Penyelesaian sudah dikirim dan sedang menunggu verifikasi akhir admin.
                        </div>
                    @elseif($task->isFinallyVerified())
                        <div class="mt-3 rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-xs text-green-900">
                            Tiket sudah diverifikasi final pada {{ optional($task->verified_at)->format('d M Y H:i') }}.
                        </div>
                    @endif

                    <div class="mt-4 flex gap-2">
                        @if(!in_array($task->status, ['Selesai', 'Ditolak']))
                            <button wire:click="openProgressModal({{ $task->id }})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-3 rounded shadow">
                                + Progress
                            </button>
                            <button wire:click="updateTaskStatus({{ $task->id }}, 'Menunggu')" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold py-2 px-3 rounded shadow">
                                Menunggu
                            </button>
                            <button wire:click="openCompletionModal({{ $task->id }})" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-3 rounded shadow">
                                √ Ajukan Selesai
                            </button>
                        @else
                            <div class="w-full text-center {{ $task->status == 'Selesai' ? 'text-green-700 bg-green-100' : 'text-gray-700 bg-gray-100' }} font-bold py-2 rounded">
                                {{ $task->isFinallyVerified() ? 'Tugas Selesai Terverifikasi' : ($task->status == 'Selesai' ? 'Menunggu Verifikasi Akhir' : 'Tugas Ditutup') }}
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
            <h3 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">{{ $modalMode === 'completion' ? 'Ajukan Penyelesaian Tugas' : 'Tambah Progress Pekerjaan' }}</h3>
            
            <form wire:submit.prevent="storeProses">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tindakan / Keterangan</label>
                    <textarea wire:model="keterangan_proses" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3" placeholder="{{ $modalMode === 'completion' ? 'Jelaskan hasil akhir pekerjaan dan kondisi perangkat.' : 'Apa yang telah dilakukan?' }}"></textarea>
                    @error('keterangan_proses') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Kendala (Opsional)</label>
                    <textarea wire:model="kendala" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2" placeholder="Apakah ada kesulitan/sparepart kurang?"></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Lampiran {{ $modalMode === 'completion' ? '(Wajib)' : '(Opsional)' }}</label>
                    <input type="file" wire:model="lampiran" class="block w-full text-sm text-gray-500 file:mr-4 file:rounded file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:font-semibold file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-2 text-xs text-gray-500">Format: JPG, PNG, atau PDF. Maksimal 4MB.</p>
                    @error('lampiran') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>

                <div class="flex justify-end gap-3 border-t pt-4">
                    <button type="button" wire:click="closeModal" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded transition">Batal</button>
                    <button type="submit" class="{{ $modalMode === 'completion' ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white font-bold py-2 px-4 rounded shadow transition">
                        {{ $modalMode === 'completion' ? 'Kirim untuk Verifikasi' : 'Simpan Progress' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
