<x-layouts.app title="Periksa Pasien">
    <div class="mx-auto max-w-6xl px-4 py-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Periksa Pasien</h1>
                <p class="mt-1 text-sm text-slate-600">Daftar pasien yang menunggu diperiksa.</p>
            </div>
        </div>

        @php
            $total = $daftarPolis->count();
        @endphp

        <div class="mt-6 rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="text-sm font-semibold text-slate-900">Antrian Pemeriksaan</div>
                    <div class="text-xs text-slate-500">Pasien yang sudah diperiksa akan terkunci (tidak bisa diproses ulang).</div>
                </div>

                <div class="w-full sm:w-80">
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input id="searchInput" type="text" placeholder="Cari nama / keluhan..."
                            class="w-full rounded-2xl border border-slate-200 bg-white py-2.5 pl-10 pr-3 text-sm text-slate-700 shadow-sm focus:outline-none focus:ring-2 ring-brand" />
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-600">
                        <tr>
                            <th class="px-6 py-3 text-left">No</th>
                            <th class="px-6 py-3 text-left">Nama Pasien</th>
                            <th class="px-6 py-3 text-left">Keluhan</th>
                            <th class="px-6 py-3 text-left">No Antrian</th>
                            <th class="px-6 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="divide-y divide-slate-200">
                        @forelse ($daftarPolis as $dp)
                            <tr class="bg-white hover:bg-slate-50">
                                <td class="px-6 py-4 text-slate-600">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $dp->pasien->nama ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-700">
                                    <div class="max-w-xl truncate" title="{{ $dp->keluhan }}">{{ $dp->keluhan }}</div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $dp->no_antrian }}</td>
                                <td class="px-6 py-4">
                                    @if ($dp->periksa)
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span
                                                class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-500">
                                                <i class="fa-solid fa-circle-check text-brand"></i>
                                                Sudah diperiksa
                                            </span>
                                            <a href="{{ route('dokter.riwayat-pasien.show', $dp->periksa->id) }}"
                                                class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                                                <i class="fa-regular fa-eye text-brand"></i>
                                                Lihat
                                            </a>
                                        </div>
                                    @else
                                        <a href="{{ route('dokter.periksa-pasien.create', $dp->id) }}"
                                            class="inline-flex items-center gap-2 rounded-2xl px-4 py-2 text-xs font-semibold btn-brand shadow-sm ring-brand">
                                            <i class="fa-solid fa-stethoscope"></i>
                                            Periksa
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-10 text-center text-slate-500" colspan="5">
                                    Tidak ada pasien yang menunggu pemeriksaan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 text-xs text-slate-500">
                Menampilkan <span id="countShown">0</span> dari <span id="countTotal">{{ $total }}</span> data.
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
