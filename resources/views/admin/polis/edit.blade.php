<x-layouts.app title="Edit Poli">
    @push('styles')
    <style>
        .focus-brand:focus { outline: none !important; box-shadow: 0 0 0 4px rgba(63, 95, 168, .28) !important; }
        .is-invalid { border-color: #fb7185 !important; box-shadow: 0 0 0 4px rgba(251, 113, 133, .18) !important; }
        .help-muted { color: #64748b; }
    </style>
@endpush

    <div class="p-4 sm:p-6">
        <div class="mx-auto max-w-3xl">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Edit Poli</h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Perbarui nama poli dan keterangan bila diperlukan.
                    </p>
                </div>

                <a href="{{ route('admin.poli.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                    <i class="fa-solid fa-arrow-left text-xs text-slate-500"></i>
                    Kembali
                </a>
            </div>

            <div class="mt-6 rounded-3xl border border-slate-200 bg-white shadow-sm">
                <form action="{{ route('admin.poli.update', $poli) }}" method="POST" id="editPoliForm">
                    @csrf
                    @method('PUT')

                    <div class="border-b border-slate-200 p-5">
                        <div class="text-sm font-semibold text-slate-900">Informasi Poli</div>
                        <p class="mt-1 text-sm text-slate-600">Nama poli wajib diisi.</p>
                    </div>

                    <div class="grid gap-5 p-5">
                        <div>
                            <label for="nama_poli" class="text-sm font-semibold text-slate-700">Nama Poli <span class="text-rose-600">*</span></label>
                            <input id="nama_poli" name="nama_poli" type="text" required
                                value="{{ old('nama_poli', $poli->nama_poli) }}"
                                class="focus-brand mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 @error('nama_poli') is-invalid @enderror">
                            @error('nama_poli')
                                <div class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="keterangan" class="text-sm font-semibold text-slate-700">Keterangan</label>
                            <textarea id="keterangan" name="keterangan" rows="3"
                                class="focus-brand mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 @error('keterangan') is-invalid @enderror">{{ old('keterangan', $poli->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-xs text-slate-500">
                            Field bertanda <span class="font-semibold text-rose-600">*</span> wajib diisi.
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('admin.poli.index') }}"
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Batal
                            </a>
                            <button type="button"
                                class="inline-flex items-center gap-2 rounded-xl px-5 py-2 text-sm font-semibold btn-brand shadow-sm ring-brand"
                                onclick="confirmSubmit('#editPoliForm', 'Update data poli?', 'Perubahan akan disimpan ke sistem.')">
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
