<div class="space-y-6">
    <h2 class="text-2xl font-bold text-gray-800 border-b pb-2">Admin Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-600 transition hover:shadow-md">
            <p class="text-sm text-gray-500 font-semibold mb-1">Total Laporan</p>
            <p class="text-4xl font-extrabold text-blue-900"><?php echo e($total); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500 transition hover:shadow-md">
            <p class="text-sm text-gray-500 font-semibold mb-1">Status Open</p>
            <p class="text-4xl font-extrabold text-red-700"><?php echo e($open); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-sky-500 transition hover:shadow-md">
            <p class="text-sm text-gray-500 font-semibold mb-1">Diverifikasi</p>
            <p class="text-4xl font-extrabold text-sky-700"><?php echo e($diverifikasi); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500 transition hover:shadow-md">
            <p class="text-sm text-gray-500 font-semibold mb-1">Dalam Proses</p>
            <p class="text-4xl font-extrabold text-yellow-600"><?php echo e($proses); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-orange-500 transition hover:shadow-md">
            <p class="text-sm text-gray-500 font-semibold mb-1">Menunggu</p>
            <p class="text-4xl font-extrabold text-orange-600"><?php echo e($menunggu); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500 transition hover:shadow-md">
            <p class="text-sm text-gray-500 font-semibold mb-1">Telah Selesai</p>
            <p class="text-4xl font-extrabold text-green-700"><?php echo e($selesai); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-gray-500 transition hover:shadow-md">
            <p class="text-sm text-gray-500 font-semibold mb-1">Ditolak</p>
            <p class="text-4xl font-extrabold text-gray-700"><?php echo e($ditolak); ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="bg-white rounded shadow-sm border p-6">
            <h3 class="font-bold text-lg text-gray-800 mb-4">Distribusi Laporan per Wilayah</h3>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $laporanPerWilayah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between rounded bg-gray-50 px-4 py-3">
                        <span class="font-medium text-gray-700"><?php echo e($item->wilayah); ?></span>
                        <span class="text-sm font-bold text-blue-700"><?php echo e($item->total); ?> laporan</span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-sm text-gray-500">Belum ada data wilayah.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded shadow-sm border p-6">
            <h3 class="font-bold text-lg text-gray-800 mb-4">Distribusi Laporan per Jenis</h3>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $laporanPerJenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between rounded bg-gray-50 px-4 py-3">
                        <span class="font-medium text-gray-700"><?php echo e($item->jenis); ?></span>
                        <span class="text-sm font-bold text-indigo-700"><?php echo e($item->total); ?> laporan</span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-sm text-gray-500">Belum ada data jenis perangkat.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="bg-white rounded shadow-sm border p-6">
        <h3 class="font-bold text-lg text-gray-800 mb-4">Laporan Masuk Terbaru</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="py-3 px-4 border-b font-semibold">Tgl Masuk</th>
                        <th class="py-3 px-4 border-b font-semibold">Perangkat</th>
                        <th class="py-3 px-4 border-b font-semibold">Prioritas</th>
                        <th class="py-3 px-4 border-b font-semibold">Status</th>
                        <th class="py-3 px-4 border-b font-semibold">Teknisi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div><?php echo e($r->tanggal->format('d M Y')); ?></div>
                            <div class="text-xs font-mono text-blue-700"><?php echo e($r->kode_tiket); ?></div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-medium text-gray-800"><?php echo e($r->perangkat->nama_perangkat); ?></span><br>
                            <span class="text-xs text-gray-500"><?php echo e($r->perangkat->jenis); ?> | <?php echo e($r->perangkat->wilayah ?: '-'); ?></span><br>
                            <span class="text-xs text-gray-400"><?php echo e($r->perangkat->lokasi); ?></span>
                        </td>
                        <td class="py-3 px-4">
                            <?php if($r->prioritas == 'Tinggi'): ?>
                                <span class="text-red-600 font-semibold"><i class="fas fa-exclamation-circle text-xs"></i> Tinggi</span>
                            <?php elseif($r->prioritas == 'Sedang'): ?>
                                <span class="text-yellow-600 font-semibold">Sedang</span>
                            <?php else: ?>
                                <span class="text-green-600 font-semibold">Rendah</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                <?php if($r->status == 'Selesai'): ?> bg-green-500 text-white
                                <?php elseif($r->status == 'Proses'): ?> bg-yellow-400 text-white
                                <?php elseif($r->status == 'Diverifikasi'): ?> bg-blue-500 text-white
                                <?php elseif($r->status == 'Menunggu'): ?> bg-orange-400 text-white
                                <?php elseif($r->status == 'Ditolak'): ?> bg-gray-500 text-white
                                <?php else: ?> bg-red-500 text-white <?php endif; ?>">
                                <?php echo e($r->status); ?>

                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-600">
                            <?php echo e($r->teknisi ? $r->teknisi->name : '-'); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">Belum ada laporan.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/livewire/dashboard-component.blade.php ENDPATH**/ ?>