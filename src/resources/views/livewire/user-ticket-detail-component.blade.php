<div class="space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <a href="{{ route('user.gangguan') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">&larr; Kembali ke riwayat tiket</a>
            <h2 class="mt-2 text-2xl font-bold text-gray-800">Detail Tiket {{ $gangguan->kode_tiket }}</h2>
            <p class="mt-1 text-sm text-gray-500">Pantau semua progres, bukti pekerjaan teknisi, dan hasil verifikasi akhir di satu halaman.</p>
        </div>
        <span class="rounded-full px-4 py-2 text-sm font-bold {{ $gangguan->isFinallyVerified() ? 'bg-green-100 text-green-700' : ($gangguan->isAwaitingFinalVerification() ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
            {{ $gangguan->workflow_status_label }}
        </span>
    </div>

    <div class="grid gap-4 lg:grid-cols-4">
        <div class="rounded-xl border bg-white p-4 shadow-sm">
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Perangkat</div>
            <div class="mt-2 text-sm font-bold text-gray-900">{{ $gangguan->perangkat->nama_perangkat }}</div>
            <div class="mt-2 text-sm text-gray-600">{{ $gangguan->perangkat->jenis }} • {{ $gangguan->perangkat->wilayah ?: '-' }}</div>
        </div>
        <div class="rounded-xl border bg-white p-4 shadow-sm">
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Prioritas</div>
            <div class="mt-2 text-sm font-bold text-gray-900">{{ $gangguan->prioritas }}</div>
        </div>
        <div class="rounded-xl border bg-white p-4 shadow-sm">
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Teknisi</div>
            <div class="mt-2 text-sm font-bold text-gray-900">{{ $gangguan->teknisi?->name ?: 'Belum diassign' }}</div>
        </div>
        <div class="rounded-xl border bg-white p-4 shadow-sm">
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Verifikasi Akhir</div>
            <div class="mt-2 text-sm font-bold text-gray-900">{{ $gangguan->verifier?->name ?: 'Belum diverifikasi' }}</div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
        <div class="rounded-2xl border bg-white p-5 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900">Kronologi Penanganan</h3>
            <div class="mt-5 space-y-4">
                @forelse($gangguan->proses->sortByDesc('id') as $item)
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ $item->actor_name }}</div>
                                <div class="mt-1 text-xs uppercase tracking-wide text-gray-500">{{ strtoupper($item->tipe_update) }}</div>
                            </div>
                            <div class="text-sm text-gray-500">{{ optional($item->tanggal_update)->format('d M Y') }}</div>
                        </div>
                        <div class="mt-3 text-sm text-gray-700">{{ $item->keterangan_proses }}</div>
                        @if($item->kendala)
                            <div class="mt-3 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-900">
                                Kendala: {{ $item->kendala }}
                            </div>
                        @endif
                        @if($item->has_attachment)
                            <div class="mt-3">
                                <a href="{{ $item->attachment_url }}" target="_blank" class="inline-flex items-center rounded-lg bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100">
                                    Lihat lampiran: {{ $item->attachment_name ?: 'Bukti pekerjaan' }}
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 px-4 py-10 text-center text-sm text-gray-500">
                        Belum ada progres yang tercatat untuk tiket ini.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border bg-white p-5 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900">Deskripsi Gangguan</h3>
                <p class="mt-3 text-sm leading-6 text-gray-700">{{ $gangguan->deskripsi }}</p>
                @if($gangguan->foto)
                    <div class="mt-4">
                        <div class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Foto saat laporan dibuat</div>
                        <img src="{{ asset('storage/' . $gangguan->foto) }}" alt="Foto gangguan" class="w-full rounded-xl border object-cover">
                    </div>
                @endif
            </div>

            <div class="rounded-2xl border bg-white p-5 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900">Status Verifikasi Akhir</h3>
                <div class="mt-3 space-y-3 text-sm text-gray-700">
                    <div><span class="font-semibold">Status:</span> {{ $gangguan->workflow_status_label }}</div>
                    <div><span class="font-semibold">Diajukan:</span> {{ optional($gangguan->submitted_for_verification_at)->format('d M Y H:i') ?: '-' }}</div>
                    <div><span class="font-semibold">Diverifikasi:</span> {{ optional($gangguan->verified_at)->format('d M Y H:i') ?: '-' }}</div>
                    <div><span class="font-semibold">Verifier:</span> {{ $gangguan->verifier?->name ?: '-' }}</div>
                    <div><span class="font-semibold">Catatan:</span> {{ $gangguan->verification_notes ?: '-' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
