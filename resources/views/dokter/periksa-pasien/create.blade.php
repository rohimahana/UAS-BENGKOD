<x-layouts.app title="Periksa Pasien">
    <div class="mx-auto max-w-6xl px-4 py-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Form Pemeriksaan</h1>
                <p class="mt-1 text-sm text-slate-600">Isi catatan pemeriksaan dan pilih obat (opsional).</p>
            </div>
            <a href="{{ route('dokter.periksa-pasien.index') }}"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                <i class="fa-solid fa-arrow-left text-brand"></i>
                Kembali
            </a>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-5">
            <!-- Patient panel -->
            <div class="lg:col-span-2">
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="grid h-10 w-10 place-items-center rounded-2xl bg-slate-100 text-slate-700 ring-brand">
                                <i class="fa-solid fa-user-injured"></i>
                            </span>
                            <div>
                                <div class="text-sm font-semibold">Informasi Pasien</div>
                                <div class="text-xs text-slate-500">No. antrian & keluhan</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 text-sm">
                        <div class="space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="text-slate-500">Nama</div>
                                <div class="text-right font-semibold text-slate-900">{{ $daftar->pasien->nama ?? '-' }}</div>
                            </div>
                            <div class="flex items-start justify-between gap-3">
                                <div class="text-slate-500">No. RM</div>
                                <div class="text-right font-semibold text-slate-900">{{ $daftar->pasien->no_rm ?? '-' }}</div>
                            </div>
                            <div class="flex items-start justify-between gap-3">
                                <div class="text-slate-500">No. Antrian</div>
                                <div class="text-right font-semibold text-slate-900">{{ $daftar->no_antrian ?? '-' }}</div>
                            </div>
                            <div class="flex items-start justify-between gap-3">
                                <div class="text-slate-500">Poli</div>
                                <div class="text-right font-semibold text-slate-900">{{ $daftar->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</div>
                            </div>
                            <div class="pt-3">
                                <div class="text-slate-500">Keluhan</div>
                                <div class="mt-2 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-slate-700">
                                    {{ $daftar->keluhan ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start gap-3">
                        <span class="grid h-10 w-10 place-items-center rounded-2xl bg-slate-100 text-slate-700 ring-brand">
                            <i class="fa-solid fa-circle-info"></i>
                        </span>
                        <div>
                            <div class="text-sm font-semibold text-slate-900">Tips</div>
                            <ul class="mt-2 list-disc pl-5 text-sm text-slate-600">
                                <li>Pilih obat dan jumlah, lalu klik <b>Tambah</b>.</li>
                                <li>Total harga obat dihitung otomatis dan disimpan ke <b>obat_json</b>.</li>
                                <li>Jika stok habis, obat otomatis tidak bisa dipilih.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form panel -->
            <div class="lg:col-span-3">
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="grid h-10 w-10 place-items-center rounded-2xl btn-brand shadow-sm ring-brand">
                                <i class="fa-solid fa-stethoscope"></i>
                            </span>
                            <div>
                                <div class="text-sm font-semibold">Input Pemeriksaan</div>
                                <div class="text-xs text-slate-500">Pastikan data benar sebelum simpan.</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        @if ($errors->any())
                            <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                                <div class="font-semibold">Terjadi Kesalahan</div>
                                <ul class="mt-2 list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('dokter.periksa-pasien.store') }}" method="POST" class="space-y-5">
                            @csrf
                            <input type="hidden" name="id_daftar_poli" value="{{ $id ?? '' }}">
                            <input type="hidden" name="obat_json" id="obat_json" value="[]">

                            <div>
                                <label for="catatan" class="text-sm font-semibold text-slate-700">Catatan Pemeriksaan</label>
                                <textarea name="catatan" id="catatan" rows="4"
                                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:outline-none focus:ring-2 ring-brand @error('catatan') border-rose-300 @enderror"
                                    placeholder="Tuliskan diagnosa, tindakan, dan catatan lainnya...">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                    <div class="mt-1 text-xs text-rose-700">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="biaya_periksa" class="text-sm font-semibold text-slate-700">Biaya Periksa</label>
                                    <input type="number" name="biaya_periksa" id="biaya_periksa" min="0" value="{{ old('biaya_periksa', 0) }}"
                                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:outline-none focus:ring-2 ring-brand @error('biaya_periksa') border-rose-300 @enderror" />
                                    @error('biaya_periksa')
                                        <div class="mt-1 text-xs text-rose-700">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <div class="text-sm font-semibold text-slate-700">Total Harga Obat</div>
                                    <div id="total-harga" class="mt-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900">
                                        Rp0
                                    </div>
                                    <div class="mt-1 text-xs text-slate-500">Dihitung otomatis dari daftar obat.</div>
                                </div>
                            </div>

                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">Obat</div>
                                        <div class="text-xs text-slate-600">Pilih obat dan jumlah, lalu tambahkan ke daftar.</div>
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold badge-brand">Opsional</span>
                                </div>

                                <div class="mt-4 grid gap-3 sm:grid-cols-5">
                                    <div class="sm:col-span-3">
                                        <label for="select-obat" class="text-xs font-semibold text-slate-600">Pilih Obat</label>
                                        <select id="select-obat"
                                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:outline-none focus:ring-2 ring-brand">
                                            <option value="">-- Pilih Obat --</option>
                                            @foreach ($obats as $obat)
                                                <option value="{{ $obat->id }}" data-nama="{{ $obat->nama_obat }}"
                                                    data-harga="{{ $obat->harga }}" data-stok="{{ $obat->stok }}"
                                                    {{ $obat->stok <= 0 ? 'disabled' : '' }}>
                                                    {{ $obat->nama_obat }} - Rp{{ number_format($obat->harga) }}
                                                    @if ($obat->stok <= 0)
                                                        (Stok habis)
                                                    @elseif($obat->stok <= $obat->stok_minimum)
                                                        (Menipis: {{ $obat->stok }})
                                                    @else
                                                        (Stok: {{ $obat->stok }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="stok-hint" class="mt-1 text-xs text-slate-500"></div>
                                    </div>

                                    <div class="sm:col-span-1">
                                        <label for="jumlah-obat" class="text-xs font-semibold text-slate-600">Jumlah</label>
                                        <input id="jumlah-obat" type="number" min="1" value="1"
                                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:outline-none focus:ring-2 ring-brand" />
                                    </div>

                                    <div class="sm:col-span-1 flex items-end">
                                        <button type="button" id="btn-tambah-obat"
                                            class="w-full inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-3 text-sm font-semibold btn-brand shadow-sm ring-brand">
                                            <i class="fa-solid fa-plus"></i>
                                            Tambah
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <div class="text-xs font-semibold text-slate-600">Daftar Obat Terpilih</div>
                                    <div id="obat-terpilih" class="mt-2 space-y-2"></div>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                                <a href="{{ route('dokter.periksa-pasien.index') }}"
                                    class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold btn-brand shadow-sm ring-brand">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                    Simpan Pemeriksaan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <script>
                    const selectObat = document.getElementById('select-obat');
                    const jumlahObat = document.getElementById('jumlah-obat');
                    const btnTambahObat = document.getElementById('btn-tambah-obat');
                    const listObat = document.getElementById('obat-terpilih');
                    const inputObatJson = document.getElementById('obat_json');
                    const totalHargaEl = document.getElementById('total-harga');
                    const stokHint = document.getElementById('stok-hint');

                    let daftarObat = [];

                    function notify(type, message) {
                        if (typeof window.toast === 'function') return window.toast({ type, message });
                        alert(message);
                    }

                    function formatRupiah(n) {
                        try {
                            return 'Rp' + (Number(n) || 0).toLocaleString('id-ID');
                        } catch (e) {
                            return 'Rp' + n;
                        }
                    }

                    function renderList() {
                        listObat.innerHTML = '';
                        let total = 0;

                        daftarObat.forEach((o, idx) => {
                            const subtotal = (Number(o.harga) || 0) * (Number(o.jumlah) || 0);
                            total += subtotal;

                            const row = document.createElement('div');
                            row.className = 'flex flex-col gap-2 rounded-2xl border border-slate-200 bg-white p-4 sm:flex-row sm:items-center sm:justify-between';
                            row.innerHTML = `
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-slate-900">${o.nama}</div>
                                    <div class="mt-1 text-xs text-slate-600">
                                        Harga: ${formatRupiah(o.harga)} • Jumlah: <b>${o.jumlah}</b> • Subtotal: <b>${formatRupiah(subtotal)}</b>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                                        onclick="window.__editObat(${idx})">Edit</button>
                                    <button type="button" class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100"
                                        onclick="window.__hapusObat(${idx})">Hapus</button>
                                </div>
                            `;
                            listObat.appendChild(row);
                        });

                        totalHargaEl.textContent = formatRupiah(total);
                        inputObatJson.value = JSON.stringify(daftarObat);
                    }

                    window.__hapusObat = function(idx) {
                        daftarObat.splice(idx, 1);
                        renderList();
                    };

                    window.__editObat = function(idx) {
                        const item = daftarObat[idx];
                        if (!item) return;

                        selectObat.value = item.id;
                        jumlahObat.value = item.jumlah;

                        // remove old then re-add on click
                        daftarObat.splice(idx, 1);
                        renderList();
                        applyStokHint();
                    };

                    function getSelectedMeta() {
                        const opt = selectObat.options[selectObat.selectedIndex];
                        const id = opt?.value;
                        if (!id) return null;
                        return {
                            id,
                            nama: opt.dataset.nama,
                            harga: parseInt(opt.dataset.harga || '0'),
                            stok: parseInt(opt.dataset.stok || '0'),
                        };
                    }

                    function applyStokHint() {
                        const meta = getSelectedMeta();
                        if (!meta) {
                            stokHint.textContent = '';
                            jumlahObat.removeAttribute('max');
                            return;
                        }
                        jumlahObat.setAttribute('max', Math.max(meta.stok, 1));
                        stokHint.textContent = meta.stok <= 0
                            ? 'Stok habis (tidak bisa dipilih).'
                            : `Stok tersedia: ${meta.stok}`;
                    }

                    selectObat.addEventListener('change', () => {
                        jumlahObat.value = 1;
                        applyStokHint();
                    });

                    // Event tombol tambah
                    btnTambahObat.addEventListener('click', () => {
                        const meta = getSelectedMeta();
                        const jumlah = parseInt(jumlahObat.value || '0');

                        if (!meta) {
                            notify('warning', 'Silakan pilih obat terlebih dahulu.');
                            return;
                        }

                        if (!jumlah || jumlah <= 0) {
                            notify('warning', 'Jumlah obat minimal 1.');
                            return;
                        }

                        if (jumlah > meta.stok) {
                            notify('error', `Stok ${meta.nama} tersisa ${meta.stok} unit.`);
                            return;
                        }

                        const existingIndex = daftarObat.findIndex(o => o.id == meta.id);
                        if (existingIndex !== -1) {
                            const totalJumlahBaru = (Number(daftarObat[existingIndex].jumlah) || 0) + jumlah;
                            if (totalJumlahBaru > meta.stok) {
                                notify('error', `Total jumlah ${meta.nama} melebihi stok (${meta.stok}).`);
                                return;
                            }
                            daftarObat[existingIndex].jumlah = totalJumlahBaru;
                        } else {
                            daftarObat.push({ ...meta, jumlah });
                        }

                        selectObat.value = '';
                        jumlahObat.value = 1;
                        applyStokHint();
                        renderList();
                    });

                    applyStokHint();
                    renderList();
                </script>
            </div>
        </div>
    </div>
</x-layouts.app>
