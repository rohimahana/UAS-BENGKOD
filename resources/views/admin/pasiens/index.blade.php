<x-layouts.app title="Data Pasien">
    @push('styles')
        <style>
            /* Brand utilities (Serenity × Rose Quartz) */
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
        $total = $pasiens?->count() ?? 0;
    @endphp

    <div class="p-4 sm:p-6">
        <div class="mx-auto max-w-7xl">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Data Pasien</h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Kelola data pasien, nomor rekam medis, dan informasi kontak.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" id="exportPasien"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                        <i class="fa-solid fa-file-arrow-down text-xs text-slate-500"></i>
                        Export CSV
                    </button>
                    <a href="{{ route('admin.pasien.create') }}"
                        class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold btn-brand shadow-sm ring-brand">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Pasien
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
                            <input id="searchPasien" type="text"
                                class="focus-brand w-full rounded-2xl border border-slate-200 bg-white py-2 pl-10 pr-3 text-sm text-slate-900 placeholder:text-slate-400"
                                placeholder="Cari no. RM, nama, email, KTP, HP, alamat…">
                        </div>

                        <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500">
                            <span class="inline-flex items-center rounded-full px-2 py-1 badge-brand">
                                Total: <span class="ml-1 font-semibold text-slate-900">{{ $total }}</span>
                            </span>
                            <span class="inline-flex items-center rounded-full px-2 py-1 badge-brand">
                                Tampil: <span id="visiblePasien" class="ml-1 font-semibold text-slate-900">{{ $total }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:hidden">
                        <span class="text-xs text-slate-500">Tampil: <span id="visiblePasienSm" class="font-semibold text-slate-900">{{ $total }}</span></span>
                        <span class="text-xs text-slate-500">Total: <span class="font-semibold text-slate-900">{{ $total }}</span></span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table id="tablePasien" class="min-w-full text-sm">
                        <thead class="sticky top-0 bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-3">No. RM</th>
                                <th class="whitespace-nowrap px-4 py-3">Nama</th>
                                <th class="whitespace-nowrap px-4 py-3">Email</th>
                                <th class="hidden lg:table-cell whitespace-nowrap px-4 py-3">No. KTP</th>
                                <th class="whitespace-nowrap px-4 py-3">No. HP</th>
                                <th class="hidden xl:table-cell whitespace-nowrap px-4 py-3">Alamat</th>
                                <th class="whitespace-nowrap px-4 py-3" data-export="0">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse ($pasiens as $pasien)
                                @php
                                    $search = implode(' ', [
                                        $pasien->no_rm,
                                        $pasien->nama,
                                        $pasien->email,
                                        $pasien->no_ktp,
                                        $pasien->no_hp,
                                        $pasien->alamat,
                                    ]);
                                @endphp
                                <tr data-row="1" data-search="{{ $search }}" class="hover:bg-slate-50/70">
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold badge-brand">
                                            {{ $pasien->no_rm ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-slate-900">{{ $pasien->nama }}</div>
                                        <div class="mt-0.5 text-xs text-slate-500">Role: Pasien</div>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">
                                        <span class="inline-flex items-center gap-2">
                                            <i class="fa-regular fa-envelope text-slate-400"></i>
                                            {{ $pasien->email }}
                                        </span>
                                    </td>
                                    <td class="hidden lg:table-cell px-4 py-3 text-slate-700">{{ $pasien->no_ktp }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ $pasien->no_hp }}</td>
                                    <td class="hidden xl:table-cell px-4 py-3 text-slate-700">
                                        {{ \Illuminate\Support\Str::limit($pasien->alamat, 56) }}
                                    </td>
                                    <td class="px-4 py-3" data-export="0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <a href="{{ route('admin.pasien.edit', $pasien->id) }}"
                                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                                <i class="fa-solid fa-pen-to-square text-[11px] text-slate-500"></i>
                                                Edit
                                            </a>
                                            <button type="button"
                                                class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100"
                                                onclick="confirmDelete('{{ route('admin.pasien.destroy', $pasien->id) }}', 'Pasien {{ $pasien->nama }}')">
                                                <i class="fa-solid fa-trash text-[11px]"></i>
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-10 text-center">
                                        <div class="mx-auto max-w-md">
                                            <div class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-slate-600">
                                                <i class="fa-solid fa-users"></i>
                                            </div>
                                            <div class="mt-4 text-base font-semibold text-slate-900">Belum ada pasien</div>
                                            <div class="mt-1 text-sm text-slate-600">Tambahkan pasien untuk mulai melakukan pendaftaran dan pemeriksaan.</div>
                                            <div class="mt-5">
                                                <a href="{{ route('admin.pasien.create') }}"
                                                    class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold btn-brand shadow-sm ring-brand">
                                                    <i class="fa-solid fa-plus text-xs"></i>
                                                    Tambah Pasien
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div id="emptyPasienSearch" class="hidden border-t border-slate-200 p-6">
                    <div class="flex flex-col items-center justify-center gap-2 text-center">
                        <div class="grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-slate-600">
                            <i class="fa-solid fa-filter"></i>
                        </div>
                        <div class="text-sm font-semibold text-slate-900">Tidak ada hasil</div>
                        <div class="text-sm text-slate-600">Coba kata kunci lain atau reset pencarian.</div>
                        <button type="button" id="resetPasien"
                            class="mt-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <p class="mt-3 text-xs text-slate-500">
                Tips: nomor RM bisa dipakai untuk pencarian cepat. Export CSV mengikuti hasil filter yang sedang tampil.
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
                const search = document.getElementById('searchPasien');
                const table = document.getElementById('tablePasien');
                const empty = document.getElementById('emptyPasienSearch');
                const resetBtn = document.getElementById('resetPasien');
                const exportBtn = document.getElementById('exportPasien');
                const visible = document.getElementById('visiblePasien');
                const visibleSm = document.getElementById('visiblePasienSm');

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

                exportBtn?.addEventListener('click', () => exportTableToCSV(table, 'data-pasien.csv'));

                apply();
            }

            document.addEventListener('DOMContentLoaded', init);
        })();
    </script>
</x-layouts.app>
