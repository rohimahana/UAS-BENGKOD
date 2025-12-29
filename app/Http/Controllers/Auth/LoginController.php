<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * LoginController - Handles user authentication
 * 
 * This controller manages login and logout functionality
 * for the Poliklinik application with role-based redirections.
 */
class LoginController extends Controller
{
    /**
     * Show the login form
     * 
     * @return View
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Handle user login with role-based redirection
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        // Validate login credentials
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string']
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.'
        ]);

        $credentials = $request->only('email', 'password');

        // Attempt authentication
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Security: prevent session fixation

            $user = Auth::user();

            // Role-based redirection
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard')
                        ->with('success', 'Selamat datang, Admin!');
                case 'dokter':
                    return redirect()->route('dokter.dashboard')
                        ->with('success', 'Selamat datang, Dr. ' . $user->nama);
                case 'pasien':
                    return redirect()->route('pasien.dashboard')
                        ->with('success', 'Selamat datang, ' . $user->nama);
                default:
                    // Handle unknown role
                    Auth::logout();
                    return redirect()->route('login')
                        ->withErrors(['email' => 'Role pengguna tidak valid.']);
            }
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput($request->except('password'));
    }

    /**
     * Handle user logout
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        // Invalidate session and regenerate token for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}