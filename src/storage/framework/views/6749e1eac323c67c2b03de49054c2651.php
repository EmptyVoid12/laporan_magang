<?php
    $timeline = $gangguan?->proses?->sortByDesc('id') ?? collect();

    $typeLabel = static fn (string $type): string => match ($type) {
        'report' => 'Laporan Dibuat',
        'assignment' => 'Penugasan',
        'status' => 'Perubahan Status',
        'progress' => 'Progress',
        'completion' => 'Penyelesaian',
        'verification' => 'Verifikasi Akhir',
        default => ucfirst($type),
    };

    $typeColor = static fn (string $type): string => match ($type) {
        'report' => 'bg-sky-500/10 text-sky-600 ring-sky-500/20',
        'assignment' => 'bg-amber-500/10 text-amber-600 ring-amber-500/20',
        'status' => 'bg-slate-500/10 text-slate-600 ring-slate-500/20',
        'progress' => 'bg-cyan-500/10 text-cyan-600 ring-cyan-500/20',
        'completion' => 'bg-emerald-500/10 text-emerald-600 ring-emerald-500/20',
        'verification' => 'bg-lime-500/10 text-lime-700 ring-lime-500/20',
        default => 'bg-slate-500/10 text-slate-600 ring-slate-500/20',
    };
?>

<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Tiket</div>
            <div class="mt-2 text-sm font-bold text-gray-900"><?php echo e($gangguan?->kode_tiket ?? '-'); ?></div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Perangkat</div>
            <div class="mt-2 text-sm font-bold text-gray-900"><?php echo e($gangguan?->perangkat?->nama_perangkat ?? '-'); ?></div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Status</div>
            <div class="mt-2 text-sm font-bold text-gray-900"><?php echo e($gangguan?->workflow_status_label ?? '-'); ?></div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Teknisi</div>
            <div class="mt-2 text-sm font-bold text-gray-900"><?php echo e($gangguan?->teknisi?->name ?? 'Belum diassign'); ?></div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Timeline Progress</h3>
                <p class="mt-1 text-sm text-gray-500">Semua progres untuk laporan ini ditampilkan berurutan di sini.</p>
            </div>
            <div class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                <?php echo e($timeline->count()); ?> riwayat
            </div>
        </div>

        <div class="mt-5 space-y-4">
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $timeline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                        <div class="space-y-3">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset <?php echo e($typeColor($item->tipe_update)); ?>">
                                <?php echo e($typeLabel($item->tipe_update)); ?>

                            </span>
                            <div class="text-sm font-semibold text-gray-900">
                                <?php echo e($item->keterangan_proses ?: '-'); ?>

                            </div>
                        </div>
                        <div class="text-sm font-medium text-gray-500">
                            <?php echo e(optional($item->tanggal_update)->format('d M Y') ?: '-'); ?>

                        </div>
                    </div>

                    <div class="mt-4 grid gap-3 md:grid-cols-2">
                        <div class="rounded-lg border border-gray-200 bg-white px-3 py-2">
                            <div class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Aktor</div>
                            <div class="mt-1 text-sm font-medium text-gray-900"><?php echo e($item->actor_name); ?></div>
                        </div>
                        <div class="rounded-lg border border-gray-200 bg-white px-3 py-2">
                            <div class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Teknisi</div>
                            <div class="mt-1 text-sm font-medium text-gray-900"><?php echo e($item->teknisi?->name ?? 'Belum diassign'); ?></div>
                        </div>
                    </div>

                    <!--[if BLOCK]><![endif]--><?php if($item->kendala): ?>
                        <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-3 py-3">
                            <div class="text-[11px] font-semibold uppercase tracking-wide text-amber-700">Kendala</div>
                            <div class="mt-1 text-sm text-amber-900"><?php echo e($item->kendala); ?></div>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <!--[if BLOCK]><![endif]--><?php if($item->has_attachment): ?>
                        <div class="mt-4">
                            <a href="<?php echo e($item->attachment_url); ?>" target="_blank" class="inline-flex rounded-lg bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100">
                                Lihat lampiran: <?php echo e($item->attachment_name ?: 'Bukti pekerjaan'); ?>

                            </a>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 px-4 py-10 text-center text-sm text-gray-500">
                    Belum ada progress yang tercatat untuk laporan ini.
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/filament/admin/resources/laporan-proses-resource/timeline.blade.php ENDPATH**/ ?>