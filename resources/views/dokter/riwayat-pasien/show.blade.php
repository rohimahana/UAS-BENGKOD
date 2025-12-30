<x-layouts.app title="Detail Pemeriksaan">
    @php
        $daftar = $periksa->daftarPoli;
        $pasien = $daftar->pasien ?? null;

        $biayaPeriksa = (int) ($periksa->biaya_periksa ?? 0);
        $detail = $periksa->detailPeriksa ?? collect();
        $totalObat = $detail->sum(function ($d) {
            $harga = (int) ($d->obat->harga ?? 0);
            $qty = max((int) ($d->jumlah ?? 1), 1);
            return $harga * $qty;
        });
        $totalBiaya = $biayaPeriksa + $totalObat;

        $tglPeriksa = $periksa->tgl_periksa ? \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d/m/Y H:i') : '-';
    @endphp

    <div class="p-4 sm:p-6">
        <div class="mx-auto max-w-6xl">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                        <i class="fa-solid fa-file-waveform text-brand mr-2"></i>Detail Pemeriksaan
                    </h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Ringkasan hasil pemeriksaan, obat, dan total biaya.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('dokter.riwayat-pasien.index') }}"
                        class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Top cards -->
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:col-span-2">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-xs font-semibold text-slate-500">Pasien</div>
                            <div class="mt-2 text-xl font-bold text-slate-900">{{ $pasien->nama ?? '-' }}</div>
                            <div class="mt-1 text-sm text-slate-600">
                                <span class="inline-flex items-center gap-2">
                                    <i class="fa-solid fa-hashtag text-slate-400 text-xs"></i>
                                    Antrian <span class="font-semibold text-slate-900">#{{ $daftar->no_antrian ?? '-' }}</span>
                                </span>
                            </div>
                        </div>

                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold badge-brand">
                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                            Selesai
                        </span>
                    </div>

                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs font-semibold text-slate-500">Poli</div>
                            <div class="mt-1 font-semibold text-slate-900">
                                {{ $daftar->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}
                            </div>
                            <div class="mt-1 text-xs text-slate-600">
                                Dokter: {{ $daftar->jadwalPeriksa->dokter->nama ?? '-' }}
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs font-semibold text-slate-500">Tanggal Periksa</div>
                            <div class="mt-1 font-semibold text-slate-900">{{ $tglPeriksa }}</div>
                            <div class="mt-1 text-xs text-slate-600">
                                Jadwal: {{ $daftar->jadwalPeriksa->hari ?? '-' }}
                                {{ $daftar->jadwalPeriksa->jam_mulai ? date('H:i', strtotime($daftar->jadwalPeriksa->jam_mulai)) : '-' }}
                                -
                                {{ $daftar->jadwalPeriksa->jam_selesai ? date('H:i', strtotime($daftar->jadwalPeriksa->jam_selesai)) : '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl border border-slate-200 p-4">
                        <div class="text-xs font-semibold text-slate-500">Keluhan</div>
                        <div class="mt-1 text-sm text-slate-800">{{ $daftar->keluhan ?? '-' }}</div>
                    </div>

                    <div class="mt-3 rounded-2xl border border-slate-200 p-4">
                        <div class="text-xs font-semibold text-slate-500">Catatan Dokter</div>
                        <div class="mt-1 text-sm text-slate-800 whitespace-pre-line">{{ $periksa->catatan ?? '-' }}</div>
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

            <!-- Obat -->
            <div class="mt-6 rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-semibold text-slate-900">Obat & Resep</div>
                            <div class="text-xs text-slate-500">Daftar obat yang diberikan pada pemeriksaan ini.</div>
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
        </div>
    </div>
</x-layouts.app>
