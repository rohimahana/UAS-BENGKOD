<x-layouts.app title="Riwayat Pasien">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">Riwayat Pasien</h1>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>No Antrian</th>
                                        <th>Nama Pasien</th>
                                        <th>Keluhan</th>
                                        <th>Tanggal Periksa</th>
                                        <th>Biaya</th>
                                        <th style="width: 150px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($riwayatPasien as $index => $periksa)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $periksa->daftarPoli->no_antrian ?? '-' }}
                                                </span>
                                            </td>
                                            <td>{{ $periksa->daftarPoli->pasien->nama ?? '-' }}</td>
                                            <td>{{ Str::limit($periksa->daftarPoli->keluhan ?? '-', 50) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <strong>Rp
                                                    {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}</strong>
                                            </td>
                                            <td>
                                                <a href="{{ route('dokter.riwayat-pasien.show', $periksa->id) }}"
                                                    class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                <p>Belum ada riwayat pemeriksaan</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#riwayatTable').DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "order": [
                        [4, "desc"]
                    ], // Sort by date descending
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                    }
                });
            });
        </script>
    @endpush
</x-layouts.app>
