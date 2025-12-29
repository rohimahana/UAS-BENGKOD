<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * HomeController - Handles the application home page
 * 
 * Manages the landing page with authentication checks and 
 * role-based redirections for logged-in users.
 */
class HomeController extends Controller
{
    /**
     * Display the home page or redirect authenticated users
     * 
     * Authenticated users are automatically redirected to their
     * respective dashboards based on their role.
     *
     * @return View|RedirectResponse
     */
    public function index(): View|RedirectResponse
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Redirect based on user role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard')
                        ->with('success', 'Selamat datang kembali, Admin!');

                case 'dokter':
                    return redirect()->route('dokter.dashboard')
                        ->with('success', 'Selamat datang kembali, Dr. ' . $user->nama);

                case 'pasien':
                    return redirect()->route('pasien.dashboard')
                        ->with('success', 'Selamat datang kembali, ' . $user->nama);

                default:
                    // Handle invalid/unknown role - logout for security
                    Auth::logout();
                    return redirect()->route('home')
                        ->with('error', 'Role pengguna tidak valid. Silakan login kembali.');
            }
        }

        // Show home page for guests
        return view('home');
    }
}