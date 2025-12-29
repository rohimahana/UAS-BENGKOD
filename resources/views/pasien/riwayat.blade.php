<x-layouts.app title="Riwayat Kunjungan">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="mb-4">
                    <i class="fas fa-history mr-2"></i>Riwayat Kunjungan
                </h1>

                @php
                    $menunggu = $daftars->filter(fn($d) => !$d->periksa)->count();
                    $selesai = $daftars->filter(fn($d) => $d->periksa)->count();
                @endphp

                <!-- Summary Statistics -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $daftars->count() }}</h3>
                                <p>Total Kunjungan</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-list"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $menunggu }}</h3>
                                <p>Menunggu Periksa</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $selesai }}</h3>
                                <p>Sudah Diperiksa</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-table mr-2"></i>Detail Riwayat
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="10%">No. Antrian</th>
                                        <th width="15%">Dokter</th>
                                        <th width="12%">Poli</th>
                                        <th width="15%">Jadwal</th>
                                        <th width="20%">Keluhan</th>
                                        <th width="10%">Status</th>
                                        <th width="13%">Tanggal Daftar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($daftars as $i => $daftar)
                                        <tr
                                            class="{{ $daftar->periksa ? 'table-success-light' : 'table-warning-light' }}">
                                            <td class="text-center">{{ $i + 1 }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge badge-{{ $daftar->periksa ? 'success' : 'warning' }} badge-lg">
                                                    {{ $daftar->no_antrian }}
                                                </span>
                                            </td>
                                            <td>
                                                <i class="fas fa-user-md mr-1 text-primary"></i>
                                                {{ $daftar->jadwalPeriksa->dokter->nama ?? '-' }}
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $daftar->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    {{ $daftar->jadwalPeriksa->hari ?? '-' }}<br>
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ date('H:i', strtotime($daftar->jadwalPeriksa->jam_mulai ?? '00:00')) }}
                                                    -
                                                    {{ date('H:i', strtotime($daftar->jadwalPeriksa->jam_selesai ?? '00:00')) }}
                                                </small>
                                            </td>
                                            <td>
                                                <small>{{ Str::limit($daftar->keluhan, 50) }}</small>
                                            </td>
                                            <td class="text-center">
                                                @if ($daftar->periksa)
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check"></i> Sudah Periksa
                                                    </span>
                                                @else
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-clock"></i> Menunggu
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>
                                                    <i class="fas fa-calendar-plus mr-1"></i>
                                                    {{ $daftar->created_at->format('d-m-Y') }}<br>
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $daftar->created_at->format('H:i') }}
                                                </small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted mb-0">Belum ada riwayat kunjungan</p>
                                                <small class="text-muted">Silakan daftar poli untuk membuat antrian
                                                    baru</small>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Informasi -->
                <div class="alert alert-info mt-3">
                    <h5 class="alert-heading">
                        <i class="fas fa-info-circle mr-2"></i>Informasi
                    </h5>
                    <ul class="mb-0">
                        <li><strong>Status Menunggu:</strong> Antrian Anda sedang menunggu untuk diperiksa oleh dokter
                        </li>
                        <li><strong>Status Sudah Periksa:</strong> Anda telah diperiksa oleh dokter dan pemeriksaan
                            selesai</li>
                        <li>Data riwayat ditampilkan dari yang terbaru ke yang terlama</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .table-warning-light {
                background-color: #fff3cd20 !important;
            }

            .table-success-light {
                background-color: #d4edda20 !important;
            }

            .badge-lg {
                font-size: 1em;
                padding: 0.5em 0.75em;
            }
        </style>
    @endpush
</x-layouts.app>
