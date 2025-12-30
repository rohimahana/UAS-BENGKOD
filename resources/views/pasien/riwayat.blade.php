<x-layouts.app title="Riwayat Kunjungan">
    <div class="mx-auto max-w-6xl px-4 py-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    <i class="fa-solid fa-clock-rotate-left text-brand mr-2"></i>Riwayat Kunjungan
                </h1>
                <p class="mt-1 text-sm text-slate-600">
                    Lihat daftar pendaftaran poli Anda. Jika sudah <span class="font-semibold">Selesai</span>, klik <span class="font-semibold">Detail</span> untuk melihat catatan dokter, obat, dan biaya.
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('pasien.daftar-poli') }}"
                    class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold btn-brand shadow-sm ring-brand">
                    <i class="fa-solid fa-plus"></i>
                    Daftar Poli
                </a>
            </div>
        </div>

        @php
            $menunggu = $daftars->filter(fn($d) => !$d->periksa)->count();
            $selesai = $daftars->filter(fn($d) => (bool) $d->periksa)->count();
        @endphp

        <!-- Summary -->
        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-slate-500">Total Kunjungan</div>
                        <div class="mt-2 text-2xl font-bold text-slate-900">{{ $daftars->count() }}</div>
                    </div>
                    <span class="grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 ring-brand">
                        <i class="fa-solid fa-layer-group text-brand"></i>
                    </span>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-slate-500">Menunggu</div>
                        <div class="mt-2 text-2xl font-bold text-slate-900">{{ $menunggu }}</div>
                    </div>
                    <span class="grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 ring-brand">
                        <i class="fa-solid fa-hourglass-half text-brand"></i>
                    </span>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-slate-500">Selesai</div>
                        <div class="mt-2 text-2xl font-bold text-slate-900">{{ $selesai }}</div>
                    </div>
                    <span class="grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 ring-brand">
                        <i class="fa-solid fa-check text-brand"></i>
                    </span>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="mt-6 rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="text-sm font-semibold text-slate-900">Daftar Kunjungan</div>
                    <div class="text-xs text-slate-500">Gunakan pencarian untuk memfilter tabel.</div>
                </div>

                <div class="w-full sm:w-80">
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input id="searchInput" type="text" placeholder="Cari poli, dokter, keluhan..."
                            class="w-full rounded-2xl border border-slate-200 bg-white py-2.5 pl-10 pr-3 text-sm text-slate-700 shadow-sm focus:outline-none focus:ring-2 ring-brand" />
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-600">
                        <tr>
                            <th class="px-6 py-3 text-left">#</th>
                            <th class="px-6 py-3 text-left">No. Antrian</th>
                            <th class="px-6 py-3 text-left">Dokter</th>
                            <th class="px-6 py-3 text-left">Poli</th>
                            <th class="px-6 py-3 text-left">Jadwal</th>
                            <th class="px-6 py-3 text-left">Keluhan</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Tanggal Daftar</th>
                            <th class="px-6 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="divide-y divide-slate-200">
                        @forelse ($daftars as $i => $daftar)
                            @php
                                $dokterNama = $daftar->jadwalPeriksa->dokter->nama ?? '-';
                                $poliNama = $daftar->jadwalPeriksa->dokter->poli->nama_poli ?? '-';
                                $jadwal = ($daftar->jadwalPeriksa->hari ?? '-') . ', ' .
                                    (isset($daftar->jadwalPeriksa->jam_mulai) ? date('H:i', strtotime($daftar->jadwalPeriksa->jam_mulai)) : '-') .
                                    ' - ' .
                                    (isset($daftar->jadwalPeriksa->jam_selesai) ? date('H:i', strtotime($daftar->jadwalPeriksa->jam_selesai)) : '-');
                                $isDone = (bool) $daftar->periksa;
                            @endphp
                            <tr class="bg-white hover:bg-slate-50">
                                <td class="px-6 py-4 text-slate-600">{{ $i + 1 }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $daftar->no_antrian }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $dokterNama }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $poliNama }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $jadwal }}</td>
                                <td class="px-6 py-4 text-slate-700">
                                    <div class="max-w-xs truncate" title="{{ $daftar->keluhan }}">{{ $daftar->keluhan }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($isDone)
                                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-3 py-1 text-xs font-semibold text-white">
                                            <i class="fa-solid fa-check text-[10px]"></i> Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold badge-brand">
                                            <i class="fa-solid fa-hourglass-half text-[10px]"></i> Menunggu
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $daftar->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    @if ($isDone)
                                        <a href="{{ route('pasien.riwayat.show', $daftar->id) }}"
                                            class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-slate-800">
                                            <i class="fa-solid fa-eye text-[10px]"></i>
                                            Detail
                                        </a>
                                    @else
                                        <button type="button" disabled
                                            class="inline-flex cursor-not-allowed items-center gap-2 rounded-xl border border-slate-200 bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-500">
                                            <i class="fa-solid fa-lock text-[10px]"></i>
                                            Belum ada hasil
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-10 text-center text-slate-500" colspan="9">
                                    Belum ada riwayat kunjungan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 text-xs text-slate-500">
                Menampilkan <span id="countShown">0</span> dari <span id="countTotal">{{ $daftars->count() }}</span> data.
            </div>
        </div>

        <script>
            (function() {
                const input = document.getElementById('searchInput');
                const body = document.getElementById('tableBody');
                const shownEl = document.getElementById('countShown');

                function update() {
                    const q = (input.value || '').toLowerCase().trim();
                    let shown = 0;

                    Array.from(body.querySelectorAll('tr')).forEach(tr => {
                        const tds = tr.querySelectorAll('td');
                        if (!tds.length) return;

                        const text = tr.innerText.toLowerCase();
                        const ok = !q || text.includes(q);
                        tr.style.display = ok ? '' : 'none';
                        if (ok) shown++;
                    });

                    shownEl.textContent = shown;
                }

                input.addEventListener('input', update);
                update();
            })();
        </script>
    </div>
</x-layouts.app>
