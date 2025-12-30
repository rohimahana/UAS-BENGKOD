<x-layouts.app title="Edit Jadwal Periksa">
    <div class="mx-auto max-w-3xl px-4 py-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Jadwal Periksa</h1>
                <p class="mt-1 text-sm text-slate-600">Perbarui hari, jam, dan status jadwal praktik Anda.</p>
            </div>
            <a href="{{ route('dokter.jadwal-periksa.index') }}"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                <i class="fa-solid fa-arrow-left text-brand"></i>
                Kembali
            </a>
        </div>

        <div class="mt-6 rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <div class="flex items-center gap-3">
                    <span class="grid h-10 w-10 place-items-center rounded-2xl bg-slate-100 text-slate-700 ring-brand">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </span>
                    <div>
                        <div class="text-sm font-semibold">Form Edit</div>
                        <div class="text-xs text-slate-500">Gunakan format jam 24 jam.</div>
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

                <form action="{{ route('dokter.jadwal-periksa.update', $jadwalPeriksa) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="hari" class="text-sm font-semibold text-slate-700">Hari</label>
                            <select name="hari" id="hari" required
                                class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:outline-none focus:ring-2 ring-brand @error('hari') border-rose-300 @enderror">
                                <option value="">Pilih Hari</option>
                                @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                                    <option value="{{ $day }}" {{ old('hari', $jadwalPeriksa->hari) == $day ? 'selected' : '' }}>
                                        {{ $day }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hari')
                                <div class="mt-1 text-xs text-rose-700">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="aktif" class="text-sm font-semibold text-slate-700">Status</label>
                            <select name="aktif" id="aktif" required
                                class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:outline-none focus:ring-2 ring-brand @error('aktif') border-rose-300 @enderror">
                                <option value="Y" {{ old('aktif', $jadwalPeriksa->aktif) == 'Y' ? 'selected' : '' }}>Aktif</option>
                                <option value="T" {{ old('aktif', $jadwalPeriksa->aktif) == 'T' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('aktif')
                                <div class="mt-1 text-xs text-rose-700">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="jam_mulai" class="text-sm font-semibold text-slate-700">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" required
                                value="{{ old('jam_mulai', date('H:i', strtotime($jadwalPeriksa->jam_mulai))) }}"
                                class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:outline-none focus:ring-2 ring-brand @error('jam_mulai') border-rose-300 @enderror" />
                            @error('jam_mulai')
                                <div class="mt-1 text-xs text-rose-700">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="jam_selesai" class="text-sm font-semibold text-slate-700">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="jam_selesai" required
                                value="{{ old('jam_selesai', date('H:i', strtotime($jadwalPeriksa->jam_selesai))) }}"
                                class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:outline-none focus:ring-2 ring-brand @error('jam_selesai') border-rose-300 @enderror" />
                            @error('jam_selesai')
                                <div class="mt-1 text-xs text-rose-700">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                        <a href="{{ route('dokter.jadwal-periksa.index') }}"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold btn-brand shadow-sm ring-brand">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
