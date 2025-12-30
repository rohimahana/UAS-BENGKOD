<x-layouts.app title="Detail Riwayat">
    @php
        $daftar = $daftarPoli;
        $jadwal = $daftar->jadwalPeriksa ?? null;
        $dokter = $jadwal->dokter ?? null;
        $poli = $dokter->poli ?? null;

        $periksa = $daftar->periksa ?? null;
        $detail = $periksa?->detailPeriksa ?? collect();

        $biayaPeriksa = (int) ($periksa->biaya_periksa ?? 0);
        $totalObat = $detail->sum(function ($d) {
            $harga = (int) ($d->obat->harga ?? 0);
            $qty = max((int) ($d->jumlah ?? 1), 1);
            return $harga * $qty;
        });
        $totalBiaya = $biayaPeriksa + $totalObat;

        $jadwalText = ($jadwal?->hari ?? '-') . ', ' .
            ($jadwal?->jam_mulai ? date('H:i', strtotime($jadwal->jam_mulai)) : '-') .
            ' - ' .
            ($jadwal?->jam_selesai ? date('H:i', strtotime($jadwal->jam_selesai)) : '-');

        $tglPeriksa = $periksa?->tgl_periksa ? \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d/m/Y H:i') : '-';
    @endphp

    <div class="p-4 sm:p-6">
        <div class="mx-auto max-w-6xl">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                        <i class="fa-solid fa-file-waveform text-brand mr-2"></i>Detail Riwayat
                    </h1>
                    <p class="mt-1 text-sm text-slate-600">Rincian pendaftaran, hasil pemeriksaan, obat, dan biaya.</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('pasien.riwayat') }}"
                        class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Kembali
                    </a>

                    @if (!$periksa)
                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold badge-brand">
                            <i class="fa-solid fa-hourglass-half text-[10px]"></i>
                            Menunggu pemeriksaan
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-3 py-1 text-xs font-semibold text-white">
                            <i class="fa-solid fa-check text-[10px]"></i>
                            Selesai
                        </span>
                    @endif
                </div>
            </div>

            <!-- Info pendaftaran -->
            <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <div class="text-xs font-semibold text-slate-500">Informasi Pendaftaran</div>
                        <div class="mt-2 text-xl font-bold text-slate-900">Antrian #{{ $daftar->no_antrian ?? '-' }}</div>
                        <div class="mt-1 text-sm text-slate-600">Dibuat: {{ $daftar->created_at?->format('d/m/Y H:i') ?? '-' }}</div>
                    </div>

                    <div class="grid gap-2 sm:text-right">
                        <div class="text-sm font-semibold text-slate-900">{{ $poli->nama_poli ?? '-' }}</div>
                        <div class="text-sm text-slate-600">Dokter: {{ $dokter->nama ?? '-' }}</div>
                        <div class="text-xs text-slate-500">Jadwal: {{ $jadwalText }}</div>
                    </div>
                </div>

                <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <div class="text-xs font-semibold text-slate-500">Keluhan</div>
                    <div class="mt-1 text-sm text-slate-800">{{ $daftar->keluhan ?? '-' }}</div>
                </div>
            </div>

            @if (!$periksa)
                <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                    <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="text-lg font-semibold text-slate-900">Belum ada hasil pemeriksaan</div>
                            <div class="mt-1 text-sm text-slate-600">
                                Status masih <span class="font-semibold">Menunggu</span>. Detail obat, biaya, dan catatan dokter akan muncul setelah pemeriksaan selesai.
                            </div>
                        </div>
                        <a href="{{ route('pasien.riwayat') }}"
                            class="inline-flex items-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold btn-brand shadow-sm ring-brand">
                            Kembali ke Riwayat
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            @else
                <!-- Hasil pemeriksaan + biaya -->
                <div class="mt-6 grid gap-4 lg:grid-cols-3">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
                        <div class="text-sm font-semibold text-slate-900">Hasil Pemeriksaan</div>
                        <div class="mt-1 text-xs text-slate-500">Tanggal periksa: {{ $tglPeriksa }}</div>

                        <div class="mt-4 rounded-2xl border border-slate-200 p-4">
                            <div class="text-xs font-semibold text-slate-500">Catatan Dokter</div>
                            <div class="mt-1 text-sm text-slate-800 whitespace-pre-line">{{ $periksa->catatan ?? '-' }}</div>
                        </div>

                        <div class="mt-3 rounded-2xl border border-slate-200 p-4">
                            <div class="text-xs font-semibold text-slate-500">Obat</div>
                            <div class="mt-1 text-sm text-slate-600">Lihat daftar lengkap di tabel bawah.</div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="text-xs font-semibold text-slate-500">Total Biaya</div>
                        <div class="mt-2 text-3xl font-extrabold text-slate-900">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</div>

                        <div class="mt-4 grid gap-2 text-sm">
                            <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                <span class="text-slate-600">Biaya Periksa</span>
                                <span class="font-semibold text-slate-900">Rp {{ number_format($biayaPeriksa, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                <span class="text-slate-600">Biaya Obat</span>
                                <span class="font-semibold text-slate-900">Rp {{ number_format($totalObat, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="mt-4 text-xs text-slate-500">
                            Total = biaya periksa + total obat (harga × jumlah).
                        </div>
                    </div>
                </div>

                <!-- Table obat -->
                <div class="mt-6 rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-semibold text-slate-900">Daftar Obat</div>
                                <div class="text-xs text-slate-500">Obat yang diberikan pada pemeriksaan ini.</div>
                            </div>
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold badge-brand">
                                {{ $detail->count() }} item
                            </span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                                <tr>
                                    <th class="px-6 py-3 text-left">Obat</th>
                                    <th class="px-6 py-3 text-left">Jumlah</th>
                                    <th class="px-6 py-3 text-left">Harga</th>
                                    <th class="px-6 py-3 text-left">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse ($detail as $d)
                                    @php
                                        $namaObat = $d->obat->nama_obat ?? '-';
                                        $harga = (int) ($d->obat->harga ?? 0);
                                        $qty = max((int) ($d->jumlah ?? 1), 1);
                                        $sub = $harga * $qty;
                                    @endphp
                                    <tr class="bg-white hover:bg-slate-50">
                                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $namaObat }}</td>
                                        <td class="px-6 py-4 text-slate-700">{{ $qty }}</td>
                                        <td class="px-6 py-4 text-slate-700">Rp {{ number_format($harga, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 font-semibold text-slate-900">Rp {{ number_format($sub, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-slate-500">
                                            Tidak ada obat pada pemeriksaan ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-200 px-6 py-4 text-sm">
                        <span class="text-slate-600">Total Obat</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($totalObat, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
