<x-layouts.app title="Edit Obat">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1 class="mb-4">Edit Obat</h1>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.obat.update', $obat->id) }}" method="POST" id="editObatForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="nama_obat" class="form-label">Nama Obat <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('nama_obat') is-invalid @enderror" id="nama_obat"
                                            name="nama_obat" value="{{ old('nama_obat', $obat->nama_obat) }}" required>
                                        @error('nama_obat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="kemasan" class="form-label">Kemasan <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('kemasan') is-invalid @enderror" id="kemasan"
                                            name="kemasan" value="{{ old('kemasan', $obat->kemasan) }}" required>
                                        @error('kemasan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="harga" class="form-label">Harga <span
                                        class="text-danger">*</span></label>
                                <input type="number" id="harga" name="harga"
                                    class="form-control @error('harga') is-invalid @enderror"
                                    value="{{ old('harga', $obat->harga) }}" required min="0" step="1">
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> <strong>Perhatian!</strong><br>
                                Untuk mengubah stok, gunakan tombol <strong>+/-/Set</strong> di halaman daftar obat
                                untuk tracking yang lebih baik.
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="stok" class="form-label">Stok Saat Ini</label>
                                        <input type="number" id="stok" name="stok"
                                            class="form-control @error('stok') is-invalid @enderror"
                                            value="{{ old('stok', $obat->stok) }}" min="0" max="10000">
                                        <small class="text-muted">Stok tersedia: {{ $obat->stok }} unit</small>
                                        @error('stok')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="stok_minimum" class="form-label">Stok Minimum</label>
                                        <input type="number" id="stok_minimum" name="stok_minimum"
                                            class="form-control @error('stok_minimum') is-invalid @enderror"
                                            value="{{ old('stok_minimum', $obat->stok_minimum) }}" min="1"
                                            max="100">
                                        <small class="text-muted">Warning ketika stok ≤ nilai ini</small>
                                        @error('stok_minimum')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <button type="button" class="btn btn-success"
                                    onclick="confirmSubmit('#editObatForm', 'Apakah Anda yakin ingin mengupdate obat ini?')">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <a href="{{ route('admin.obat.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
