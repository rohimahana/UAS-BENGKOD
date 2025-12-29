<x-layouts.app title="Data Obat">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-12">

                {{-- Flash messages will be handled by SweetAlert2 in the layout --}}

                <h1 class="mb-4">Data Obat</h1>

                <a href="{{ route('admin.obat.create') }}" class="btn btn-primary mb-3">
                    <i class="fas fa-plus"></i> Tambah Obat
                </a>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Nama Obat</th>
                                <th>Kemasan</th>
                                <th>Harga</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($obats as $obat)
                                <tr>
                                    <td>{{ $obat->nama_obat }}</td>
                                    <td>{{ $obat->kemasan }}</td>
                                    <td>Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('admin.obat.edit', $obat->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="confirmDelete('{{ route('admin.obat.destroy', $obat->id) }}', 'Obat {{ $obat->nama_obat }}')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="4">
                                        Belum ada data obat
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
