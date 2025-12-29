<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * RoleMiddleware - Middleware untuk role-based access control
 * 
 * Memastikan hanya user dengan role tertentu yang dapat mengakses route.
 * Jika user tidak memiliki role yang sesuai, akan dialihkan ke dashboard mereka
 * dengan pesan error yang informatif.
 */
class RoleMiddleware
{
    /**
     * Handle incoming request and check user role
     * 
     * @param Request $request
     * @param Closure $next
     * @param string $role Expected role (admin, dokter, or pasien)
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = Auth::user();

        // Cek apakah role user sesuai dengan yang diizinkan
        if ($user->role !== $role) {
            // Redirect ke dashboard sesuai role user (bukan error 403)
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard')
                        ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');

                case 'dokter':
                    return redirect()->route('dokter.dashboard')
                        ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');

                case 'pasien':
                    return redirect()->route('pasien.dashboard')
                        ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');

                default:
                    // Role tidak dikenali - logout untuk keamanan
                    Auth::logout();
                    return redirect()->route('home')
                        ->with('error', 'Role pengguna tidak valid. Silakan login kembali.');
            }
        }

        return $next($request);
    }
}
