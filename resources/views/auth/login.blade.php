<x-layouts.auth title="Masuk">
    <div class="mx-auto w-full max-w-5xl">
        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Left: brand panel -->
            <div class="hidden overflow-hidden rounded-3xl border border-slate-200 bg-white/70 p-8 shadow-sm backdrop-blur lg:block">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-2xl btn-brand ring-brand shadow-sm">
                        <i class="fa-solid fa-hospital"></i>
                    </span>
                    <div>
                        <div class="text-sm font-bold tracking-tight text-slate-900">Poliklinik</div>
                        <div class="text-xs text-slate-500">Pendaftaran • Antrian • Pemeriksaan</div>
                    </div>
                </a>

                <h1 class="mt-10 text-3xl font-bold tracking-tight text-slate-900">
                    Selamat datang kembali.
                </h1>
                <p class="mt-3 text-sm leading-relaxed text-slate-600">
                    Masuk untuk mengelola layanan sesuai peran Anda (Admin, Dokter, atau Pasien).
                </p>

                <div class="mt-8 grid gap-3">
                    <div class="rounded-2xl border border-slate-200 bg-white p-4">
                        <div class="flex items-center gap-3">
                            <div class="grid h-10 w-10 place-items-center rounded-2xl bg-slate-100 text-slate-700">
                                <i class="fa-solid fa-shield-heart"></i>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-slate-900">Aman & rapi</div>
                                <div class="text-xs text-slate-500">Hak akses sesuai role</div>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-4">
                        <div class="flex items-center gap-3">
                            <div class="grid h-10 w-10 place-items-center rounded-2xl bg-slate-100 text-slate-700">
                                <i class="fa-solid fa-gauge-high"></i>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-slate-900">Cepat dipakai</div>
                                <div class="text-xs text-slate-500">UI bersih, tombol jelas</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 text-xs text-slate-500">
                    Kembali ke <a href="{{ route('home') }}" class="font-semibold text-slate-700 hover:text-slate-900">landing page</a>.
                </div>
            </div>

            <!-- Right: form -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-slate-900">Masuk</h2>
                        <p class="mt-1 text-sm text-slate-600">Gunakan email & password yang terdaftar.</p>
                    </div>
                    <a href="{{ route('home') }}" class="grid h-10 w-10 place-items-center rounded-2xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50" title="Kembali">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>

                @if ($errors->any())
                    <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                        <div class="font-semibold">Login gagal</div>
                        <div class="mt-1">{{ $errors->first() }}</div>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="text-sm font-semibold text-slate-700">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none ring-brand">
                    </div>

                    <div>
                        <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
                        <input type="password" id="password" name="password" required
                            class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none ring-brand">
                    </div>

                    <button type="submit" class="w-full rounded-2xl px-5 py-3 text-sm font-semibold btn-brand ring-brand shadow-sm">
                        Masuk
                        <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                    </button>

                    <div class="text-center text-sm text-slate-600">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-semibold text-brand hover:underline">Daftar pasien</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.auth>
