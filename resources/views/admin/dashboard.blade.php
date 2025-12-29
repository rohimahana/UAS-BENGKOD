<x-layouts.app title="Admin Dashboard - Poliklinik">
    @push('styles')
        <style>
            .welcome-card {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border-radius: 15px;
                border: none;
                margin-bottom: 20px;
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
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Admin
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
                                        <i class="fas fa-user-shield mr-2"></i>
                                        Selamat Datang, {{ Auth::user()->nama }}!
                                    </h3>
                                    <p class="mb-0">
                                        Anda login sebagai <strong>Administrator</strong>.
                                        Kelola sistem poliklinik dengan mudah dan efisien.
                                    </p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <i class="fas fa-user-cog fa-5x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ \App\Models\User::where('role', 'pasien')->count() }}</h3>
                            <p>Total Pasien</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="{{ route('admin.pasien.index') }}" class="small-box-footer">
                            Info lebih lanjut <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ \App\Models\User::where('role', 'dokter')->count() }}</h3>
                            <p>Total Dokter</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <a href="{{ route('admin.dokter.index') }}" class="small-box-footer">
                            Info lebih lanjut <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ \App\Models\Poli::count() }}</h3>
                            <p>Total Poli</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <a href="{{ route('admin.poli.index') }}" class="small-box-footer">
                            Info lebih lanjut <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ \App\Models\Obat::count() }}</h3>
                            <p>Total Obat</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-pills"></i>
                        </div>
                        <a href="{{ route('admin.obat.index') }}" class="small-box-footer">
                            Info lebih lanjut <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Management Cards -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card stats-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-cogs mr-2"></i>Manajemen Data
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div
                                    class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <span><i class="fas fa-user-md text-success mr-2"></i>Kelola Dokter</span>
                                    <span
                                        class="badge badge-success badge-pill">{{ \App\Models\User::where('role', 'dokter')->count() }}</span>
                                </div>
                                <div
                                    class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <span><i class="fas fa-hospital text-warning mr-2"></i>Kelola Poli</span>
                                    <span class="badge badge-warning badge-pill">{{ \App\Models\Poli::count() }}</span>
                                </div>
                                <div
                                    class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <span><i class="fas fa-users text-info mr-2"></i>Kelola Pasien</span>
                                    <span
                                        class="badge badge-info badge-pill">{{ \App\Models\User::where('role', 'pasien')->count() }}</span>
                                </div>
                                <div
                                    class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <span><i class="fas fa-pills text-danger mr-2"></i>Kelola Obat</span>
                                    <span class="badge badge-danger badge-pill">{{ \App\Models\Obat::count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card stats-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-2"></i>Aktivitas Hari Ini
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div
                                    class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <span><i class="fas fa-calendar-check text-primary mr-2"></i>Pasien Terdaftar Hari
                                        Ini</span>
                                    <span class="badge badge-primary badge-pill">
                                        {{ \App\Models\User::where('role', 'pasien')->whereDate('created_at', today())->count() }}
                                    </span>
                                </div>
                                <div
                                    class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <span><i class="fas fa-stethoscope text-success mr-2"></i>Pemeriksaan Hari
                                        Ini</span>
                                    <span class="badge badge-success badge-pill">
                                        {{ \App\Models\Periksa::whereDate('created_at', today())->count() }}
                                    </span>
                                </div>
                                <div
                                    class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <span><i class="fas fa-clipboard-list text-info mr-2"></i>Antrian Aktif</span>
                                    <span class="badge badge-info badge-pill">
                                        {{ \App\Models\DaftarPoli::whereDate('created_at', today())->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
