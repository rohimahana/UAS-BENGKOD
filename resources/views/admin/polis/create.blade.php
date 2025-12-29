<x-layouts.app title="Tambah Poli">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1 class="mb-4">Tambah Poli</h1>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.poli.store') }}" method="POST" id="poliForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="nama_poli" class="form-label">Nama Poli <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('nama_poli') is-invalid @enderror" id="nama_poli"
                                            name="nama_poli" value="{{ old('nama_poli') }}"
                                            placeholder="Masukkan nama poli" required>
                                        @error('nama_poli')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" rows="3"
                                    class="form-control @error('keterangan') is-invalid @enderror" placeholder="Masukkan keterangan poli (opsional)">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <button type="button" class="btn btn-primary"
                                    onclick="confirmSubmit('#poliForm', 'Apakah Anda yakin ingin menambahkan poli ini?')">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="{{ route('admin.poli.index') }}" class="btn btn-secondary">
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
