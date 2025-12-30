<x-layouts.app title="Riwayat Pemeriksaan">
    @php
        $total = $riwayatPasien?->count() ?? 0;
    @endphp

    <div class="p-4 sm:p-6">
        <div class="mx-auto max-w-7xl">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                        <i class="fa-solid fa-clock-rotate-left text-brand mr-2"></i>Riwayat Pemeriksaan
                    </h1>
                    <p class="mt-1 text-sm text-slate-600">Daftar pasien yang sudah Anda periksa beserta rincian biayanya.</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" id="exportRiwayat"
                        class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                        <i class="fa-solid fa-file-arrow-down text-xs text-slate-500"></i>
                        Export CSV
                    </button>
                </div>
            </div>

            <div class="mt-6 rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-200 p-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="relative w-full sm:max-w-sm">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input id="searchRiwayat" type="text"
                                class="w-full rounded-2xl border border-slate-200 bg-white py-2 pl-10 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 ring-brand"
                                placeholder="Cari nama pasien, poli, keluhan, antrian…">
                        </div>

                        <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500">
                            <span class="inline-flex items-center rounded-full px-2 py-1 badge-brand">
                                Total: <span class="ml-1 font-semibold text-slate-900">{{ $total }}</span>
                            </span>
                            <span class="inline-flex items-center rounded-full px-2 py-1 badge-brand">
                                Tampil: <span id="visibleRiwayat" class="ml-1 font-semibold text-slate-900">{{ $total }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:hidden">
                        <span class="text-xs text-slate-500">Tampil: <span id="visibleRiwayatSm" class="font-semibold text-slate-900">{{ $total }}</span></span>
                        <span class="text-xs text-slate-500">Total: <span class="font-semibold text-slate-900">{{ $total }}</span></span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table id="tableRiwayat" class="min-w-full text-sm">
                        <thead class="sticky top-0 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-3">#</th>
                                <th class="whitespace-nowrap px-4 py-3">Antrian</th>
                                <th class="whitespace-nowrap px-4 py-3">Pasien</th>
                                <th class="whitespace-nowrap px-4 py-3">Poli</th>
                                <th class="whitespace-nowrap px-4 py-3">Keluhan</th>
                                <th class="whitespace-nowrap px-4 py-3">Tanggal</th>
                                <th class="whitespace-nowrap px-4 py-3">Biaya</th>
                                <th class="whitespace-nowrap px-4 py-3" data-export="0">Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="bodyRiwayat" class="divide-y divide-slate-200">
                            @forelse ($riwayatPasien as $i => $periksa)
                                @php
                                    $biayaPeriksa = (int) ($periksa->biaya_periksa ?? 0);
                                    $totalObat = $periksa->detailPeriksa
                                        ? $periksa->detailPeriksa->sum(function ($d) {
                                            $harga = (int) ($d->obat->harga ?? 0);
                                            $qty = max((int) ($d->jumlah ?? 1), 1);
                                            return $harga * $qty;
                                        })
                                        : 0;
                                    $totalBiaya = $biayaPeriksa + $totalObat;
                                    $antrian = $periksa->daftarPoli->no_antrian ?? '-';
                                    $nama = $periksa->daftarPoli->pasien->nama ?? '-';
                                    $poli = $periksa->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-';
                                    $keluhan = $periksa->daftarPoli->keluhan ?? '-';
                                    $tgl = $periksa->tgl_periksa ? \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d/m/Y H:i') : '-';
                                @endphp

                                <tr class="bg-white hover:bg-slate-50"
                                    data-search="{{ strtolower($antrian.' '.$nama.' '.$poli.' '.$keluhan.' '.$tgl) }}">
                                    <td class="whitespace-nowrap px-4 py-4 text-slate-600">{{ $i + 1 }}</td>
                                    <td class="whitespace-nowrap px-4 py-4">
                                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold badge-brand">
                                            #{{ $antrian }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 font-semibold text-slate-900">{{ $nama }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-slate-700">{{ $poli }}</td>
                                    <td class="px-4 py-4 text-slate-700">
                                        <div class="max-w-md truncate" title="{{ $keluhan }}">
                                            {{ \Illuminate\Support\Str::limit($keluhan, 60) }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 text-slate-600">{{ $tgl }}</td>
                                    <td class="whitespace-nowrap px-4 py-4">
                                        <div class="font-semibold text-slate-900">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</div>
                                    <td class="whitespace-nowrap px-4 py-4">
                                        <a href="{{ route('dokter.riwayat-pasien.show', $periksa->id) }}"
                                            class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-slate-800">
                                            <i class="fa-solid fa-eye text-[10px]"></i>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                                        Belum ada riwayat pemeriksaan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-4 text-xs text-slate-500">
                    Menampilkan <span id="countRiwayat">0</span> dari <span id="totalRiwayat">{{ $total }}</span> data.
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const input = document.getElementById('searchRiwayat');
            const body = document.getElementById('bodyRiwayat');
            const countEl = document.getElementById('countRiwayat');
            const visibleEl = document.getElementById('visibleRiwayat');
            const visibleSmEl = document.getElementById('visibleRiwayatSm');

            function filterRows() {
                const q = (input.value || '').toLowerCase().trim();
                let shown = 0;

                Array.from(body.querySelectorAll('tr')).forEach((tr) => {
                    const search = (tr.getAttribute('data-search') || '').toLowerCase();
                    const hasCells = tr.querySelectorAll('td').length > 0;

                    if (!hasCells) return; // empty state row

                    const ok = !q || search.includes(q) || tr.innerText.toLowerCase().includes(q);
                    tr.style.display = ok ? '' : 'none';
                    if (ok) shown++;
                });

                countEl.textContent = String(shown);
                if (visibleEl) visibleEl.textContent = String(shown);
                if (visibleSmEl) visibleSmEl.textContent = String(shown);
            }

            input?.addEventListener('input', filterRows);
            filterRows();

            // Export CSV for visible rows
            document.getElementById('exportRiwayat')?.addEventListener('click', () => {
                const rows = Array.from(document.querySelectorAll('#tableRiwayat tbody tr'))
                    .filter(tr => tr.style.display !== 'none' && tr.querySelectorAll('td').length);

                const head = Array.from(document.querySelectorAll('#tableRiwayat thead th'))
                    .filter(th => th.getAttribute('data-export') !== '0')
                    .map(th => th.innerText.trim());

                const csvRows = [];
                csvRows.push(head.join(','));

                rows.forEach(tr => {
                    const cols = Array.from(tr.querySelectorAll('td'));
                    // exclude last (aksi)
                    const exportCols = cols.slice(0, cols.length - 1).map(td => {
                        const text = (td.innerText || '').replace(/\s+/g, ' ').trim();
                        return '\"' + text.replaceAll('\"', '\"\"') + '\"';
                    });
                    csvRows.push(exportCols.join(','));
                });

                const blob = new Blob([csvRows.join('\\n')], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `riwayat-pemeriksaan-${new Date().toISOString().slice(0,10)}.csv`;
                document.body.appendChild(a);
                a.click();
                a.remove();
                URL.revokeObjectURL(url);
            });
        })();
    </script>
</x-layouts.app>
