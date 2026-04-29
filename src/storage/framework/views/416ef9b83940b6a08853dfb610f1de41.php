<div class="relative min-h-screen overflow-hidden bg-[#07111d] px-5 py-8 text-slate-100 sm:px-6 lg:px-10">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(45,212,191,0.16),_transparent_28%),radial-gradient(circle_at_bottom_right,_rgba(249,115,22,0.12),_transparent_26%),linear-gradient(180deg,_#07111d_0%,_#0b1728_55%,_#08111f_100%)]"></div>
        <div class="absolute inset-0 opacity-30" style="background-image: linear-gradient(rgba(148,163,184,0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(148,163,184,0.08) 1px, transparent 1px); background-size: 28px 28px;"></div>
    </div>

    <div class="relative z-10 mx-auto flex min-h-[calc(100vh-4rem)] max-w-3xl items-center">
        <section class="w-full rounded-[2.2rem] border border-white/10 bg-slate-950/75 p-7 shadow-[0_35px_120px_rgba(2,8,23,0.55)] backdrop-blur-xl sm:p-8">
            <div class="text-[11px] font-bold uppercase tracking-[0.28em] text-teal-200">Reset Password</div>
            <h1 class="mt-3 text-3xl font-black text-white sm:text-4xl">Buat password baru</h1>
            <p class="mt-3 text-sm leading-6 text-slate-300">Atur ulang password akun Anda, lalu kembali login dari halaman yang sama untuk pengguna maupun teknisi.</p>

            <?php if(session()->has('error')): ?>
                <div class="mt-6 rounded-2xl border border-rose-400/20 bg-rose-400/10 px-4 py-3 text-sm text-rose-200">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <form wire:submit.prevent="resetPassword" class="mt-6 space-y-5">
                <input type="hidden" wire:model="token">

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-200">Alamat Email</label>
                    <input type="email" wire:model="email" class="w-full cursor-not-allowed rounded-2xl border border-white/10 bg-white/[0.05] px-4 py-3.5 text-slate-400" readonly>
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
                    <label class="mb-2 block text-sm font-bold text-slate-200">Password Baru</label>
                    <input type="password" wire:model="password" class="w-full rounded-2xl border border-white/10 bg-white/[0.05] px-4 py-3.5 text-slate-100 placeholder:text-slate-500 focus:border-teal-300/40 focus:outline-none" placeholder="Masukkan password baru" required autofocus>
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
                    <label class="mb-2 block text-sm font-bold text-slate-200">Konfirmasi Password Baru</label>
                    <input type="password" wire:model="password_confirmation" class="w-full rounded-2xl border border-white/10 bg-white/[0.05] px-4 py-3.5 text-slate-100 placeholder:text-slate-500 focus:border-teal-300/40 focus:outline-none" placeholder="Ulangi password baru" required>
                </div>

                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-teal-300 to-cyan-300 px-5 py-3.5 text-sm font-black text-slate-950 transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_18px_40px_rgba(45,212,191,0.28)]" wire:loading.attr="disabled">
                    <span wire:loading.remove>Simpan Password Baru</span>
                    <span wire:loading>Memproses...</span>
                </button>
            </form>
        </section>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/livewire/reset-password-component.blade.php ENDPATH**/ ?>