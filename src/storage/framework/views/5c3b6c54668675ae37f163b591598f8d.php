<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOC - Manajemen Laporan Gangguan</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal text-gray-800">
    
    <?php if(auth()->guard()->check()): ?>
        <nav class="bg-blue-800 p-4 shadow-md text-white flex justify-between items-center">
            <div class="font-bold text-xl">
                NOC Panel 
                <span class="text-sm font-normal ml-2 px-2 py-1 bg-blue-700 rounded-lg"><?php echo e(ucfirst(auth()->user()->role)); ?></span>
            </div>
            <div class="flex items-center gap-4">
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