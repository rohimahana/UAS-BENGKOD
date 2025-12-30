<x-layouts.landing title="Poliklinik Digital">
    <!-- Top bar -->
    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/80 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-2xl btn-brand shadow-sm ring-brand">
                    <i class="fa-solid fa-hospital"></i>
                </span>
                <div class="leading-tight">
                    <div class="text-sm font-semibold tracking-tight">Poliklinik Digital</div>
                    <div class="text-xs text-slate-500">Pendaftaran • Antrian • Pemeriksaan</div>
                </div>
            </a>

            <nav class="hidden items-center gap-6 text-sm text-slate-600 md:flex">
                <a href="#fitur" class="hover:text-slate-900">Fitur</a>
                <a href="#alur" class="hover:text-slate-900">Alur</a>
                <a href="#faq" class="hover:text-slate-900">FAQ</a>
            </nav>

            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : (Auth::user()->role === 'dokter' ? route('dokter.dashboard') : route('pasien.dashboard')) }}"
                        class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-xl px-4 py-2 text-sm font-semibold btn-brand">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="rounded-xl px-4 py-2 text-sm font-semibold btn-brand">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="relative overflow-hidden">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -left-32 -top-32 h-80 w-80 rounded-full bg-slate-200/70 blur-3xl"></div>
            <div class="absolute -right-32 top-20 h-80 w-80 rounded-full bg-slate-200/50 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/2 h-96 w-[42rem] -translate-x-1/2 rounded-full bg-white blur-3xl">
            </div>
        </div>

        <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 py-14 lg:grid-cols-2 lg:py-20">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold badge-brand">
                    <i class="fa-solid fa-shield-heart"></i>
                    Sistem terintegrasi untuk layanan poliklinik
                </div>

                <h1 class="mt-5 text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">
                    Lebih cepat daftar,
                    <span class="text-brand">lebih rapi</span> kelola pasien.
                </h1>

                <p class="mt-4 text-base leading-relaxed text-slate-600 sm:text-lg">
                    Kelola pendaftaran, jadwal dokter, antrian, pemeriksaan, hingga riwayat pasien dalam satu sistem yang
                    simpel dan mudah digunakan.
                </p>

                <div class="mt-7 flex flex-wrap items-center gap-3">
                    @auth
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : (Auth::user()->role === 'dokter' ? route('dokter.dashboard') : route('pasien.dashboard')) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold btn-brand shadow-sm ring-brand">
                        Buka Dashboard
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    @else
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold btn-brand shadow-sm ring-brand">
                        Mulai Daftar
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    @endauth
                    <a href="#alur"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                        Lihat Alur
                        <i class="fa-regular fa-circle-play text-xs"></i>
                    </a>
                </div>

                <div class="mt-8 grid grid-cols-3 gap-3 text-xs text-slate-600 sm:max-w-md">
                    <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                        <div class="font-semibold text-slate-900">Antrian tertata</div>
                        <div class="mt-1">Minim antre panjang</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                        <div class="font-semibold text-slate-900">Data aman</div>
                        <div class="mt-1">Role-based access</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                        <div class="font-semibold text-slate-900">Mudah dipakai</div>
                        <div class="mt-1">Tampilan bersih</div>
                    </div>
                </div>
            </div>

            <!-- Right side: preview card -->
            <div class="relative">
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span
                                class="grid h-10 w-10 place-items-center rounded-2xl bg-slate-100 text-slate-700 ring-brand">
                                <i class="fa-solid fa-stethoscope"></i>
                            </span>
                            <div>
                                <div class="text-sm font-semibold">Ringkasan Layanan</div>
                                <div class="text-xs text-slate-500">Contoh tampilan beranda</div>
                            </div>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold badge-brand">Online</span>
                    </div>

                    <div class="grid gap-4 p-6 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 p-4 hover:bg-slate-50">
                            <div class="flex items-center justify-between">
                                <div class="text-xs font-semibold text-slate-500">Pendaftaran</div>
                                <i class="fa-solid fa-user-plus text-slate-400"></i>
                            </div>
                            <div class="mt-2 text-sm font-semibold text-slate-900">Daftar pasien baru</div>
                            <div class="mt-1 text-xs text-slate-600">Input data & pilih poli</div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 p-4 hover:bg-slate-50">
                            <div class="flex items-center justify-between">
                                <div class="text-xs font-semibold text-slate-500">Jadwal</div>
                                <i class="fa-regular fa-calendar-days text-slate-400"></i>
                            </div>
                            <div class="mt-2 text-sm font-semibold text-slate-900">Atur jam praktik</div>
                            <div class="mt-1 text-xs text-slate-600">Lebih mudah koordinasi</div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 p-4 hover:bg-slate-50">
                            <div class="flex items-center justify-between">
                                <div class="text-xs font-semibold text-slate-500">Pemeriksaan</div>
                                <i class="fa-solid fa-notes-medical text-slate-400"></i>
                            </div>
                            <div class="mt-2 text-sm font-semibold text-slate-900">Catat diagnosa</div>
                            <div class="mt-1 text-xs text-slate-600">Tindakan & resep obat</div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 p-4 hover:bg-slate-50">
                            <div class="flex items-center justify-between">
                                <div class="text-xs font-semibold text-slate-500">Riwayat</div>
                                <i class="fa-solid fa-clock-rotate-left text-slate-400"></i>
                            </div>
                            <div class="mt-2 text-sm font-semibold text-slate-900">Lacak kunjungan</div>
                            <div class="mt-1 text-xs text-slate-600">Rekam medis terstruktur</div>
                        </div>
                    </div>

                    <div class="border-t border-slate-200 px-6 py-4">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="text-xs text-slate-600">
                                <span class="font-semibold text-slate-900">Tips:</span> tema warna sekarang pakai
                                <code class="rounded bg-slate-100 px-1.5 py-0.5 text-[11px]">Serenity × Rose Quartz</code>
                            </div>
                            <a href="{{{ route('login') }}}"
                                class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-semibold btn-brand shadow-sm ring-brand">
                                Masuk sebagai pengguna
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-3 gap-3 text-xs text-slate-600">
                    <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-user-doctor text-brand"></i>
                            <span class="font-semibold text-slate-900">Dokter</span>
                        </div>
                        <div class="mt-1">Jadwal & periksa</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-users text-brand"></i>
                            <span class="font-semibold text-slate-900">Pasien</span>
                        </div>
                        <div class="mt-1">Daftar & riwayat</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-sitemap text-brand"></i>
                            <span class="font-semibold text-slate-900">Admin</span>
                        </div>
                        <div class="mt-1">Kelola master</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Flow -->
    <section id="alur" class="mx-auto max-w-6xl px-4 py-12 lg:py-16">
        <div class="flex flex-col gap-2">
            <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Alur singkat penggunaan</h2>
            <p class="max-w-2xl text-sm leading-relaxed text-slate-600 sm:text-base">
                Dibuat agar pengguna cepat paham, terutama di loket pendaftaran dan ruang periksa.
            </p>
        </div>

        <div class="mt-8 grid gap-4 lg:grid-cols-3">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="grid h-12 w-12 place-items-center rounded-2xl btn-brand shadow-sm ring-brand">
                        <span class="text-sm font-bold">1</span>
                    </div>
                    <i class="fa-solid fa-id-card text-slate-400"></i>
                </div>
                <h3 class="mt-4 text-base font-semibold">Daftar / Login</h3>
                <p class="mt-1 text-sm text-slate-600">Pasien membuat akun, lalu masuk ke sistem.</p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="grid h-12 w-12 place-items-center rounded-2xl btn-brand shadow-sm ring-brand">
                        <span class="text-sm font-bold">2</span>
                    </div>
                    <i class="fa-solid fa-building-circle-check text-slate-400"></i>
                </div>
                <h3 class="mt-4 text-base font-semibold">Pilih Poli</h3>
                <p class="mt-1 text-sm text-slate-600">Pilih poli & jadwal yang tersedia untuk mengambil antrian.</p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="grid h-12 w-12 place-items-center rounded-2xl btn-brand shadow-sm ring-brand">
                        <span class="text-sm font-bold">3</span>
                    </div>
                    <i class="fa-solid fa-notes-medical text-slate-400"></i>
                </div>
                <h3 class="mt-4 text-base font-semibold">Pemeriksaan</h3>
                <p class="mt-1 text-sm text-slate-600">Dokter mengisi hasil pemeriksaan & resep, tersimpan ke riwayat.</p>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="fitur" class="border-t border-slate-200 bg-white">
        <div class="mx-auto max-w-6xl px-4 py-12 lg:py-16">
            <div class="flex flex-col gap-2">
                <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Fitur utama</h2>
                <p class="max-w-2xl text-sm leading-relaxed text-slate-600 sm:text-base">
                    Fokus ke hal yang sering dipakai sehari-hari — tampilan bersih, tombol jelas, dan alur ringkas.
                </p>
            </div>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <div class="flex items-center gap-3">
                        <span class="grid h-11 w-11 place-items-center rounded-2xl bg-white shadow-sm ring-brand">
                            <i class="fa-solid fa-user-doctor text-brand"></i>
                        </span>
                        <div class="text-base font-semibold">Manajemen Dokter</div>
                    </div>
                    <p class="mt-3 text-sm text-slate-600">Kelola profil dokter & jadwal praktik secara rapi.</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <div class="flex items-center gap-3">
                        <span class="grid h-11 w-11 place-items-center rounded-2xl bg-white shadow-sm ring-brand">
                            <i class="fa-solid fa-clipboard-list text-brand"></i>
                        </span>
                        <div class="text-base font-semibold">Antrian & Periksa</div>
                    </div>
                    <p class="mt-3 text-sm text-slate-600">Proses antrian, catatan pemeriksaan, dan resep obat.</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <div class="flex items-center gap-3">
                        <span class="grid h-11 w-11 place-items-center rounded-2xl bg-white shadow-sm ring-brand">
                            <i class="fa-solid fa-pills text-brand"></i>
                        </span>
                        <div class="text-base font-semibold">Stok Obat</div>
                    </div>
                    <p class="mt-3 text-sm text-slate-600">Pantau obat masuk/keluar agar stok tetap terkontrol.</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <div class="flex items-center gap-3">
                        <span class="grid h-11 w-11 place-items-center rounded-2xl bg-white shadow-sm ring-brand">
                            <i class="fa-solid fa-file-waveform text-brand"></i>
                        </span>
                        <div class="text-base font-semibold">Riwayat Pasien</div>
                    </div>
                    <p class="mt-3 text-sm text-slate-600">Riwayat kunjungan tersusun sehingga mudah dicari.</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <div class="flex items-center gap-3">
                        <span class="grid h-11 w-11 place-items-center rounded-2xl bg-white shadow-sm ring-brand">
                            <i class="fa-solid fa-lock text-brand"></i>
                        </span>
                        <div class="text-base font-semibold">Hak Akses</div>
                    </div>
                    <p class="mt-3 text-sm text-slate-600">Admin, dokter, dan pasien punya menu sesuai peran.</p>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <div class="flex items-center gap-3">
                        <span class="grid h-11 w-11 place-items-center rounded-2xl bg-white shadow-sm ring-brand">
                            <i class="fa-solid fa-gauge-high text-brand"></i>
                        </span>
                        <div class="text-base font-semibold">Performa Cepat</div>
                    </div>
                    <p class="mt-3 text-sm text-slate-600">UI ringan dan responsif (desktop & mobile).</p>
                </div>
            </div>

            <div class="mt-10 rounded-3xl p-8 text-white shadow-sm"
                style="background: linear-gradient(135deg, var(--brand-cta-1) 0%, var(--brand-cta-2) 100%);">
                <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
                    <div>
                        <h3 class="text-xl font-bold tracking-tight">Siap mencoba?</h3>
                        <p class="mt-2 text-sm text-white/85">Daftar sebagai pasien untuk mulai mengambil antrian.</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{{ route('register') }}}"
                            class="rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 hover:bg-white/90">
                            Daftar Sekarang
                        </a>
                        <a href="{{{ route('login') }}}"
                            class="rounded-2xl border border-white/30 bg-white/10 px-5 py-3 text-sm font-semibold text-white hover:bg-white/15">
                            Masuk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="mx-auto max-w-6xl px-4 py-12 lg:py-16">
        <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">FAQ</h2>

        <div class="mt-6 grid gap-4 lg:grid-cols-2">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="text-sm font-semibold">Saya pasien, mulai dari mana?</div>
                <div class="mt-2 text-sm text-slate-600">
                    Klik <span class="font-semibold">Daftar</span>, lengkapi data, lalu pilih poli untuk mengambil
                    antrian.
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="text-sm font-semibold">Saya dokter, aksesnya bagaimana?</div>
                <div class="mt-2 text-sm text-slate-600">
                    Login menggunakan akun dokter. Menu akan menyesuaikan role (jadwal, periksa, riwayat).
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="text-sm font-semibold">Tampilan ini bisa dikustom lagi?</div>
                <div class="mt-2 text-sm text-slate-600">
                    Bisa. Beranda ini dibuat modular, jadi kamu bisa tambah section (testimoni, lokasi, kontak) tanpa
                    ngubah halaman internal.
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="text-sm font-semibold">Mobile-friendly?</div>
                <div class="mt-2 text-sm text-slate-600">
                    Iya. Grid responsif, spacing lega, tombol besar, dan teks nyaman dibaca.
                </div>
            </div>
        </div>
    </section>

    <footer class="border-t border-slate-200 bg-white">
        <div
            class="mx-auto flex max-w-6xl flex-col gap-3 px-4 py-8 text-sm text-slate-600 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-hospital text-brand"></i>
                <span>© {{ date('Y') }} Poliklinik Digital</span>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <a href="#fitur" class="hover:text-slate-900">Fitur</a>
                <a href="#alur" class="hover:text-slate-900">Alur</a>
                <a href="{{{ route('login') }}}" class="hover:text-slate-900">Masuk</a>
            </div>
        </div>
    </footer>
</x-layouts.landing>
