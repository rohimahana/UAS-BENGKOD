<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Exception;

/**
 * DokterController - Manages doctor-related operations
 * 
 * Handles both doctor dashboard for logged-in doctors and
 * CRUD operations for admin to manage doctor accounts.
 */
class DokterController extends Controller
{
    /**
     * Display doctor dashboard
     * 
     * Shows personalized dashboard for logged-in doctors
     * with their schedule and patient information.
     *
     * @return View
     */
    public function dashboard(): View
    {
        try {
            // Future: Add doctor-specific statistics here
            // e.g., today's appointments, patient queue, etc.

            return view('dokter.dashboard');

        } catch (Exception $e) {
            Log::error('Doctor dashboard error: ' . $e->getMessage());

            return view('dokter.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat dashboard.');
        }
    }

    /**
     * Display list of all doctors (Admin function)
     * 
     * Shows paginated list of doctors with their assigned
     * policlinics for administrative management.
     *
     * @return View
     */
    public function index(): View
    {
        try {
            $dokters = User::where('role', 'dokter')
                ->with('poli')
                ->orderBy('nama')
                ->get();

            return view('admin.dokters.index', compact('dokters'));

        } catch (Exception $e) {
            Log::error('Failed to load doctors list: ' . $e->getMessage());

            return view('admin.dokters.index', ['dokters' => collect()])
                ->with('error', 'Gagal memuat daftar dokter.');
        }
    }

    /**
     * Show form to create new doctor (Admin function)
     * 
     * Displays form with available policlinics for
     * creating a new doctor account.
     *
     * @return View|RedirectResponse
     */
    public function create(): View|RedirectResponse
    {
        try {
            $polis = Poli::orderBy('nama_poli')->get();

            if ($polis->isEmpty()) {
                return redirect()->route('admin.dokter.index')
                    ->with('warning', 'Harap buat poliklinik terlebih dahulu sebelum menambah dokter.');
            }

            return view('admin.dokters.create', compact('polis'));

        } catch (Exception $e) {
            Log::error('Failed to load create doctor form: ' . $e->getMessage());

            return redirect()->route('admin.dokter.index')
                ->with('error', 'Gagal memuat form tambah dokter.');
        }
    }

    /**
     * Store new doctor data (Admin function)
     * 
     * Validates input and creates new doctor account
     * with encrypted password and assigned policlinic.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validate input data
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255|min:3',
                'alamat' => 'required|string|max:500|min:10',
                'no_hp' => 'required|string|max:20|min:10|regex:/^[0-9+\-\s]+$/',
                'no_ktp' => 'required|string|size:16|regex:/^[0-9]{16}$/|unique:users,no_ktp',
                'id_poli' => 'required|exists:polis,id',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ], [
                'nama.required' => 'Nama dokter wajib diisi.',
                'nama.min' => 'Nama dokter minimal 3 karakter.',
                'alamat.required' => 'Alamat dokter wajib diisi.',
                'alamat.min' => 'Alamat minimal 10 karakter.',
                'no_hp.required' => 'Nomor HP wajib diisi.',
                'no_hp.regex' => 'Format nomor HP tidak valid.',
                'no_ktp.required' => 'Nomor KTP wajib diisi.',
                'no_ktp.size' => 'Nomor KTP harus 16 digit.',
                'no_ktp.regex' => 'Nomor KTP harus berupa angka.',
                'no_ktp.unique' => 'Nomor KTP sudah terdaftar.',
                'id_poli.required' => 'Poliklinik wajib dipilih.',
                'id_poli.exists' => 'Poliklinik yang dipilih tidak valid.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.regex' => 'Password harus mengandung huruf besar, kecil, dan angka.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]);

            // Create new doctor
            $doctor = User::create([
                'nama' => $validatedData['nama'],
                'alamat' => $validatedData['alamat'],
                'no_hp' => $validatedData['no_hp'],
                'no_ktp' => $validatedData['no_ktp'],
                'id_poli' => $validatedData['id_poli'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'dokter',
                'email_verified_at' => now(), // Auto-verify doctor accounts
            ]);

            Log::info('New doctor created successfully', [
                'doctor_id' => $doctor->id,
                'doctor_name' => $doctor->nama,
                'poli_id' => $doctor->id_poli
            ]);

            return redirect()->route('admin.dokter.index')
                ->with('message', 'Data dokter berhasil ditambahkan')
                ->with('type', 'success');

        } catch (ValidationException $e) {
            Log::warning('Doctor creation validation failed', [
                'errors' => $e->errors()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (Exception $e) {
            Log::error('Failed to create doctor: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menambahkan dokter. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Show form to edit doctor data (Admin function)
     * 
     * Displays edit form with current doctor data
     * and available policlinics.
     *
     * @param User $dokter
     * @return View|RedirectResponse
     */
    public function edit(User $dokter): View|RedirectResponse
    {
        try {
            // Verify that the user is actually a doctor
            if ($dokter->role !== 'dokter') {
                return redirect()->route('admin.dokter.index')
                    ->with('error', 'Pengguna yang dipilih bukan dokter.');
            }

            $polis = Poli::orderBy('nama_poli')->get();

            return view('admin.dokters.edit', compact('dokter', 'polis'));

        } catch (Exception $e) {
            Log::error('Failed to load edit doctor form: ' . $e->getMessage(), [
                'doctor_id' => $dokter->id ?? null
            ]);

            return redirect()->route('admin.dokter.index')
                ->with('error', 'Gagal memuat form edit dokter.');
        }
    }

    /**
     * Update doctor data (Admin function)
     * 
     * Validates and updates doctor information including
     * optional password change.
     *
     * @param Request $request
     * @param User $dokter
     * @return RedirectResponse
     */
    public function update(Request $request, User $dokter): RedirectResponse
    {
        try {
            // Verify that the user is actually a doctor
            if ($dokter->role !== 'dokter') {
                return redirect()->route('admin.dokter.index')
                    ->with('error', 'Pengguna yang dipilih bukan dokter.');
            }

            // Validate input data
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255|min:3',
                'alamat' => 'required|string|max:500|min:10',
                'no_hp' => 'required|string|max:20|min:10|regex:/^[0-9+\-\s]+$/',
                'no_ktp' => 'required|string|size:16|regex:/^[0-9]{16}$/|unique:users,no_ktp,' . $dokter->id,
                'id_poli' => 'required|exists:polis,id',
                'email' => 'required|string|email|max:255|unique:users,email,' . $dokter->id,
                'password' => 'nullable|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ], [
                'nama.required' => 'Nama dokter wajib diisi.',
                'nama.min' => 'Nama dokter minimal 3 karakter.',
                'alamat.required' => 'Alamat dokter wajib diisi.',
                'alamat.min' => 'Alamat minimal 10 karakter.',
                'no_hp.required' => 'Nomor HP wajib diisi.',
                'no_hp.regex' => 'Format nomor HP tidak valid.',
                'no_ktp.required' => 'Nomor KTP wajib diisi.',
                'no_ktp.size' => 'Nomor KTP harus 16 digit.',
                'no_ktp.regex' => 'Nomor KTP harus berupa angka.',
                'no_ktp.unique' => 'Nomor KTP sudah terdaftar.',
                'id_poli.required' => 'Poliklinik wajib dipilih.',
                'id_poli.exists' => 'Poliklinik yang dipilih tidak valid.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.regex' => 'Password harus mengandung huruf besar, kecil, dan angka.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]);

            // Prepare update data
            $updateData = [
                'nama' => $validatedData['nama'],
                'alamat' => $validatedData['alamat'],
                'no_hp' => $validatedData['no_hp'],
                'no_ktp' => $validatedData['no_ktp'],
                'id_poli' => $validatedData['id_poli'],
                'email' => $validatedData['email'],
            ];

            // Add password to update data if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validatedData['password']);
            }

            // Update doctor
            $dokter->update($updateData);

            Log::info('Doctor updated successfully', [
                'doctor_id' => $dokter->id,
                'doctor_name' => $dokter->nama,
                'updated_fields' => array_keys($updateData)
            ]);

            return redirect()->route('admin.dokter.index')
                ->with('message', 'Data dokter berhasil diubah')
                ->with('type', 'success');

        } catch (ValidationException $e) {
            Log::warning('Doctor update validation failed', [
                'doctor_id' => $dokter->id,
                'errors' => $e->errors()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (Exception $e) {
            Log::error('Failed to update doctor: ' . $e->getMessage(), [
                'doctor_id' => $dokter->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mengubah data dokter. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Delete doctor data (Admin function)
     * 
     * Safely removes doctor account after checking
     * for related data dependencies.
     *
     * @param User $dokter
     * @return RedirectResponse
     */
    public function destroy(User $dokter): RedirectResponse
    {
        try {
            // Verify that the user is actually a doctor
            if ($dokter->role !== 'dokter') {
                return redirect()->route('admin.dokter.index')
                    ->with('message', 'Pengguna yang dipilih bukan dokter.')
                    ->with('type', 'error');
            }

            // Check for related schedules
            $hasSchedules = \App\Models\JadwalPeriksa::where('id_dokter', $dokter->id)->exists();

            // Check if doctor has any patient registrations through their schedules
            $hasDaftarPoli = \App\Models\DaftarPoli::whereHas('jadwalPeriksa', function ($query) use ($dokter) {
                $query->where('id_dokter', $dokter->id);
            })->exists();

            if ($hasSchedules || $hasDaftarPoli) {
                return redirect()->route('admin.dokter.index')
                    ->with('message', 'Dokter tidak dapat dihapus karena masih memiliki jadwal atau riwayat pendaftaran pasien.')
                    ->with('type', 'warning');
            }

            $doctorName = $dokter->nama;
            $doctorId = $dokter->id;

            // Delete doctor
            $dokter->delete();

            Log::info('Doctor deleted successfully', [
                'doctor_id' => $doctorId,
                'doctor_name' => $doctorName
            ]);

            return redirect()->route('admin.dokter.index')
                ->with('message', 'Data dokter berhasil dihapus')
                ->with('type', 'success');

        } catch (Exception $e) {
            Log::error('Failed to delete doctor: ' . $e->getMessage(), [
                'doctor_id' => $dokter->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.dokter.index')
                ->with('message', 'Gagal menghapus data dokter: ' . $e->getMessage())
                ->with('type', 'error');
        }
    }
}