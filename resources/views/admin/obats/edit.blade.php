<x-layouts.app title="Edit Obat">
    @push('styles')
    <style>
        .focus-brand:focus { outline: none !important; box-shadow: 0 0 0 4px rgba(63, 95, 168, .28) !important; }
        .is-invalid { border-color: #fb7185 !important; box-shadow: 0 0 0 4px rgba(251, 113, 133, .18) !important; }
        .help-muted { color: #64748b; }
    </style>
@endpush

    <div class="p-4 sm:p-6">
        <div class="mx-auto max-w-4xl">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Edit Obat</h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Perbarui informasi obat. Untuk tracking stok yang rapi, sebaiknya ubah stok melalui aksi di daftar obat.
                    </p>
                </div>

                <a href="{{ route('admin.obat.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                    <i class="fa-solid fa-arrow-left text-xs text-slate-500"></i>
                    Kembali
                </a>
            </div>

            <div class="mt-6 rounded-3xl border border-slate-200 bg-white shadow-sm">
                <form action="{{ route('admin.obat.update', $obat->id) }}" method="POST" id="editObatForm">
                    @csrf
                    @method('PUT')

                    <div class="border-b border-slate-200 p-5">
                        <div class="text-sm font-semibold text-slate-900">Informasi Obat</div>
                        <p class="mt-1 text-sm text-slate-600">Nama, kemasan, dan harga.</p>
                    </div>

                    <div class="grid gap-5 p-5 sm:grid-cols-2">
                        <div>
                            <label for="nama_obat" class="text-sm font-semibold text-slate-700">Nama Obat <span class="text-rose-600">*</span></label>
                            <input id="nama_obat" name="nama_obat" type="text" required
                                value="{{ old('nama_obat', $obat->nama_obat) }}"
                                class="focus-brand mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 @error('nama_obat') is-invalid @enderror">
                            @error('nama_obat')
                                <div class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="kemasan" class="text-sm font-semibold text-slate-700">Kemasan <span class="text-rose-600">*</span></label>
                            <input id="kemasan" name="kemasan" type="text" required
                                value="{{ old('kemasan', $obat->kemasan) }}"
                                class="focus-brand mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 @error('kemasan') is-invalid @enderror">
                            @error('kemasan')
                                <div class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="harga" class="text-sm font-semibold text-slate-700">Harga (Rp) <span class="text-rose-600">*</span></label>
                            <input id="harga" name="harga" type="number" required min="0" step="1"
                                value="{{ old('harga', $obat->harga) }}"
                                class="focus-brand mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 @error('harga') is-invalid @enderror">
                            @error('harga')
                                <div class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="border-t border-slate-200 p-5">
                        <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4">
                            <div class="mt-0.5 text-amber-700"><i class="fa-solid fa-triangle-exclamation"></i></div>
                            <div>
                                <div class="text-sm font-semibold text-amber-900">Catatan Stok</div>
                                <p class="mt-1 text-sm text-amber-800">
                                    Idealnya, stok diubah melalui aksi <strong>+/−/Set</strong> di daftar obat agar tracking lebih jelas.
                                    Jika sistem kamu masih mengizinkan edit stok di sini, gunakan dengan hati-hati.
                                </p>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="stok" class="text-sm font-semibold text-slate-700">Stok Saat Ini</label>
                                <input id="stok" name="stok" type="number" min="0" max="10000"
                                    value="{{ old('stok', $obat->stok) }}"
                                    class="focus-brand mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 @error('stok') is-invalid @enderror">
                                <div class="mt-2 text-xs help-muted">Stok tersedia: {{ $obat->stok }} unit</div>
                                @error('stok')
                                    <div class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="stok_minimum" class="text-sm font-semibold text-slate-700">Stok Minimum</label>
                                <input id="stok_minimum" name="stok_minimum" type="number" min="1" max="100"
                                    value="{{ old('stok_minimum', $obat->stok_minimum) }}"
                                    class="focus-brand mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 @error('stok_minimum') is-invalid @enderror">
                                <div class="mt-2 text-xs help-muted">Warning ketika stok ≤ nilai ini.</div>
                                @error('stok_minimum')
                                    <div class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-xs text-slate-500">
                            Field bertanda <span class="font-semibold text-rose-600">*</span> wajib diisi.
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('admin.obat.index') }}"
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Batal
                            </a>
                            <button type="button"
                                class="inline-flex items-center gap-2 rounded-xl px-5 py-2 text-sm font-semibold btn-brand shadow-sm ring-brand"
                                onclick="confirmSubmit('#editObatForm', 'Update data obat?', 'Perubahan akan disimpan ke sistem.')">
                                <i class="fa-solid fa-floppy-disk text-xs"></i>
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
