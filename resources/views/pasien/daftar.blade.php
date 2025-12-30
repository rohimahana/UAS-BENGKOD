<x-layouts.app title="Daftar Poli">
    <div class="mx-auto max-w-6xl px-4 py-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Daftar Poli</h1>
                <p class="mt-1 text-sm text-slate-600">Pilih poli, jadwal, lalu tuliskan keluhan Anda.</p>
            </div>
            <a href="{{ route('pasien.riwayat') }}"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                <i class="fa-solid fa-clock-rotate-left text-brand"></i>
                Riwayat Kunjungan
            </a>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-5">
            <!-- Form -->
            <div class="lg:col-span-3">
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="grid h-10 w-10 place-items-center rounded-2xl bg-slate-100 text-slate-700 ring-brand">
                                <i class="fa-solid fa-notes-medical"></i>
                            </span>
                            <div>
                                <div class="text-sm font-semibold">Form Pendaftaran Poli</div>
                                <div class="text-xs text-slate-500">Pastikan jadwal dipilih sebelum submit.</div>
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

                        <form action="{{ route('pasien.daftar-poli.submit') }}" method="POST" id="formDaftarPoli"
                            class="space-y-4">
                            @csrf
                            <input type="hidden" name="id_pasien" value="{{ $pasien->id }}">

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="no_rm" class="text-sm font-semibold text-slate-700">No. Rekam Medis</label>
                                    <input id="no_rm" type="text" value="{{ $pasien->no_rm }}" readonly
                                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700" />
                                </div>
                                <div>
                                    <label for="nama_pasien" class="text-sm font-semibold text-slate-700">Nama Pasien</label>
                                    <input id="nama_pasien" type="text" value="{{ $pasien->nama }}" readonly
                                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700" />
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="selectPoli" class="text-sm font-semibold text-slate-700">Pilih Poli</label>
                                    <select name="id_poli" id="selectPoli"
                                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:outline-none focus:ring-2 ring-brand">
                                        <option value="">-- Pilih Poli --</option>
                                        @forelse ($polis as $poli)
                                            <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                                        @empty
                                            <option value="" disabled>Tidak ada poli tersedia</option>
                                        @endforelse
                                    </select>
                                    <div class="mt-1 text-xs text-slate-500">Total poli: {{ count($polis) }}</div>
                                </div>

                                <div>
                                    <label for="selectJadwal" class="text-sm font-semibold text-slate-700">
                                        Pilih Jadwal <span class="text-rose-600">*</span>
                                    </label>
                                    <select name="id_jadwal" id="selectJadwal" required
                                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:outline-none focus:ring-2 ring-brand @error('id_jadwal') border-rose-300 @enderror">
                                        <option value="">-- Pilih Jadwal --</option>
                                        @forelse ($jadwals as $jadwal)
                                            <option value="{{ $jadwal->id }}" data-id-poli="{{ $jadwal->dokter->poli->id ?? '' }}">
                                                {{ $jadwal->dokter->poli->nama_poli ?? 'Poli tidak tersedia' }} -
                                                {{ $jadwal->hari }},
                                                {{ date('H:i', strtotime($jadwal->jam_mulai)) }} -
                                                {{ date('H:i', strtotime($jadwal->jam_selesai)) }} -
                                                Dr. {{ $jadwal->dokter->nama ?? 'Tidak tersedia' }}
                                            </option>
                                        @empty
                                            <option value="" disabled>Tidak ada jadwal tersedia saat ini</option>
                                        @endforelse
                                    </select>
                                    <div class="mt-1 text-xs text-slate-500">Total jadwal aktif: {{ count($jadwals) }}</div>
                                    @error('id_jadwal')
                                        <div class="mt-1 text-xs text-rose-700">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="keluhan" class="text-sm font-semibold text-slate-700">
                                    Keluhan <span class="text-rose-600">*</span>
                                </label>
                                <textarea name="keluhan" id="keluhan" rows="4" required
                                    placeholder="Jelaskan keluhan Anda..."
                                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:outline-none focus:ring-2 ring-brand @error('keluhan') border-rose-300 @enderror">{{ old('keluhan') }}</textarea>
                                @error('keluhan')
                                    <div class="mt-1 text-xs text-rose-700">{{ $message }}</div>
                                @enderror
                                <div class="mt-1 text-xs text-slate-500">Minimal 10 karakter.</div>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="text-xs text-slate-500">
                                    Dengan menekan tombol daftar, data Anda akan masuk ke antrian pemeriksaan.
                                </div>

                                <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold btn-brand shadow-sm ring-brand">
                                    <i class="fa-solid fa-plus"></i>
                                    Daftar Poli
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Side panel -->
            <div class="lg:col-span-2">
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="grid h-10 w-10 place-items-center rounded-2xl bg-slate-100 text-slate-700 ring-brand">
                                <i class="fa-solid fa-list-ol"></i>
                            </span>
                            <div>
                                <div class="text-sm font-semibold">Antrian Anda</div>
                                <div class="text-xs text-slate-500">Daftar yang belum diperiksa</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        @php
                            $antrianMenunggu = \App\Models\DaftarPoli::where('id_pasien', $pasien->id)
                                ->with(['jadwalPeriksa.dokter.poli'])
                                ->whereDoesntHave('periksa')
                                ->orderBy('created_at', 'desc')
                                ->get();
                        @endphp

                        @if ($antrianMenunggu->count() > 0)
                            <div class="mb-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                                <span class="font-semibold">{{ $antrianMenunggu->count() }}</span> antrian sedang menunggu pemeriksaan.
                            </div>

                            <div class="overflow-hidden rounded-2xl border border-slate-200">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-600">
                                        <tr>
                                            <th class="px-4 py-3 text-left">No</th>
                                            <th class="px-4 py-3 text-left">Poli</th>
                                            <th class="px-4 py-3 text-left">Dokter</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200">
                                        @foreach ($antrianMenunggu as $antrian)
                                            <tr class="bg-white">
                                                <td class="px-4 py-3 font-semibold text-slate-900">
                                                    {{ $antrian->no_antrian ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3 text-slate-700">
                                                    {{ $antrian->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3 text-slate-700">
                                                    {{ $antrian->jadwalPeriksa->dokter->nama ?? '-' }}
                                                </td>
                                            </tr>
                                            <tr class="bg-white/60">
                                                <td class="px-4 pb-3 text-xs text-slate-500" colspan="3">
                                                    Jadwal:
                                                    <span class="font-semibold text-slate-700">
                                                        {{ $antrian->jadwalPeriksa->hari ?? '-' }},
                                                        {{ isset($antrian->jadwalPeriksa->jam_mulai) ? date('H:i', strtotime($antrian->jadwalPeriksa->jam_mulai)) : '-' }}
                                                        -
                                                        {{ isset($antrian->jadwalPeriksa->jam_selesai) ? date('H:i', strtotime($antrian->jadwalPeriksa->jam_selesai)) : '-' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 text-xs text-slate-500">
                                Tips: pilih poli terlebih dahulu agar jadwal lebih mudah disaring.
                            </div>
                        @else
                            <div class="grid place-items-center rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-6 text-center">
                                <div class="grid h-12 w-12 place-items-center rounded-2xl bg-white shadow-sm ring-brand">
                                    <i class="fa-regular fa-face-smile text-brand"></i>
                                </div>
                                <div class="mt-3 text-sm font-semibold text-slate-900">Belum ada antrian aktif</div>
                                <div class="mt-1 text-xs text-slate-600">Silakan daftar poli untuk mendapatkan nomor antrian.</div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start gap-3">
                        <span class="grid h-10 w-10 place-items-center rounded-2xl bg-slate-100 text-slate-700 ring-brand">
                            <i class="fa-solid fa-circle-info"></i>
                        </span>
                        <div>
                            <div class="text-sm font-semibold">Petunjuk singkat</div>
                            <ol class="mt-2 list-decimal pl-5 text-sm text-slate-600">
                                <li>Pilih poli.</li>
                                <li>Pilih jadwal dokter yang tersedia.</li>
                                <li>Tuliskan keluhan minimal 10 karakter.</li>
                                <li>Submit untuk mendapatkan nomor antrian.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectPoli = document.getElementById('selectPoli');
        const selectJadwal = document.getElementById('selectJadwal');
        const form = document.getElementById('formDaftarPoli');

        // Filter jadwal berdasarkan poli yang dipilih
        selectPoli.addEventListener('change', function() {
            const poliId = this.value;
            selectJadwal.value = '';

            Array.from(selectJadwal.options).forEach(option => {
                if (!option.value) return;
                const optionPoliId = option.getAttribute('data-id-poli');
                option.hidden = !!poliId && optionPoliId !== poliId;
            });
        });

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const jadwalValue = selectJadwal.value;
            const keluhanValue = document.getElementById('keluhan').value.trim();

            if (!jadwalValue) {
                window.toast?.({ type: 'error', title: 'Jadwal belum dipilih', message: 'Silakan pilih jadwal periksa terlebih dahulu.' });
                selectJadwal.focus();
                return;
            }

            if (keluhanValue.length < 10) {
                window.toast?.({ type: 'warning', title: 'Keluhan terlalu singkat', message: `Keluhan minimal 10 karakter. Saat ini: ${keluhanValue.length} karakter.` });
                document.getElementById('keluhan').focus();
                return;
            }

            const ok = await window.confirmModal?.({
                title: 'Konfirmasi Pendaftaran',
                message: 'Apakah data sudah benar dan Anda ingin mendaftar poli?',
                confirmText: 'Ya, Daftar',
                cancelText: 'Batal',
                variant: 'brand'
            });

            if (ok) form.submit();
        });
    });
</script>
    </div>
</x-layouts.app>
