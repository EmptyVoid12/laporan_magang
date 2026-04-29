<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOC - Manajemen Laporan Gangguan</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<?php ($isHomePage = request()->routeIs('home')); ?>
<body class="bg-gray-100 font-sans leading-normal tracking-normal text-gray-800">

    <?php if($isHomePage): ?>
        <?php echo e($slot); ?>

    <?php elseif(auth()->check()): ?>
        <nav class="bg-blue-800 p-4 shadow-md text-white flex justify-between items-center">
            <div class="font-bold text-xl">
                NOC Panel 
                <span class="text-sm font-normal ml-2 px-2 py-1 bg-blue-700 rounded-lg"><?php echo e(ucfirst(auth()->user()->role)); ?></span>
            </div>
            <div class="flex items-center gap-4">
                <details class="relative">
                    <summary class="flex cursor-pointer list-none items-center gap-2 rounded-full bg-blue-700 px-3 py-2 text-sm">
                        Notifikasi
                        <?php ($unreadCount = auth()->user()->unreadNotifications()->count()); ?>
                        <?php if($unreadCount > 0): ?>
                            <span class="rounded-full bg-white px-2 py-0.5 text-xs font-bold text-blue-700"><?php echo e($unreadCount); ?></span>
                        <?php endif; ?>
                    </summary>
                    <div class="absolute right-0 z-50 mt-3 w-96 rounded-xl border border-gray-200 bg-white p-4 text-gray-800 shadow-2xl">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-sm font-bold text-gray-900">Notifikasi Aktivitas</div>
                                <div class="text-xs text-gray-500">Update tiket terbaru untuk akun Anda.</div>
                            </div>
                            <form method="POST" action="<?php echo e(route('notifications.read-all')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Tandai semua dibaca</button>
                            </form>
                        </div>
                        <div class="mt-4 max-h-96 space-y-3 overflow-y-auto">
                            <?php $__empty_1 = true; $__currentLoopData = auth()->user()->notifications()->latest()->take(8)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="rounded-lg border <?php echo e($notification->read_at ? 'border-gray-200 bg-gray-50' : 'border-blue-200 bg-blue-50'); ?> p-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="text-sm font-bold text-gray-900"><?php echo e($notification->data['title'] ?? 'Notifikasi'); ?></div>
                                            <div class="mt-1 text-sm text-gray-600"><?php echo e($notification->data['message'] ?? '-'); ?></div>
                                            <?php if(!empty($notification->data['ticket_code'])): ?>
                                                <div class="mt-2 text-xs font-mono text-blue-700"><?php echo e($notification->data['ticket_code']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <?php if(!$notification->read_at): ?>
                                            <form method="POST" action="<?php echo e(route('notifications.read', $notification->id)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Baca</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                    <?php if(!empty($notification->data['url'])): ?>
                                        <a href="<?php echo e($notification->data['url']); ?>" class="mt-3 inline-flex text-xs font-semibold text-blue-600 hover:text-blue-700">
                                            Buka tiket
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-4 py-8 text-center text-sm text-gray-500">
                                    Belum ada notifikasi untuk akun Anda.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </details>
                <span><?php echo e(auth()->user()->name); ?></span>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-sm">
                        Logout
                    </button>
                </form>
            </div>
        </nav>
        
        <div class="flex flex-col md:flex-row min-h-screen">
            <!-- Sidebar -->
            <div class="bg-white w-full md:w-64 border-r border-gray-200">
                <ul class="flex flex-col py-4">
                    <?php if(auth()->user()->role === 'admin'): ?>
                        <li class="px-5 py-2 hover:bg-gray-100">
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="block">Dashboard</a>
                        </li>
                        <li class="px-5 py-2 hover:bg-gray-100">
                            <a href="<?php echo e(route('admin.perangkat')); ?>" class="block">Manajemen Perangkat</a>
                        </li>
                        <li class="px-5 py-2 hover:bg-gray-100">
                            <a href="<?php echo e(route('admin.gangguan')); ?>" class="block">Laporan Masuk</a>
                        </li>
                    <?php elseif(auth()->user()->role === 'operator'): ?>
                        <li class="px-5 py-2 hover:bg-gray-100">
                            <a href="<?php echo e(route('user.gangguan')); ?>" class="block">Buat Laporan</a>
                        </li>
                    <?php elseif(auth()->user()->role === 'teknisi'): ?>
                        <li class="px-5 py-2 hover:bg-gray-100">
                            <a href="<?php echo e(route('teknisi.task')); ?>" class="block">Daftar Tugas</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Content -->
            <div class="flex-1 p-6">
                <?php echo e($slot); ?>

            </div>
        </div>
    <?php else: ?>
        <?php echo e($slot); ?>

    <?php endif; ?>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html>
<?php /**PATH /var/www/html/resources/views/components/layouts/app.blade.php ENDPATH**/ ?>