<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Exception;

/**
 * PoliController - Manages policlinic operations
 * 
 * Handles CRUD operations for managing policlinics
 * including their names, descriptions, and associated doctors.
 */
class PoliController extends Controller
{
    /**
     * Display list of all policlinics (Admin function)
     * 
     * Shows all policlinics with their names, descriptions,
     * and associated doctors count.
     *
     * @return View
     */
    public function index(): View
    {
        try {
            $polis = Poli::orderBy('nama_poli', 'asc')
                ->withCount(['dokters', 'jadwalPeriksa'])
                ->get();

            return view('admin.polis.index', compact('polis'));

        } catch (Exception $e) {
            Log::error('Failed to load policlinics list: ' . $e->getMessage());

            return view('admin.polis.index', ['polis' => collect()])
                ->with('error', 'Gagal memuat daftar poliklinik.');
        }
    }

    /**
     * Show form to create new policlinic (Admin function)
     * 
     * Displays form for creating a new policlinic
     * with name and description fields.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.polis.create');
    }

    /**
     * Store new policlinic data (Admin function)
     * 
     * Validates input and creates new policlinic
     * with unique name and optional description.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validate input data
            $validatedData = $request->validate([
                'nama_poli' => 'required|string|max:50|min:3|unique:polis,nama_poli|regex:/^[a-zA-Z\s]+$/',
                'keterangan' => 'nullable|string|max:500|min:5',
            ], [
                'nama_poli.required' => 'Nama poliklinik wajib diisi.',
                'nama_poli.min' => 'Nama poliklinik minimal 3 karakter.',
                'nama_poli.max' => 'Nama poliklinik maksimal 50 karakter.',
                'nama_poli.unique' => 'Nama poliklinik sudah terdaftar.',
                'nama_poli.regex' => 'Nama poliklinik hanya boleh berisi huruf dan spasi.',
                'keterangan.min' => 'Keterangan minimal 5 karakter jika diisi.',
                'keterangan.max' => 'Keterangan maksimal 500 karakter.',
            ]);

            // Create new policlinic
            $poli = Poli::create([
                'nama_poli' => trim($validatedData['nama_poli']),
                'keterangan' => $validatedData['keterangan'] ? trim($validatedData['keterangan']) : null,
            ]);

            Log::info('New policlinic created successfully', [
                'poli_id' => $poli->id,
                'poli_name' => $poli->nama_poli
            ]);

            return redirect()->route('admin.poli.index')
                ->with('message', 'Poliklinik berhasil ditambahkan')
                ->with('type', 'success');

        } catch (ValidationException $e) {
            Log::warning('Policlinic creation validation failed', [
                'errors' => $e->errors()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (Exception $e) {
            Log::error('Failed to create policlinic: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menambahkan poliklinik. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Show form to edit policlinic data (Admin function)
     * 
     * Displays edit form with current policlinic data
     * for administrative updates.
     *
     * @param Poli $poli
     * @return View|RedirectResponse
     */
    public function edit(Poli $poli): View|RedirectResponse
    {
        try {
            return view('admin.polis.edit', compact('poli'));

        } catch (Exception $e) {
            Log::error('Failed to load edit policlinic form: ' . $e->getMessage(), [
                'poli_id' => $poli->id
            ]);

            return redirect()->route('admin.poli.index')
                ->with('error', 'Gagal memuat form edit poliklinik.');
        }
    }

    /**
     * Update policlinic data (Admin function)
     * 
     * Validates and updates policlinic information
     * including name and description.
     *
     * @param Request $request
     * @param Poli $poli
     * @return RedirectResponse
     */
    public function update(Request $request, Poli $poli): RedirectResponse
    {
        try {
            // Validate input data
            $validatedData = $request->validate([
                'nama_poli' => 'required|string|max:50|min:3|unique:polis,nama_poli,' . $poli->id . '|regex:/^[a-zA-Z\s]+$/',
                'keterangan' => 'nullable|string|max:500|min:5',
            ], [
                'nama_poli.required' => 'Nama poliklinik wajib diisi.',
                'nama_poli.min' => 'Nama poliklinik minimal 3 karakter.',
                'nama_poli.max' => 'Nama poliklinik maksimal 50 karakter.',
                'nama_poli.unique' => 'Nama poliklinik sudah terdaftar.',
                'nama_poli.regex' => 'Nama poliklinik hanya boleh berisi huruf dan spasi.',
                'keterangan.min' => 'Keterangan minimal 5 karakter jika diisi.',
                'keterangan.max' => 'Keterangan maksimal 500 karakter.',
            ]);

            // Update policlinic
            $poli->update([
                'nama_poli' => trim($validatedData['nama_poli']),
                'keterangan' => $validatedData['keterangan'] ? trim($validatedData['keterangan']) : null,
            ]);

            Log::info('Policlinic updated successfully', [
                'poli_id' => $poli->id,
                'poli_name' => $poli->nama_poli
            ]);

            return redirect()->route('admin.poli.index')
                ->with('message', 'Poliklinik berhasil diperbarui')
                ->with('type', 'success');

        } catch (ValidationException $e) {
            Log::warning('Policlinic update validation failed', [
                'poli_id' => $poli->id,
                'errors' => $e->errors()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (Exception $e) {
            Log::error('Failed to update policlinic: ' . $e->getMessage(), [
                'poli_id' => $poli->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mengubah data poliklinik. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Delete policlinic data (Admin function)
     * 
     * Safely removes policlinic after checking
     * for associated doctors and schedules.
     *
     * @param Poli $poli
     * @return RedirectResponse
     */
    public function destroy(Poli $poli): RedirectResponse
    {
        try {
            // Check for associated doctors
            $hasDoctors = $poli->dokters()->exists();
            $hasSchedules = $poli->jadwalPeriksa()->exists();

            if ($hasDoctors || $hasSchedules) {
                return redirect()->route('admin.poli.index')
                    ->with('warning', 'Poliklinik tidak dapat dihapus karena masih memiliki dokter atau jadwal terkait.');
            }

            $poliName = $poli->nama_poli;
            $poliId = $poli->id;

            // Delete policlinic
            $poli->delete();

            Log::info('Policlinic deleted successfully', [
                'poli_id' => $poliId,
                'poli_name' => $poliName
            ]);

            return redirect()->route('admin.poli.index')
                ->with('message', 'Poliklinik berhasil dihapus')
                ->with('type', 'success');

        } catch (Exception $e) {
            Log::error('Failed to delete policlinic: ' . $e->getMessage(), [
                'poli_id' => $poli->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.poli.index')
                ->with('error', 'Gagal menghapus poliklinik. Silakan coba lagi.');
        }
    }
}