<x-layouts.app title="Dashboard Admin - Poliklinik">
    @php
        $user = Auth::user();

        // Prefer data dari controller (DashboardAdminController), fallback jika belum ada
        $totalPoli = $totalPoli ?? \App\Models\Poli::count();
        $totalDokter = $totalDokter ?? \App\Models\User::where('role', 'dokter')->count();
        $totalPasien = $totalPasien ?? \App\Models\User::where('role', 'pasien')->count();
        $totalObat = $totalObat ?? \App\Models\Obat::count();

        $aktiveDokter = $aktiveDokter ?? \App\Models\User::where('role', 'dokter')->whereNotNull('email_verified_at')->count();
        $pasienBaru = $pasienBaru ?? \App\Models\User::where('role', 'pasien')->whereDate('created_at', '>=', now()->subDays(30))->count();
        $poliAktif = $poliAktif ?? \App\Models\Poli::whereHas('jadwalPeriksa')->count();

        $todayNewPatients = \App\Models\User::where('role', 'pasien')->whereDate('created_at', today())->count();
        $todayQueue = \App\Models\DaftarPoli::whereDate('created_at', today())->count();
        $todayExams = \App\Models\Periksa::whereDate('created_at', today())->count();

        $recentDaftar = \App\Models\DaftarPoli::with(['pasien', 'jadwalPeriksa.dokter.poli'])
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        $recentPeriksa = \App\Models\Periksa::with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.dokter.poli'])
            ->orderByDesc('tgl_periksa')
            ->limit(6)
            ->get();

        $todayLabel = now()->locale('id')->isoFormat('dddd, D MMMM YYYY');
    @endphp

    <main class="px-3 px-lg-4 py-4">
        <div class="mx-auto max-w-6xl">
            <!-- Hero / Welcome -->
            <div class="rounded-3xl border border-slate-200 shadow-sm overflow-hidden"
                style="background: linear-gradient(135deg, rgba(146,168,209,.28) 0%, rgba(247,202,201,.22) 100%);">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold badge-brand">
                                <i class="fa-solid fa-shield-halved"></i>
                                Administrator
                            </div>
                            <h1 class="mt-3 text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                                Selamat datang, {{ $user->nama ?? 'Admin' }}
                            </h1>
                            <p class="mt-2 text-sm sm:text-base text-slate-700/90 max-w-2xl">
                                Ringkasan data poliklinik dan aktivitas hari ini. {{ $todayLabel }}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.poli.create') }}" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold btn-brand ring-brand shadow-sm">
                                <i class="fa-solid fa-plus"></i> Poli
                            </a>
                            <a href="{{ route('admin.dokter.create') }}" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold btn-brand ring-brand shadow-sm">
                                <i class="fa-solid fa-plus"></i> Dokter
                            </a>
                            <a href="{{ route('admin.pasien.create') }}" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold btn-brand ring-brand shadow-sm">
                                <i class="fa-solid fa-plus"></i> Pasien
                            </a>
                            <a href="{{ route('admin.obat.create') }}" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold btn-brand ring-brand shadow-sm">
                                <i class="fa-solid fa-plus"></i> Obat
                            </a>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 border-t border-slate-200/70 bg-white/70 p-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="text-xs font-semibold text-slate-500">Pasien baru hari ini</div>
                        <div class="mt-1 text-2xl font-bold text-slate-900">{{ $todayNewPatients }}</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="text-xs font-semibold text-slate-500">Antrian terdaftar hari ini</div>
                        <div class="mt-1 text-2xl font-bold text-slate-900">{{ $todayQueue }}</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="text-xs font-semibold text-slate-500">Pemeriksaan hari ini</div>
                        <div class="mt-1 text-2xl font-bold text-slate-900">{{ $todayExams }}</div>
                    </div>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('admin.pasien.index') }}" class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-500">Total Pasien</div>
                            <div class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $totalPasien }}</div>
                        </div>
                        <div class="sidebar-icon group-hover:ring-2 group-hover:ring-[var(--brand-ring)]">
                            <i class="fa-solid fa-users text-brand"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-slate-600">Kelola data pasien & riwayat</div>
                </a>

                <a href="{{ route('admin.dokter.index') }}" class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-500">Total Dokter</div>
                            <div class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $totalDokter }}</div>
                        </div>
                        <div class="sidebar-icon group-hover:ring-2 group-hover:ring-[var(--brand-ring)]">
                            <i class="fa-solid fa-user-doctor text-brand"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-slate-600">Atur dokter & jadwal praktik</div>
                </a>

                <a href="{{ route('admin.poli.index') }}" class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-500">Total Poli</div>
                            <div class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $totalPoli }}</div>
                        </div>
                        <div class="sidebar-icon group-hover:ring-2 group-hover:ring-[var(--brand-ring)]">
                            <i class="fa-solid fa-hospital text-brand"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-slate-600">Kelola poli & layanan</div>
                </a>

                <a href="{{ route('admin.obat.index') }}" class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-500">Total Obat</div>
                            <div class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $totalObat }}</div>
                        </div>
                        <div class="sidebar-icon group-hover:ring-2 group-hover:ring-[var(--brand-ring)]">
                            <i class="fa-solid fa-pills text-brand"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-slate-600">Stok & manajemen obat</div>
                </a>
            </div>

            <!-- Insights & Recent Activity -->
            <div class="mt-6 grid gap-4 lg:grid-cols-3">
                <!-- Insights -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-bold text-slate-900">Insight</h2>
                        <span class="badge-brand rounded-full px-3 py-1 text-xs font-semibold">30 hari</span>
                    </div>

                    <div class="mt-5 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-slate-600">Dokter aktif (verifikasi email)</div>
                            <div class="text-sm font-semibold text-slate-900">{{ $aktiveDokter }}</div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-slate-600">Pasien baru (30 hari)</div>
                            <div class="text-sm font-semibold text-slate-900">{{ $pasienBaru }}</div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-slate-600">Poli aktif (punya jadwal)</div>
                            <div class="text-sm font-semibold text-slate-900">{{ $poliAktif }}</div>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.poli.index') }}" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                            <i class="fa-solid fa-building mr-2 text-brand"></i> Poli
                        </a>
                        <a href="{{ route('admin.obat.index') }}" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                            <i class="fa-solid fa-pills mr-2 text-brand"></i> Obat
                        </a>
                        <a href="{{ route('admin.dokter.index') }}" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                            <i class="fa-solid fa-user-doctor mr-2 text-brand"></i> Dokter
                        </a>
                        <a href="{{ route('admin.pasien.index') }}" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                            <i class="fa-solid fa-users mr-2 text-brand"></i> Pasien
                        </a>
                    </div>
                </div>

                <!-- Recent queue -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-1">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-bold text-slate-900">Antrian terbaru</h2>
                        <a href="{{ route('admin.pasien.index') }}" class="text-xs font-semibold text-brand hover:underline">Lihat data</a>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse ($recentDaftar as $row)
                            @php
                                $poli = $row->jadwalPeriksa->dokter->poli->nama_poli ?? $row->jadwalPeriksa->poli->nama_poli ?? 'Poli';
                                $dokter = $row->jadwalPeriksa->dokter->nama ?? 'Dokter';
                            @endphp
                            <div class="rounded-2xl border border-slate-200 p-4 hover:bg-slate-50">
                                <div class="flex items-start justify-between" style="gap:12px;">
                                    <div style="min-width:0;">
                                        <div class="text-sm font-semibold text-slate-900 truncate">
                                            {{ $row->pasien->nama ?? 'Pasien' }}
                                        </div>
                                        <div class="mt-1 text-xs text-slate-600 truncate">
                                            {{ $poli }} • {{ $dokter }}
                                        </div>
                                        <div class="mt-2 inline-flex items-center gap-2 rounded-full px-3 py-1 text-[11px] badge-brand">
                                            <i class="fa-solid fa-hashtag"></i>
                                            No Antrian: <span class="font-semibold">{{ $row->no_antrian }}</span>
                                        </div>
                                    </div>
                                    <div class="text-xs text-slate-500 whitespace-nowrap">
                                        {{ $row->created_at?->format('d/m H:i') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                                Belum ada pendaftaran antrian.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent exams -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-1">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-bold text-slate-900">Pemeriksaan terbaru</h2>
                        <span class="text-xs text-slate-500">Update otomatis</span>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse ($recentPeriksa as $row)
                            @php
                                $poli = $row->daftarPoli?->jadwalPeriksa?->dokter?->poli?->nama_poli
                                    ?? $row->daftarPoli?->jadwalPeriksa?->poli?->nama_poli
                                    ?? 'Poli';
                                $dokter = $row->daftarPoli?->jadwalPeriksa?->dokter?->nama ?? 'Dokter';
                                $pasien = $row->daftarPoli?->pasien?->nama ?? 'Pasien';
                            @endphp
                            <div class="rounded-2xl border border-slate-200 p-4 hover:bg-slate-50">
                                <div class="flex items-start justify-between" style="gap:12px;">
                                    <div style="min-width:0;">
                                        <div class="text-sm font-semibold text-slate-900 truncate">{{ $pasien }}</div>
                                        <div class="mt-1 text-xs text-slate-600 truncate">{{ $poli }} • {{ $dokter }}</div>
                                        <div class="mt-2 text-[11px] text-slate-600">
                                            Biaya: <span class="font-semibold text-slate-900">Rp {{ number_format((int) ($row->biaya_periksa ?? 0), 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <div class="text-xs text-slate-500 whitespace-nowrap">
                                        {{ optional($row->tgl_periksa ?? $row->created_at)->format('d/m H:i') }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                                Belum ada pemeriksaan.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>
