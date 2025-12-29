<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Exception;

/**
 * PasienController - Manages patient-related operations
 * 
 * Handles both patient dashboard for logged-in patients and
 * CRUD operations for admin to manage patient accounts.
 */
class PasienController extends Controller
{
    /**
     * Display patient dashboard
     * 
     * Shows personalized dashboard for logged-in patients
     * with their appointment history and medical records.
     *
     * @return View
     */
    public function dashboard(): View
    {
        try {
            // Future: Add patient-specific data here
            // e.g., upcoming appointments, medical history, etc.

            return view('pasien.dashboard');

        } catch (Exception $e) {
            Log::error('Patient dashboard error: ' . $e->getMessage());

            return view('pasien.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat dashboard.');
        }
    }

    /**
     * Display list of all patients (Admin function)
     * 
     * Shows paginated list of patients with their
     * medical record numbers and contact information.
     *
     * @return View
     */
    public function index(): View
    {
        try {
            $pasiens = User::pasiens()
                ->orderBy('nama', 'asc')
                ->get();

            return view('admin.pasiens.index', compact('pasiens'));

        } catch (Exception $e) {
            Log::error('Failed to load patients list: ' . $e->getMessage());

            return view('admin.pasiens.index', ['pasiens' => collect()])
                ->with('error', 'Gagal memuat daftar pasien.');
        }
    }

    /**
     * Show form to create new patient (Admin function)
     * 
     * Displays form for creating a new patient account
     * with automatic medical record number generation.
     *
     * @return View|RedirectResponse
     */
    public function create(): View|RedirectResponse
    {
        try {
            return view('admin.pasiens.create');

        } catch (Exception $e) {
            Log::error('Failed to load create patient form: ' . $e->getMessage());

            return redirect()->route('admin.pasien.index')
                ->with('error', 'Gagal memuat form tambah pasien.');
        }
    }

    /**
     * Store new patient data (Admin function)
     * 
     * Validates input and creates new patient account with
     * automatic medical record number generation.
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
                'no_ktp' => 'required|string|size:16|regex:/^[0-9]{16}$/|unique:users',
                'no_hp' => 'required|string|max:20|min:10|regex:/^[0-9+\-\s]+$/',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ], [
                'nama.required' => 'Nama pasien wajib diisi.',
                'nama.min' => 'Nama pasien minimal 3 karakter.',
                'alamat.required' => 'Alamat pasien wajib diisi.',
                'alamat.min' => 'Alamat minimal 10 karakter.',
                'no_ktp.required' => 'Nomor KTP wajib diisi.',
                'no_ktp.size' => 'Nomor KTP harus 16 digit.',
                'no_ktp.regex' => 'Nomor KTP harus berupa angka.',
                'no_ktp.unique' => 'Nomor KTP sudah terdaftar.',
                'no_hp.required' => 'Nomor HP wajib diisi.',
                'no_hp.regex' => 'Format nomor HP tidak valid.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.regex' => 'Password harus mengandung huruf besar, kecil, dan angka.',
            ]);

            // Generate medical record number automatically
            $lastPasien = User::pasiens()
                ->whereNotNull('no_rm')
                ->orderBy('no_rm', 'desc')
                ->first();

            $lastNumber = $lastPasien ? (int) substr($lastPasien->no_rm, -6) : 0;
            $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
            $no_rm = date('Ym') . $newNumber;

            // Create new patient
            $patient = User::create([
                'nama' => $validatedData['nama'],
                'alamat' => $validatedData['alamat'],
                'no_ktp' => $validatedData['no_ktp'],
                'no_hp' => $validatedData['no_hp'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'no_rm' => $no_rm,
                'role' => 'pasien',
                'email_verified_at' => now(), // Auto-verify patient accounts
            ]);

            Log::info('New patient created successfully', [
                'patient_id' => $patient->id,
                'patient_name' => $patient->nama,
                'no_rm' => $patient->no_rm
            ]);

            return redirect()->route('admin.pasien.index')
                ->with('message', 'Data pasien berhasil ditambahkan dengan No. RM: ' . $no_rm)
                ->with('type', 'success');

        } catch (ValidationException $e) {
            Log::warning('Patient creation validation failed', [
                'errors' => $e->errors()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (Exception $e) {
            Log::error('Failed to create patient: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menambahkan pasien. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Show form to edit patient data (Admin function)
     * 
     * Displays edit form with current patient data
     * for administrative updates.
     *
     * @param string $id
     * @return View|RedirectResponse
     */
    public function edit(string $id): View|RedirectResponse
    {
        try {
            $pasien = User::findOrFail($id);

            // Verify that the user is actually a patient
            if ($pasien->role !== 'pasien') {
                return redirect()->route('admin.pasien.index')
                    ->with('error', 'Pengguna yang dipilih bukan pasien.');
            }

            return view('admin.pasiens.edit', compact('pasien'));

        } catch (Exception $e) {
            Log::error('Failed to load edit patient form: ' . $e->getMessage(), [
                'patient_id' => $id
            ]);

            return redirect()->route('admin.pasien.index')
                ->with('error', 'Gagal memuat form edit pasien.');
        }
    }

    /**
     * Update patient data (Admin function)
     * 
     * Validates and updates patient information including
     * optional password change.
     *
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        try {
            $pasien = User::findOrFail($id);

            // Verify that the user is actually a patient
            if ($pasien->role !== 'pasien') {
                return redirect()->route('admin.pasien.index')
                    ->with('error', 'Pengguna yang dipilih bukan pasien.');
            }

            // Validate input data
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255|min:3',
                'alamat' => 'required|string|max:500|min:10',
                'no_ktp' => 'required|string|size:16|regex:/^[0-9]{16}$/|unique:users,no_ktp,' . $pasien->id,
                'no_hp' => 'required|string|max:20|min:10|regex:/^[0-9+\-\s]+$/',
                'email' => 'required|string|email|max:255|unique:users,email,' . $pasien->id,
                'password' => 'nullable|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ], [
                'nama.required' => 'Nama pasien wajib diisi.',
                'nama.min' => 'Nama pasien minimal 3 karakter.',
                'alamat.required' => 'Alamat pasien wajib diisi.',
                'alamat.min' => 'Alamat minimal 10 karakter.',
                'no_ktp.required' => 'Nomor KTP wajib diisi.',
                'no_ktp.size' => 'Nomor KTP harus 16 digit.',
                'no_ktp.regex' => 'Nomor KTP harus berupa angka.',
                'no_ktp.unique' => 'Nomor KTP sudah terdaftar.',
                'no_hp.required' => 'Nomor HP wajib diisi.',
                'no_hp.regex' => 'Format nomor HP tidak valid.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.regex' => 'Password harus mengandung huruf besar, kecil, dan angka.',
            ]);

            // Prepare update data
            $updateData = [
                'nama' => $validatedData['nama'],
                'alamat' => $validatedData['alamat'],
                'no_ktp' => $validatedData['no_ktp'],
                'no_hp' => $validatedData['no_hp'],
                'email' => $validatedData['email'],
            ];

            // Add password to update data if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validatedData['password']);
            }

            // Update patient
            $pasien->update($updateData);

            Log::info('Patient updated successfully', [
                'patient_id' => $pasien->id,
                'patient_name' => $pasien->nama,
                'updated_fields' => array_keys($updateData)
            ]);

            return redirect()->route('admin.pasien.index')
                ->with('message', 'Data pasien berhasil diperbarui')
                ->with('type', 'success');

        } catch (ValidationException $e) {
            Log::warning('Patient update validation failed', [
                'patient_id' => $id,
                'errors' => $e->errors()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (Exception $e) {
            Log::error('Failed to update patient: ' . $e->getMessage(), [
                'patient_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mengubah data pasien. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Delete patient data (Admin function)
     * 
     * Safely removes patient account after checking
     * for related medical data dependencies.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $pasien = User::findOrFail($id);

            // Verify that the user is actually a patient
            if ($pasien->role !== 'pasien') {
                return redirect()->route('admin.pasien.index')
                    ->with('message', 'Pengguna yang dipilih bukan pasien.')
                    ->with('type', 'error');
            }

            // Check for related medical data (appointments through daftar_poli)
            $hasAppointments = \App\Models\DaftarPoli::where('id_pasien', $pasien->id)->exists();

            if ($hasAppointments) {
                return redirect()->route('admin.pasien.index')
                    ->with('message', 'Pasien tidak dapat dihapus karena memiliki riwayat pendaftaran atau pemeriksaan.')
                    ->with('type', 'warning');
            }

            $patientName = $pasien->nama;
            $patientRM = $pasien->no_rm;

            // Delete patient
            $pasien->delete();

            Log::info('Patient deleted successfully', [
                'patient_id' => $id,
                'patient_name' => $patientName,
                'no_rm' => $patientRM
            ]);

            return redirect()->route('admin.pasien.index')
                ->with('message', 'Data pasien berhasil dihapus')
                ->with('type', 'success');

        } catch (Exception $e) {
            Log::error('Failed to delete patient: ' . $e->getMessage(), [
                'patient_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.pasien.index')
                ->with('message', 'Gagal menghapus data pasien: ' . $e->getMessage())
                ->with('type', 'error');
        }
    }
}
