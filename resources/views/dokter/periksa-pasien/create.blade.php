<x-layouts.app title="Periksa Pasien">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Form Pemeriksaan Pasien</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('dokter.periksa-pasien.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id_daftar_poli" value="{{ $id ?? ($daftar->id ?? '') }}">

                            <div class="form-group mb-3">
                                <label for="obat" class="form-label">Pilih Obat</label>
                                <select id="select-obat" class="form-control">
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach ($obats as $obat)
                                        <option value="{{ $obat->id }}" data-nama="{{ $obat->nama_obat }}"
                                            data-harga="{{ $obat->harga }}" data-stok="{{ $obat->stok }}"
                                            {{ $obat->stok <= 0 ? 'disabled' : '' }}>
                                            {{ $obat->nama_obat }} - Rp{{ number_format($obat->harga) }}
                                            @if ($obat->stok <= 0)
                                                ⚠ <span style="color: red;">Stok Habis</span>
                                            @elseif($obat->stok <= $obat->stok_minimum)
                                                (Tersisa: {{ $obat->stok }} unit)
                                            @else
                                                (Stok: {{ $obat->stok }} unit)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="jumlah-obat" class="form-label">Jumlah</label>
                                <input type="number" id="jumlah-obat" class="form-control" min="1"
                                    value="1" placeholder="Masukkan jumlah obat">
                                <small class="text-muted">Tentukan jumlah obat yang akan diberikan</small>
                            </div>

                            <div class="form-group mb-3">
                                <button type="button" id="btn-tambah-obat" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Obat
                                </button>
                            </div>

                            <div class="form-group mb-3">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea name="catatan" id="catatan" class="form-control" rows="4" required>{{ old('catatan') }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label>Obat Terpilih</label>
                                <ul id="obat-terpilih" class="list-group mb-2"></ul>
                                <input type="hidden" name="biaya_periksa" id="biaya_periksa" value="0">
                                <input type="hidden" name="obat_json" id="obat_json">
                            </div>

                            <div class="form-group mb-3">
                                <label>Total Harga</label>
                                <div id="total-harga"></div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="{{ route('dokter.periksa-pasien.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const selectObat = document.getElementById('select-obat');
            const jumlahObat = document.getElementById('jumlah-obat');
            const btnTambahObat = document.getElementById('btn-tambah-obat');
            const listObat = document.getElementById('obat-terpilih');
            const inputBiaya = document.getElementById('biaya_periksa');
            const inputObatJson = document.getElementById('obat_json');
            const totalHargaEl = document.getElementById('total-harga');

            let daftarObat = [];

            // Event untuk tombol tambah obat
            btnTambahObat.addEventListener('click', () => {
                const selectedOption = selectObat.options[selectObat.selectedIndex];
                const id = selectedOption.value;
                const nama = selectedOption.dataset.nama;
                const harga = parseInt(selectedOption.dataset.harga || 0);
                const stok = parseInt(selectedOption.dataset.stok || 0);
                const jumlah = parseInt(jumlahObat.value || 1);

                // Validasi
                if (!id) {
                    alert('⚠ Silakan pilih obat terlebih dahulu!');
                    return;
                }

                if (jumlah <= 0) {
                    alert('⚠ Jumlah harus lebih dari 0!');
                    return;
                }

                if (jumlah > stok) {
                    alert(
                        `⚠ Stok tidak mencukupi!\n\nObat: ${nama}\nJumlah diminta: ${jumlah} unit\nStok tersedia: ${stok} unit`);
                    return;
                }

                // Cek apakah obat sudah ada di daftar
                const existingIndex = daftarObat.findIndex(o => o.id == id);
                if (existingIndex >= 0) {
                    const totalJumlahBaru = daftarObat[existingIndex].jumlah + jumlah;

                    if (totalJumlahBaru > stok) {
                        alert(
                            `⚠ Stok tidak mencukupi!\n\nObat: ${nama}\nTotal jumlah: ${totalJumlahBaru} unit\nStok tersedia: ${stok} unit`);
                        return;
                    }

                    daftarObat[existingIndex].jumlah = totalJumlahBaru;
                } else {
                    daftarObat.push({
                        id,
                        nama,
                        harga,
                        stok,
                        jumlah
                    });
                }

                renderObat();
                selectObat.selectedIndex = 0;
                jumlahObat.value = 1;
            });

            function renderObat() {
                listObat.innerHTML = '';
                let total = 0;

                daftarObat.forEach((obat, index) => {
                    const subtotal = obat.harga * obat.jumlah;
                    total += subtotal;

                    const item = document.createElement('li');
                    item.className = 'list-group-item d-flex justify-content-between align-items-center';
                    item.innerHTML = `
                    <div>
                        <strong>${obat.nama}</strong><br>
                        <small class="text-muted">
                            ${obat.jumlah} x Rp ${obat.harga.toLocaleString('id-ID')} = 
                            <strong>Rp ${subtotal.toLocaleString('id-ID')}</strong>
                        </small>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger" onclick="hapusObat(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                    listObat.appendChild(item);
                });

                // Update total
                const biayaJasaDokter = 150000; // Biaya jasa dokter tetap
                const totalBiaya = biayaJasaDokter + total;

                inputBiaya.value = totalBiaya;
                totalHargaEl.innerHTML = `
                    <div class="alert alert-info">
                        <div>Biaya Jasa Dokter: <strong>Rp ${biayaJasaDokter.toLocaleString('id-ID')}</strong></div>
                        <div>Biaya Obat: <strong>Rp ${total.toLocaleString('id-ID')}</strong></div>
                        <hr class="my-2">
                        <div class="fs-5">Total Biaya: <strong>Rp ${totalBiaya.toLocaleString('id-ID')}</strong></div>
                    </div>
                `;

                // Update JSON untuk dikirim ke controller (format: [{id: 1, jumlah: 2}, ...])
                inputObatJson.value = JSON.stringify(daftarObat.map(o => ({
                    id: o.id,
                    jumlah: o.jumlah
                })));
            }

            function hapusObat(index) {
                daftarObat.splice(index, 1);
                renderObat();
            }

            // Initial render (kosong)
            renderObat();
        </script>
    @endpush
</x-layouts.app>
