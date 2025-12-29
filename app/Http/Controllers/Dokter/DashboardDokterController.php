<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use App\Models\DaftarPoli;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * DashboardDokterController - Handles doctor dashboard
 * 
 * Manages the doctor dashboard view and statistics
 * including patient queue, examination history, and schedule info.
 */
class DashboardDokterController extends Controller
{
    /**
     * Display doctor dashboard (main entry point)
     * 
     * Shows dashboard with statistics and information
     * for the logged-in doctor.
     * 
     * @return View
     */
    public function index(): View
    {
        return $this->dashboard();
    }

    /**
     * Display doctor dashboard with statistics
     * 
     * Shows comprehensive dashboard including:
     * - Active schedule count
     * - Patient queue today
     * - Total examinations
     * - Recent patient registrations
     * 
     * @return View
     */
    public function dashboard(): View
    {
        try {
            // Get logged-in doctor
            $dokter = Auth::user();

            // Get active schedules count
            $activeSchedulesCount = JadwalPeriksa::where('id_dokter', $dokter->id)
                ->where('aktif', 'Y')
                ->count();

            // Get patient queue today (registered but not examined)
            $todayQueueCount = DaftarPoli::whereHas('jadwalPeriksa', function ($query) use ($dokter) {
                $query->where('id_dokter', $dokter->id);
            })
                ->whereDate('created_at', today())
                ->whereDoesntHave('periksa')
                ->count();

            // Get total examinations
            $totalExaminations = Periksa::whereHas('daftarPoli.jadwalPeriksa', function ($query) use ($dokter) {
                $query->where('id_dokter', $dokter->id);
            })->count();

            // Get recent patient registrations (last 5)
            $recentRegistrations = DaftarPoli::with(['pasien', 'jadwalPeriksa.poli'])
                ->whereHas('jadwalPeriksa', function ($query) use ($dokter) {
                    $query->where('id_dokter', $dokter->id);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Get examinations today
            $todayExaminations = Periksa::whereHas('daftarPoli.jadwalPeriksa', function ($query) use ($dokter) {
                $query->where('id_dokter', $dokter->id);
            })
                ->whereDate('created_at', today())
                ->count();

            return view('dokter.dashboard', compact(
                'activeSchedulesCount',
                'todayQueueCount',
                'totalExaminations',
                'recentRegistrations',
                'todayExaminations'
            ));

        } catch (Exception $e) {
            Log::error('Failed to load doctor dashboard: ' . $e->getMessage(), [
                'doctor_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return dashboard with empty data on error
            return view('dokter.dashboard', [
                'activeSchedulesCount' => 0,
                'todayQueueCount' => 0,
                'totalExaminations' => 0,
                'recentRegistrations' => collect(),
                'todayExaminations' => 0
            ])->with('error', 'Gagal memuat dashboard. Silakan refresh halaman.');
        }
    }
}
