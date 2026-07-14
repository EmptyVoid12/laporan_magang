<div class="relative min-h-screen overflow-hidden bg-slate-50 text-slate-700 dark:bg-slate-950 dark:text-slate-300">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.08),_transparent_26%),radial-gradient(circle_at_top_right,_rgba(56,189,248,0.08),_transparent_22%),linear-gradient(180deg,_#f8fafc_0%,_#f1f5f9_100%)] dark:bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.03),_transparent_26%),radial-gradient(circle_at_top_right,_rgba(56,189,248,0.03),_transparent_22%),linear-gradient(180deg,_#090d16_0%,_#0f172a_100%)]"></div>
        <div class="absolute inset-0 opacity-40 dark:opacity-20" style="background-image: linear-gradient(rgba(148,163,184,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(148,163,184,0.1) 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="absolute -left-20 top-20 h-72 w-72 rounded-full bg-indigo-400/10 dark:bg-indigo-500/5 blur-3xl"></div>
        <div class="absolute right-0 top-0 h-80 w-80 rounded-full bg-sky-400/10 dark:bg-sky-500/5 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto flex w-full max-w-7xl flex-col gap-6 px-5 py-5 sm:px-6 lg:px-10 lg:py-8">
        <header class="rounded-2xl border border-slate-200 bg-white/80 px-5 py-4 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/80">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-4">
                    <img src="<?php echo e(asset('images/logo-dishub.png')); ?>" alt="Logo Dinas Perhubungan" class="h-12 w-auto object-contain" />
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-indigo-600 dark:text-indigo-400">Dinas Perhubungan DKI Jakarta</p>
                        <h1 class="mt-1 text-base font-black text-slate-900 sm:text-lg dark:text-white">Laporan Gangguan Perangkat</h1>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    
                    <button class="theme-toggle-btn inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-300" aria-label="Toggle dark mode">
                        <svg class="theme-toggle-dark-icon hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                        </svg>
                        <svg class="theme-toggle-light-icon hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                        </svg>
                    </button>

                    <?php ($portalActor = Auth::guard('web')->user()); ?>
                    <?php ($portalActor = $portalActor && in_array($portalActor->role, ['user', 'teknisi'], true) ? $portalActor : null); ?>
                    <!--[if BLOCK]><![endif]--><?php if($portalActor): ?>
                        <!--[if BLOCK]><![endif]--><?php if($portalActor->role === 'teknisi'): ?>
                            <a href="<?php echo e(route('teknisi.task')); ?>" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 shadow-sm">
                                Daftar Tugas
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(route('user.gangguan')); ?>" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 shadow-sm">
                                Buat Laporan
                            </a>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="inline-flex items-center justify-center rounded-lg bg-white border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-700">
                            Login
                        </a>
                        <a href="<?php echo e(route('register')); ?>" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 shadow-sm">
                            Daftar Baru
                        </a>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </header>

        <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            <!-- Card 1: Total Laporan -->
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 group dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <div class="text-[10px] font-bold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">Total Laporan</div>
                    <div class="text-slate-400 group-hover:text-indigo-500 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <div class="mt-4 text-4xl font-black text-slate-900 dark:text-white"><?php echo e($total); ?></div>
                <div class="mt-2 text-[10px] text-slate-400 dark:text-slate-500 font-medium">Akumulasi aduan masuk</div>
            </div>

            <!-- Card 2: Belum Ditangani -->
            <div class="rounded-2xl border border-slate-200 border-l-4 border-l-red-500 bg-white p-5 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 group hover:border-red-300 dark:border-slate-800 dark:border-l-red-500 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <div class="text-[10px] font-bold uppercase tracking-[0.24em] text-red-500 dark:text-red-400">Belum Ditangani</div>
                    <div class="text-red-400 group-hover:text-red-600 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
                <div class="mt-4 text-4xl font-black text-slate-900 dark:text-white"><?php echo e($open); ?></div>
                <div class="mt-2 text-[10px] text-slate-400 dark:text-slate-500 font-medium">Menunggu respon admin</div>
            </div>

            <!-- Card 3: Dalam Penanganan -->
            <div class="rounded-2xl border border-slate-200 border-l-4 border-l-sky-500 bg-white p-5 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 group hover:border-sky-300 dark:border-slate-800 dark:border-l-sky-500 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <div class="text-[10px] font-bold uppercase tracking-[0.24em] text-sky-500 dark:text-sky-400">Dalam Penanganan</div>
                    <div class="text-sky-400 group-hover:text-sky-600 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>
                <div class="mt-4 text-4xl font-black text-slate-900 dark:text-white"><?php echo e($proses); ?></div>
                <div class="mt-2 text-[10px] text-slate-400 dark:text-slate-500 font-medium">Pekerjaan aktif teknisi</div>
            </div>

            <!-- Card 4: Tertunda -->
            <div class="rounded-2xl border border-slate-200 border-l-4 border-l-orange-500 bg-white p-5 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 group hover:border-orange-300 dark:border-slate-800 dark:border-l-orange-500 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <div class="text-[10px] font-bold uppercase tracking-[0.24em] text-orange-500 dark:text-orange-400">Tertunda</div>
                    <div class="text-orange-400 group-hover:text-orange-600 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="mt-4 text-4xl font-black text-slate-900 dark:text-white"><?php echo e($menunggu); ?></div>
                <div class="mt-2 text-[10px] text-slate-400 dark:text-slate-500 font-medium">Penanganan ditunda</div>
            </div>

            <!-- Card 5: Menunggu Verifikasi -->
            <div class="rounded-2xl border border-slate-200 border-l-4 border-l-yellow-500 bg-white p-5 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 group hover:border-yellow-300 dark:border-slate-800 dark:border-l-yellow-500 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <div class="text-[10px] font-bold uppercase tracking-[0.24em] text-yellow-500 dark:text-yellow-400">Menunggu Verifikasi</div>
                    <div class="text-yellow-400 group-hover:text-yellow-600 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                </div>
                <div class="mt-4 text-4xl font-black text-slate-900 dark:text-white"><?php echo e($selesai); ?></div>
                <div class="mt-2 text-[10px] text-slate-400 dark:text-slate-500 font-medium">Menunggu validasi admin</div>
            </div>

            <!-- Card 6: Selesai Terverifikasi -->
            <div class="rounded-2xl border border-slate-200 border-l-4 border-l-emerald-500 bg-white p-5 shadow-sm transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 group hover:border-emerald-300 dark:border-slate-800 dark:border-l-emerald-500 dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <div class="text-[10px] font-bold uppercase tracking-[0.24em] text-emerald-500 dark:text-emerald-400">Selesai Terverifikasi</div>
                    <div class="text-emerald-400 group-hover:text-emerald-600 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                </div>
                <div class="mt-4 text-4xl font-black text-slate-900 dark:text-white"><?php echo e($diverifikasi); ?></div>
                <div class="mt-2 text-[10px] text-slate-400 dark:text-slate-500 font-medium">Terselesaikan sepenuhnya</div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-[0.28em] text-indigo-600 dark:text-indigo-400">Ticket Lookup</div>
                        <h3 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">Cek status tiket</h3>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.85-4.65a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/>
                        </svg>
                    </div>
                </div>

                <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">
                    Masukkan kode tiket untuk melihat progres penanganan terbaru.
                </p>

                <form wire:submit.prevent="searchTicket" class="mt-5 space-y-4">
                    <div>
                        <label class="mb-2 block text-[11px] font-bold uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">Kode Tiket</label>
                        <div class="relative">
                            <input
                                type="text"
                                wire:model="ticketCode"
                                placeholder="NOC-20260421-0001"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 pr-12 text-sm font-semibold tracking-[0.05em] text-slate-900 placeholder:text-slate-400 focus:border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-100 dark:border-slate-800 dark:bg-slate-950 dark:text-white dark:focus:ring-indigo-950/50"
                            >
                            <span class="absolute inset-y-0 right-4 flex items-center text-slate-400">#</span>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['ticketCode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="mt-2 block text-xs text-red-500"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3.5 text-sm font-bold text-white transition hover:bg-indigo-700 shadow-sm">
                        Cek status
                        <span>+</span>
                    </button>
                </form>

                <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/50">
                    <!--[if BLOCK]><![endif]--><?php if($ticketResult): ?>
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <div class="text-[11px] font-bold uppercase tracking-[0.26em] text-indigo-600 dark:text-indigo-400"><?php echo e($ticketResult->kode_tiket); ?></div>
                                <div class="mt-2 text-xl font-bold text-slate-900 dark:text-white"><?php echo e($ticketResult->perangkat->nama_perangkat); ?></div>
                                <div class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                                    <?php echo e($ticketResult->perangkat->jenis); ?> • <?php echo e($ticketResult->perangkat->wilayah ?: '-'); ?> • <?php echo e($ticketResult->perangkat->lokasi); ?>

                                </div>
                            </div>
                            <div class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-450">
                                <?php echo e($ticketResult->workflow_status_label); ?>

                            </div>
                        </div>

                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                                <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Prioritas</div>
                                <div class="mt-2 text-sm font-bold text-slate-900 dark:text-white"><?php echo e($ticketResult->prioritas); ?></div>
                            </div>
                            <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                                <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Teknisi</div>
                                <div class="mt-2 text-sm font-bold text-slate-900 dark:text-white"><?php echo e($ticketResult->teknisi?->name ?: 'Belum diassign'); ?></div>
                            </div>
                        </div>

                        <div class="mt-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Timeline Penanganan</div>
                            <div class="mt-4 space-y-3">
                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $ticketResult->proses->sortByDesc('id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proses): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-3 dark:border-slate-800 dark:bg-slate-950">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <div class="text-sm font-bold text-slate-900 dark:text-white"><?php echo e($proses->actor_name); ?></div>
                                                <div class="mt-1 text-[11px] uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-400"><?php echo e($proses->tipe_update); ?></div>
                                            </div>
                                            <div class="text-xs text-slate-400 dark:text-slate-500"><?php echo e(optional($proses->tanggal_update)->format('d M Y')); ?></div>
                                        </div>
                                        <div class="mt-3 text-sm text-slate-600 dark:text-slate-350"><?php echo e($proses->keterangan_proses); ?></div>
                                        <!--[if BLOCK]><![endif]--><?php if($proses->kendala): ?>
                                            <div class="mt-2 text-xs text-amber-600 dark:text-amber-450">Kendala: <?php echo e($proses->kendala); ?></div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-sm text-slate-500 text-center dark:border-slate-700 dark:bg-slate-950 dark:text-slate-400">
                                        Belum ada progres teknis yang dicatat untuk tiket ini.
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    <?php elseif($ticketLookupAttempted): ?>
                        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm leading-6 text-red-600 dark:border-red-950/40 dark:bg-red-950/20 dark:text-red-400">
                            Tiket belum ditemukan. Coba cek lagi kode tiket yang Anda masukkan.
                        </div>
                    <?php else: ?>
                        <div class="grid gap-3">
                            <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white p-3 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                                <span class="text-sm text-slate-500 dark:text-slate-400">Format tiket</span>
                                <span class="font-mono text-xs font-bold text-indigo-600 dark:text-indigo-400">NOC-YYYYMMDD-XXXX</span>
                            </div>
                            <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white p-3 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                                <span class="text-sm text-slate-500 dark:text-slate-400">Informasi tampil</span>
                                <span class="text-xs font-bold text-slate-900 dark:text-white">status, prioritas, teknisi, timeline</span>
                            </div>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-[0.28em] text-indigo-600 dark:text-indigo-400">Live Feed</div>
                    <h3 class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">Tiket terbaru</h3>
                </div>
                <p class="max-w-xl text-sm leading-6 text-slate-500 dark:text-slate-400">
                    Ringkasan singkat aktivitas laporan terbaru.
                </p>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $recentReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex h-full flex-col rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-indigo-300/50 hover:bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950/50 dark:hover:bg-slate-900">
                        <div class="flex items-start justify-between gap-3">
                            <div class="font-mono text-[11px] font-bold tracking-[0.18em] text-indigo-600"><?php echo e($report->kode_tiket); ?></div>
                            <span class="rounded-full px-3 py-1 text-[11px] font-bold
                                <?php if($report->isFinallyVerified()): ?> bg-emerald-100 text-emerald-700
                                <?php elseif($report->isAwaitingFinalVerification()): ?> bg-lime-100 text-lime-700
                                <?php elseif($report->status === 'Selesai'): ?> bg-green-100 text-green-700
                                <?php elseif($report->status === 'Proses'): ?> bg-sky-100 text-sky-700
                                <?php elseif($report->status === 'Diterima'): ?> bg-indigo-100 text-indigo-700
                                <?php elseif($report->status === 'Menunggu'): ?> bg-orange-100 text-orange-700
                                <?php elseif($report->status === 'Ditolak'): ?> bg-slate-200 text-slate-600
                                <?php else: ?> bg-red-100 text-red-700
                                <?php endif; ?>">
                                <?php echo e($report->workflow_status_label); ?>

                            </span>
                        </div>

                        <div class="mt-4">
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white"><?php echo e($report->perangkat->nama_perangkat); ?></h4>
                            <div class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                                <?php echo e($report->perangkat->jenis); ?><br>
                                <?php echo e($report->perangkat->wilayah ?: '-'); ?><br>
                                <?php echo e($report->perangkat->lokasi); ?>

                            </div>
                        </div>

                        <div class="mt-5 grid gap-3 text-sm">
                            <div class="rounded-xl border border-slate-200 bg-white px-3 py-3 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                                <div class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">Prioritas</div>
                                <div class="mt-2 font-bold text-slate-900 dark:text-white"><?php echo e($report->prioritas); ?></div>
                            </div>
                            <div class="rounded-xl border border-slate-200 bg-white px-3 py-3 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                                <div class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">Teknisi</div>
                                <div class="mt-2 font-bold text-slate-900 dark:text-white"><?php echo e($report->teknisi?->name ?: 'Belum diassign'); ?></div>
                            </div>
                        </div>

                        <div class="mt-5 border-t border-slate-200 pt-4 text-xs text-slate-400 dark:border-slate-800 dark:text-slate-500">
                            Dibuat <?php echo e(optional($report->tanggal)->format('d M Y') ?: '-'); ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full rounded-xl border border-dashed border-slate-300 bg-slate-50 px-6 py-14 text-center">
                        <h4 class="text-xl font-bold text-slate-900">Belum ada laporan terbaru</h4>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Saat tiket pertama masuk, daftar aktivitas terbaru akan muncul di sini.</p>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </section>

        <footer class="pb-2 text-center text-sm text-slate-500 dark:text-slate-400">
            <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                &copy; <?php echo e(date('Y')); ?> Dinas Perhubungan DKI Jakarta
            </span>
        </footer>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/livewire/home-component.blade.php ENDPATH**/ ?>