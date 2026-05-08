<div class="relative min-h-screen overflow-hidden bg-slate-50 px-5 py-8 text-slate-700 sm:px-6 lg:px-10">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.08),_transparent_28%),radial-gradient(circle_at_bottom_right,_rgba(56,189,248,0.08),_transparent_26%),linear-gradient(180deg,_#f8fafc_0%,_#f1f5f9_55%,_#e2e8f0_100%)]"></div>
    </div>

    <div class="relative z-10 mx-auto flex min-h-[calc(100vh-4rem)] max-w-md items-center">
        <section class="w-full rounded-2xl border border-slate-200 bg-white/80 p-7 shadow-sm backdrop-blur-xl sm:p-8">
            <h1 class="text-3xl font-black text-slate-900">Daftar</h1>

            <form wire:submit.prevent="register" class="mt-6 space-y-5">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Nama Lengkap</label>
                    <input type="text" wire:model="name" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-900 placeholder:text-slate-400 focus:border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-100" placeholder="Nama lengkap" required autofocus>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="mt-2 block text-xs text-red-500"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Alamat Email</label>
                    <input type="email" wire:model="email" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-900 placeholder:text-slate-400 focus:border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-100" placeholder="nama@email.com" required>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="mt-2 block text-xs text-red-500"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Password</label>
                    <input type="password" wire:model="password" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-900 placeholder:text-slate-400 focus:border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-100" placeholder="Minimal 8 karakter" required>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="mt-2 block text-xs text-red-500"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Konfirmasi Password</label>
                    <input type="password" wire:model="password_confirmation" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-900 placeholder:text-slate-400 focus:border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-100" placeholder="Ulangi password" required>
                </div>

                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3.5 text-sm font-black text-white transition hover:-translate-y-0.5 hover:shadow-md" wire:loading.attr="disabled">
                    <span wire:loading.remove>Daftar</span>
                    <span wire:loading>Memproses...</span>
                </button>
            </form>

            <div class="mt-5 text-center text-sm text-slate-500">
                <a href="<?php echo e(route('login')); ?>" class="font-semibold text-indigo-600 hover:text-indigo-700">Sudah punya akun? Login</a>
            </div>
            <div class="mt-6 border-t border-slate-200 pt-4 text-center text-sm">
                <a href="<?php echo e(route('home')); ?>" class="font-medium text-slate-400 hover:text-slate-600">Kembali ke beranda</a>
            </div>
        </section>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/livewire/register-component.blade.php ENDPATH**/ ?>