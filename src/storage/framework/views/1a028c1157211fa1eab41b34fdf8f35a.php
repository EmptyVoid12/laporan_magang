<div class="bg-white rounded shadow p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Manajemen Laporan Masuk</h2>

    <?php if(session()->has('message')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <span class="block sm:inline"><?php echo e(session('message')); ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-7 gap-4 mb-6">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari tiket, perangkat, deskripsi..." class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">
        <select wire:model.live="filterJenis" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">
            <option value="">Semua Jenis</option>
            <?php $__currentLoopData = $jenisOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select wire:model.live="filterWilayah" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">
            <option value="">Semua Wilayah</option>
            <?php $__currentLoopData = $wilayahOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select wire:model.live="filterStatus" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">
            <option value="">Semua Status</option>
            <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select wire:model.live="filterPrioritas" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none">
            <option value="">Semua Prioritas</option>
            <?php $__currentLoopData = $prioritasOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <?php $__empty_1 = true; $__currentLoopData = $gangguans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4 font-mono text-xs text-blue-700 font-semibold"><?php echo e($g->kode_tiket); ?></td>
                    <td class="py-3 px-4"><?php echo e($g->tanggal->format('d/m/Y')); ?></td>
                    <td class="py-3 px-4 font-medium">
                        <?php echo e($g->perangkat->nama_perangkat); ?>

                        <br><span class="text-xs text-gray-500"><?php echo e($g->perangkat->jenis); ?> | <?php echo e($g->perangkat->wilayah ?: '-'); ?></span>
                        <br><span class="text-xs text-gray-400"><?php echo e($g->perangkat->lokasi); ?></span>
                    </td>
                    <td class="py-3 px-4 text-sm"><?php echo e(Str::limit($g->deskripsi, 40)); ?></td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded text-xs font-semibold 
                            <?php if($g->prioritas == 'Tinggi'): ?> bg-red-100 text-red-800 
                            <?php elseif($g->prioritas == 'Sedang'): ?> bg-yellow-100 text-yellow-800 
                            <?php else: ?> bg-green-100 text-green-800 <?php endif; ?>">
                            <?php echo e($g->prioritas); ?>

                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            <?php if($g->status == 'Selesai'): ?> bg-green-500 text-white
                            <?php elseif($g->status == 'Proses'): ?> bg-yellow-400 text-white
                            <?php elseif($g->status == 'Diverifikasi'): ?> bg-blue-500 text-white
                            <?php elseif($g->status == 'Menunggu'): ?> bg-orange-400 text-white
                            <?php elseif($g->status == 'Ditolak'): ?> bg-gray-500 text-white
                            <?php else: ?> bg-red-500 text-white <?php endif; ?>">
                            <?php echo e($g->status); ?>

                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <select wire:change="assignTeknisi(<?php echo e($g->id); ?>, $event.target.value)" class="text-sm shadow appearance-none border rounded w-full py-1 px-2 text-gray-700 focus:outline-none">
                            <option value="">-- Belum Diassign --</option>
                            <?php $__currentLoopData = $teknisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teknisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($teknisi->id); ?>" <?php echo e($g->teknisi_id == $teknisi->id ? 'selected' : ''); ?>>
                                    <?php echo e($teknisi->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </td>
                    <td class="py-3 px-4">
                        <select wire:change="updateStatus(<?php echo e($g->id); ?>, $event.target.value)" class="text-sm shadow appearance-none border rounded w-full py-1 px-2 text-gray-700 focus:outline-none">
                            <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php echo e($g->status == $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </td>
                    <td class="py-3 px-4 text-xs text-gray-600 min-w-72">
                        <?php $__empty_2 = true; $__currentLoopData = $g->proses->sortByDesc('created_at')->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proses): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <div class="mb-2 rounded border bg-gray-50 p-2">
                                <div class="font-semibold text-gray-700"><?php echo e($proses->actor_name); ?> • <?php echo e($proses->tanggal_update->format('d/m/Y')); ?></div>
                                <div><?php echo e($proses->keterangan_proses); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                            <span class="text-gray-400">Belum ada riwayat.</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" class="py-6 text-center text-gray-500">Belum ada laporan masuk.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <?php echo e($gangguans->links()); ?>

    </div>
</div>
<?php /**PATH /var/www/html/resources/views/livewire/admin-gangguan-component.blade.php ENDPATH**/ ?>