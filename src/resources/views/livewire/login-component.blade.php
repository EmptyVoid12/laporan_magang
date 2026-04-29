<div class="relative min-h-screen overflow-hidden bg-[#07111d] px-5 py-8 text-slate-100 sm:px-6 lg:px-10">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(45,212,191,0.18),_transparent_28%),radial-gradient(circle_at_bottom_right,_rgba(56,189,248,0.14),_transparent_26%),linear-gradient(180deg,_#07111d_0%,_#0b1728_55%,_#08111f_100%)]"></div>
    </div>

    <div class="relative z-10 mx-auto flex min-h-[calc(100vh-4rem)] max-w-md items-center">
        <section class="w-full rounded-[2rem] border border-white/10 bg-slate-950/75 p-7 shadow-[0_35px_120px_rgba(2,8,23,0.55)] backdrop-blur-xl sm:p-8">
            <h1 class="text-3xl font-black text-white">Login</h1>

            @if (session()->has('error'))
                <div class="mt-6 rounded-2xl border border-rose-400/20 bg-rose-400/10 px-4 py-3 text-sm text-rose-200" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="login" class="mt-6 space-y-5">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-200" for="email">Email</label>
                    <input
                        wire:model="email"
                        class="w-full rounded-2xl border border-white/10 bg-white/[0.05] px-4 py-3.5 text-slate-100 placeholder:text-slate-500 focus:border-cyan-300/40 focus:outline-none @error('email') border-rose-400/40 @enderror"
                        id="email"
                        type="email"
                        placeholder="email@example.com"
                    >
                    @error('email')
                        <span class="mt-2 block text-xs text-rose-300">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-200" for="password">Password</label>
                    <input
                        wire:model="password"
                        class="w-full rounded-2xl border border-white/10 bg-white/[0.05] px-4 py-3.5 text-slate-100 placeholder:text-slate-500 focus:border-cyan-300/40 focus:outline-none @error('password') border-rose-400/40 @enderror"
                        id="password"
                        type="password"
                        placeholder="Masukkan password"
                    >
                    @error('password')
                        <span class="mt-2 block text-xs text-rose-300">{{ $message }}</span>
                    @enderror
                    <div class="mt-3 flex justify-end">
                        <a class="text-xs font-semibold text-cyan-200 hover:text-cyan-100" href="{{ route('password.request') }}">
                            Lupa Password?
                        </a>
                    </div>
                </div>

                <button class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-teal-300 to-cyan-300 px-5 py-3.5 text-sm font-black text-slate-950 transition hover:-translate-y-0.5 hover:shadow-[0_18px_40px_rgba(45,212,191,0.32)]" type="submit">
                    Login
                    <span>+</span>
                </button>
            </form>

            <div class="mt-5 text-center text-sm text-slate-300">
                <a href="{{ route('register') }}" class="font-semibold text-cyan-200 hover:text-cyan-100">Daftar akun baru</a>
            </div>
            <div class="mt-6 border-t border-white/10 pt-4 text-center text-sm">
                <a href="{{ route('home') }}" class="font-medium text-slate-400 hover:text-white">Kembali ke beranda</a>
            </div>
        </section>
    </div>
</div>
