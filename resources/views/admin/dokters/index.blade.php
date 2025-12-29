<x-layouts.app title="Data Dokter">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-12">

                {{-- Flash messages will be handled by SweetAlert2 in the layout --}}

                <h1 class="mb-4">Data Dokter</h1>

                <a href="{{ route('admin.dokter.create') }}" class="btn btn-primary mb-3">
                    <i class="fas fa-plus"></i> Tambah Dokter
                </a>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Nama Dokter</th>
                                <th>Email</th>
                                <th>No. KTP</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th>Poli</th>
                                <th style="width: 150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dokters as $index => $dokter)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $dokter->nama }}</td>
                                    <td>{{ $dokter->email }}</td>
                                    <td>{{ $dokter->no_ktp }}</td>
                                    <td>{{ $dokter->no_hp }}</td>
                                    <td>{{ $dokter->alamat }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $dokter->poli->nama_poli ?? 'Belum Di pilih' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.dokter.edit', $dokter) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>Edit
                                        </a>
                                        <form action="{{ route('admin.dokter.destroy', $dokter) }}" method="POST"
                                            style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete('{{ route('admin.dokter.destroy', $dokter) }}', 'Dokter {{ $dokter->nama }}')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="8">
                                        Belum ada Dokter
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
