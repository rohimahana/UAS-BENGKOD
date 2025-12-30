<x-layouts.app title="Data Dokter">
    @push('styles')
        <style>
            /* Brand utilities (Serenity × Rose Quartz) */
            :root {
                --brand-bg-1: #92A8D1; /* Serenity */
                --brand-bg-2: #F7CAC9; /* Rose Quartz */
                --brand-cta-1: #3F5FA8; /* Serenity deep */
                --brand-cta-2: #A44A5C; /* Rose deep */
                --brand-ring: rgba(63, 95, 168, .22);
                --brand-ring-strong: rgba(63, 95, 168, .35);
            }

            .btn-brand {
                color: #fff !important;
                background: linear-gradient(135deg, var(--brand-cta-1) 0%, var(--brand-cta-2) 100%) !important;
                border: 0 !important;
            }

            .btn-brand:hover { filter: brightness(.97); }

            .text-brand { color: var(--brand-cta-1) !important; }

            .badge-brand {
                background: rgba(146, 168, 209, .22) !important;
                color: #1f2a44 !important;
                border: 1px solid rgba(63, 95, 168, .18) !important;
            }

            .ring-brand { box-shadow: 0 0 0 4px var(--brand-ring); }

            .focus-brand:focus { outline: none; box-shadow: 0 0 0 4px var(--brand-ring-strong); }
        </style>
    @endpush

    @php
        $total = $dokters?->count() ?? 0;

        $poliNames = $dokters
            ->map(fn($d) => $d->poli->nama_poli ?? 'Belum dipilih')
            ->unique()
            ->sort()
            ->values();
    @endphp

    <div class="p-4 sm:p-6">
        <div class="mx-auto max-w-7xl">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Data Dokter</h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Kelola akun dokter, data kontak, dan poliklinik penugasan.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" id="exportDokter"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                        <i class="fa-solid fa-file-arrow-down text-xs text-slate-500"></i>
                        Export CSV
                    </button>
                    <a href="{{ route('admin.dokter.create') }}"
                        class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold btn-brand shadow-sm ring-brand">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Dokter
                    </a>
                </div>
            </div>

            <div class="mt-6 rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-200 p-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="relative w-full sm:max-w-sm">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input id="searchDokter" type="text"
                                class="focus-brand w-full rounded-2xl border border-slate-200 bg-white py-2 pl-10 pr-3 text-sm text-slate-900 placeholder:text-slate-400"
                                placeholder="Cari nama, email, KTP, HP, alamat…">
                        </div>

                        <div class="w-full sm:max-w-xs">
                            <select id="filterPoli"
                                class="focus-brand w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900">
                                <option value="">Semua Poli</option>
                                @foreach ($poliNames as $name)
                                    <option value="{{ $name }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500">
                            <span class="inline-flex items-center rounded-full px-2 py-1 badge-brand">
                                Total: <span class="ml-1 font-semibold text-slate-900">{{ $total }}</span>
                            </span>
                            <span class="inline-flex items-center rounded-full px-2 py-1 badge-brand">
                                Tampil: <span id="visibleDokter" class="ml-1 font-semibold text-slate-900">{{ $total }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:hidden">
                        <span class="text-xs text-slate-500">Tampil: <span id="visibleDokterSm" class="font-semibold text-slate-900">{{ $total }}</span></span>
                        <span class="text-xs text-slate-500">Total: <span class="font-semibold text-slate-900">{{ $total }}</span></span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table id="tableDokter" class="min-w-full text-sm">
                        <thead class="sticky top-0 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-3">#</th>
                                <th class="whitespace-nowrap px-4 py-3">Nama</th>
                                <th class="whitespace-nowrap px-4 py-3">Email</th>
                                <th class="hidden lg:table-cell whitespace-nowrap px-4 py-3">No. KTP</th>
                                <th class="whitespace-nowrap px-4 py-3">No. HP</th>
                                <th class="hidden xl:table-cell whitespace-nowrap px-4 py-3">Alamat</th>
                                <th class="whitespace-nowrap px-4 py-3">Poli</th>
                                <th class="whitespace-nowrap px-4 py-3" data-export="0">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse ($dokters as $index => $dokter)
                                @php
                                    $poliLabel = $dokter->poli->nama_poli ?? 'Belum dipilih';
                                    $search = implode(' ', [
                                        $dokter->nama,
                                        $dokter->email,
                                        $dokter->no_ktp,
                                        $dokter->no_hp,
                                        $dokter->alamat,
                                        $poliLabel,
                                    ]);
                                @endphp
                                <tr data-row="1" data-search="{{ $search }}" data-filter="{{ $poliLabel }}"
                                    class="hover:bg-slate-50/70">
                                    <td class="px-4 py-3 text-slate-600">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-slate-900">{{ $dokter->nama }}</div>
                                        <div class="mt-0.5 text-xs text-slate-500">Role: Dokter</div>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">
                                        <span class="inline-flex items-center gap-2">
                                            <i class="fa-regular fa-envelope text-slate-400"></i>
                                            {{ $dokter->email }}
                                        </span>
                                    </td>
                                    <td class="hidden lg:table-cell px-4 py-3 text-slate-700">{{ $dokter->no_ktp }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $dokter->no_hp }}</td>
                                    <td class="hidden xl:table-cell px-4 py-3 text-slate-700">
                                        {{ \Illuminate\Support\Str::limit($dokter->alamat, 48) }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold badge-brand">
                                            {{ $poliLabel }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3" data-export="0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <a href="{{ route('admin.dokter.edit', $dokter) }}"
                                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                                <i class="fa-solid fa-pen-to-square text-[11px] text-slate-500"></i>
                                                Edit
                                            </a>
                                            <button type="button"
                                                class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100"
                                                onclick="confirmDelete('{{ route('admin.dokter.destroy', $dokter) }}', 'Dokter {{ $dokter->nama }}')">
                                                <i class="fa-solid fa-trash text-[11px]"></i>
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-10 text-center">
                                        <div class="mx-auto max-w-md">
                                            <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-slate-600">
                                                <i class="fa-solid fa-user-doctor"></i>
                                            </div>
                                            <div class="mt-4 text-base font-semibold text-slate-900">Belum ada dokter</div>
                                            <div class="mt-1 text-sm text-slate-600">Tambahkan dokter pertama untuk mulai membuat jadwal praktik.</div>
                                            <div class="mt-5">
                                                <a href="{{ route('admin.dokter.create') }}"
                                                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold btn-brand shadow-sm ring-brand">
                                                    <i class="fa-solid fa-plus text-xs"></i>
                                                    Tambah Dokter
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="emptyDokterSearch" class="hidden border-t border-slate-200 p-6">
                    <div class="flex flex-col items-center justify-center gap-2 text-center">
                        <div class="grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-slate-600">
                            <i class="fa-solid fa-filter"></i>
                        </div>
                        <div class="text-sm font-semibold text-slate-900">Tidak ada hasil</div>
                        <div class="text-sm text-slate-600">Coba kata kunci lain atau reset filter.</div>
                        <button type="button" id="resetDokter"
                            class="mt-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <p class="mt-3 text-xs text-slate-500">
                Tips: kamu bisa cari cepat dengan email/no. KTP/no. HP. Export CSV akan mengikuti hasil filter yang sedang tampil.
            </p>
        </div>
    </div>

    <script>
        (function() {
            function csvEscape(value) {
                const s = (value ?? '').toString().replace(/\s+/g, ' ').trim();
                if (s.includes('"') || s.includes(',') || s.includes('\n')) return '"' + s.replace(/"/g, '""') + '"';
                return s;
            }

            function exportTableToCSV(table, filename) {
                const rows = Array.from(table.querySelectorAll('tr'));
                const lines = [];

                rows.forEach(row => {
                    if (row.style.display === 'none') return;
                    const cells = Array.from(row.children).filter(c => c.getAttribute('data-export') !== '0');
                    if (!cells.length) return;
                    const line = cells.map(c => csvEscape(c.innerText)).join(',');
                    lines.push(line);
                });

                const blob = new Blob([lines.join('\n')], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();
                URL.revokeObjectURL(url);
            }

            function init() {
                const search = document.getElementById('searchDokter');
                const filterPoli = document.getElementById('filterPoli');
                const table = document.getElementById('tableDokter');
                const empty = document.getElementById('emptyDokterSearch');
                const resetBtn = document.getElementById('resetDokter');
                const exportBtn = document.getElementById('exportDokter');
                const visible = document.getElementById('visibleDokter');
                const visibleSm = document.getElementById('visibleDokterSm');

                if (!table) return;

                const rows = Array.from(table.querySelectorAll('tbody tr[data-row="1"]'));

                function apply() {
                    const q = (search?.value || '').toLowerCase().trim();
                    const f = (filterPoli?.value || '').toLowerCase().trim();
                    let shown = 0;

                    rows.forEach(r => {
                        const hay = (r.getAttribute('data-search') || '').toLowerCase();
                        const poli = (r.getAttribute('data-filter') || '').toLowerCase();
                        const okQ = !q || hay.includes(q);
                        const okF = !f || poli === f;
                        const show = okQ && okF;
                        r.style.display = show ? '' : 'none';
                        if (show) shown++;
                    });

                    if (visible) visible.textContent = shown;
                    if (visibleSm) visibleSm.textContent = shown;

                    if (empty) {
                        empty.classList.toggle('hidden', shown !== 0);
                    }
                }

                search?.addEventListener('input', apply);
                filterPoli?.addEventListener('change', apply);

                resetBtn?.addEventListener('click', () => {
                    if (search) search.value = '';
                    if (filterPoli) filterPoli.value = '';
                    apply();
                    search?.focus();
                });

                exportBtn?.addEventListener('click', () => {
                    exportTableToCSV(table, 'data-dokter.csv');
                });

                apply();
            }

            document.addEventListener('DOMContentLoaded', init);
        })();
    </script>
</x-layouts.app>
