<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * HomeController - Landing page.
 *
 * UX note:
 * - Always show landing page (no auto redirect to dashboard)
 * - If user is still logged in, auto-logout to force login again (requested behavior)
 */
class HomeController extends Controller
{
    public function index(Request $request): View
    {
        // If still logged-in, force logout so reopening/rerunning the app starts clean
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return view('home');
    }
}
