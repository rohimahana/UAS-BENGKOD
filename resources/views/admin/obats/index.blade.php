<x-layouts.app title="Data Obat">
    @push('styles')
        <style>
            :root {
                --brand-bg-1: #92A8D1;
                --brand-bg-2: #F7CAC9;
                --brand-cta-1: #3F5FA8;
                --brand-cta-2: #A44A5C;
                --brand-ring: rgba(63, 95, 168, .22);
                --brand-ring-strong: rgba(63, 95, 168, .35);
            }

            .btn-brand { color: #fff !important; background: linear-gradient(135deg, var(--brand-cta-1) 0%, var(--brand-cta-2) 100%) !important; border: 0 !important; }
            .btn-brand:hover { filter: brightness(.97); }
            .text-brand { color: var(--brand-cta-1) !important; }
            .badge-brand { background: rgba(146, 168, 209, .22) !important; color: #1f2a44 !important; border: 1px solid rgba(63, 95, 168, .18) !important; }
            .ring-brand { box-shadow: 0 0 0 4px var(--brand-ring); }
            .focus-brand:focus { outline: none; box-shadow: 0 0 0 4px var(--brand-ring-strong); }
        </style>
    @endpush

    @php
        $total = $obats?->count() ?? 0;
    @endphp

    <div class="p-4 sm:p-6">
        <div class="mx-auto max-w-7xl">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Data Obat</h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Kelola master obat: nama, kemasan, harga, dan stok.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" id="exportObat"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                        <i class="fa-solid fa-file-arrow-down text-xs text-slate-500"></i>
                        Export CSV
                    </button>
                    <a href="{{ route('admin.obat.create') }}"
                        class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold btn-brand shadow-sm ring-brand">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Obat
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
                            <input id="searchObat" type="text"
                                class="focus-brand w-full rounded-2xl border border-slate-200 bg-white py-2 pl-10 pr-3 text-sm text-slate-900 placeholder:text-slate-400"
                                placeholder="Cari nama obat / kemasan…">
                        </div>

                        <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500">
                            <span class="inline-flex items-center rounded-full px-2 py-1 badge-brand">
                                Total: <span class="ml-1 font-semibold text-slate-900">{{ $total }}</span>
                            </span>
                            <span class="inline-flex items-center rounded-full px-2 py-1 badge-brand">
                                Tampil: <span id="visibleObat" class="ml-1 font-semibold text-slate-900">{{ $total }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:hidden">
                        <span class="text-xs text-slate-500">Tampil: <span id="visibleObatSm" class="font-semibold text-slate-900">{{ $total }}</span></span>
                        <span class="text-xs text-slate-500">Total: <span class="font-semibold text-slate-900">{{ $total }}</span></span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table id="tableObat" class="min-w-full text-sm">
                        <thead class="sticky top-0 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-3">Nama Obat</th>
                                <th class="whitespace-nowrap px-4 py-3">Kemasan</th>
                                <th class="whitespace-nowrap px-4 py-3">Harga</th>
                                <th class="whitespace-nowrap px-4 py-3">Stok</th>
                                <th class="whitespace-nowrap px-4 py-3">Status</th>
                                <th class="whitespace-nowrap px-4 py-3" data-export="0">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse ($obats as $obat)
                                @php
                                    $stok = (int) ($obat->stok ?? 0);
                                    $min = (int) ($obat->stok_minimum ?? 0);

                                    if ($stok <= 0) {
                                        $status = 'Habis';
                                        $badge = 'bg-rose-50 text-rose-700 border-rose-200';
                                    } elseif ($min > 0 && $stok <= $min) {
                                        $status = 'Menipis';
                                        $badge = 'bg-amber-50 text-amber-800 border-amber-200';
                                    } else {
                                        $status = 'Aman';
                                        $badge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                    }

                                    $search = implode(' ', [$obat->nama_obat, $obat->kemasan, $obat->harga, $stok, $status]);
                                @endphp
                                <tr data-row="1" data-search="{{ $search }}" class="hover:bg-slate-50/70">
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-slate-900">{{ $obat->nama_obat }}</div>
                                        <div class="mt-0.5 text-xs text-slate-500">Master Obat</div>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">{{ $obat->kemasan }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold badge-brand">
                                            Rp {{ number_format($obat->harga, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-slate-900">{{ $stok }}</div>
                                        <div class="mt-0.5 text-xs text-slate-500">Min: {{ $min }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center rounded-full border px-2 py-1 text-xs font-semibold {{ $badge }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3" data-export="0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <button type="button"
                                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                                                data-stock-action="{{ route('admin.obat.adjust-stock', $obat->id) }}"
                                                data-stock-name="{{ $obat->nama_obat }}"
                                                data-stock-current="{{ $stok }}"
                                                onclick="openStockModal(this)">
                                                <i class="fa-solid fa-boxes-stacked text-[11px] text-slate-500"></i>
                                                Atur Stok
                                            </button>

                                            <a href="{{ route('admin.obat.edit', $obat->id) }}"
                                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                                <i class="fa-solid fa-pen-to-square text-[11px] text-slate-500"></i>
                                                Edit
                                            </a>

                                            <button type="button"
                                                class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100"
                                                onclick="confirmDelete('{{ route('admin.obat.destroy', $obat->id) }}', 'Obat {{ $obat->nama_obat }}')">
                                                <i class="fa-solid fa-trash text-[11px]"></i>
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center">
                                        <div class="mx-auto max-w-md">
                                            <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-slate-600">
                                                <i class="fa-solid fa-pills"></i>
                                            </div>
                                            <div class="mt-4 text-base font-semibold text-slate-900">Belum ada data obat</div>
                                            <div class="mt-1 text-sm text-slate-600">Tambahkan obat untuk digunakan pada proses pemeriksaan dan resep.</div>
                                            <div class="mt-5">
                                                <a href="{{ route('admin.obat.create') }}"
                                                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold btn-brand shadow-sm ring-brand">
                                                    <i class="fa-solid fa-plus text-xs"></i>
                                                    Tambah Obat
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="emptyObatSearch" class="hidden border-t border-slate-200 p-6">
                    <div class="flex flex-col items-center justify-center gap-2 text-center">
                        <div class="grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-slate-600">
                            <i class="fa-solid fa-filter"></i>
                        </div>
                        <div class="text-sm font-semibold text-slate-900">Tidak ada hasil</div>
                        <div class="text-sm text-slate-600">Coba kata kunci lain atau reset pencarian.</div>
                        <button type="button" id="resetObat"
                            class="mt-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Adjust stock -->
    <div id="stockModal" class="fixed inset-0 z-[60] hidden">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeStockModal()"></div>
        <div class="absolute left-1/2 top-1/2 w-[92%] max-w-lg -translate-x-1/2 -translate-y-1/2">
            <div class="rounded-3xl border border-slate-200 bg-white shadow-xl">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-4">
                    <div>
                        <div class="text-sm font-semibold text-slate-900">Atur Stok Obat</div>
                        <div id="stockModalName" class="mt-0.5 text-xs text-slate-500">—</div>
                    </div>
                    <button type="button" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50" onclick="closeStockModal()">
                        Tutup
                    </button>
                </div>

                <form id="stockForm" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <div class="text-xs font-semibold text-slate-600">Stok saat ini</div>
                            <div id="stockModalCurrent" class="mt-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900">0</div>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-slate-600" for="stock_action">Aksi</label>
                            <select id="stock_action" name="action"
                                class="focus-brand mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900">
                                <option value="add">Tambah (+)</option>
                                <option value="subtract">Kurangi (-)</option>
                                <option value="set">Set nilai</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-slate-600" for="stock_jumlah">Jumlah</label>
                        <input id="stock_jumlah" name="jumlah" type="number" min="0" value="1"
                            class="focus-brand mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900" />
                        <div class="mt-1 text-xs text-slate-500">Catatan: sistem akan menolak jika stok menjadi minus.</div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button"
                            class="rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                            onclick="closeStockModal()">
                            Batal
                        </button>
                        <button type="button"
                            class="rounded-2xl px-5 py-3 text-sm font-semibold btn-brand shadow-sm ring-brand"
                            onclick="confirmSubmit('#stockForm', 'Ubah stok?', 'Stok obat akan diperbarui sesuai aksi yang kamu pilih.')">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
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

            function initSearchExport() {
                const search = document.getElementById('searchObat');
                const table = document.getElementById('tableObat');
                const empty = document.getElementById('emptyObatSearch');
                const resetBtn = document.getElementById('resetObat');
                const exportBtn = document.getElementById('exportObat');
                const visible = document.getElementById('visibleObat');
                const visibleSm = document.getElementById('visibleObatSm');

                if (!table) return;

                const rows = Array.from(table.querySelectorAll('tbody tr[data-row="1"]'));

                function apply() {
                    const q = (search?.value || '').toLowerCase().trim();
                    let shown = 0;

                    rows.forEach(r => {
                        const hay = (r.getAttribute('data-search') || '').toLowerCase();
                        const show = !q || hay.includes(q);
                        r.style.display = show ? '' : 'none';
                        if (show) shown++;
                    });

                    if (visible) visible.textContent = shown;
                    if (visibleSm) visibleSm.textContent = shown;
                    if (empty) empty.classList.toggle('hidden', shown !== 0);
                }

                search?.addEventListener('input', apply);

                resetBtn?.addEventListener('click', () => {
                    if (search) search.value = '';
                    apply();
                    search?.focus();
                });

                exportBtn?.addEventListener('click', () => exportTableToCSV(table, 'data-obat.csv'));

                apply();
            }

            initSearchExport();
        })();

        function openStockModal(btn) {
            const modal = document.getElementById('stockModal');
            const form = document.getElementById('stockForm');
            const nameEl = document.getElementById('stockModalName');
            const currentEl = document.getElementById('stockModalCurrent');
            const jumlahEl = document.getElementById('stock_jumlah');
            const actionEl = document.getElementById('stock_action');

            const actionUrl = btn.getAttribute('data-stock-action');
            const name = btn.getAttribute('data-stock-name') || '-';
            const current = btn.getAttribute('data-stock-current') || '0';

            if (form) form.action = actionUrl;
            if (nameEl) nameEl.textContent = name;
            if (currentEl) currentEl.textContent = current;
            if (jumlahEl) jumlahEl.value = 1;
            if (actionEl) actionEl.value = 'add';

            modal?.classList.remove('hidden');
        }

        function closeStockModal() {
            document.getElementById('stockModal')?.classList.add('hidden');
        }
    </script>
</x-layouts.app>
