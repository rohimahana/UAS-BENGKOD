<x-layouts.app title="Detail Riwayat Pasien">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-clipboard-list"></i> Detail Riwayat Pemeriksaan</h2>
                    <a href="{{ route('dokter.riwayat-pasien.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <!-- Informasi Pasien -->
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user-injured"></i> Informasi Pasien</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nama Pasien:</strong> {{ $periksa->daftarPoli->pasien->nama ?? '-' }}</p>
                                <p><strong>No. Rekam Medis:</strong>
                                    <span
                                        class="badge badge-info">{{ $periksa->daftarPoli->pasien->no_rm ?? '-' }}</span>
                                </p>
                                <p><strong>No. Antrian:</strong>
                                    <span
                                        class="badge badge-primary">{{ $periksa->daftarPoli->no_antrian ?? '-' }}</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Poli:</strong>
                                    {{ $periksa->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</p>
                                <p><strong>Dokter:</strong> Dr.
                                    {{ $periksa->daftarPoli->jadwalPeriksa->dokter->nama ?? '-' }}</p>
                                <p><strong>Tanggal Periksa:</strong>
                                    {{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d/m/Y H:i') }} WIB</p>
                            </div>
                        </div>
                        <hr>
                        <p><strong>Keluhan:</strong></p>
                        <p class="text-muted">{{ $periksa->daftarPoli->keluhan ?? '-' }}</p>
                    </div>
                </div>

                <!-- Catatan Dokter -->
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-notes-medical"></i> Catatan Dokter</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $periksa->catatan ?: 'Tidak ada catatan' }}</p>
                    </div>
                </div>

                <!-- Obat yang Diresepkan -->
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-pills"></i> Obat yang Diresepkan</h5>
                    </div>
                    <div class="card-body">
                        @if ($periksa->detailPeriksa && $periksa->detailPeriksa->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Nama Obat</th>
                                            <th width="150">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $totalObat = 0; @endphp
                                        @foreach ($periksa->detailPeriksa as $index => $detail)
                                            @php $totalObat += $detail->obat->harga; @endphp
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $detail->obat->nama_obat ?? '-' }}</td>
                                                <td class="text-right">Rp
                                                    {{ number_format($detail->obat->harga, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="table-active">
                                            <td colspan="2" class="text-right"><strong>Total Harga Obat:</strong>
                                            </td>
                                            <td class="text-right"><strong>Rp
                                                    {{ number_format($totalObat, 0, ',', '.') }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted mb-0">Tidak ada obat yang diresepkan</p>
                        @endif
                    </div>
                </div>

                <!-- Total Biaya -->
                <div class="card mb-3">
                    <div class="card-body text-center bg-light">
                        <h5 class="card-title mb-3">Total Biaya Pemeriksaan</h5>
                        <h2 class="text-success mb-0">
                            <i class="fas fa-money-bill-wave"></i>
                            Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}
                        </h2>
                        <small class="text-muted">Sudah termasuk biaya konsultasi dan obat</small>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('dokter.riwayat-pasien.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="fas fa-print"></i> Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            @media print {

                .main-sidebar,
                .main-header,
                .content-header,
                .btn,
                .card-header {
                    display: none !important;
                }

                .content-wrapper {
                    margin: 0 !important;
                    padding: 0 !important;
                }

                .card {
                    border: 1px solid #ddd !important;
                    box-shadow: none !important;
                    page-break-inside: avoid;
                }
            }
        </style>
    @endpush
</x-layouts.app>
