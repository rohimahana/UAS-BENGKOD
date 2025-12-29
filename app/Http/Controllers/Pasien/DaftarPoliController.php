<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\Poli;
use App\Models\JadwalPeriksa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Exception;

/**
 * DaftarPoliController - Manages patient clinic registration
 * 
 * Handles patient registration for clinic appointments,
 * queue management, and appointment history tracking.
 */
class DaftarPoliController extends Controller
{
    /**
     * Display patient's clinic registration history
     * 
     * Shows complete list of patient's appointments with
     * doctor information, queue numbers, and examination status.
     *
     * @return View
     */
    public function index(): View
    {
        try {
            $daftars = DaftarPoli::with([
                'jadwalPeriksa.dokter.poli',
                'periksa.detailPeriksa.obat'
            ])
                ->where('id_pasien', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();

            return view('pasien.riwayat', compact('daftars'));

        } catch (Exception $e) {
            Log::error('Failed to load patient registration history: ' . $e->getMessage(), [
                'patient_id' => Auth::id()
            ]);

            return view('pasien.riwayat', ['daftars' => collect()])
                ->with('error', 'Gagal memuat riwayat pendaftaran.');
        }
    }

    /**
     * Get data for patient registration (GET method)
     * 
     * Menampilkan data pasien yang sedang login, daftar poli, 
     * dan jadwal periksa dengan eager loading sesuai instruksi Tugas 5
     *
     * @return View|RedirectResponse
     */
    public function get(): View|RedirectResponse
    {
        try {
            // Data pasien yang sedang login dengan eager loading
            $pasien = User::with(['daftarPoli.jadwalPeriksa.dokter.poli'])
                ->find(Auth::id());

            // Daftar poli dengan relasi dokter dan jadwal (eager loading)
            // Hanya tampilkan poli yang memiliki dokter dengan jadwal aktif
            $polis = Poli::with([
                'dokters.jadwalPeriksa' => function ($query) {
                    $query->where('aktif', 'Y') // Fixed: 'Y' instead of true
                        ->orderBy('hari')
                        ->orderBy('jam_mulai');
                }
            ])
                ->whereHas('dokters.jadwalPeriksa', function ($query) {
                    $query->where('aktif', 'Y'); // Fixed: 'Y' instead of true
                })
                ->orderBy('nama_poli')
                ->get();

            // Jadwal periksa aktif dengan relasi dokter dan poli (eager loading)  
            $jadwals = JadwalPeriksa::with(['dokter.poli'])
                ->where('aktif', 'Y') // Fixed: 'Y' instead of true
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get();

            return view('pasien.daftar', compact('pasien', 'polis', 'jadwals'));

        } catch (Exception $e) {
            Log::error('Failed to load registration form: ' . $e->getMessage(), [
                'patient_id' => Auth::id()
            ]);

            return redirect()->route('pasien.dashboard')
                ->with('error', 'Gagal memuat form pendaftaran.');
        }
    }

    /**
     * Submit patient registration (POST method)
     * 
     * Memproses data input, menghitung nomor antrian secara otomatis,
     * dan menyimpan data pendaftaran ke database sesuai instruksi Tugas 5
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function submit(Request $request): RedirectResponse
    {
        try {
            // Log incoming request
            Log::info('=== DAFTAR POLI SUBMIT START ===');
            Log::info('Request data:', $request->all());
            Log::info('Auth user ID: ' . Auth::id());

            // Validasi data input
            $validatedData = $request->validate([
                'id_jadwal' => 'required|exists:jadwal_periksas,id',
                'keluhan' => 'required|string|min:10|max:500',
            ], [
                'id_jadwal.required' => 'Jadwal dokter wajib dipilih.',
                'id_jadwal.exists' => 'Jadwal yang dipilih tidak valid.',
                'keluhan.required' => 'Keluhan wajib diisi.',
                'keluhan.min' => 'Keluhan minimal 10 karakter.',
                'keluhan.max' => 'Keluhan maksimal 500 karakter.',
            ]);

            Log::info('Validation passed:', $validatedData);

            // Cek apakah pasien sudah terdaftar di jadwal yang sama hari ini
            $existingRegistration = DaftarPoli::where('id_pasien', Auth::id())
                ->where('id_jadwal', $validatedData['id_jadwal'])
                ->whereDate('created_at', Carbon::today())
                ->exists();

            Log::info('Existing registration check for same schedule:', ['exists' => $existingRegistration]);

            if ($existingRegistration) {
                Log::warning('Patient already registered for this schedule today');
                return redirect()->back()
                    ->with('warning', 'Anda sudah terdaftar untuk jadwal dokter ini hari ini.')
                    ->withInput();
            }

            // Hitung nomor antrian secara otomatis
            $count = DaftarPoli::where('id_jadwal', $validatedData['id_jadwal'])
                ->whereDate('created_at', Carbon::today())
                ->count();
            $no_antrian = $count + 1;

            Log::info('Queue calculation:', ['count' => $count, 'no_antrian' => $no_antrian]);

            // Batasi maksimal 20 pasien per hari
            if ($no_antrian > 20) {
                Log::warning('Queue full', ['no_antrian' => $no_antrian]);
                return redirect()->back()
                    ->with('error', 'Kuota pendaftaran hari ini sudah penuh.');
            }

            // Simpan data pendaftaran ke database
            $dataToCreate = [
                'id_pasien' => Auth::id(),
                'id_jadwal' => $validatedData['id_jadwal'],
                'keluhan' => trim($validatedData['keluhan']),
                'no_antrian' => $no_antrian,
            ];

            Log::info('Data to create:', $dataToCreate);

            $daftar = DaftarPoli::create($dataToCreate);

            Log::info('Registration created successfully:', [
                'id' => $daftar->id,
                'no_antrian' => $daftar->no_antrian
            ]);

            // Log aktivitas
            $jadwal = JadwalPeriksa::with('dokter.poli')->find($validatedData['id_jadwal']);
            Log::info('Patient registration submitted successfully', [
                'registration_id' => $daftar->id,
                'patient_id' => Auth::id(),
                'queue_number' => $no_antrian,
                'doctor' => $jadwal->dokter->nama ?? 'Unknown',
                'poli' => $jadwal->dokter->poli->nama_poli ?? 'Unknown'
            ]);

            return redirect()->route('pasien.riwayat')
                ->with('success', "Pendaftaran berhasil! Nomor antrian Anda: $no_antrian");

        } catch (ValidationException $e) {
            Log::error('Validation failed:', ['errors' => $e->errors()]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (Exception $e) {
            Log::error('Failed to submit patient registration: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Gagal melakukan pendaftaran. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Show detailed registration information
     * 
     * Displays comprehensive information about a specific
     * clinic registration including examination results.
     *
     * @param DaftarPoli $daftarPoli
     * @return View|RedirectResponse
     */
    public function show(DaftarPoli $daftarPoli): View|RedirectResponse
    {
        try {
            // Check if registration belongs to authenticated patient
            if ($daftarPoli->id_pasien !== Auth::id()) {
                return redirect()->route('pasien.riwayat')
                    ->with('error', 'Akses tidak diizinkan.');
            }

            $daftarPoli->load([
                'jadwalPeriksa.dokter.poli',
                'periksa.detailPeriksa.obat'
            ]);

            return view('pasien.detail-riwayat', compact('daftarPoli'));

        } catch (Exception $e) {
            Log::error('Failed to show registration details: ' . $e->getMessage(), [
                'registration_id' => $daftarPoli->id,
                'patient_id' => Auth::id()
            ]);

            return redirect()->route('pasien.riwayat')
                ->with('error', 'Gagal memuat detail pendaftaran.');
        }
    }

    /**
     * Cancel clinic registration (Patient function)
     * 
     * Allows patient to cancel registration if examination
     * hasn't been conducted yet.
     *
     * @param DaftarPoli $daftarPoli
     * @return RedirectResponse
     */
    public function destroy(DaftarPoli $daftarPoli): RedirectResponse
    {
        try {
            // Check if registration belongs to authenticated patient
            if ($daftarPoli->id_pasien !== Auth::id()) {
                return redirect()->route('pasien.riwayat')
                    ->with('error', 'Akses tidak diizinkan.');
            }

            // Check if examination has been conducted
            if ($daftarPoli->periksa()->exists()) {
                return redirect()->route('pasien.riwayat')
                    ->with('warning', 'Pendaftaran tidak dapat dibatalkan karena sudah diperiksa.');
            }

            // Check if cancellation is still allowed (at least 1 hour before appointment)
            $scheduledTime = Carbon::createFromFormat('H:i', $daftarPoli->jadwalPeriksa->jam_mulai);
            $now = Carbon::now();

            if ($now->diffInHours($scheduledTime, false) < 1) {
                return redirect()->route('pasien.riwayat')
                    ->with('warning', 'Pendaftaran tidak dapat dibatalkan kurang dari 1 jam sebelum jadwal.');
            }

            $registrationInfo = [
                'registration_id' => $daftarPoli->id,
                'queue_number' => $daftarPoli->no_antrian,
                'doctor' => $daftarPoli->jadwalPeriksa->dokter->nama,
                'poli' => $daftarPoli->jadwalPeriksa->dokter->poli->nama_poli
            ];

            // Delete registration
            $daftarPoli->delete();

            Log::info('Clinic registration cancelled by patient', array_merge($registrationInfo, [
                'patient_id' => Auth::id()
            ]));

            return redirect()->route('pasien.riwayat')
                ->with('message', 'Pendaftaran berhasil dibatalkan')
                ->with('type', 'success');

        } catch (Exception $e) {
            Log::error('Failed to cancel registration: ' . $e->getMessage(), [
                'registration_id' => $daftarPoli->id,
                'patient_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('pasien.riwayat')
                ->with('error', 'Gagal membatalkan pendaftaran. Silakan coba lagi.');
        }
    }
}
