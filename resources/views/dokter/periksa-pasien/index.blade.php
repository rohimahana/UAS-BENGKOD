<x-layouts.app title="Periksa Pasien">

    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-12">

                <h1 class="mb-4">Periksa Pasien</h1>

                {{-- Flash messages handled by SweetAlert2 in layout --}}

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="50">No</th>
                                        <th>Nama Pasien</th>
                                        <th>Keluhan</th>
                                        <th width="100">No Antrian</th>
                                        <th width="150">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($daftarPolis as $dp)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $dp->pasien->nama ?? '-' }}</td>
                                            <td>{{ Str::limit($dp->keluhan ?? '-', 80) }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-info badge-lg">
                                                    {{ $dp->no_antrian }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if ($dp->periksa)
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle"></i> Sudah Diperiksa
                                                    </span>
                                                @else
                                                    <a href="{{ route('dokter.periksa-pasien.create', $dp->id) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fas fa-stethoscope"></i> Periksa
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                <p>Tidak ada pasien yang terdaftar untuk diperiksa.</p>
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

    <script>
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 200);
            }
        }, 2000);
    </script>

</x-layouts.app>
