<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * DashboardPasienController - Handles patient dashboard
 * 
 * Manages the patient dashboard view with medical history
 * and appointment information.
 */
class DashboardPasienController extends Controller
{
    /**
     * Display patient dashboard (main entry point)
     * 
     * Shows dashboard with appointment history and
     * medical records for the logged-in patient.
     * 
     * @return View
     */
    public function index(): View
    {
        return $this->dashboard();
    }

    /**
     * Display patient dashboard with statistics
     * 
     * Shows comprehensive dashboard including:
     * - Total appointments
     * - Completed examinations
     * - Pending appointments
     * - Recent appointment history
     * 
     * @return View
     */
    public function dashboard(): View
    {
        try {
            // Get logged-in patient
            $pasien = Auth::user();

            // Get total appointments count
            $totalAppointments = DaftarPoli::where('id_pasien', $pasien->id)->count();

            // Get completed examinations (with periksa)
            $completedExaminations = DaftarPoli::where('id_pasien', $pasien->id)
                ->whereHas('periksa')
                ->count();

            // Get pending appointments (not examined yet)
            $pendingAppointments = DaftarPoli::where('id_pasien', $pasien->id)
                ->whereDoesntHave('periksa')
                ->count();

            // Get recent appointments (last 5)
            $recentAppointments = DaftarPoli::with(['jadwalPeriksa.dokter', 'jadwalPeriksa.poli', 'periksa'])
                ->where('id_pasien', $pasien->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Get upcoming appointments (today and future, not examined)
            $upcomingAppointments = DaftarPoli::with(['jadwalPeriksa.dokter', 'jadwalPeriksa.poli'])
                ->where('id_pasien', $pasien->id)
                ->whereDoesntHave('periksa')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            return view('pasien.dashboard', compact(
                'totalAppointments',
                'completedExaminations',
                'pendingAppointments',
                'recentAppointments',
                'upcomingAppointments'
            ));

        } catch (Exception $e) {
            Log::error('Failed to load patient dashboard: ' . $e->getMessage(), [
                'patient_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return dashboard with empty data on error
            return view('pasien.dashboard', [
                'totalAppointments' => 0,
                'completedExaminations' => 0,
                'pendingAppointments' => 0,
                'recentAppointments' => collect(),
                'upcomingAppointments' => collect()
            ])->with('error', 'Gagal memuat dashboard. Silakan refresh halaman.');
        }
    }
}
