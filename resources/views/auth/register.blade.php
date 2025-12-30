<x-layouts.auth title="Daftar Pasien">
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
                    Buat akun pasien.
                </h1>
                <p class="mt-3 text-sm leading-relaxed text-slate-600">
                    Setelah daftar, Anda bisa langsung mengambil antrian poli dan melihat riwayat pemeriksaan.
                </p>

                <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-6">
                    <div class="flex items-start gap-3">
                        <div class="grid h-10 w-10 place-items-center rounded-2xl bg-slate-100 text-slate-700">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-slate-900">Catatan</div>
                            <ul class="mt-2 space-y-1 text-xs text-slate-600">
                                <li>• Nomor KTP harus 16 digit.</li>
                                <li>• Password minimal 8 karakter.</li>
                                <li>• Setelah daftar, sistem membuat No. RM otomatis.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: form card -->
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-5">
                    <div class="text-base font-bold tracking-tight text-slate-900">Daftar Pasien</div>
                    <div class="mt-1 text-sm text-slate-600">Isi data diri untuk membuat akun.</div>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4 px-6 py-6">
                    @csrf

                    @if ($errors->any())
                        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                            <div class="font-semibold">Periksa lagi input Anda:</div>
                            <ul class="mt-2 list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="nama" class="text-sm font-semibold text-slate-700">Nama lengkap</label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                                class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none ring-brand"
                                placeholder="Contoh: Rohima Choirul Hana">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="alamat" class="text-sm font-semibold text-slate-700">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="3" required
                                class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none ring-brand"
                                placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
                        </div>

                        <div>
                            <label for="no_ktp" class="text-sm font-semibold text-slate-700">No. KTP</label>
                            <input type="text" id="no_ktp" name="no_ktp" value="{{ old('no_ktp') }}" required maxlength="16"
                                class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none ring-brand"
                                placeholder="16 digit">
                        </div>

                        <div>
                            <label for="no_hp" class="text-sm font-semibold text-slate-700">No. HP</label>
                            <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required
                                class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none ring-brand"
                                placeholder="08xxxxxxxxxx">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="email" class="text-sm font-semibold text-slate-700">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none ring-brand"
                                placeholder="nama@email.com">
                        </div>

                        <div>
                            <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
                            <input type="password" id="password" name="password" required
                                class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none ring-brand">
                        </div>

                        <div>
                            <label for="password_confirmation" class="text-sm font-semibold text-slate-700">Konfirmasi password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="mt-1 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-300 focus:outline-none ring-brand">
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-2xl px-5 py-3 text-sm font-semibold btn-brand ring-brand shadow-sm">
                        Buat Akun
                        <i class="fa-solid fa-user-plus ml-2 text-xs"></i>
                    </button>

                    <div class="text-center text-sm text-slate-600">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-semibold text-brand hover:underline">Masuk</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-6 text-center text-xs text-slate-500">
            Dengan mendaftar, Anda menyetujui penggunaan data untuk keperluan layanan poliklinik.
        </div>
    </div>
</x-layouts.auth>
