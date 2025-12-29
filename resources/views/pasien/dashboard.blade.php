<x-layouts.app title="Pasien Dashboard - Poliklinik">
    @push('styles')
        <style>
            .welcome-card {
                background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
                color: white;
                border-radius: 15px;
                border: none;
                margin-bottom: 20px;
            }

            .patient-info {
                background: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(10px);
                border-radius: 10px;
                padding: 15px;
                margin-top: 15px;
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .stats-card {
                border-radius: 15px;
                border: none;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease;
            }

            .stats-card:hover {
                transform: translateY(-5px);
            }

            .small-box {
                border-radius: 15px;
                position: relative;
                overflow: hidden;
            }

            .small-box .icon {
                transition: all 0.3s ease;
            }

            .small-box:hover .icon {
                transform: scale(1.1);
            }

            .quick-action-card {
                border-radius: 15px;
                padding: 30px;
                transition: all 0.3s ease;
                cursor: pointer;
                border: 2px solid transparent;
                background: white;
                height: 100%;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            }

            .quick-action-card:hover {
                border-color: #17a2b8;
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(23, 162, 184, 0.2);
            }

            .quick-action-icon {
                width: 70px;
                height: 70px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 15px;
                font-size: 30px;
            }

            .quick-action-card h4 {
                font-size: 18px;
                font-weight: 700;
                margin-bottom: 10px;
            }

            .content-header h1 {
                color: #495057;
                font-weight: 600;
            }

            .breadcrumb {
                background: transparent;
            }

            .card {
                border-radius: 15px;
                border: none;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            }

            .card-header {
                background: #f8f9fa;
                border-bottom: 1px solid #dee2e6;
                border-radius: 15px 15px 0 0 !important;
            }
        </style>
    @endpush

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-user mr-2"></i>Dashboard Pasien
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            <i class="fas fa-home mr-1"></i>Dashboard
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Welcome Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card welcome-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3 class="mb-3">
                                        <i class="fas fa-heartbeat mr-2"></i>
                                        Selamat Datang, {{ Auth::user()->nama }}!
                                    </h3>
                                    <p class="mb-0">
                                        Kelola jadwal pemeriksaan dan lihat riwayat kesehatan Anda dengan mudah.
                                    </p>
                                    <div class="patient-info">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <small class="text-white-50">No. Rekam Medis</small>
                                                <div class="font-weight-bold text-white">
                                                    {{ Auth::user()->no_rm ?? 'N/A' }}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-white-50">No. KTP</small>
                                                <div class="font-weight-bold text-white">
                                                    {{ Auth::user()->no_ktp ?? 'N/A' }}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-white-50">No. HP</small>
                                                <div class="font-weight-bold text-white">
                                                    {{ Auth::user()->no_hp ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <i class="fas fa-user-injured fa-5x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ \App\Models\DaftarPoli::where('id_pasien', Auth::id())->count() }}</h3>
                            <p>Total Kunjungan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <a href="{{ route('pasien.riwayat') }}" class="small-box-footer">
                            Lihat Riwayat <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 col-12">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ \App\Models\DaftarPoli::where('id_pasien', Auth::id())->whereDoesntHave('periksa')->count() }}
                            </h3>
                            <p>Menunggu Periksa</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <a href="{{ route('pasien.daftar-poli') }}" class="small-box-footer">
                            Daftar Poli <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informasi Jadwal Dokter -->
            <div class="row">
                <div class="col-12">
                    <div class="card stats-card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white">
                                <i class="fas fa-calendar-alt mr-2"></i>Jadwal Dokter & Poli -
                                {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            @php
                                $jadwalHariIni = \App\Models\JadwalPeriksa::with(['dokter.poli'])
                                    ->where('hari', now()->locale('id')->dayName)
                                    ->where('aktif', 'Y')
                                    ->orderBy('jam_mulai', 'asc')
                                    ->get();
                            @endphp

                            @if ($jadwalHariIni->count() > 0)
                                <div class="row">
                                    @foreach ($jadwalHariIni as $jadwal)
                                        <div class="col-lg-6 col-12 mb-3">
                                            <div class="card border-left-primary shadow-sm h-100">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            <div class="bg-primary rounded-circle p-3"
                                                                style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                                                <i class="fas fa-user-md fa-2x text-white"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <h5 class="font-weight-bold mb-1">
                                                                {{ $jadwal->dokter->nama }}
                                                            </h5>
                                                            <p class="mb-2">
                                                                <span class="badge badge-info badge-lg">
                                                                    <i
                                                                        class="fas fa-hospital mr-1"></i>{{ $jadwal->dokter->poli->nama_poli }}
                                                                </span>
                                                            </p>
                                                            <div class="d-flex align-items-center text-muted">
                                                                <i class="fas fa-clock mr-2"></i>
                                                                <strong>{{ date('H:i', strtotime($jadwal->jam_mulai)) }}</strong>
                                                                <span class="mx-2">-</span>
                                                                <strong>{{ date('H:i', strtotime($jadwal->jam_selesai)) }}</strong>
                                                            </div>
                                                            <div class="mt-2">
                                                                @php
                                                                    $now = now()->format('H:i');
                                                                    $jamMulai = date(
                                                                        'H:i',
                                                                        strtotime($jadwal->jam_mulai),
                                                                    );
                                                                    $jamSelesai = date(
                                                                        'H:i',
                                                                        strtotime($jadwal->jam_selesai),
                                                                    );
                                                                @endphp

                                                                @if ($now >= $jamMulai && $now <= $jamSelesai)
                                                                    <span class="badge badge-success">
                                                                        <i class="fas fa-circle mr-1"></i>Sedang Praktik
                                                                    </span>
                                                                @elseif($now < $jamMulai)
                                                                    <span class="badge badge-warning">
                                                                        <i class="fas fa-hourglass-half mr-1"></i>Belum
                                                                        Dimulai
                                                                    </span>
                                                                @else
                                                                    <span class="badge badge-secondary">
                                                                        <i class="fas fa-check-circle mr-1"></i>Selesai
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Summary Info -->
                                <div class="row mt-3">
                                    <div class="col-md-4 col-12 mb-2">
                                        <div class="small-box bg-success">
                                            <div class="inner">
                                                <h4>{{ \App\Models\User::where('role', 'dokter')->count() }}</h4>
                                                <p>Total Dokter</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-user-md"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12 mb-2">
                                        <div class="small-box bg-info">
                                            <div class="inner">
                                                <h4>{{ \App\Models\Poli::count() }}</h4>
                                                <p>Total Poli</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-hospital"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12 mb-2">
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h4>{{ $jadwalHariIni->count() }}</h4>
                                                <p>Dokter Praktik Hari Ini</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    <h5 class="alert-heading">
                                        <i class="fas fa-info-circle mr-2"></i>Tidak Ada Jadwal Hari Ini
                                    </h5>
                                    <p class="mb-0">
                                        Tidak ada dokter yang praktik pada hari
                                        <strong>{{ now()->locale('id')->dayName }}</strong>.
                                        Silakan cek jadwal di hari lain atau hubungi pihak poliklinik untuk informasi
                                        lebih lanjut.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Antrian Anda Sedang Menunggu -->
            @php
                $antrianMenunggu = \App\Models\DaftarPoli::where('id_pasien', Auth::id())
                    ->with(['jadwalPeriksa.dokter.poli'])
                    ->whereDoesntHave('periksa')
                    ->orderBy('created_at', 'desc')
                    ->get();
            @endphp

            @if ($antrianMenunggu->count() > 0)
                <div class="row">
                    <div class="col-12">
                        <div class="card stats-card">
                            <div class="card-header bg-warning">
                                <h3 class="card-title text-white">
                                    <i class="fas fa-clock mr-2"></i>Antrian Anda yang Sedang Menunggu
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Anda memiliki <strong>{{ $antrianMenunggu->count() }}</strong> antrian yang sedang
                                    menunggu pemeriksaan
                                </div>

                                <div class="row">
                                    @foreach ($antrianMenunggu as $antrian)
                                        <div class="col-lg-4 col-md-6 col-12 mb-3">
                                            <div class="card border-left-warning shadow-sm h-100">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div>
                                                            <h5 class="font-weight-bold text-warning mb-1">
                                                                No. Antrian: {{ $antrian->no_antrian }}
                                                            </h5>
                                                            <span class="badge badge-info">
                                                                {{ $antrian->jadwalPeriksa->dokter->poli->nama_poli ?? 'N/A' }}
                                                            </span>
                                                        </div>
                                                        <i class="fas fa-hourglass-half fa-2x text-warning"></i>
                                                    </div>

                                                    <hr>

                                                    <div class="mb-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-user-md mr-1"></i> Dokter
                                                        </small>
                                                        <div class="font-weight-bold">
                                                            {{ $antrian->jadwalPeriksa->dokter->nama ?? 'N/A' }}
                                                        </div>
                                                    </div>

                                                    <div class="mb-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar mr-1"></i> Jadwal
                                                        </small>
                                                        <div>
                                                            {{ $antrian->jadwalPeriksa->hari ?? 'N/A' }},
                                                            {{ date('H:i', strtotime($antrian->jadwalPeriksa->jam_mulai ?? '00:00')) }}
                                                            -
                                                            {{ date('H:i', strtotime($antrian->jadwalPeriksa->jam_selesai ?? '00:00')) }}
                                                        </div>
                                                    </div>

                                                    <div class="mb-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-notes-medical mr-1"></i> Keluhan
                                                        </small>
                                                        <div class="small">
                                                            {{ Str::limit($antrian->keluhan, 50) }}
                                                        </div>
                                                    </div>

                                                    <div class="mt-3">
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            Terdaftar: {{ $antrian->created_at->format('d/m/Y H:i') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="text-center mt-3">
                                    <a href="{{ route('pasien.riwayat') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-history mr-2"></i>Lihat Semua Riwayat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
