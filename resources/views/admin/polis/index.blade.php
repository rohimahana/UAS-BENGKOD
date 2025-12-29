<x-layouts.app title="Data Poli">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-12">

                {{-- Flash messages will be handled by SweetAlert2 in the layout --}}

                <h1 class="mb-4">Data Poli</h1>

                <a href="{{ route('admin.poli.create') }}" class="btn btn-primary mb-3">
                    <i class="fas fa-plus"></i> Tambah Poli
                </a>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Nama Poli</th>
                                <th>Keterangan</th>
                                <th style="width: 150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($polis as $index => $poli)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $poli->nama_poli }}</td>
                                    <td>{{ $poli->keterangan }}</td>
                                    <td>
                                        <a href="{{ route('admin.poli.edit', $poli) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="confirmDelete('{{ route('admin.poli.destroy', $poli) }}', 'Poli {{ $poli->nama_poli }}')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="4">
                                        Belum ada Poli
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
