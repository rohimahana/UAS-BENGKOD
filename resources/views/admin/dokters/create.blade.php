<x-layouts.app title="Tambah Dokter">
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
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Tambah Dokter</h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Buat akun dokter, lengkapi kontak, dan tentukan poli penugasan.
                    </p>
                </div>

                <a href="{{ route('admin.dokter.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                    <i class="fa-solid fa-arrow-left text-xs text-slate-500"></i>
                    Kembali
                </a>
            </div>

            <div class="mt-6 rounded-3xl border border-slate-200 bg-white shadow-sm">
                <form action="{{ route('admin.dokter.store') }}" method="POST" id="dokterForm">
                    @csrf

                    <div class="border-b border-slate-200 p-5">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold text-slate-900">Profil Dokter</div>
                            <span class="rounded-full px-2 py-1 text-xs font-semibold badge-brand">Wajib diisi</span>
                        </div>
                        <p class="mt-1 text-sm text-slate-600">Informasi dasar untuk akun dokter.</p>
                    </div>

                    <div class="grid gap-5 p-5 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <label for="nama" class="text-sm font-semibold text-slate-700">Nama Lengkap <span class="text-rose-600">*</span></label>
                            <input id="nama" name="nama" type="text" required
                                value="{{ old('nama') }}"
                                placeholder="Contoh: dr. Andi Saputra"
                                class="focus-brand mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 @error('nama') is-invalid @enderror">
                            @error('nama')
                                <div class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="sm:col-span-1">
                            <label for="email" class="text-sm font-semibold text-slate-700">Email <span class="text-rose-600">*</span></label>
                            <input id="email" name="email" type="email" required
                                value="{{ old('email') }}"
                                placeholder="nama@email.com"
                                class="focus-brand mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="sm:col-span-1">
                            <label for="no_ktp" class="text-sm font-semibold text-slate-700">No. KTP <span class="text-rose-600">*</span></label>
                            <input id="no_ktp" name="no_ktp" type="text" required maxlength="16" pattern="[0-9]{16}"
                                value="{{ old('no_ktp') }}"
                                placeholder="16 digit angka"
                                class="focus-brand mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 @error('no_ktp') is-invalid @enderror">
                            <div class="mt-2 text-xs help-muted">Harus 16 digit angka.</div>
                            @error('no_ktp')
                                <div class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="sm:col-span-1">
                            <label for="no_hp" class="text-sm font-semibold text-slate-700">No. HP <span class="text-rose-600">*</span></label>
                            <input id="no_hp" name="no_hp" type="text" required
                                value="{{ old('no_hp') }}"
                                placeholder="Contoh: 08xxxxxxxxxx"
                                class="focus-brand mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 @error('no_hp') is-invalid @enderror">
                            @error('no_hp')
                                <div class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="alamat" class="text-sm font-semibold text-slate-700">Alamat <span class="text-rose-600">*</span></label>
                            <textarea id="alamat" name="alamat" rows="3" required
                                placeholder="Alamat lengkap"
                                class="focus-brand mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="id_poli" class="text-sm font-semibold text-slate-700">Poli <span class="text-rose-600">*</span></label>
                            <select id="id_poli" name="id_poli" required
                                class="focus-brand mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 @error('id_poli') is-invalid @enderror">
                                <option value="">Pilih Poli</option>
                                @foreach ($polis as $poli)
                                    <option value="{{ $poli->id }}" {{ old('id_poli') == $poli->id ? 'selected' : '' }}>
                                        {{ $poli->nama_poli }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_poli')
                                <div class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="border-t border-slate-200 p-5">
                        <div class="text-sm font-semibold text-slate-900">Keamanan Akun</div>
                        <p class="mt-1 text-sm text-slate-600">Buat password awal untuk dokter.</p>

                        <div class="mt-4 grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="password" class="text-sm font-semibold text-slate-700">Password <span class="text-rose-600">*</span></label>
                                <div class="relative mt-2">
                                    <input id="password" name="password" type="password" required
                                        placeholder="Minimal 6 karakter"
                                        class="focus-brand w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 pr-12 text-sm text-slate-900 placeholder:text-slate-400 @error('password') is-invalid @enderror">
                                    <button type="button" data-toggle="pw" data-target="password"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="text-sm font-semibold text-slate-700">Konfirmasi Password <span class="text-rose-600">*</span></label>
                                <div class="relative mt-2">
                                    <input id="password_confirmation" name="password_confirmation" type="password" required
                                        placeholder="Ulangi password"
                                        class="focus-brand w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 pr-12 text-sm text-slate-900 placeholder:text-slate-400">
                                    <button type="button" data-toggle="pw" data-target="password_confirmation"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                                <div class="mt-2 text-xs help-muted">Pastikan password sama.</div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-xs text-slate-500">
                            Field bertanda <span class="font-semibold text-rose-600">*</span> wajib diisi.
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('admin.dokter.index') }}"
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Batal
                            </a>
                            <button type="button"
                                class="inline-flex items-center gap-2 rounded-xl px-5 py-2 text-sm font-semibold btn-brand shadow-sm ring-brand"
                                onclick="confirmSubmit('#dokterForm', 'Simpan dokter baru?', 'Data dokter akan dibuat dan bisa digunakan untuk login.')">
                                <i class="fa-solid fa-floppy-disk text-xs"></i>
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Simple show/hide password
            document.querySelectorAll('[data-toggle="pw"]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-target');
                    const input = document.getElementById(id);
                    if (!input) return;

                    input.type = input.type === 'password' ? 'text' : 'password';
                    btn.innerHTML = input.type === 'password'
                        ? '<i class="fa-regular fa-eye"></i>'
                        : '<i class="fa-regular fa-eye-slash"></i>';
                });
            });
        </script>
    @endpush
</x-layouts.app>
