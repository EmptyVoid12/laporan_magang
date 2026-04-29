<?php if (isset($component)) { $__componentOriginal166a02a7c5ef5a9331faf66fa665c256 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.page.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Tiket</div>
                <div class="mt-3 text-3xl font-black text-slate-900"><?php echo e($stats['total']); ?></div>
            </div>
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wide text-amber-700">Menunggu Verifikasi Akhir</div>
                <div class="mt-3 text-3xl font-black text-amber-900"><?php echo e($stats['pending_final_verification']); ?></div>
            </div>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Selesai Terverifikasi</div>
                <div class="mt-3 text-3xl font-black text-emerald-900"><?php echo e($stats['verified_completed']); ?></div>
            </div>
            <div class="rounded-2xl border border-sky-200 bg-sky-50 p-5 shadow-sm">
                <div class="text-xs font-semibold uppercase tracking-wide text-sky-700">Rata-rata Waktu Penyelesaian Laporan</div>
                <div class="mt-3 text-3xl font-black text-sky-900"><?php echo e($stats['avg_resolution_hours'] !== null ? $stats['avg_resolution_hours'] . ' jam' : '-'); ?></div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900">Ringkasan Performa Teknisi</h3>
                <div class="mt-5 overflow-x-auto">
                    <table class="w-full min-w-[720px] text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-500">
                                <th class="py-3 pr-4 font-semibold">Teknisi</th>
                                <th class="py-3 pr-4 font-semibold">Assigned</th>
                                <th class="py-3 pr-4 font-semibold">Aktif</th>
                                <th class="py-3 pr-4 font-semibold">Pending Final</th>
                                <th class="py-3 pr-4 font-semibold">Selesai Verify</th>
                                <th class="py-3 font-semibold">Rata-rata Waktu Penyelesaian Laporan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $technicianRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="border-b border-slate-100">
                                    <td class="py-3 pr-4 font-semibold text-slate-900"><?php echo e($row['name']); ?></td>
                                    <td class="py-3 pr-4"><?php echo e($row['assigned']); ?></td>
                                    <td class="py-3 pr-4"><?php echo e($row['active']); ?></td>
                                    <td class="py-3 pr-4"><?php echo e($row['pending_verification']); ?></td>
                                    <td class="py-3 pr-4"><?php echo e($row['verified_done']); ?></td>
                                    <td class="py-3"><?php echo e($row['avg_resolution_hours'] !== null ? $row['avg_resolution_hours'] : '-'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-500">Belum ada data teknisi.</td>
                                </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900">Tiket Selesai Terverifikasi Terbaru</h3>
                <div class="mt-5 space-y-3">
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $recentVerifiedTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs font-mono text-blue-700"><?php echo e($ticket->kode_tiket); ?></div>
                            <div class="mt-1 text-sm font-bold text-slate-900"><?php echo e($ticket->perangkat?->nama_perangkat ?: '-'); ?></div>
                            <div class="mt-2 text-sm text-slate-600">Teknisi: <?php echo e($ticket->teknisi?->name ?: '-'); ?></div>
                            <div class="mt-1 text-sm text-slate-600">Diverifikasi: <?php echo e(optional($ticket->verified_at)->format('d M Y H:i') ?: '-'); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                            Belum ada tiket yang selesai dan diverifikasi.
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $attributes = $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $component = $__componentOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/filament/admin/pages/reports-overview.blade.php ENDPATH**/ ?>