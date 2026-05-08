<div class="space-y-6">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Laporan Gangguan</h1>
            <p class="mt-1 text-sm text-slate-500">Buat laporan baru atau pantau status laporan Anda.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        
        <div class="xl:col-span-1">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-base font-bold text-slate-800">Buat Laporan Baru</h2>
                <p class="mt-1 text-xs text-slate-400">Isi formulir untuk melaporkan gangguan perangkat.</p>

                <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
                    <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        <?php echo e(session('message')); ?>

                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <form wire:submit.prevent="store" class="mt-5 space-y-4">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Jenis Perangkat</label>
                        <select wire:model.live="jenis" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100">
                            <option value="">Semua Jenis</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $jenisOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Wilayah Jakarta</label>
                        <select wire:model.live="wilayah" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100">
                            <option value="">Semua Wilayah</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $wilayahOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Pilih Perangkat</label>
                        <select wire:key="perangkat-select-<?php echo e($jenis ?: 'semua'); ?>-<?php echo e($wilayah ?: 'semua'); ?>" wire:model="perangkat_id" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100">
                            <option value="">-- Pilih --</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $perangkats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>"><?php echo e($p->nama_perangkat); ?> - <?php echo e($p->jenis); ?> - <?php echo e($p->wilayah ?: 'Tanpa Wilayah'); ?> (<?php echo e($p->lokasi); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['perangkat_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="mt-1 block text-xs text-red-500"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php if($perangkats->isEmpty()): ?>
                            <span class="mt-1 block text-xs text-slate-400">Tidak ada perangkat untuk filter ini.</span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Tanggal</label>
                        <input type="date" wire:model="tanggal" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="mt-1 block text-xs text-red-500"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Prioritas</label>
                        <select wire:model="prioritas" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100">
                            <option value="Rendah">Rendah</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                        </select>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['prioritas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="mt-1 block text-xs text-red-500"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Deskripsi Kerusakan</label>
                        <textarea wire:model="deskripsi" rows="3" class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100" placeholder="Jelaskan detail gangguan yang terjadi..."></textarea>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="mt-1 block text-xs text-red-500"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Upload Foto (Opsional)</label>
                        <input type="file" wire:model="foto" class="block w-full text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-xs file:font-semibold file:text-indigo-600 hover:file:bg-indigo-100">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="mt-1 block text-xs text-red-500"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php if($foto): ?>
                            <div class="mt-3">
                                <p class="mb-1 text-xs text-slate-400">Preview:</p>
                                <img src="<?php echo e($foto->temporaryUrl()); ?>" class="h-28 w-auto rounded-lg border border-slate-200 object-cover">
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700" wire:loading.attr="disabled">
                        <span wire:loading.remove>Kirim Laporan</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </form>
            </div>
        </div>

        
        <div class="xl:col-span-2">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-base font-bold text-slate-800">Riwayat Laporan Anda</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs uppercase tracking-wide text-slate-400">
                                <th class="px-5 py-3 font-semibold">Tiket</th>
                                <th class="px-5 py-3 font-semibold">Tanggal</th>
                                <th class="px-5 py-3 font-semibold">Perangkat</th>
                                <th class="px-5 py-3 font-semibold">Prioritas</th>
                                <th class="px-5 py-3 font-semibold">Status</th>
                                <th class="px-5 py-3 text-center font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $riwayatLaporan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laporan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="px-5 py-3 font-mono text-xs font-semibold text-indigo-600"><?php echo e($laporan->kode_tiket); ?></td>
                                <td class="px-5 py-3 text-slate-600"><?php echo e($laporan->tanggal->format('d M Y')); ?></td>
                                <td class="px-5 py-3">
                                    <div class="font-medium text-slate-800"><?php echo e($laporan->perangkat->nama_perangkat); ?></div>
                                    <div class="text-xs text-slate-400"><?php echo e($laporan->perangkat->jenis); ?> · <?php echo e($laporan->perangkat->wilayah ?: '-'); ?></div>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold
                                        <?php if($laporan->prioritas == 'Tinggi'): ?> bg-red-50 text-red-600
                                        <?php elseif($laporan->prioritas == 'Sedang'): ?> bg-amber-50 text-amber-600
                                        <?php else: ?> bg-emerald-50 text-emerald-600 <?php endif; ?>">
                                        <?php echo e($laporan->prioritas); ?>

                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold
                                        <?php if($laporan->isFinallyVerified()): ?> bg-emerald-100 text-emerald-700
                                        <?php elseif($laporan->isAwaitingFinalVerification()): ?> bg-amber-100 text-amber-700
                                        <?php elseif($laporan->status == 'Selesai'): ?> bg-green-100 text-green-700
                                        <?php elseif($laporan->status == 'Proses'): ?> bg-sky-100 text-sky-700
                                        <?php elseif($laporan->status == 'Diverifikasi'): ?> bg-indigo-100 text-indigo-700
                                        <?php elseif($laporan->status == 'Menunggu'): ?> bg-orange-100 text-orange-700
                                        <?php elseif($laporan->status == 'Ditolak'): ?> bg-slate-100 text-slate-500
                                        <?php else: ?> bg-red-100 text-red-700 <?php endif; ?>">
                                        <?php echo e($laporan->workflow_status_label); ?>

                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?php echo e(route('user.gangguan.show', $laporan)); ?>" class="rounded-md bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-100">Detail</a>
                                        <!--[if BLOCK]><![endif]--><?php if($laporan->status == 'Open'): ?>
                                            <button wire:click="deleteLaporan(<?php echo e($laporan->id); ?>)" class="rounded-md bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-600 transition hover:bg-red-100" onclick="confirm('Yakin ingin menghapus laporan ini?') || event.stopImmediatePropagation()">Hapus</button>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-400">Anda belum pernah membuat laporan.</td>
                            </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/livewire/gangguan-component.blade.php ENDPATH**/ ?>