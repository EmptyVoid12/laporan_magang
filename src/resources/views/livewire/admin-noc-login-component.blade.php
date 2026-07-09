<div class="min-h-screen flex items-center justify-center bg-slate-950 px-4 relative overflow-hidden">
    {{-- Background Decorative Gradients --}}
    <div class="absolute top-0 -left-4 w-96 h-96 bg-indigo-900/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 -right-4 w-96 h-96 bg-violet-900/20 rounded-full blur-3xl"></div>

    <div class="w-full max-w-md relative z-10">
        {{-- Logo & Brand --}}
        <div class="text-center mb-8">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 font-extrabold text-white text-xl shadow-lg shadow-indigo-600/30">
                N
            </div>
            <h2 class="mt-4 text-2xl font-black text-white tracking-tight">NOC Portal Login</h2>
            <p class="mt-1.5 text-sm text-slate-400">Pusat Operasi Jaringan & Pemantauan Perangkat</p>
        </div>

        {{-- Login Card --}}
        <div class="rounded-2xl border border-slate-800 bg-slate-900/50 backdrop-blur-xl p-6 shadow-2xl">
            @if (session()->has('error'))
                <div class="mb-4 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="login" class="space-y-4">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-400">Alamat Email Administrator</label>
                    <input type="email" wire:model="email" class="w-full rounded-lg border border-slate-800 bg-slate-950 px-3.5 py-2.5 text-sm text-white placeholder-slate-600 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="admin@noc.com">
                    @error('email') <span class="text-xs text-red-400 mt-1 block font-medium">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-400">Kata Sandi</label>
                    <input type="password" wire:model="password" class="w-full rounded-lg border border-slate-800 bg-slate-950 px-3.5 py-2.5 text-sm text-white placeholder-slate-600 outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="••••••••">
                    @error('password') <span class="text-xs text-red-400 mt-1 block font-medium">{{ $message }}</span>@enderror
                </div>

                <button type="submit" class="w-full rounded-lg bg-indigo-600 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-600/20 transition hover:bg-indigo-700 outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-950" wire:loading.attr="disabled">
                    <span wire:loading.remove>Masuk ke Dashboard</span>
                    <span wire:loading>Memverifikasi...</span>
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <p class="mt-6 text-center text-xs text-slate-500">
            &copy; 2026 NOC Operation. All rights reserved.
        </p>
    </div>
</div>
