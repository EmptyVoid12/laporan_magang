<div class="relative min-h-screen overflow-hidden bg-[#07111d] text-slate-100">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(45,212,191,0.18),_transparent_26%),radial-gradient(circle_at_top_right,_rgba(249,115,22,0.12),_transparent_22%),linear-gradient(180deg,_#07111d_0%,_#0b1728_50%,_#08111f_100%)]"></div>
        <div class="absolute inset-0 opacity-25" style="background-image: linear-gradient(rgba(148,163,184,0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(148,163,184,0.08) 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="absolute -left-20 top-20 h-72 w-72 rounded-full bg-teal-400/20 blur-3xl"></div>
        <div class="absolute right-0 top-0 h-80 w-80 rounded-full bg-sky-500/15 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto flex w-full max-w-7xl flex-col gap-6 px-5 py-5 sm:px-6 lg:px-10 lg:py-8">
        <header class="rounded-[1.8rem] border border-white/10 bg-white/[0.05] px-5 py-4 shadow-[0_20px_70px_rgba(2,8,23,0.35)] backdrop-blur-xl">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-teal-300 via-cyan-300 to-sky-400 text-sm font-black text-slate-950">
                        NOC
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-teal-200">Network Operation Center</p>
                        <h1 class="mt-1 text-base font-black text-white sm:text-lg">Laporan Gangguan Perangkat</h1>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <!--[if BLOCK]><![endif]--><?php if(auth()->guard()->check()): ?>
                        <!--[if BLOCK]><![endif]--><?php if(in_array(auth()->user()->role, ['admin', 'super_admin']) || auth()->user()->hasRole('super_admin')): ?>
                            <a href="<?php echo e(url('/admin')); ?>" class="inline-flex items-center justify-center rounded-full bg-white px-4 py-2.5 text-sm font-black text-slate-950 transition hover:bg-teal-100">
                                Panel
                            </a>
                        <?php elseif(auth()->user()->role === 'teknisi'): ?>
                            <a href="<?php echo e(route('teknisi.task')); ?>" class="inline-flex items-center justify-center rounded-full bg-white px-4 py-2.5 text-sm font-black text-slate-950 transition hover:bg-teal-100">
                                Tugas
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(route('user.gangguan')); ?>" class="inline-flex items-center justify-center rounded-full bg-white px-4 py-2.5 text-sm font-black text-slate-950 transition hover:bg-teal-100">
                                Lapor
                            </a>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="inline-flex items-center justify-center rounded-full bg-white px-4 py-2.5 text-sm font-black text-slate-950 transition hover:bg-teal-100">
                            Login
                        </a>
                        <a href="<?php echo e(route('register')); ?>" class="inline-flex items-center justify-center rounded-full border border-white/15 bg-white/5 px-4 py-2.5 text-sm font-semibold text-white transition hover:border-white/25 hover:bg-white/10">
                            Daftar
                        </a>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </header>
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[1.6rem] border border-teal-300/20 bg-gradient-to-br from-teal-300/12 to-transparent p-5">
                <div class="text-[11px] font-bold uppercase tracking-[0.24em] text-teal-200">Total Tiket</div>
                <div class="mt-4 text-4xl font-black text-white"><?php echo e($total); ?></div>
            </div>
            <div class="rounded-[1.6rem] border border-rose-300/20 bg-gradient-to-br from-rose-300/12 to-transparent p-5">
                <div class="text-[11px] font-bold uppercase tracking-[0.24em] text-rose-200">Butuh Respon</div>
                <div class="mt-4 text-4xl font-black text-white"><?php echo e($open); ?></div>
            </div>
            <div class="rounded-[1.6rem] border border-sky-300/20 bg-gradient-to-br from-sky-300/12 to-transparent p-5">
                <div class="text-[11px] font-bold uppercase tracking-[0.24em] text-sky-200">Terverifikasi</div>
                <div class="mt-4 text-4xl font-black text-white"><?php echo e($diverifikasi); ?></div>
            </div>
            <div class="rounded-[1.6rem] border border-amber-300/20 bg-gradient-to-br from-amber-300/12 to-transparent p-5">
                <div class="text-[11px] font-bold uppercase tracking-[0.24em] text-amber-200">Diproses</div>
                <div class="mt-4 text-4xl font-black text-white"><?php echo e($proses + $menunggu); ?></div>
            </div>
        </section>

        <section class="rounded-[2rem] border border-white/10 bg-slate-950/70 p-5 shadow-[0_28px_90px_rgba(2,8,23,0.45)] backdrop-blur-xl sm:p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-[0.28em] text-cyan-200">Ticket Lookup</div>
                        <h3 class="mt-2 text-2xl font-black text-white">Cek status tiket</h3>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl border border-cyan-300/20 bg-cyan-300/10 text-cyan-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-4.65a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                        </svg>
                    </div>
                </div>

                <p class="mt-3 text-sm leading-6 text-slate-300">
                    Masukkan kode tiket untuk melihat progres penanganan terbaru.
                </p>

                <form wire:submit.prevent="searchTicket" class="mt-5 space-y-4">
                    <div>
                        <label class="mb-2 block text-[11px] font-bold uppercase tracking-[0.24em] text-slate-400">Kode Tiket</label>
                        <div class="relative">
                            <input
                                type="text"
                                wire:model="ticketCode"
                                placeholder="NOC-20260421-0001"
                                class="w-full rounded-2xl border border-white/10 bg-white/[0.05] px-4 py-3.5 pr-12 text-sm font-semibold tracking-[0.05em] text-white placeholder:text-slate-500 focus:border-cyan-300/40 focus:outline-none"
                            >
                            <span class="absolute inset-y-0 right-4 flex items-center text-slate-500">#</span>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['ticketCode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="mt-2 block text-xs text-rose-300"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-cyan-300 to-sky-400 px-5 py-3.5 text-sm font-black text-slate-950 transition hover:brightness-110">
                        Cek status
                        <span>+</span>
                    </button>
                </form>

                <div class="mt-5 rounded-[1.5rem] border border-white/8 bg-white/[0.04] p-4">
                    <!--[if BLOCK]><![endif]--><?php if($ticketResult): ?>
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <div class="text-[11px] font-bold uppercase tracking-[0.26em] text-emerald-200"><?php echo e($ticketResult->kode_tiket); ?></div>
                                <div class="mt-2 text-xl font-black text-white"><?php echo e($ticketResult->perangkat->nama_perangkat); ?></div>
                                <div class="mt-2 text-sm leading-6 text-slate-300">
                                    <?php echo e($ticketResult->perangkat->jenis); ?> • <?php echo e($ticketResult->perangkat->wilayah ?: '-'); ?> • <?php echo e($ticketResult->perangkat->lokasi); ?>

                                </div>
                            </div>
                            <div class="rounded-full bg-emerald-400/15 px-3 py-1 text-xs font-bold text-emerald-200">
                                <?php echo e($ticketResult->workflow_status_label); ?>

                            </div>
                        </div>

                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-2xl border border-white/8 bg-slate-900/70 p-3">
                                <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Prioritas</div>
                                <div class="mt-2 text-sm font-bold text-white"><?php echo e($ticketResult->prioritas); ?></div>
                            </div>
                            <div class="rounded-2xl border border-white/8 bg-slate-900/70 p-3">
                                <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Teknisi</div>
                                <div class="mt-2 text-sm font-bold text-white"><?php echo e($ticketResult->teknisi?->name ?: 'Belum diassign'); ?></div>
                            </div>
                        </div>

                        <div class="mt-4 rounded-2xl border border-white/8 bg-slate-900/70 p-4">
                            <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Timeline Penanganan</div>
                            <div class="mt-4 space-y-3">
                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $ticketResult->proses->sortByDesc('id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proses): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="rounded-xl border border-white/10 bg-white/[0.04] p-3">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <div class="text-sm font-bold text-white"><?php echo e($proses->actor_name); ?></div>
                                                <div class="mt-1 text-[11px] uppercase tracking-[0.18em] text-cyan-200"><?php echo e($proses->tipe_update); ?></div>
                                            </div>
                                            <div class="text-xs text-slate-400"><?php echo e(optional($proses->tanggal_update)->format('d M Y')); ?></div>
                                        </div>
                                        <div class="mt-3 text-sm text-slate-200"><?php echo e($proses->keterangan_proses); ?></div>
                                        <!--[if BLOCK]><![endif]--><?php if($proses->kendala): ?>
                                            <div class="mt-2 text-xs text-amber-200">Kendala: <?php echo e($proses->kendala); ?></div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="rounded-xl border border-dashed border-white/15 bg-white/[0.03] px-4 py-6 text-sm text-slate-400">
                                        Belum ada progres teknis yang dicatat untuk tiket ini.
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    <?php elseif($ticketLookupAttempted): ?>
                        <div class="rounded-2xl border border-rose-400/20 bg-rose-400/10 p-4 text-sm leading-6 text-rose-200">
                            Tiket belum ditemukan. Coba cek lagi kode tiket yang Anda masukkan.
                        </div>
                    <?php else: ?>
                        <div class="grid gap-3">
                            <div class="flex items-center justify-between rounded-2xl border border-white/8 bg-slate-900/70 p-3">
                                <span class="text-sm text-slate-300">Format tiket</span>
                                <span class="font-mono text-xs text-cyan-200">NOC-YYYYMMDD-XXXX</span>
                            </div>
                            <div class="flex items-center justify-between rounded-2xl border border-white/8 bg-slate-900/70 p-3">
                                <span class="text-sm text-slate-300">Informasi tampil</span>
                                <span class="text-xs font-bold text-white">status, prioritas, teknisi, timeline</span>
                            </div>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
        </section>

        <section class="rounded-[2rem] border border-white/10 bg-white/[0.05] p-6 backdrop-blur-xl">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-[0.28em] text-cyan-200">Live Feed</div>
                    <h3 class="mt-2 font-['Georgia','Times_New_Roman',serif] text-3xl font-bold text-white">Tiket terbaru</h3>
                </div>
                <p class="max-w-xl text-sm leading-6 text-slate-300">
                    Ringkasan singkat aktivitas laporan terbaru.
                </p>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $recentReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex h-full flex-col rounded-[1.6rem] border border-white/10 bg-slate-950/55 p-4 transition hover:border-cyan-300/25 hover:bg-slate-900/70">
                        <div class="flex items-start justify-between gap-3">
                            <div class="font-mono text-[11px] font-bold tracking-[0.18em] text-cyan-200"><?php echo e($report->kode_tiket); ?></div>
                            <span class="rounded-full px-3 py-1 text-[11px] font-black
                                <?php if($report->status === 'Selesai'): ?> bg-emerald-300/15 text-emerald-200
                                <?php elseif($report->status === 'Proses'): ?> bg-amber-300/15 text-amber-200
                                <?php elseif($report->status === 'Diverifikasi'): ?> bg-sky-300/15 text-sky-200
                                <?php elseif($report->status === 'Menunggu'): ?> bg-orange-300/15 text-orange-200
                                <?php elseif($report->status === 'Ditolak'): ?> bg-slate-300/15 text-slate-200
                                <?php else: ?> bg-rose-300/15 text-rose-200
                                <?php endif; ?>">
                                <?php echo e($report->status); ?>

                            </span>
                        </div>

                        <div class="mt-4">
                            <h4 class="text-lg font-black text-white"><?php echo e($report->perangkat->nama_perangkat); ?></h4>
                            <div class="mt-2 text-sm leading-6 text-slate-300">
                                <?php echo e($report->perangkat->jenis); ?><br>
                                <?php echo e($report->perangkat->wilayah ?: '-'); ?><br>
                                <?php echo e($report->perangkat->lokasi); ?>

                            </div>
                        </div>

                        <div class="mt-5 grid gap-3 text-sm">
                            <div class="rounded-2xl border border-white/8 bg-white/[0.04] px-3 py-3">
                                <div class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Prioritas</div>
                                <div class="mt-2 font-bold text-white"><?php echo e($report->prioritas); ?></div>
                            </div>
                            <div class="rounded-2xl border border-white/8 bg-white/[0.04] px-3 py-3">
                                <div class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Teknisi</div>
                                <div class="mt-2 font-bold text-white"><?php echo e($report->teknisi?->name ?: 'Belum diassign'); ?></div>
                            </div>
                        </div>

                        <div class="mt-5 border-t border-white/8 pt-4 text-xs text-slate-400">
                            Dibuat <?php echo e(optional($report->tanggal)->format('d M Y') ?: '-'); ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full rounded-[1.7rem] border border-dashed border-white/15 bg-slate-950/35 px-6 py-14 text-center">
                        <h4 class="text-xl font-black text-white">Belum ada laporan terbaru</h4>
                        <p class="mt-2 text-sm leading-6 text-slate-400">Saat tiket pertama masuk, daftar aktivitas terbaru akan muncul di sini.</p>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </section>

        <footer class="pb-2 text-center text-sm text-slate-400">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.04] px-4 py-2">
                <span class="h-2 w-2 rounded-full bg-teal-300"></span>
                &copy; <?php echo e(date('Y')); ?> Network Operation Center
            </span>
        </footer>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/livewire/home-component.blade.php ENDPATH**/ ?>