<x-layouts.app title="Dashboard Pasien - Poliklinik">
    @php
        $user = Auth::user();

        // Prefer data dari controller (DashboardPasienController), fallback jika belum ada
        $totalAppointments = $totalAppointments ?? \App\Models\DaftarPoli::where('id_pasien', $user->id)->count();
        $completedExaminations = $completedExaminations ?? \App\Models\DaftarPoli::where('id_pasien', $user->id)->whereHas('periksa')->count();
        $pendingAppointments = $pendingAppointments ?? \App\Models\DaftarPoli::where('id_pasien', $user->id)->whereDoesntHave('periksa')->count();

        $recentAppointments = $recentAppointments
            ?? \App\Models\DaftarPoli::with(['jadwalPeriksa.dokter.poli', 'periksa'])
                ->where('id_pasien', $user->id)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

        $upcomingAppointments = $upcomingAppointments
            ?? \App\Models\DaftarPoli::with(['jadwalPeriksa.dokter.poli'])
                ->where('id_pasien', $user->id)
                ->whereDoesntHave('periksa')
                ->orderByDesc('created_at')
                ->limit(3)
                ->get();

        // Jadwal dokter hari ini (fitur yang berguna untuk pasien)
        $hariIni = now()->locale('id')->dayName;
        $jadwalHariIni = \App\Models\JadwalPeriksa::with(['dokter.poli'])
            ->where('hari', $hariIni)
            ->where('aktif', 'Y')
            ->orderBy('jam_mulai', 'asc')
            ->limit(6)
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
                                <i class="fa-solid fa-heart-pulse"></i>
                                Pasien
                            </div>
                            <h1 class="mt-3 text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                                Halo, {{ $user->nama ?? 'Pasien' }}
                            </h1>
                            <p class="mt-2 text-sm sm:text-base text-slate-700/90 max-w-2xl">
                                Pantau antrian, daftar poli, dan lihat riwayat kunjungan. {{ $todayLabel }}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('pasien.daftar-poli') }}" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold btn-brand ring-brand shadow-sm">
                                <i class="fa-solid fa-hospital-user"></i> Daftar Poli
                            </a>
                            <a href="{{ route('pasien.riwayat') }}" class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold bg-white border border-slate-200 text-slate-700 shadow-sm hover:bg-slate-50">
                                <i class="fa-solid fa-clock-rotate-left text-brand"></i> Riwayat
                            </a>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-3 md:grid-cols-3">
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="text-xs font-semibold text-slate-500">No. Rekam Medis</div>
                            <div class="mt-1 text-sm font-semibold text-slate-900">{{ $user->no_rm ?? '—' }}</div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="text-xs font-semibold text-slate-500">No. KTP</div>
                            <div class="mt-1 text-sm font-semibold text-slate-900">{{ $user->no_ktp ?? '—' }}</div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="text-xs font-semibold text-slate-500">No. HP</div>
                            <div class="mt-1 text-sm font-semibold text-slate-900">{{ $user->no_hp ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-500">Total kunjungan</div>
                            <div class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $totalAppointments }}</div>
                        </div>
                        <div class="sidebar-icon">
                            <i class="fa-solid fa-clipboard-check text-brand"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-slate-600">Semua pendaftaran poli kamu</div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-500">Selesai diperiksa</div>
                            <div class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $completedExaminations }}</div>
                        </div>
                        <div class="sidebar-icon">
                            <i class="fa-solid fa-file-waveform text-brand"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-slate-600">Riwayat pemeriksaan tersimpan</div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-500">Menunggu periksa</div>
                            <div class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $pendingAppointments }}</div>
                        </div>
                        <div class="sidebar-icon">
                            <i class="fa-solid fa-hourglass-half text-brand"></i>
                        </div>
                    </div>
                    <div class="mt-4 text-xs text-slate-600">Antrian yang belum diperiksa</div>
                </div>
            </div>

            <div class="mt-6 grid gap-4 lg:grid-cols-2">
                <!-- Upcoming / waiting -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-bold text-slate-900">Antrian kamu</h2>
                        <a href="{{ route('pasien.daftar-poli') }}" class="text-xs font-semibold text-brand hover:underline">Daftar lagi</a>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse ($upcomingAppointments as $row)
                            @php
                                $poli = $row->jadwalPeriksa?->dokter?->poli?->nama_poli ?? $row->jadwalPeriksa?->poli?->nama_poli ?? 'Poli';
                                $dokter = $row->jadwalPeriksa?->dokter?->nama ?? 'Dokter';
                            @endphp
                            <div class="rounded-2xl border border-slate-200 p-4 hover:bg-slate-50">
                                <div class="flex items-start justify-between" style="gap:12px;">
                                    <div style="min-width:0;">
                                        <div class="text-sm font-semibold text-slate-900 truncate">
                                            {{ $poli }} • Dr. {{ $dokter }}
                                        </div>
                                        <div class="mt-1 text-xs text-slate-600 truncate">
                                            Keluhan: {{ \Illuminate\Support\Str::limit($row->keluhan ?? '-', 60) }}
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
                                Belum ada antrian aktif. Klik <span class="font-semibold">Daftar Poli</span> untuk mengambil nomor.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Jadwal dokter hari ini -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-bold text-slate-900">Jadwal dokter hari ini</h2>
                        <span class="badge-brand rounded-full px-3 py-1 text-xs font-semibold">{{ $hariIni }}</span>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse ($jadwalHariIni as $jadwal)
                            @php
                                $jamMulai = date('H:i', strtotime($jadwal->jam_mulai));
                                $jamSelesai = date('H:i', strtotime($jadwal->jam_selesai));
                                $nowTime = now()->format('H:i');

                                $status = 'Belum mulai';
                                if ($nowTime >= $jamMulai && $nowTime <= $jamSelesai) {
                                    $status = 'Sedang praktik';
                                } elseif ($nowTime > $jamSelesai) {
                                    $status = 'Selesai';
                                }

                                $poli = $jadwal->dokter?->poli?->nama_poli ?? 'Poli';
                                $dokter = $jadwal->dokter?->nama ?? 'Dokter';
                            @endphp

                            <div class="rounded-2xl border border-slate-200 p-4 hover:bg-slate-50">
                                <div class="flex items-start justify-between" style="gap:12px;">
                                    <div style="min-width:0;">
                                        <div class="text-sm font-semibold text-slate-900 truncate">{{ $poli }}</div>
                                        <div class="mt-1 text-xs text-slate-600 truncate">Dr. {{ $dokter }}</div>
                                        <div class="mt-2 text-xs text-slate-600">
                                            <i class="fa-regular fa-clock mr-1 text-brand"></i>
                                            <span class="font-semibold text-slate-900">{{ $jamMulai }}</span> – <span class="font-semibold text-slate-900">{{ $jamSelesai }}</span>
                                        </div>
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-[11px] font-semibold badge-brand">{{ $status }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                                Tidak ada dokter yang praktik hari ini.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                        <i class="fa-solid fa-circle-info mr-2 text-brand"></i>
                        Jadwal dapat berubah. Untuk mengambil antrian, gunakan tombol <span class="font-semibold">Daftar Poli</span>.
                    </div>
                </div>
            </div>

            <!-- Recent history -->
            <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-slate-900">Riwayat terbaru</h2>
                    <a href="{{ route('pasien.riwayat') }}" class="text-xs font-semibold text-brand hover:underline">Lihat semua</a>
                </div>

                <div class="mt-4 grid gap-3 md:grid-cols-2">
                    @forelse ($recentAppointments as $row)
                        @php
                            $poli = $row->jadwalPeriksa?->dokter?->poli?->nama_poli ?? $row->jadwalPeriksa?->poli?->nama_poli ?? 'Poli';
                            $dokter = $row->jadwalPeriksa?->dokter?->nama ?? 'Dokter';
                            $status = $row->periksa ? 'Selesai' : 'Menunggu';
                        @endphp
                        <div class="rounded-2xl border border-slate-200 p-4 hover:bg-slate-50">
                            <div class="flex items-start justify-between" style="gap:12px;">
                                <div style="min-width:0;">
                                    <div class="text-sm font-semibold text-slate-900 truncate">{{ $poli }} • Dr. {{ $dokter }}</div>
                                    <div class="mt-1 text-xs text-slate-600 truncate">Keluhan: {{ \Illuminate\Support\Str::limit($row->keluhan ?? '-', 70) }}</div>
                                    <div class="mt-2 text-xs text-slate-500">{{ $row->created_at?->format('d/m/Y H:i') }}</div>
                                </div>
                                <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $status === 'Selesai' ? 'badge-brand' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600 md:col-span-2">
                            Belum ada riwayat kunjungan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>
