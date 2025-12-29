<x-layouts.app title="Tambah Dokter">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1 class="mb-4">Tambah Dokter</h1>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.dokter.store') }}" method="POST" id="dokterForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="nama" class="form-label">Nama Lengkap <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                            id="nama" name="nama" value="{{ old('nama') }}"
                                            placeholder="Masukkan nama lengkap" required>
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}"
                                            placeholder="Masukkan email" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="no_ktp" class="form-label">No KTP <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('no_ktp') is-invalid @enderror"
                                            id="no_ktp" name="no_ktp" value="{{ old('no_ktp') }}"
                                            placeholder="Masukkan nomor KTP" maxlength="16" pattern="[0-9]{16}"
                                            title="No KTP harus 16 digit angka" required>
                                        @error('no_ktp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">No KTP harus 16 digit angka</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="no_hp" class="form-label">No HP <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                            id="no_hp" name="no_hp" value="{{ old('no_hp') }}"
                                            placeholder="Masukkan nomor HP" required>
                                        @error('no_hp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="alamat" class="form-label">Alamat <span
                                        class="text-danger">*</span></label>
                                <textarea required name="alamat" id="alamat" rows="3"
                                    class="form-control @error('alamat') is-invalid @enderror" placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="id_poli" class="form-label">Poli <span class="text-danger">*</span></label>
                                <select name="id_poli" id="id_poli"
                                    class="form-control @error('id_poli') is-invalid @enderror" required>
                                    <option value="">Pilih Poli</option>
                                    @foreach ($polis as $poli)
                                        <option value="{{ $poli->id }}"
                                            {{ old('id_poli') == $poli->id ? 'selected' : '' }}>
                                            {{ $poli->nama_poli }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_poli')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="password" class="form-label">Password <span
                                                class="text-danger">*</span></label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" id="password"
                                            name="password" placeholder="Masukkan password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" placeholder="Masukkan ulang password"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <button type="button" class="btn btn-primary"
                                    onclick="confirmSubmit('#dokterForm', 'Apakah Anda yakin ingin menambahkan dokter ini?')">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="{{ route('admin.dokter.index') }}" class="btn btn-secondary">
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
