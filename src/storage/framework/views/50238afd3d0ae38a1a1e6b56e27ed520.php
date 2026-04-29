<div class="bg-white rounded shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Perangkat</h2>
        <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
            Tambah Perangkat
        </button>
    </div>

    <?php if(session()->has('message')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <span class="block sm:inline"><?php echo e(session('message')); ?></span>
        </div>
    <?php endif; ?>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-blue-100 text-blue-900 shadow-sm">
                    <th class="py-3 px-4 border-b font-semibold">Nama</th>
                    <th class="py-3 px-4 border-b font-semibold">Jenis</th>
                    <th class="py-3 px-4 border-b font-semibold">Wilayah</th>
                    <th class="py-3 px-4 border-b font-semibold">Lokasi</th>
                    <th class="py-3 px-4 border-b font-semibold">Deskripsi</th>
                    <th class="py-3 px-4 border-b font-semibold w-32">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $perangkats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 border-b transition">
                    <td class="py-3 px-4"><?php echo e($p->nama_perangkat); ?></td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 bg-gray-200 text-gray-800 rounded-full text-xs font-semibold"><?php echo e($p->jenis); ?></span>
                    </td>
                    <td class="py-3 px-4"><?php echo e($p->wilayah ?: '-'); ?></td>
                    <td class="py-3 px-4"><?php echo e($p->lokasi); ?></td>
                    <td class="py-3 px-4"><?php echo e(Str::limit($p->deskripsi, 50)); ?></td>
                    <td class="py-3 px-4 flex gap-2">
                        <button wire:click="edit(<?php echo e($p->id); ?>)" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded shadow-sm text-sm">Edit</button>
                        <button wire:click="delete(<?php echo e($p->id); ?>)" 
                            onclick="confirm('Yakin ingin menghapus perangkat ini?') || event.stopImmediatePropagation()"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow-sm text-sm">Hapus</button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="py-4 text-center text-gray-500">Belum ada perangkat.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <?php echo e($perangkats->links()); ?>

    </div>

    <?php if($isOpen): ?>
    <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
            <h3 class="text-xl font-bold mb-4"><?php echo e($perangkat_id ? 'Edit Perangkat' : 'Tambah Perangkat'); ?></h3>
            
            <form wire:submit.prevent="store">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Perangkat</label>
                    <input type="text" wire:model="nama_perangkat" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <?php $__errorArgs = ['nama_perangkat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Jenis</label>
                    <select wire:model="jenis" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Pilih Jenis</option>
                        <?php $__currentLoopData = $jenisOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['jenis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Wilayah</label>
                    <select wire:model="wilayah" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Pilih Wilayah</option>
                        <?php $__currentLoopData = $wilayahOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['wilayah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi</label>
                    <input type="text" wire:model="lokasi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <?php $__errorArgs = ['lokasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                    <textarea wire:model="deskripsi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3"></textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" wire:click="closeModal" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded">Batal</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php /**PATH /var/www/html/resources/views/livewire/perangkat-component.blade.php ENDPATH**/ ?>