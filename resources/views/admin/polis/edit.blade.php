<x-layouts.app title="Edit Poli">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1 class="mb-4">Edit Poli</h1>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.poli.update', $poli) }}" method="POST" id="editPoliForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="nama_poli" class="form-label">Nama Poli <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('nama_poli') is-invalid @enderror" id="nama_poli"
                                            name="nama_poli" value="{{ old('nama_poli', $poli->nama_poli) }}"
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
                                    class="form-control @error('keterangan') is-invalid @enderror" placeholder="Masukkan keterangan poli (opsional)">{{ old('keterangan', $poli->keterangan) }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <button type="button" class="btn btn-success"
                                    onclick="confirmSubmit('#editPoliForm', 'Apakah Anda yakin ingin mengupdate poli ini?')">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <a href="{{ route('admin.poli.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
