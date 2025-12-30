<x-layouts.app title="Jadwal Periksa - Dokter">
    <div class="mx-auto max-w-6xl px-4 py-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Jadwal Periksa</h1>
                <p class="mt-1 text-sm text-slate-600">Kelola jadwal praktik Anda. Toggle untuk aktif/nonaktif.</p>
            </div>

            <a href="{{ route('dokter.jadwal-periksa.create') }}"
                class="inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-semibold btn-brand shadow-sm ring-brand">
                <i class="fa-solid fa-plus"></i>
                Tambah Jadwal
            </a>
        </div>

        @php
            $total = $jadwalPeriksas->count();
            $aktif = $jadwalPeriksas->where('aktif', 'Y')->count();
            $nonaktif = $jadwalPeriksas->where('aktif', 'T')->count();
        @endphp

        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-slate-500">Total Jadwal</div>
                        <div class="mt-2 text-2xl font-bold text-slate-900">{{ $total }}</div>
                    </div>
                    <span class="grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 ring-brand">
                        <i class="fa-solid fa-layer-group text-brand"></i>
                    </span>
                </div>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-slate-500">Aktif</div>
                        <div class="mt-2 text-2xl font-bold text-slate-900">{{ $aktif }}</div>
                    </div>
                    <span class="grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 ring-brand">
                        <i class="fa-solid fa-toggle-on text-brand"></i>
                    </span>
                </div>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-slate-500">Nonaktif</div>
                        <div class="mt-2 text-2xl font-bold text-slate-900">{{ $nonaktif }}</div>
                    </div>
                    <span class="grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 ring-brand">
                        <i class="fa-solid fa-toggle-off text-brand"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="mt-6 rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="text-sm font-semibold text-slate-900">Daftar Jadwal</div>
                    <div class="text-xs text-slate-500">Klik toggle untuk mengubah status.</div>
                </div>

                <div class="w-full sm:w-80">
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input id="searchInput" type="text" placeholder="Cari hari / jam..."
                            class="w-full rounded-2xl border border-slate-200 bg-white py-2.5 pl-10 pr-3 text-sm text-slate-700 shadow-sm focus:outline-none focus:ring-2 ring-brand" />
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-600">
                        <tr>
                            <th class="px-6 py-3 text-left">No</th>
                            <th class="px-6 py-3 text-left">Hari</th>
                            <th class="px-6 py-3 text-left">Mulai</th>
                            <th class="px-6 py-3 text-left">Selesai</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="divide-y divide-slate-200">
                        @forelse ($jadwalPeriksas as $index => $jadwalPeriksa)
                            <tr class="bg-white hover:bg-slate-50">
                                <td class="px-6 py-4 text-slate-600">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $jadwalPeriksa->hari }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ date('H:i', strtotime($jadwalPeriksa->jam_mulai)) }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ date('H:i', strtotime($jadwalPeriksa->jam_selesai)) }}</td>
                                <td class="px-6 py-4">
                                    @if ($jadwalPeriksa->aktif == 'Y')
                                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-3 py-1 text-xs font-semibold text-white">
                                            <i class="fa-solid fa-check text-[10px]"></i> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">
                                            <i class="fa-solid fa-xmark text-[10px]"></i> Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <form action="{{ route('dokter.jadwal-periksa.update', $jadwalPeriksa) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="hari" value="{{ $jadwalPeriksa->hari }}">
                                            <input type="hidden" name="jam_mulai" value="{{ date('H:i', strtotime($jadwalPeriksa->jam_mulai)) }}">
                                            <input type="hidden" name="jam_selesai" value="{{ date('H:i', strtotime($jadwalPeriksa->jam_selesai)) }}">
                                            <input type="hidden" name="aktif" value="{{ $jadwalPeriksa->aktif == 'Y' ? 'T' : 'Y' }}">

                                            <button type="submit"
                                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
                                                title="{{ $jadwalPeriksa->aktif == 'Y' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i class="fa-solid {{ $jadwalPeriksa->aktif == 'Y' ? 'fa-toggle-on' : 'fa-toggle-off' }} text-brand"></i>
                                                Toggle
                                            </button>
                                        </form>

                                        <a href="{{ route('dokter.jadwal-periksa.edit', $jadwalPeriksa) }}"
                                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50"
                                            title="Edit Jadwal">
                                            <i class="fa-solid fa-pen text-brand"></i>
                                            Edit
                                        </a>
<button
    type="button"
    onclick="confirmDelete('{{ route('dokter.jadwal-periksa.destroy', $jadwalPeriksa) }}', 'jadwal periksa ini')"
    class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 shadow-sm hover:bg-rose-100"
    title="Hapus Jadwal">
    <i class="fa-solid fa-trash"></i>
    Hapus
</button>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-10 text-center text-slate-500" colspan="6">Belum ada jadwal.</td>
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
