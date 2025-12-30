<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeriksaPasienController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPolis = DaftarPoli::with(['pasien', 'jadwalPeriksa.dokter.poli', 'periksa'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antrian', 'asc')
            ->get();

        // Show patients that are NOT examined first
        $daftarPolis = $daftarPolis->sortBy(fn ($dp) => $dp->periksa ? 1 : 0)->values();

        return view('dokter.periksa-pasien.index', compact('daftarPolis'));
    }

    public function create($id)
    {
        $daftar = DaftarPoli::with(['pasien', 'jadwalPeriksa.dokter.poli', 'periksa'])->findOrFail($id);

        if ($daftar->periksa) {
            return redirect()
                ->route('dokter.riwayat-pasien.show', $daftar->periksa->id)
                ->with('message', 'Pasien ini sudah diperiksa. Silakan lihat detail pemeriksaannya.')
                ->with('type', 'info');
        }

        // Only show medicines that exist; UI will disable out-of-stock
        $obats = Obat::orderBy('nama_obat')->get();

        return view('dokter.periksa-pasien.create', compact('obats', 'id', 'daftar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_daftar_poli' => 'required|exists:daftar_polis,id',
            'catatan' => 'required|string',
            'biaya_periksa' => 'required|numeric|min:0',
            'obat_json' => 'nullable|string',
        ]);

        try {
            $lowStockNotes = [];

            DB::transaction(function () use ($request, &$lowStockNotes) {
                // Lock daftar_poli row to prevent double submit
                $daftar = DaftarPoli::whereKey($request->id_daftar_poli)->lockForUpdate()->firstOrFail();
                $existing = $daftar->periksa()->first();
                if ($existing) {
                    throw new \Exception('Pasien ini sudah diperiksa. Pemeriksaan tidak bisa dibuat dua kali.');
                }

                // Decode & normalize medicines: [{id, jumlah}]
                $raw = json_decode($request->obat_json, true) ?? [];
                $need = []; // [id => qty]
                foreach ($raw as $row) {
                    $id = (int) ($row['id'] ?? 0);
                    $qty = (int) ($row['jumlah'] ?? 0);
                    if ($id <= 0) continue;
                    if ($qty < 1) $qty = 1;
                    $need[$id] = ($need[$id] ?? 0) + $qty;
                }

                // Lock medicines rows
                $obats = collect();
                if (!empty($need)) {
                    $obats = Obat::whereIn('id', array_keys($need))->lockForUpdate()->get()->keyBy('id');

                    // Validate stock availability
                    foreach ($need as $obatId => $qty) {
                        $obat = $obats->get($obatId);
                        if (!$obat) {
                            throw new \Exception('Obat tidak ditemukan.');
                        }
                        if ((int) $obat->stok < $qty) {
                            throw new \Exception("Stok obat '{$obat->nama_obat}' tidak mencukupi. Tersedia: {$obat->stok}, Dibutuhkan: {$qty}");
                        }
                    }
                }

                // Create examination
                $periksa = Periksa::create([
                    'id_daftar_poli' => $request->id_daftar_poli,
                    'tgl_periksa' => now(),
                    'catatan' => $request->catatan,
                    'biaya_periksa' => $request->biaya_periksa,
                ]);

                // Save medicines + deduct stock
                foreach ($need as $obatId => $qty) {
                    DetailPeriksa::create([
                        'id_periksa' => $periksa->id,
                        'id_obat' => $obatId,
                        'jumlah' => $qty,
                    ]);

                    $obat = $obats->get($obatId);
                    $obat->stok = (int) $obat->stok - $qty;
                    $obat->save();

                    if ($obat->isLowStock()) {
                        $lowStockNotes[] = "Stok '{$obat->nama_obat}' menipis (sisa {$obat->stok}).";
                    }
                    if ($obat->isOutOfStock()) {
                        $lowStockNotes[] = "Stok '{$obat->nama_obat}' habis (0).";
                    }
                }
            });

            // Success + optional low stock warning
            $redirect = redirect()
                ->route('dokter.periksa-pasien.index')
                ->with('message', 'Pemeriksaan pasien berhasil disimpan!')
                ->with('type', 'success');

            if (!empty($lowStockNotes)) {
                $redirect->with('warning', implode(' ', $lowStockNotes));
            }

            return $redirect;

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
