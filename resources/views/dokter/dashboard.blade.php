<x-layouts.app title="Dashboard Dokter - Poliklinik">
    @php
        $user = Auth::user();

        // Prefer data dari controller (DashboardDokterController), fallback jika belum ada
        $activeSchedulesCount = $activeSchedulesCount ?? \App\Models\JadwalPeriksa::where('id_dokter', $user->id)->where('aktif', 'Y')->count();
        $todayQueueCount = $todayQueueCount
            ?? \App\Models\DaftarPoli::whereHas('jadwalPeriksa', fn($q) => $q->where('id_dokter', $user->id))
                ->whereDate('created_at', today())
                ->whereDoesntHave('periksa')
                ->count();

        $todayExaminations = $todayExaminations
            ?? \App\Models\Periksa::whereHas('daftarPoli.jadwalPeriksa', fn($q) => $q->where('id_dokter', $user->id))
                ->whereDate('created_at', today())
                ->count();

        $totalExaminations = $totalExaminations
            ?? \App\Models\Periksa::whereHas('daftarPoli.jadwalPeriksa', fn($q) => $q->where('id_dokter', $user->id))
                ->count();

        $recentRegistrations = $recentRegistrations
            ?? \App\Models\DaftarPoli::with(['pasien', 'jadwalPeriksa.poli'])
                ->whereHas('jadwalPeriksa', fn($q) => $q->where('id_dokter', $user->id))
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

        $todayLabel = now()->locale('id')->isoFormat('dddd, D MMMM YYYY');
    @endphp

    <main class="px-3 px-lg-4 py-4">
        <div class="mx-auto max-w-6xl">
            <!-- Hero -->
            <div class="rounded-3xl border border-slate-200 shadow-sm overflow-hidden"
                style="background: linear-gradient(135deg, rgba(146,168,209,.28) 0%, rgba(247,202,201,.22) 100%);">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold badge-brand">
                                <i class="fa-solid fa-stethoscope"></i>
                                Dokter
                            </div>
                            <h1 class="mt-3 text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                                Halo, Dr. {{ $user->nama ?? 'Dokter' }}
                            </h1>
                            <p class="mt-2 text-sm sm:text-base text-slate-700/90 max-w-2xl">
                                Kelola jadwal praktik, antrian pasien, dan riwayat pemeriksaan. {{ $todayLabel }}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('dokter.jadwal-periksa.create') }}" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold btn-brand ring-brand shadow-sm">
                                <i class="fa-solid fa-calendar-plus"></i> Jadwal Baru
                            </a>
                            <a href="{{ route('dokter.periksa-pasien.index') }}" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold bg-white border border-slate-200 text-slate-700 shadow-sm hover:bg-slate-50">
                                <i class="fa-solid fa-user-check text-brand"></i> Periksa Pasien
                            </a>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 border-t border-slate-200/70 bg-white/70 p-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="text-xs font-semibold text-slate-500">Jadwal aktif</div>
                        <div class="mt-1 text-2xl font-bold text-slate-900">{{ $activeSchedulesCount }}</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="text-xs font-semibold text-slate-500">Antrian menunggu hari ini</div>
                        <div class="mt-1 text-2xl font-bold text-slate-900">{{ $todayQueueCount }}</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="text-xs font-semibold text-slate-500">Pemeriksaan hari ini</div>
                        <div class="mt-1 text-2xl font-bold text-slate-900">{{ $todayExaminations }}</div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <a href="{{ route('dokter.jadwal-periksa.index') }}" class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-500">Semua jadwal</div>
                            <div class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ \App\Models\JadwalPeriksa::where('id_dokter', $user->id)->count() }}</div>
                        </div>
                        <div class="sidebar-icon group-hover:ring-2 group-hover:ring-[var(--brand-ring)]">
                            <i class="fa-solid fa-calendar-check text-brand"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-slate-600">Kelola hari & jam praktik</div>
                </a>

                <a href="{{ route('dokter.periksa-pasien.index') }}" class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-500">Total pemeriksaan</div>
                            <div class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $totalExaminations }}</div>
                        </div>
                        <div class="sidebar-icon group-hover:ring-2 group-hover:ring-[var(--brand-ring)]">
                            <i class="fa-solid fa-notes-medical text-brand"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-slate-600">Semua catatan pemeriksaan</div>
                </a>

                <a href="{{ route('dokter.riwayat-pasien.index') }}" class="group rounded-3xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-500">Riwayat pasien</div>
                            <div class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $totalExaminations }}</div>
                        </div>
                        <div class="sidebar-icon group-hover:ring-2 group-hover:ring-[var(--brand-ring)]">
                            <i class="fa-solid fa-clock-rotate-left text-brand"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-slate-600">Cari data kunjungan pasien</div>
                </a>
            </div>

            <!-- Recent registrations -->
            <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-slate-900">Pendaftaran terbaru</h2>
                    <a href="{{ route('dokter.periksa-pasien.index') }}" class="text-xs font-semibold text-brand hover:underline">Buka antrian</a>
                </div>

                <div class="mt-4 grid gap-3 md:grid-cols-2">
                    @forelse ($recentRegistrations as $row)
                        @php
                            $poli = $row->jadwalPeriksa?->poli?->nama_poli ?? $row->jadwalPeriksa?->dokter?->poli?->nama_poli ?? 'Poli';
                            $pasien = $row->pasien?->nama ?? 'Pasien';
                            $status = $row->periksa ? 'Selesai' : 'Menunggu';
                        @endphp
                        <div class="rounded-2xl border border-slate-200 p-4 hover:bg-slate-50">
                            <div class="flex items-start justify-between" style="gap:12px;">
                                <div style="min-width:0;">
                                    <div class="text-sm font-semibold text-slate-900 truncate">{{ $pasien }}</div>
                                    <div class="mt-1 text-xs text-slate-600 truncate">{{ $poli }} • No Antrian {{ $row->no_antrian }}</div>
                                    <div class="mt-2 text-xs text-slate-500">{{ $row->created_at?->format('d/m/Y H:i') }}</div>
                                </div>
                                <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $status === 'Selesai' ? 'badge-brand' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600 md:col-span-2">
                            Belum ada pendaftaran pasien.
                        </div>
                    @endforelse
                </div>

                <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                    <i class="fa-solid fa-circle-info mr-2 text-brand"></i>
                    Untuk memulai pemeriksaan, buka menu <span class="font-semibold">Periksa Pasien</span> dan pilih pasien yang menunggu.
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>
