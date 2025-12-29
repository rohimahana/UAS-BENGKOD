<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\Obat;
use App\Models\Periksa;
use App\Models\DetailPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeriksaPasienController extends Controller
{
    /**
     * Display a listing of patients registered for examination.
     */
    public function index()
    {
        // Get the logged-in doctor's ID
        $dokterId = Auth::id();

        // Get all patients registered for this doctor with their relations
        $daftarPolis = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antrian', 'asc')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPolis'));
    }

    /**
     * Show the form for examining a patient.
     */
    public function create($id)
    {
        // Get all available medicines
        $obats = Obat::all();

        // Return view with medicines and patient registration ID
        return view('dokter.periksa-pasien.create', compact('obats', 'id'));
    }

    /**
     * Store the examination results.
     */
    public function store(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'id_daftar_poli' => 'required|exists:daftar_polis,id',
            'catatan' => 'required|string',
            'biaya_periksa' => 'required|numeric',
            'obat_json' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Decode selected medicines from JSON
            // Format: [{id: 1, jumlah: 2}, {id: 3, jumlah: 1}]
            $obatTerpilih = json_decode($request->obat_json, true) ?? [];

            // Validate stock availability before processing
            foreach ($obatTerpilih as $obat) {
                $obatModel = Obat::findOrFail($obat['id']);

                if ($obatModel->stok < $obat['jumlah']) {
                    throw new \Exception("Stok obat '{$obatModel->nama_obat}' tidak mencukupi. Tersedia: {$obatModel->stok}, Dibutuhkan: {$obat['jumlah']}");
                }
            }

            // Create new examination record
            $periksa = Periksa::create([
                'id_daftar_poli' => $request->id_daftar_poli,
                'tgl_periksa' => now(),
                'catatan' => $request->catatan,
                'biaya_periksa' => $request->biaya_periksa,
            ]);

            // Save each selected medicine to detail_periksas table and update stock
            foreach ($obatTerpilih as $obat) {
                // Save detail periksa with quantity
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat' => $obat['id'],
                    'jumlah' => $obat['jumlah'],
                ]);

                // Auto-deduct stock (Capstone Feature)
                $obatModel = Obat::findOrFail($obat['id']);
                $obatModel->stok -= $obat['jumlah'];
                $obatModel->save();
            }

            DB::commit();

            return redirect()
                ->route('dokter.periksa-pasien.index')
                ->with('message', 'Pemeriksaan pasien berhasil disimpan!')
                ->with('type', 'success');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan pemeriksaan: ' . $e->getMessage());
        }
    }
}
