<x-layouts.app title="Daftar Poli">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-12">
                {{-- Flash messages handled by SweetAlert2 in layout --}}

                <h1 class="mb-4">Daftar Poli</h1>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Form Pendaftaran Poli</h5>
                            </div>
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <strong>Terjadi Kesalahan!</strong>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('pasien.daftar-poli.submit') }}" method="POST"
                                    id="formDaftarPoli">
                                    @csrf
                                    <input type="hidden" name="id_pasien" value="{{ $pasien->id }}">

                                    <div class="mb-3">
                                        <label for="no_rm" class="form-label">Nomor Rekam Medis</label>
                                        <input type="text" class="form-control" id="no_rm"
                                            value="{{ $pasien->no_rm }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="nama_pasien" class="form-label">Nama Pasien</label>
                                        <input type="text" class="form-control" id="nama_pasien"
                                            value="{{ $pasien->nama }}" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="selectPoli" class="form-label">Pilih Poli</label>
                                        <select name="id_poli" id="selectPoli" class="form-control">
                                            <option value="">-- Pilih Poli --</option>
                                            @forelse ($polis as $poli)
                                                <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                                            @empty
                                                <option value="" disabled>Tidak ada poli tersedia</option>
                                            @endforelse
                                        </select>
                                        <small class="text-muted">Total poli: {{ count($polis) }}</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="selectJadwal" class="form-label">Pilih Jadwal Periksa <span
                                                class="text-danger">*</span></label>
                                        <select name="id_jadwal" id="selectJadwal"
                                            class="form-control @error('id_jadwal') is-invalid @enderror" required>
                                            <option value="">-- Pilih Jadwal --</option>
                                            @forelse ($jadwals as $jadwal)
                                                <option value="{{ $jadwal->id }}"
                                                    data-id-poli="{{ $jadwal->dokter->poli->id ?? '' }}">
                                                    {{ $jadwal->dokter->poli->nama_poli ?? 'Poli tidak tersedia' }} -
                                                    {{ $jadwal->hari }},
                                                    {{ date('H:i', strtotime($jadwal->jam_mulai)) }} -
                                                    {{ date('H:i', strtotime($jadwal->jam_selesai)) }} -
                                                    Dr. {{ $jadwal->dokter->nama ?? 'Tidak tersedia' }}
                                                </option>
                                            @empty
                                                <option value="" disabled>Tidak ada jadwal tersedia saat ini
                                                </option>
                                            @endforelse
                                        </select>
                                        <small class="text-muted">Total jadwal aktif: {{ count($jadwals) }}</small>
                                        @error('id_jadwal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="keluhan" class="form-label">Keluhan <span
                                                class="text-danger">*</span></label>
                                        <textarea name="keluhan" id="keluhan" rows="4" class="form-control @error('keluhan') is-invalid @enderror"
                                            placeholder="Jelaskan keluhan Anda..." required>{{ old('keluhan') }}</textarea>
                                        @error('keluhan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-plus-circle"></i> Daftar Poli
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <!-- Antrian Sedang Menunggu -->
                        <div class="card mb-3">
                            <div class="card-header bg-warning text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-clock"></i> Antrian Sedang Menunggu
                                </h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $antrianMenunggu = \App\Models\DaftarPoli::where('id_pasien', $pasien->id)
                                        ->with(['jadwalPeriksa.dokter.poli'])
                                        ->whereDoesntHave('periksa')
                                        ->orderBy('created_at', 'desc')
                                        ->get();
                                @endphp

                                @if ($antrianMenunggu->count() > 0)
                                    <div class="alert alert-info mb-3">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <strong>{{ $antrianMenunggu->count() }}</strong> antrian Anda sedang menunggu
                                        pemeriksaan
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>No. Antrian</th>
                                                    <th>Poli</th>
                                                    <th>Dokter</th>
                                                    <th>Jadwal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($antrianMenunggu as $antrian)
                                                    <tr>
                                                        <td>
                                                            <span class="badge badge-warning badge-lg">
                                                                {{ $antrian->no_antrian }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <small class="font-weight-bold">
                                                                {{ $antrian->jadwalPeriksa->dokter->poli->nama_poli ?? 'N/A' }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <small>
                                                                {{ $antrian->jadwalPeriksa->dokter->nama ?? 'N/A' }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <small class="text-muted">
                                                                {{ $antrian->jadwalPeriksa->hari ?? 'N/A' }}
                                                            </small>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-3 text-center">
                                        <a href="{{ route('pasien.riwayat') }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-history mr-1"></i> Lihat Semua Riwayat
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-3">
                                        <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                                        <p class="mb-0">Tidak ada antrian yang sedang menunggu</p>
                                        <small class="text-muted">Silakan daftar poli untuk membuat antrian baru</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Informasi & Petunjuk -->
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-info-circle"></i> Petunjuk & Informasi
                                </h5>
                            </div>
                            <div class="card-body">
                                <h6 class="text-primary">
                                    <i class="fas fa-clipboard-list"></i> Langkah Pendaftaran:
                                </h6>
                                <ol class="mb-3">
                                    <li>Pilih <strong>Poli</strong> sesuai keluhan Anda</li>
                                    <li>Pilih <strong>Jadwal Dokter</strong> yang tersedia</li>
                                    <li>Tuliskan <strong>Keluhan</strong> dengan jelas (min. 10 karakter)</li>
                                    <li>Klik tombol <strong>"Daftar Poli"</strong></li>
                                </ol>

                                <hr>

                                <h6 class="text-success">
                                    <i class="fas fa-lightbulb"></i> Tips:
                                </h6>
                                <ul class="mb-0 small">
                                    <li>Anda akan mendapatkan <strong>nomor antrian</strong> otomatis</li>
                                    <li>Datang <strong>15 menit</strong> sebelum jadwal praktik</li>
                                    <li>Bawa <strong>kartu identitas</strong> dan nomor rekam medis</li>
                                    <li>Lihat status antrian di menu <strong>Riwayat Poli</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectPoli = document.getElementById('selectPoli');
                const selectJadwal = document.getElementById('selectJadwal');
                const form = document.getElementById('formDaftarPoli');

                console.log('Poli count:', selectPoli.options.length - 1);
                console.log('Jadwal count:', selectJadwal.options.length - 1);
                console.log('Form action:', form.action);
                console.log('Form method:', form.method);

                // Filter jadwal berdasarkan poli yang dipilih
                selectPoli.addEventListener('change', function() {
                    const poliId = this.value;
                    console.log('Poli dipilih:', poliId);

                    let visibleCount = 0;
                    Array.from(selectJadwal.options).forEach(option => {
                        if (option.value === "") return;

                        if (!poliId) {
                            option.style.display = '';
                            visibleCount++;
                        } else {
                            const isMatch = option.dataset.idPoli == poliId;
                            option.style.display = isMatch ? '' : 'none';
                            if (isMatch) visibleCount++;
                        }
                    });

                    console.log('Jadwal yang cocok:', visibleCount);
                    selectJadwal.value = "";

                    // SweetAlert notification for poli selection
                    if (poliId) {
                        const poliName = selectPoli.options[selectPoli.selectedIndex].text;
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: `Poli ${poliName} dipilih`,
                            text: `${visibleCount} jadwal tersedia`,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                    }
                });

                // Auto-select poli ketika jadwal dipilih
                selectJadwal.addEventListener('change', function() {
                    const selected = this.options[this.selectedIndex];
                    const poliId = selected.dataset.idPoli;
                    console.log('Jadwal dipilih, poli ID:', poliId);

                    if (!selectPoli.value && poliId) {
                        selectPoli.value = poliId;
                        selectPoli.dispatchEvent(new Event('change'));
                    }
                });

                // Form submit validation with SweetAlert2
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // Prevent default submission first

                    const jadwalValue = selectJadwal.value;
                    const keluhanValue = document.getElementById('keluhan').value.trim();

                    console.log('=== FORM SUBMIT DEBUG ===');
                    console.log('Jadwal ID:', jadwalValue);
                    console.log('Keluhan:', keluhanValue);
                    console.log('Keluhan length:', keluhanValue.length);

                    // Validation: Jadwal tidak dipilih
                    if (!jadwalValue) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Jadwal Belum Dipilih!',
                            text: 'Silakan pilih jadwal periksa terlebih dahulu.',
                            confirmButtonText: 'Oke',
                            confirmButtonColor: '#3085d6'
                        });
                        return false;
                    }

                    // Validation: Keluhan kurang dari 10 karakter
                    if (keluhanValue.length < 10) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Keluhan Terlalu Singkat!',
                            text: `Keluhan minimal 10 karakter. Saat ini: ${keluhanValue.length} karakter.`,
                            confirmButtonText: 'Oke',
                            confirmButtonColor: '#f39c12'
                        });
                        return false;
                    }

                    // Validation passed - Show confirmation
                    const selectedJadwalText = selectJadwal.options[selectJadwal.selectedIndex].text;

                    Swal.fire({
                        title: 'Konfirmasi Pendaftaran',
                        html: `
                            <div class="text-left">
                                <p><strong>Jadwal:</strong><br>${selectedJadwalText}</p>
                                <p><strong>Keluhan:</strong><br>${keluhanValue}</p>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: '<i class="fas fa-check"></i> Ya, Daftar!',
                        cancelButtonText: '<i class="fas fa-times"></i> Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'animated fadeInDown'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            Swal.fire({
                                title: 'Sedang Memproses...',
                                html: 'Mohon tunggu sebentar.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            console.log('Form validation passed, submitting to:', form.action);
                            // Submit form
                            form.submit();
                        }
                    });
                });

                // Auto-hide alerts with fade effect
                setTimeout(() => {
                    const alerts = document.querySelectorAll('.alert-success, .alert-warning');
                    alerts.forEach(alert => {
                        alert.classList.add('fade');
                        alert.classList.remove('show');
                        setTimeout(() => alert.remove(), 500);
                    });
                }, 5000);

                // Show SweetAlert for validation errors from server
                @if ($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        html: `
                            <ul class="text-left" style="list-style-position: inside;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        `,
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#dc3545'
                    });
                @endif
            });
        </script>
    @endpush
</x-layouts.app>
