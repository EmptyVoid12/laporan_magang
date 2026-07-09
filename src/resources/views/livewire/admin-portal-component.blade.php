<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Portal Admin Terpadu</h1>
            <p class="mt-1 text-sm text-slate-500">Pusat kendali operasional, pemantauan jaringan, dan manajemen data sistem.</p>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="border-b border-slate-200">
        <div class="flex flex-wrap -mb-px gap-1">


            <button wire:click="setTab('gangguan')" class="inline-flex items-center gap-2 border-b-2 px-5 py-3 text-sm font-bold transition-all outline-none {{ $activeTab === 'gangguan' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                Laporan Gangguan
                <span class="rounded-full bg-red-100 px-2 py-0.5 text-2xs text-red-600 font-bold">
                    {{ \App\Models\Gangguan::where('status', \App\Models\Gangguan::STATUS_OPEN)->count() }}
                </span>
            </button>

            <button wire:click="setTab('perangkat')" class="inline-flex items-center gap-2 border-b-2 px-5 py-3 text-sm font-bold transition-all outline-none {{ $activeTab === 'perangkat' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/></svg>
                Perangkat Jaringan
            </button>

            <button wire:click="setTab('riwayat')" class="inline-flex items-center gap-2 border-b-2 px-5 py-3 text-sm font-bold transition-all outline-none {{ $activeTab === 'riwayat' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Riwayat & Laporan
            </button>

            <button wire:click="setTab('users')" class="inline-flex items-center gap-2 border-b-2 px-5 py-3 text-sm font-bold transition-all outline-none {{ $activeTab === 'users' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Manajemen Pengguna
            </button>
        </div>
    </div>



    {{-- TAB 2: LAPORAN GANGGUAN (Incident Tickets) --}}
    @if ($activeTab === 'gangguan')
        <div class="space-y-6">
            {{-- Alert --}}
            @if (session()->has('message_gangguan'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('message_gangguan') }}
                </div>
            @endif

            {{-- Filter Bar --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7">
                    <input type="text" wire:model.live.debounce.300ms="searchGangguan" placeholder="Cari tiket..." class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100">
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

            {{-- Tickets Table / List --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm min-w-[1100px]">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs uppercase tracking-wide text-slate-400 bg-slate-50/50">
                                <th class="px-4 py-3 font-semibold">Tiket & Tgl</th>
                                <th class="px-4 py-3 font-semibold">Perangkat</th>
                                <th class="px-4 py-3 font-semibold">Prioritas & Status</th>
                                <th class="px-4 py-3 font-semibold">Tugaskan</th>
                                <th class="px-4 py-3 font-semibold">Ubah Status</th>
                                <th class="px-4 py-3 font-semibold">Detail Tindakan & Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($gangguans as $g)
                                <tr class="hover:bg-slate-50/80 transition-all align-top">
                                    <td class="px-4 py-4">
                                        <div class="font-mono text-xs font-bold text-indigo-600">{{ $g->kode_tiket }}</div>
                                        <div class="text-xs text-slate-500 mt-1">{{ $g->tanggal->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="font-semibold text-slate-800">{{ $g->perangkat->nama_perangkat }}</div>
                                        <div class="text-xs text-slate-400 mt-0.5">{{ $g->perangkat->jenis }} · {{ $g->perangkat->wilayah ?: '-' }}</div>
                                        <div class="text-xs text-slate-500 mt-1 max-w-[200px] italic">"{{ Str::limit($g->deskripsi, 50) }}"</div>
                                    </td>
                                    <td class="px-4 py-4 space-y-1.5">
                                        <div>
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold @if($g->prioritas == 'Tinggi') bg-red-50 text-red-600 @elseif($g->prioritas == 'Sedang') bg-amber-50 text-amber-600 @else bg-emerald-50 text-emerald-600 @endif">{{ $g->prioritas }}</span>
                                        </div>
                                        <div>
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold @if($g->status == 'Selesai') bg-emerald-100 text-emerald-700 @elseif($g->status == 'Proses') bg-sky-100 text-sky-700 @elseif($g->status == 'Diverifikasi') bg-indigo-100 text-indigo-700 @elseif($g->status == 'Menunggu') bg-orange-100 text-orange-700 @elseif($g->status == 'Ditolak') bg-slate-100 text-slate-500 @else bg-red-100 text-red-700 @endif">{{ $g->status }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <select wire:change="assignTeknisi({{ $g->id }}, $event.target.value)" class="rounded-md border border-slate-200 bg-slate-50 px-2 py-1.5 text-xs outline-none focus:border-indigo-300 w-full max-w-[150px]">
                                            <option value="">-- Belum --</option>
                                            @foreach($teknisis as $teknisi)
                                                <option value="{{ $teknisi->id }}" {{ $g->teknisi_id == $teknisi->id ? 'selected' : '' }}>{{ $teknisi->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-4">
                                        <select wire:change="updateStatus({{ $g->id }}, $event.target.value)" class="rounded-md border border-slate-200 bg-slate-50 px-2 py-1.5 text-xs outline-none focus:border-indigo-300 w-full max-w-[150px]">
                                            @foreach($statusOptions as $value => $label)
                                                <option value="{{ $value }}" {{ $g->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-4 min-w-[280px]">
                                        {{-- If Awaiting Final Verification --}}
                                        @if ($g->isAwaitingFinalVerification())
                                            <div class="rounded-lg border border-amber-200 bg-amber-50/50 p-3 space-y-2.5">
                                                <span class="text-xs font-bold text-amber-800 block">Menunggu Verifikasi Admin</span>
                                                
                                                {{-- Fetch completion image proof --}}
                                                @php
                                                    $completionLog = $g->proses->where('tipe_update', 'completion')->first() 
                                                        ?? $g->proses->whereNotNull('attachment_path')->first();
                                                @endphp
                                                @if($completionLog && $completionLog->attachment_path)
                                                    <div class="mt-1">
                                                        <a href="{{ $completionLog->attachment_url }}" target="_blank">
                                                            <img src="{{ $completionLog->attachment_url }}" class="h-20 w-auto rounded border border-slate-200 object-cover hover:opacity-90 transition">
                                                        </a>
                                                    </div>
                                                @endif

                                                <textarea wire:model="verificationNotes.{{ $g->id }}" rows="2" placeholder="Catatan persetujuan..." class="w-full rounded border border-slate-200 bg-white p-2 text-xs outline-none focus:border-indigo-300"></textarea>
                                                <button wire:click="verifyTicket({{ $g->id }})" class="w-full rounded bg-emerald-600 py-1.5 text-xs font-bold text-white transition hover:bg-emerald-700">Setujui & Selesaikan</button>
                                                
                                                <div class="border-t border-amber-200 pt-2 space-y-1.5">
                                                    <textarea wire:model="rejectionNotes.{{ $g->id }}" rows="2" placeholder="Alasan tolak/revisi..." class="w-full rounded border border-slate-200 bg-white p-2 text-xs outline-none focus:border-red-300"></textarea>
                                                    @error('rejection_' . $g->id)
                                                        <span class="text-[10px] text-red-500 font-bold block">{{ $message }}</span>
                                                    @enderror
                                                    <button wire:click="rejectTicket({{ $g->id }})" class="w-full rounded bg-red-50 border border-red-200 text-red-600 py-1.5 text-xs font-bold transition hover:bg-red-100">Tolak & Minta Revisi</button>
                                                </div>
                                            </div>
                                        @else
                                            {{-- Regular timeline list --}}
                                            <div class="space-y-1.5">
                                                @forelse($g->proses->sortByDesc('created_at')->take(2) as $proses)
                                                    <div class="rounded-md border border-slate-100 bg-slate-50 p-2 text-xs">
                                                        <div class="font-bold text-slate-700 flex justify-between">
                                                            <span>{{ $proses->actor_name }}</span>
                                                            <span class="text-slate-400">{{ $proses->tanggal_update->format('d/m') }}</span>
                                                        </div>
                                                        <div class="text-slate-600 mt-0.5 leading-snug">{{ Str::limit($proses->keterangan_proses, 55) }}</div>
                                                        @if ($proses->kendala)
                                                            <div class="mt-1 font-semibold text-red-600 bg-red-50 px-1 py-0.5 rounded text-[10px]">
                                                                Kendala: {{ $proses->kendala }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @empty
                                                    <span class="text-slate-300 text-xs">— Belum ada riwayat tindakan —</span>
                                                @endforelse
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-10 text-center text-sm text-slate-400">Belum ada laporan masuk.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-slate-100 px-5 py-3">{{ $gangguans->links() }}</div>
            </div>
        </div>
    @endif

    {{-- TAB 3: PERANGKAT (Network Devices) --}}
    @if ($activeTab === 'perangkat')
        <div class="space-y-6">
            {{-- Alert --}}
            @if (session()->has('message_perangkat'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('message_perangkat') }}
                </div>
            @endif

            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-800">Daftar Perangkat Jaringan</h2>
                <button wire:click="createPerangkat" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition">
                    + Tambah Perangkat
                </button>
            </div>

            {{-- Devices Table --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs uppercase tracking-wide text-slate-400 bg-slate-50/50">
                                <th class="px-5 py-3 font-semibold">Nama Perangkat</th>
                                <th class="px-5 py-3 font-semibold">Jenis</th>
                                <th class="px-5 py-3 font-semibold">Wilayah</th>
                                <th class="px-5 py-3 font-semibold">Lokasi</th>
                                <th class="px-5 py-3 font-semibold">Deskripsi</th>
                                <th class="px-5 py-3 text-center font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($perangkats as $p)
                                <tr class="hover:bg-slate-50/80 transition-all">
                                    <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $p->nama_perangkat }}</td>
                                    <td class="px-5 py-3.5 text-slate-600">{{ $p->jenis }}</td>
                                    <td class="px-5 py-3.5 text-slate-600">{{ $p->wilayah }}</td>
                                    <td class="px-5 py-3.5 text-slate-600">{{ $p->lokasi }}</td>
                                    <td class="px-5 py-3.5 text-xs text-slate-500 max-w-[200px] truncate">{{ $p->deskripsi ?: '—' }}</td>
                                    <td class="px-5 py-3.5 text-center">
                                        <div class="flex justify-center gap-2">
                                            <button wire:click="editPerangkat({{ $p->id }})" class="rounded bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-600 hover:bg-indigo-100">Ubah</button>
                                            <button onclick="confirm('Yakin ingin menghapus perangkat ini?') || event.stopImmediatePropagation()" wire:click="deletePerangkat({{ $p->id }})" class="rounded bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-600 hover:bg-red-100">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-10 text-center text-sm text-slate-400">Belum ada perangkat jaringan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-slate-100 px-5 py-3">{{ $perangkats->links() }}</div>
            </div>
        </div>

        {{-- Perangkat Modal --}}
        @if($isPerangkatOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm px-4">
                <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl animate-in fade-in zoom-in-95 duration-150">
                    <h3 class="text-lg font-bold text-slate-900">{{ $perangkat_id ? 'Ubah Perangkat' : 'Tambah Perangkat Baru' }}</h3>
                    <form wire:submit.prevent="storePerangkat" class="mt-4 space-y-4">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-slate-600">Nama Perangkat</label>
                            <input type="text" wire:model="nama_perangkat" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 outline-none focus:border-indigo-300">
                            @error('nama_perangkat') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-slate-600">Jenis Perangkat</label>
                            <select wire:model="jenis" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 outline-none focus:border-indigo-300">
                                <option value="">-- Pilih --</option>
                                @foreach($jenisOptions as $val => $lbl)
                                    <option value="{{ $val }}">{{ $lbl }}</option>
                                @endforeach
                            </select>
                            @error('jenis') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-slate-600">Wilayah</label>
                            <select wire:model="wilayah" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 outline-none focus:border-indigo-300">
                                <option value="">-- Pilih --</option>
                                @foreach($wilayahOptions as $val => $lbl)
                                    <option value="{{ $val }}">{{ $lbl }}</option>
                                @endforeach
                            </select>
                            @error('wilayah') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-slate-600">Lokasi / Alamat</label>
                            <input type="text" wire:model="lokasi" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 outline-none focus:border-indigo-300">
                            @error('lokasi') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-slate-600">Deskripsi Tambahan</label>
                            <textarea wire:model="deskripsi_perangkat" rows="3" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 outline-none focus:border-indigo-300"></textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                            <button type="button" wire:click="closePerangkatModal" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 transition">Batal</button>
                            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 shadow transition">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endif

    {{-- TAB 4: RIWAYAT & LAPORAN (History & Performance) --}}
    @if ($activeTab === 'riwayat')
        <div class="space-y-6">
            {{-- Performance Metrics --}}
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-bold text-slate-800">Ringkasan Kinerja & Riwayat Kerja Teknisi</h3>
                <p class="text-xs text-slate-400 mt-1 mb-4">Statistik efektivitas penyelesaian laporan gangguan di lapangan.</p>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm min-w-[720px]">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-500 font-semibold bg-slate-50/50">
                                <th class="py-3 px-4 font-semibold">Nama Teknisi</th>
                                <th class="py-3 px-4 font-semibold">Email</th>
                                <th class="py-3 px-4 font-semibold text-center">Tiket Aktif</th>
                                <th class="py-3 px-4 font-semibold text-center">Menunggu Verifikasi</th>
                                <th class="py-3 px-4 font-semibold text-center">Selesai Terverifikasi</th>
                                <th class="py-3 px-4 font-semibold text-center">Rata-rata Waktu Penyelesaian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($technicianPerformance as $row)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-3 px-4 font-bold text-slate-800">{{ $row['name'] }}</td>
                                    <td class="py-3 px-4 text-slate-600 text-xs">{{ $row['email'] }}</td>
                                    <td class="py-3 px-4 text-center text-slate-700 font-medium">{{ $row['active'] }}</td>
                                    <td class="py-3 px-4 text-center text-amber-600 font-bold">{{ $row['pending'] }}</td>
                                    <td class="py-3 px-4 text-center text-emerald-600 font-bold">{{ $row['completed'] }}</td>
                                    <td class="py-3 px-4 text-center text-slate-700 font-medium">
                                        {{ $row['avg_hours'] !== null ? $row['avg_hours'] . ' jam' : '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-8 text-center text-slate-400">Belum ada data teknisi terdaftar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Exporter Section --}}
            <div class="grid gap-6 md:grid-cols-2">
                {{-- Global System Exporter --}}
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-slate-800">Unduh Laporan Sistem (Format CSV)</h3>
                    <p class="text-xs text-slate-400">Ekspor data global untuk audit manajemen internal.</p>
                    <div class="flex flex-col gap-2.5">
                        <a href="{{ route('admin.reports.export', ['type' => 'tickets']) }}" class="flex items-center justify-between rounded-lg border border-slate-200 hover:border-indigo-300 hover:bg-slate-50 p-3 text-xs font-semibold text-slate-700 transition">
                            <span>Ekspor Semua Tiket Gangguan</span>
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </a>
                        <a href="{{ route('admin.reports.export', ['type' => 'technicians']) }}" class="flex items-center justify-between rounded-lg border border-slate-200 hover:border-indigo-300 hover:bg-slate-50 p-3 text-xs font-semibold text-slate-700 transition">
                            <span>Ekspor Kinerja & Status Teknisi</span>
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </a>
                        <a href="{{ route('admin.reports.export', ['type' => 'timeline']) }}" class="flex items-center justify-between rounded-lg border border-slate-200 hover:border-indigo-300 hover:bg-slate-50 p-3 text-xs font-semibold text-slate-700 transition">
                            <span>Ekspor Log Timeline Tindakan (Audit Log)</span>
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Individual Technician Exporter --}}
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-slate-800">Unduh Riwayat Bulanan Kerja Teknisi</h3>
                    <p class="text-xs text-slate-400">Unduh rekapitulasi kerja bulanan per individu untuk pembagian kerja bulanan.</p>
                    <form wire:submit.prevent="exportTeknisiBulanan" class="space-y-3">
                        <div>
                            <label class="block text-2xs font-semibold text-slate-500 mb-1">Pilih Teknisi</label>
                            <select wire:model="exportTeknisiId" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700 outline-none focus:border-indigo-300">
                                <option value="">-- Pilih --</option>
                                @foreach($technicianPerformance as $t)
                                    <option value="{{ $t['id'] }}">{{ $t['name'] }}</option>
                                @endforeach
                            </select>
                            @error('exportTeknisiId') <span class="text-2xs text-red-500 mt-0.5 block font-semibold">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-2xs font-semibold text-slate-500 mb-1">Pilih Bulan</label>
                            <input type="month" wire:model="exportMonth" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700 outline-none focus:border-indigo-300">
                            @error('exportMonth') <span class="text-2xs text-red-500 mt-0.5 block font-semibold">{{ $message }}</span>@enderror
                        </div>

                        <button type="submit" class="w-full rounded-lg bg-indigo-600 py-2 text-xs font-bold text-white shadow hover:bg-indigo-700 transition">
                            Unduh Laporan Bulanan (CSV)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- TAB 5: MANAJEMEN PENGGUNA --}}
    @if ($activeTab === 'users')
        <div class="space-y-6">
            {{-- Alert --}}
            @if (session()->has('message_user'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('message_user') }}
                </div>
            @endif

            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-800">Daftar Pengguna & Staff</h2>
                <button wire:click="createUser" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition">
                    + Tambah Pengguna Baru
                </button>
            </div>

            {{-- Users Table --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs uppercase tracking-wide text-slate-400 bg-slate-50/50">
                                <th class="px-5 py-3 font-semibold">Nama Lengkap</th>
                                <th class="px-5 py-3 font-semibold">Email</th>
                                <th class="px-5 py-3 font-semibold">Peran (Role)</th>
                                <th class="px-5 py-3 font-semibold">Dibuat Pada</th>
                                <th class="px-5 py-3 text-center font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($users as $user)
                                <tr class="hover:bg-slate-50/80 transition-all">
                                    <td class="px-5 py-3.5 font-semibold text-slate-800 flex items-center gap-2.5">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-50 text-indigo-600 font-bold text-xs">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span>{{ $user->name }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-600">{{ $user->email }}</td>
                                    <td class="px-5 py-3.5">
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold capitalize
                                            @if($user->role === 'admin') bg-purple-50 text-purple-600
                                            @elseif($user->role === 'operator') bg-blue-50 text-blue-600
                                            @elseif($user->role === 'teknisi') bg-orange-50 text-orange-600
                                            @else bg-slate-100 text-slate-600 @endif">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-400 text-xs">{{ $user->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-5 py-3.5 text-center">
                                        <div class="flex justify-center gap-2">
                                            <button wire:click="editUser({{ $user->id }})" class="rounded bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-600 hover:bg-indigo-100">Ubah</button>
                                            @if($user->id !== auth()->id())
                                                <button onclick="confirm('Yakin ingin menghapus pengguna ini?') || event.stopImmediatePropagation()" wire:click="deleteUser({{ $user->id }})" class="rounded bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-600 hover:bg-red-100">Hapus</button>
                                            @else
                                                <span class="text-slate-300 text-xs px-2.5 py-1 font-semibold italic">Aktif</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-slate-100 px-5 py-3">{{ $users->links() }}</div>
            </div>
        </div>

        {{-- User Modal --}}
        @if($isUserOpen)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm px-4">
                <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl animate-in fade-in zoom-in-95 duration-150">
                    <h3 class="text-lg font-bold text-slate-900">{{ $user_id ? 'Ubah Akun Pengguna' : 'Tambah Pengguna Baru' }}</h3>
                    <form wire:submit.prevent="storeUser" class="mt-4 space-y-4">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-slate-600">Nama Lengkap</label>
                            <input type="text" wire:model="name" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 outline-none focus:border-indigo-300">
                            @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-slate-600">Email</label>
                            <input type="email" wire:model="email" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 outline-none focus:border-indigo-300">
                            @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-slate-600">Peran / Hak Akses</label>
                            <select wire:model="role" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 outline-none focus:border-indigo-300">
                                <option value="user">User / Masyarakat</option>
                                <option value="teknisi">Teknisi Lapangan</option>
                                <option value="operator">Operator Staf NOC</option>
                                <option value="admin">Administrator</option>
                            </select>
                            @error('role') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-slate-600">{{ $user_id ? 'Kata Sandi Baru (Opsional)' : 'Kata Sandi' }}</label>
                            <input type="password" wire:model="password" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 outline-none focus:border-indigo-300">
                            @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-slate-600">Konfirmasi Kata Sandi</label>
                            <input type="password" wire:model="password_confirmation" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 outline-none focus:border-indigo-300">
                        </div>

                        <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                            <button type="button" wire:click="closeUserModal" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 transition">Batal</button>
                            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 shadow transition">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endif
</div>
