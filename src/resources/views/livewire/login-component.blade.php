<div class="relative min-h-screen overflow-hidden bg-slate-50 px-5 py-8 text-slate-700 sm:px-6 lg:px-10">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.08),_transparent_28%),radial-gradient(circle_at_bottom_right,_rgba(56,189,248,0.08),_transparent_26%),linear-gradient(180deg,_#f8fafc_0%,_#f1f5f9_55%,_#e2e8f0_100%)]"></div>
    </div>

    <div class="relative z-10 mx-auto flex min-h-[calc(100vh-4rem)] max-w-md items-center">
        <section class="w-full rounded-2xl border border-slate-200 bg-white/80 p-7 shadow-sm backdrop-blur-xl sm:p-8">
            <h1 class="text-3xl font-black text-slate-900">Login</h1>

            @if (session()->has('error'))
                <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="login" class="mt-6 space-y-5">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700" for="email">Email</label>
                    <input
                        wire:model="email"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-900 placeholder:text-slate-400 focus:border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-100 @error('email') border-red-300 @enderror"
                        id="email"
                        type="email"
                        placeholder="email@example.com"
                    >
                    @error('email')
                        <span class="mt-2 block text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700" for="password">Password</label>
                    <input
                        wire:model="password"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-900 placeholder:text-slate-400 focus:border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-100 @error('password') border-red-300 @enderror"
                        id="password"
                        type="password"
                        placeholder="Masukkan password"
                    >
                    @error('password')
                        <span class="mt-2 block text-xs text-red-500">{{ $message }}</span>
                    @enderror
                    <div class="mt-3 flex justify-end">
                        <a class="text-xs font-semibold text-indigo-600 hover:text-indigo-700" href="{{ route('password.request') }}">
                            Lupa Password?
                        </a>
                    </div>
                </div>

                <button class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3.5 text-sm font-black text-white transition hover:-translate-y-0.5 hover:shadow-md" type="submit">
                    Login
                    <span>+</span>
                </button>
            </form>

            <div class="mt-5 text-center text-sm text-slate-500">
                <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700">Daftar akun baru</a>
            </div>
            <div class="mt-6 border-t border-slate-200 pt-4 text-center text-sm">
                <a href="{{ route('home') }}" class="font-medium text-slate-400 hover:text-slate-600">Kembali ke beranda</a>
            </div>
        </section>
    </div>
</div>
