<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Manajemen Perangkat</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola data perangkat yang dimonitor.</p>
        </div>
        <button wire:click="create" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Perangkat
        </button>
    </div>

    @if (session()->has('message'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('message') }}</div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-xs uppercase tracking-wide text-slate-400">
                        <th class="px-5 py-3 font-semibold">Nama</th>
                        <th class="px-5 py-3 font-semibold">Jenis</th>
                        <th class="px-5 py-3 font-semibold">Wilayah</th>
                        <th class="px-5 py-3 font-semibold">Lokasi</th>
                        <th class="px-5 py-3 font-semibold">Deskripsi</th>
                        <th class="px-5 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($perangkats as $p)
                    <tr class="hover:bg-slate-50/80">
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $p->nama_perangkat }}</td>
                        <td class="px-5 py-3"><span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600">{{ $p->jenis }}</span></td>
                        <td class="px-5 py-3 text-slate-600">{{ $p->wilayah ?: '-' }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $p->lokasi }}</td>
                        <td class="px-5 py-3 text-xs text-slate-500">{{ Str::limit($p->deskripsi, 50) }}</td>
                        <td class="px-5 py-3">
                            <div class="flex gap-1.5">
                                <button wire:click="edit({{ $p->id }})" class="rounded-md bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-600 hover:bg-amber-100">Edit</button>
                                <button wire:click="delete({{ $p->id }})" onclick="confirm('Yakin ingin menghapus?') || event.stopImmediatePropagation()" class="rounded-md bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-600 hover:bg-red-100">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-sm text-slate-400">Belum ada perangkat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 px-5 py-3">{{ $perangkats->links() }}</div>
    </div>

    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
        <div class="w-full max-w-lg rounded-xl border border-slate-200 bg-white p-6 shadow-2xl mx-4">
            <h3 class="text-lg font-bold text-slate-900">{{ $perangkat_id ? 'Edit Perangkat' : 'Tambah Perangkat' }}</h3>
            <form wire:submit.prevent="store" class="mt-5 space-y-4">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">Nama Perangkat</label>
                    <input type="text" wire:model="nama_perangkat" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100">
                    @error('nama_perangkat') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">Jenis</label>
                    <select wire:model="jenis" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-indigo-300">
                        <option value="">Pilih Jenis</option>
                        @foreach($jenisOptions as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach
                    </select>
                    @error('jenis') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">Wilayah</label>
                    <select wire:model="wilayah" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-indigo-300">
                        <option value="">Pilih Wilayah</option>
                        @foreach($wilayahOptions as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach
                    </select>
                    @error('wilayah') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">Lokasi</label>
                    <input type="text" wire:model="lokasi" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100">
                    @error('lokasi') <span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-600">Deskripsi</label>
                    <textarea wire:model="deskripsi" rows="3" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100"></textarea>
                </div>
                <div class="flex justify-end gap-2 border-t border-slate-100 pt-4">
                    <button type="button" wire:click="closeModal" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
