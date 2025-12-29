<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * JadwalPeriksaController - Manages doctor schedule
 * 
 * Handles CRUD operations for doctor examination schedules.
 * Each doctor can create multiple schedules with different days and times.
 * Only one schedule can be active at a time for each doctor.
 */
class JadwalPeriksaController extends Controller
{
    /**
     * Display list of examination schedules
     * 
     * Shows all schedules belonging to the logged-in doctor,
     * ordered by day of the week.
     * 
     * @return View
     */
    public function index(): View
    {
        $dokter = Auth::user();
        $jadwalPeriksas = JadwalPeriksa::where('id_dokter', $dokter->id)
            ->orderBy('hari')
            ->get();

        return view('dokter.jadwal-periksa.index', compact('jadwalPeriksas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dokter.jadwal-periksa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'aktif' => 'required|in:Y,T'
        ]);

        JadwalPeriksa::create([
            'id_dokter' => Auth::id(),
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'aktif' => $request->aktif
        ]);

        return redirect()->route('dokter.jadwal-periksa.index')
            ->with('message', 'Jadwal periksa berhasil ditambahkan')
            ->with('type', 'success');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JadwalPeriksa $jadwalPeriksa)
    {
        // Ensure only the owner can edit
        if ($jadwalPeriksa->id_dokter !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('dokter.jadwal-periksa.edit', compact('jadwalPeriksa'));
    }

    /**
     * Update the specified resource in storage.
     * Bisa digunakan untuk update jadwal atau toggle status aktif
     */
    public function update(Request $request, JadwalPeriksa $jadwalPeriksa)
    {
        // Pastikan hanya pemilik jadwal yang bisa update
        if ($jadwalPeriksa->id_dokter !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi input
        $request->validate([
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'aktif' => 'required|in:Y,T'
        ]);

        // Update data jadwal
        $jadwalPeriksa->update([
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'aktif' => $request->aktif
        ]);

        return redirect()->route('dokter.jadwal-periksa.index')
            ->with('message', 'Jadwal periksa berhasil diperbarui')
            ->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalPeriksa $jadwalPeriksa)
    {
        // Ensure only the owner can delete
        if ($jadwalPeriksa->id_dokter !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $jadwalPeriksa->delete();

        return redirect()->route('dokter.jadwal-periksa.index')
            ->with('message', 'Jadwal periksa berhasil dihapus')
            ->with('type', 'success');
    }
}