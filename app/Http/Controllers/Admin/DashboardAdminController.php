<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use App\Models\User;
use App\Models\Obat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * AdminController - Handles administrative dashboard and statistics
 * 
 * Manages the admin section with dashboard statistics including
 * counts of policlinics, doctors, patients, and medicines.
 */
class DashboardAdminController extends Controller
{
    /**
     * Admin index page - redirects to dashboard
     * 
     * @return RedirectResponse
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.dashboard')
            ->with('info', 'Selamat datang di panel administrasi');
    }

    /**
     * Display admin dashboard with statistics
     * 
     * Shows comprehensive statistics about the clinic including
     * total counts of policlinics, doctors, patients, and medicines.
     *
     * @return View
     */
    public function dashboard(): View
    {
        try {
            // Gather dashboard statistics
            $statistics = [
                'totalPoli' => Poli::count(),
                'totalDokter' => User::where('role', 'dokter')->count(),
                'totalPasien' => User::where('role', 'pasien')->count(),
                'totalObat' => Obat::count()
            ];

            // Additional useful statistics
            $additionalStats = [
                'aktiveDokter' => User::where('role', 'dokter')
                    ->whereNotNull('email_verified_at')
                    ->count(),
                'pasienBaru' => User::where('role', 'pasien')
                    ->whereDate('created_at', '>=', now()->subDays(30))
                    ->count(),
                'poliAktif' => Poli::whereHas('jadwalPeriksa')
                    ->count()
            ];

            return view('admin.dashboard', array_merge($statistics, $additionalStats));

        } catch (Exception $e) {
            // Log error for debugging
            Log::error('Admin dashboard error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // Return dashboard with default values
            return view('admin.dashboard', [
                'totalPoli' => 0,
                'totalDokter' => 0,
                'totalPasien' => 0,
                'totalObat' => 0,
                'aktiveDokter' => 0,
                'pasienBaru' => 0,
                'poliAktif' => 0
            ])->with('error', 'Terjadi kesalahan saat memuat data dashboard.');
        }
    }
}