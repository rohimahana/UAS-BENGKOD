<x-layouts.app title="Edit Jadwal Periksa">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1 class="mb-4">Edit Jadwal Periksa</h1>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('dokter.jadwal-periksa.update', $jadwalPeriksa) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-3">
                                <label for="hari" class="form-label">Hari <span class="text-danger">*</span></label>
                                <select name="hari" id="hari"
                                    class="form-select @error('hari') is-invalid @enderror" required>
                                    <option value="">Pilih Hari</option>
                                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                                        <option value="{{ $day }}"
                                            {{ (old('hari') ?? $jadwalPeriksa->hari) == $day ? 'selected' : '' }}>
                                            {{ $day }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hari')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="jam_mulai" class="form-label">Jam Mulai <span
                                                class="text-danger">*</span></label>
                                        <input type="time" name="jam_mulai" id="jam_mulai"
                                            class="form-control @error('jam_mulai') is-invalid @enderror"
                                            value="{{ old('jam_mulai') ?? date('H:i', strtotime($jadwalPeriksa->jam_mulai)) }}"
                                            required>
                                        @error('jam_mulai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="jam_selesai" class="form-label">Jam Selesai <span
                                                class="text-danger">*</span></label>
                                        <input type="time" name="jam_selesai" id="jam_selesai"
                                            class="form-control @error('jam_selesai') is-invalid @enderror"
                                            value="{{ old('jam_selesai') ?? date('H:i', strtotime($jadwalPeriksa->jam_selesai)) }}"
                                            required>
                                        @error('jam_selesai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="aktif" class="form-label">Status Jadwal <span
                                        class="text-danger">*</span></label>
                                <select name="aktif" id="aktif"
                                    class="form-select @error('aktif') is-invalid @enderror" required>
                                    <option value="Y"
                                        {{ (old('aktif') ?? $jadwalPeriksa->aktif) == 'Y' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="T"
                                        {{ (old('aktif') ?? $jadwalPeriksa->aktif) == 'T' ? 'selected' : '' }}>Tidak
                                        Aktif
                                    </option>
                                </select>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> Jadwal yang aktif akan muncul di daftar pilihan
                                    pasien
                                </small>
                                @error('aktif')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <a href="{{ route('dokter.jadwal-periksa.index') }}" class="btn btn-secondary">
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
