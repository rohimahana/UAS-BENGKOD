<?php

use App\Http\Controllers\Auth\{
    LoginController,
    RegisterController
};
use App\Http\Controllers\Admin\{
    DashboardAdminController,
    DokterController,
    ObatController,
    PasienController,
    PoliController
};
use App\Http\Controllers\Dokter\{
    DashboardDokterController,
    JadwalPeriksaController,
    PeriksaPasienController,
    RiwayatPasienController
};
use App\Http\Controllers\Pasien\{
    DashboardPasienController,
    DaftarPoliController
};
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/**
 * Public Routes
 */
Route::get('/', [HomeController::class, 'index'])->name('home');

/**
 * Authentication Routes
 */
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Registration routes (for patients only)
    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Logout route (authenticated users only)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/**
 * Admin Dashboard Routes
 */
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardAdminController::class, 'index'])->name('index');
    Route::get('/dashboard', [DashboardAdminController::class, 'dashboard'])->name('dashboard');

    // Resource Management
    Route::resource('poli', PoliController::class)->except(['show']);
    Route::resource('dokter', DokterController::class)->except(['show']);
    Route::resource('pasien', PasienController::class)->except(['show']);
    Route::resource('obat', ObatController::class)->except(['show']);

    // Stock Management (Capstone Feature)
    Route::post('obat/{obat}/adjust-stock', [ObatController::class, 'adjustStock'])->name('obat.adjust-stock');
});

/**
 * Dokter Dashboard Routes
 */
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->name('dokter.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardDokterController::class, 'index'])->name('index');
    Route::get('/dashboard', [DashboardDokterController::class, 'dashboard'])->name('dashboard');

    // Jadwal Management (CRUD resource standar tanpa method custom)
    Route::resource('jadwal-periksa', JadwalPeriksaController::class)->except(['show']);

    // Patient Examination
    Route::prefix('periksa-pasien')->name('periksa-pasien.')->group(function () {
        Route::get('/', [PeriksaPasienController::class, 'index'])->name('index');
        Route::get('/create/{id}', [PeriksaPasienController::class, 'create'])->name('create');
        Route::post('/store', [PeriksaPasienController::class, 'store'])->name('store');
    });

    // Patient History
    Route::prefix('riwayat-pasien')->name('riwayat-pasien.')->group(function () {
        Route::get('/', [RiwayatPasienController::class, 'index'])->name('index');
        Route::get('/{id}', [RiwayatPasienController::class, 'show'])->name('show');
    });
});

/**
 * Pasien Dashboard Routes
 */
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardPasienController::class, 'index'])->name('index');
    Route::get('/dashboard', [DashboardPasienController::class, 'dashboard'])->name('dashboard');

    // Poli Registration - Sesuai instruksi Tugas 5
    Route::get('/daftar-poli', [DaftarPoliController::class, 'get'])->name('daftar-poli');
    Route::post('/daftar-poli', [DaftarPoliController::class, 'submit'])->name('daftar-poli.submit');

    // Medical History
    Route::get('/riwayat', [DaftarPoliController::class, 'index'])->name('riwayat');
});
