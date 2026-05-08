<div class="relative min-h-screen overflow-hidden bg-slate-50 px-5 py-8 text-slate-700 sm:px-6 lg:px-10">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(56,189,248,0.08),_transparent_28%),radial-gradient(circle_at_bottom_right,_rgba(99,102,241,0.08),_transparent_26%),linear-gradient(180deg,_#f8fafc_0%,_#f1f5f9_55%,_#e2e8f0_100%)]"></div>
        <div class="absolute inset-0 opacity-40" style="background-image: linear-gradient(rgba(148,163,184,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(148,163,184,0.1) 1px, transparent 1px); background-size: 28px 28px;"></div>
    </div>

    <div class="relative z-10 mx-auto flex min-h-[calc(100vh-4rem)] max-w-3xl items-center">
        <section class="w-full rounded-2xl border border-slate-200 bg-white/80 p-7 shadow-sm backdrop-blur-xl sm:p-8">
            <div class="text-[11px] font-bold uppercase tracking-[0.28em] text-indigo-600">Password Recovery</div>
            <h1 class="mt-3 text-3xl font-black text-slate-900 sm:text-4xl">Lupa password?</h1>
            <p class="mt-3 text-sm leading-6 text-slate-500">Masukkan email akun Anda. Kami akan mengirimkan link untuk membuat password baru.</p>

            @if($statusMessage)
                <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ $statusMessage }}
                </div>
            @endif

            @if(session()->has('error'))
                <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="sendResetLink" class="mt-6 space-y-5">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Alamat Email</label>
                    <input type="email" wire:model="email" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-900 placeholder:text-slate-400 focus:border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-100" placeholder="email@example.com" required autofocus>
                    @error('email') <span class="mt-2 block text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3.5 text-sm font-black text-white transition duration-300 hover:-translate-y-0.5 hover:shadow-md" wire:loading.attr="disabled">
                    <span wire:loading.remove>Kirim Link Reset</span>
                    <span wire:loading>Memproses...</span>
                </button>
            </form>

            <div class="mt-6 flex flex-wrap gap-3 text-sm">
                <a href="{{ route('login') }}" class="inline-flex items-center rounded-xl bg-white border border-slate-200 px-4 py-2.5 font-black text-slate-900 transition hover:bg-slate-50 shadow-sm">
                    Kembali ke Login
                </a>
                <a href="{{ route('home') }}" class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 font-semibold text-slate-700 transition hover:bg-slate-50 shadow-sm">
                    Ke beranda
                </a>
            </div>
        </section>
    </div>
</div>
