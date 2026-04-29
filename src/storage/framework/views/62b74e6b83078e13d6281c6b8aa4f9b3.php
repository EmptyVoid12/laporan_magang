<div class="relative min-h-screen overflow-hidden bg-[#07111d] px-5 py-8 text-slate-100 sm:px-6 lg:px-10">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(249,115,22,0.16),_transparent_24%),radial-gradient(circle_at_bottom_right,_rgba(45,212,191,0.12),_transparent_28%),linear-gradient(180deg,_#07111d_0%,_#0b1728_55%,_#08111f_100%)]"></div>
    </div>

    <div class="relative z-10 mx-auto flex min-h-[calc(100vh-4rem)] max-w-md items-center">
        <section class="w-full rounded-[2rem] border border-white/10 bg-slate-950/75 p-7 shadow-[0_35px_120px_rgba(2,8,23,0.55)] backdrop-blur-xl sm:p-8">
            <h1 class="text-3xl font-black text-white">Daftar</h1>

            <form wire:submit.prevent="register" class="mt-6 space-y-5">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-200">Nama Lengkap</label>
                    <input type="text" wire:model="name" class="w-full rounded-2xl border border-white/10 bg-white/[0.05] px-4 py-3.5 text-slate-100 placeholder:text-slate-500 focus:border-orange-300/40 focus:outline-none" placeholder="Nama lengkap" required autofocus>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="mt-2 block text-xs text-rose-300"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-200">Alamat Email</label>
                    <input type="email" wire:model="email" class="w-full rounded-2xl border border-white/10 bg-white/[0.05] px-4 py-3.5 text-slate-100 placeholder:text-slate-500 focus:border-orange-300/40 focus:outline-none" placeholder="nama@email.com" required>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="mt-2 block text-xs text-rose-300"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-200">Password</label>
                    <input type="password" wire:model="password" class="w-full rounded-2xl border border-white/10 bg-white/[0.05] px-4 py-3.5 text-slate-100 placeholder:text-slate-500 focus:border-orange-300/40 focus:outline-none" placeholder="Minimal 8 karakter" required>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="mt-2 block text-xs text-rose-300"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-200">Konfirmasi Password</label>
                    <input type="password" wire:model="password_confirmation" class="w-full rounded-2xl border border-white/10 bg-white/[0.05] px-4 py-3.5 text-slate-100 placeholder:text-slate-500 focus:border-orange-300/40 focus:outline-none" placeholder="Ulangi password" required>
                </div>

                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-orange-300 to-amber-200 px-5 py-3.5 text-sm font-black text-slate-950 transition hover:-translate-y-0.5 hover:shadow-[0_18px_40px_rgba(251,146,60,0.32)]" wire:loading.attr="disabled">
                    <span wire:loading.remove>Daftar</span>
                    <span wire:loading>Memproses...</span>
                </button>
            </form>

            <div class="mt-5 text-center text-sm text-slate-300">
                <a href="<?php echo e(route('login')); ?>" class="font-semibold text-orange-200 hover:text-orange-100">Sudah punya akun? Login</a>
            </div>
            <div class="mt-6 border-t border-white/10 pt-4 text-center text-sm">
                <a href="<?php echo e(route('home')); ?>" class="font-medium text-slate-400 hover:text-white">Kembali ke beranda</a>
            </div>
        </section>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/livewire/register-component.blade.php ENDPATH**/ ?>