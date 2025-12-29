<x-layouts.app title="Data Pasien">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-12">

                {{-- Flash messages will be handled by SweetAlert2 in the layout --}}

                <h1 class="mb-4">Data Pasien</h1>

                <a href="{{ route('admin.pasien.create') }}" class="btn btn-primary mb-3">
                    <i class="fas fa-plus"></i> Tambah Pasien
                </a>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>No. RM</th>
                                <th>Nama Pasien</th>
                                <th>Email</th>
                                <th>No. KTP</th>
                                <th>No. HP</th>
                                <th>Alamat</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pasiens as $pasien)
                                <tr>
                                    <td>{{ $pasien->no_rm ?? '-' }}</td>
                                    <td>{{ $pasien->nama }}</td>
                                    <td>{{ $pasien->email }}</td>
                                    <td>{{ $pasien->no_ktp }}</td>
                                    <td>{{ $pasien->no_hp }}</td>
                                    <td>{{ Str::limit($pasien->alamat, 50) }}</td>
                                    <td>
                                        <a href="{{ route('admin.pasien.edit', $pasien->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.pasien.destroy', $pasien->id) }}" method="POST"
                                            style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete('{{ route('admin.pasien.destroy', $pasien->id) }}', 'Pasien {{ $pasien->nama }}')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="7">
                                        Belum ada data pasien
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
