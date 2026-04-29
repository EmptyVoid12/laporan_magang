<div class="relative min-h-screen overflow-hidden bg-[#07111d] px-5 py-8 text-slate-100 sm:px-6 lg:px-10">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(56,189,248,0.16),_transparent_28%),radial-gradient(circle_at_bottom_right,_rgba(45,212,191,0.12),_transparent_26%),linear-gradient(180deg,_#07111d_0%,_#0b1728_55%,_#08111f_100%)]"></div>
        <div class="absolute inset-0 opacity-30" style="background-image: linear-gradient(rgba(148,163,184,0.08) 1px, transparent 1px), linear-gradient(90deg, rgba(148,163,184,0.08) 1px, transparent 1px); background-size: 28px 28px;"></div>
    </div>

    <div class="relative z-10 mx-auto flex min-h-[calc(100vh-4rem)] max-w-3xl items-center">
        <section class="w-full rounded-[2.2rem] border border-white/10 bg-slate-950/75 p-7 shadow-[0_35px_120px_rgba(2,8,23,0.55)] backdrop-blur-xl sm:p-8">
            <div class="text-[11px] font-bold uppercase tracking-[0.28em] text-cyan-200">Password Recovery</div>
            <h1 class="mt-3 text-3xl font-black text-white sm:text-4xl">Lupa password?</h1>
            <p class="mt-3 text-sm leading-6 text-slate-300">Masukkan email akun Anda. Kami akan mengirimkan link untuk membuat password baru.</p>

            @if($statusMessage)
                <div class="mt-6 rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-200">
                    {{ $statusMessage }}
                </div>
            @endif

            @if(session()->has('error'))
                <div class="mt-6 rounded-2xl border border-rose-400/20 bg-rose-400/10 px-4 py-3 text-sm text-rose-200">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="sendResetLink" class="mt-6 space-y-5">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-200">Alamat Email</label>
                    <input type="email" wire:model="email" class="w-full rounded-2xl border border-white/10 bg-white/[0.05] px-4 py-3.5 text-slate-100 placeholder:text-slate-500 focus:border-cyan-300/40 focus:outline-none" placeholder="email@example.com" required autofocus>
                    @error('email') <span class="mt-2 block text-xs text-rose-300">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-cyan-300 to-sky-400 px-5 py-3.5 text-sm font-black text-slate-950 transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_18px_40px_rgba(56,189,248,0.28)]" wire:loading.attr="disabled">
                    <span wire:loading.remove>Kirim Link Reset</span>
                    <span wire:loading>Memproses...</span>
                </button>
            </form>

            <div class="mt-6 flex flex-wrap gap-3 text-sm">
                <a href="{{ route('login') }}" class="inline-flex items-center rounded-full bg-white px-4 py-2.5 font-black text-slate-950 transition hover:bg-cyan-100">
                    Kembali ke Login
                </a>
                <a href="{{ route('home') }}" class="inline-flex items-center rounded-full border border-white/12 bg-white/5 px-4 py-2.5 font-semibold text-white transition hover:bg-white/10">
                    Ke beranda
                </a>
            </div>
        </section>
    </div>
</div>
