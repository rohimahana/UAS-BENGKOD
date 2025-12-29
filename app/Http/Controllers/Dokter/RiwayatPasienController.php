<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPasienController extends Controller
{
    /**
     * Display a listing of patient examination history.
     * 
     * Menampilkan riwayat pemeriksaan pasien yang ditangani
     * oleh dokter yang sedang login (filter by id_dokter).
     */
    public function index()
    {
        // Get logged-in doctor
        $dokter = Auth::user();

        // Get examination records ONLY for this doctor
        // Filter berdasarkan jadwal periksa yang dimiliki dokter ini
        $riwayatPasien = Periksa::with([
            'daftarPoli.pasien',
            'daftarPoli.jadwalPeriksa.dokter',
            'detailPeriksa.obat' // Fixed: detailPeriksa instead of detailPeriksas
        ])
            ->whereHas('daftarPoli.jadwalPeriksa', function ($query) use ($dokter) {
                $query->where('id_dokter', $dokter->id);
            })
            ->orderBy('tgl_periksa', 'desc')
            ->get();

        return view('dokter.riwayat-pasien.index', compact('riwayatPasien'));
    }

    /**
     * Display the specified examination details.
     * 
     * Menampilkan detail pemeriksaan pasien tertentu.
     * Verifikasi bahwa pemeriksaan ini dilakukan oleh dokter yang login.
     */
    public function show($id)
    {
        // Get logged-in doctor
        $dokter = Auth::user();

        // Get single examination record with all relations
        // findOrFail will throw 404 if not found
        $periksa = Periksa::with([
            'daftarPoli.pasien',
            'daftarPoli.jadwalPeriksa.dokter',
            'daftarPoli.jadwalPeriksa.poli',
            'detailPeriksa.obat' // Fixed: detailPeriksa instead of detailPeriksas
        ])
            ->whereHas('daftarPoli.jadwalPeriksa', function ($query) use ($dokter) {
                $query->where('id_dokter', $dokter->id);
            })
            ->findOrFail($id);

        return view('dokter.riwayat-pasien.show', compact('periksa'));
    }
}
