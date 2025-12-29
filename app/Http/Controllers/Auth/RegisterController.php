<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

/**
 * RegisterController - Handles patient registration
 * 
 * This controller manages new patient registration with automatic
 * medical record number generation and auto-login after successful registration.
 */
class RegisterController extends Controller
{
    /**
     * Show the registration form
     * 
     * @return View
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Handle user registration (patients only)
     * 
     * Validates input, generates medical record number (No. RM),
     * creates new patient account, and auto-login user.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function register(Request $request): RedirectResponse
    {
        // Validate registration data
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:500'],
            'no_ktp' => ['required', 'string', 'size:16', 'unique:users,no_ktp', 'regex:/^[0-9]+$/'],
            'no_hp' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'no_ktp.required' => 'Nomor KTP wajib diisi.',
            'no_ktp.size' => 'Nomor KTP harus 16 digit.',
            'no_ktp.regex' => 'Nomor KTP hanya boleh berisi angka.',
            'no_ktp.unique' => 'Nomor KTP sudah terdaftar.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        // Generate unique medical record number (No. RM)
        // Format: YYYYMMXXXXXX (example: 202512000001)
        // Consistent dengan format di PasienController
        $lastPasien = User::where('role', 'pasien')
            ->whereNotNull('no_rm')
            ->orderBy('no_rm', 'desc')
            ->first();

        $lastNumber = $lastPasien ? (int) substr($lastPasien->no_rm, -6) : 0;
        $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        $no_rm = date('Ym') . $newNumber;

        // Create new patient user
        try {
            $user = User::create([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_ktp' => $request->no_ktp,
                'no_hp' => $request->no_hp,
                'no_rm' => $no_rm,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pasien', // New registrations are always patients
            ]);

            // Auto-login after successful registration
            Auth::login($user);
            $request->session()->regenerate(); // Security: prevent session fixation

            return redirect()->route('pasien.dashboard')
                ->with('success', 'Pendaftaran berhasil! Selamat datang, ' . $user->nama);

        } catch (Exception $e) {
            return back()
                ->withErrors(['general' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }
}
