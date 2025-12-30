<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * LoginController - Handles user authentication
 *
 * UX note:
 * - Remove "Selamat datang" / "Berhasil" popups on login/logout (requested)
 */
class LoginController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Role-based redirection (no welcome popup)
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'dokter' => redirect()->route('dokter.dashboard'),
                'pasien' => redirect()->route('pasien.dashboard'),
                default => $this->logoutInvalidRole($request),
            };
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput($request->except('password'));
    }

    private function logoutInvalidRole(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->withErrors(['email' => 'Role pengguna tidak valid.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect without popup message
        return redirect()->route('login');
    }
}
