<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Exception;

/**
 * ObatController - Manages medicine/drug operations
 * 
 * Handles CRUD operations for managing medicines including
 * their names, packaging, and pricing information.
 */
class ObatController extends Controller
{
    /**
     * Display list of all medicines (Admin function)
     * 
     * Shows all medicines with their names, packaging,
     * prices, and usage statistics.
     *
     * @return View
     */
    public function index(): View
    {
        try {
            $obats = Obat::orderBy('nama_obat', 'asc')
                ->withCount('detailPeriksa')
                ->get();

            return view('admin.obats.index', compact('obats'));

        } catch (Exception $e) {
            Log::error('Failed to load medicines list: ' . $e->getMessage());

            return view('admin.obats.index', ['obats' => collect()])
                ->with('error', 'Gagal memuat daftar obat.');
        }
    }

    /**
     * Show form to create new medicine (Admin function)
     * 
     * Displays form for creating a new medicine
     * with name, packaging, and price fields.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.obats.create');
    }

    /**
     * Store new medicine data (Admin function)
     * 
     * Validates input and creates new medicine
     * with proper formatting and price validation.
     * Includes stock management fields (Capstone Feature).
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validate input data
            $validatedData = $request->validate([
                'nama_obat' => 'required|string|max:255|min:3|regex:/^[a-zA-Z\s\-\.0-9]+$/',
                'kemasan' => 'required|string|max:100|min:2|regex:/^[a-zA-Z0-9\s\-\.\/]+$/',
                'harga' => 'required|numeric|min:100|max:1000000',
                'stok' => 'nullable|integer|min:0|max:10000',
                'stok_minimum' => 'nullable|integer|min:1|max:100',
            ], [
                'nama_obat.required' => 'Nama obat wajib diisi.',
                'nama_obat.min' => 'Nama obat minimal 3 karakter.',
                'nama_obat.max' => 'Nama obat maksimal 255 karakter.',
                'nama_obat.regex' => 'Nama obat hanya boleh berisi huruf, angka, spasi, titik, dan strip.',
                'kemasan.required' => 'Kemasan obat wajib diisi.',
                'kemasan.min' => 'Kemasan minimal 2 karakter.',
                'kemasan.max' => 'Kemasan maksimal 100 karakter.',
                'kemasan.regex' => 'Kemasan hanya boleh berisi huruf, angka, spasi, strip, titik, dan garis miring.',
                'harga.required' => 'Harga obat wajib diisi.',
                'harga.numeric' => 'Harga harus berupa angka.',
                'harga.min' => 'Harga minimal Rp 100.',
                'harga.max' => 'Harga maksimal Rp 1.000.000.',
                'stok.integer' => 'Stok harus berupa angka.',
                'stok.min' => 'Stok minimal 0.',
                'stok.max' => 'Stok maksimal 10.000.',
                'stok_minimum.integer' => 'Stok minimum harus berupa angka.',
                'stok_minimum.min' => 'Stok minimum minimal 1.',
                'stok_minimum.max' => 'Stok minimum maksimal 100.',
            ]);

            // Create new medicine
            $obat = Obat::create([
                'nama_obat' => trim($validatedData['nama_obat']),
                'kemasan' => trim($validatedData['kemasan']),
                'harga' => round($validatedData['harga']),
                'stok' => $validatedData['stok'] ?? 0,
                'stok_minimum' => $validatedData['stok_minimum'] ?? 10,
            ]);

            Log::info('New medicine created successfully', [
                'obat_id' => $obat->id,
                'obat_name' => $obat->nama_obat,
                'price' => $obat->harga,
                'stok' => $obat->stok
            ]);

            return redirect()->route('admin.obat.index')
                ->with('message', 'Data obat berhasil ditambahkan')
                ->with('type', 'success');

        } catch (ValidationException $e) {
            Log::warning('Medicine creation validation failed', [
                'errors' => $e->errors()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (Exception $e) {
            Log::error('Failed to create medicine: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menambahkan obat. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Show form to edit medicine data (Admin function)
     * 
     * Displays edit form with current medicine data
     * for administrative updates.
     *
     * @param Obat $obat
     * @return View|RedirectResponse
     */
    public function edit(Obat $obat): View|RedirectResponse
    {
        try {
            return view('admin.obats.edit', compact('obat'));

        } catch (Exception $e) {
            Log::error('Failed to load edit medicine form: ' . $e->getMessage(), [
                'obat_id' => $obat->id
            ]);

            return redirect()->route('admin.obat.index')
                ->with('error', 'Gagal memuat form edit obat.');
        }
    }

    /**
     * Update medicine data (Admin function)
     * 
     * Validates and updates medicine information
     * including name, packaging, and price.
     *
     * @param Request $request
     * @param Obat $obat
     * @return RedirectResponse
     */
    public function update(Request $request, Obat $obat): RedirectResponse
    {
        try {
            // Validate input data
            $validatedData = $request->validate([
                'nama_obat' => 'required|string|max:255|min:3|regex:/^[a-zA-Z\s\-\.0-9]+$/',
                'kemasan' => 'required|string|max:100|min:2|regex:/^[a-zA-Z0-9\s\-\.\/]+$/',
                'harga' => 'required|numeric|min:100|max:1000000',
                'stok' => 'nullable|integer|min:0|max:10000',
                'stok_minimum' => 'nullable|integer|min:1|max:100',
            ], [
                'nama_obat.required' => 'Nama obat wajib diisi.',
                'nama_obat.min' => 'Nama obat minimal 3 karakter.',
                'nama_obat.max' => 'Nama obat maksimal 255 karakter.',
                'nama_obat.regex' => 'Nama obat hanya boleh berisi huruf, angka, spasi, titik, dan strip.',
                'kemasan.required' => 'Kemasan obat wajib diisi.',
                'kemasan.min' => 'Kemasan minimal 2 karakter.',
                'kemasan.max' => 'Kemasan maksimal 100 karakter.',
                'kemasan.regex' => 'Kemasan hanya boleh berisi huruf, angka, spasi, strip, titik, dan garis miring.',
                'harga.required' => 'Harga obat wajib diisi.',
                'harga.numeric' => 'Harga harus berupa angka.',
                'harga.min' => 'Harga minimal Rp 100.',
                'harga.max' => 'Harga maksimal Rp 1.000.000.',
                'stok.integer' => 'Stok harus berupa angka.',
                'stok.min' => 'Stok minimal 0.',
                'stok.max' => 'Stok maksimal 10.000.',
                'stok_minimum.integer' => 'Stok minimum harus berupa angka.',
                'stok_minimum.min' => 'Stok minimum minimal 1.',
                'stok_minimum.max' => 'Stok minimum maksimal 100.',
            ]);

            // Update medicine
            $obat->update([
                'nama_obat' => trim($validatedData['nama_obat']),
                'kemasan' => trim($validatedData['kemasan']),
                'harga' => round($validatedData['harga']),
                'stok' => $validatedData['stok'] ?? $obat->stok,
                'stok_minimum' => $validatedData['stok_minimum'] ?? $obat->stok_minimum,
            ]);

            Log::info('Medicine updated successfully', [
                'obat_id' => $obat->id,
                'obat_name' => $obat->nama_obat,
                'price' => $obat->harga
            ]);

            return redirect()->route('admin.obat.index')
                ->with('message', 'Data obat berhasil diperbarui')
                ->with('type', 'success');

        } catch (ValidationException $e) {
            Log::warning('Medicine update validation failed', [
                'obat_id' => $obat->id,
                'errors' => $e->errors()
            ]);

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (Exception $e) {
            Log::error('Failed to update medicine: ' . $e->getMessage(), [
                'obat_id' => $obat->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mengubah data obat. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Delete medicine data (Admin function)
     * 
     * Safely removes medicine after checking
     * for usage in prescriptions.
     *
     * @param Obat $obat
     * @return RedirectResponse
     */
    public function destroy(Obat $obat): RedirectResponse
    {
        try {
            // Check if medicine is used in prescriptions
            $isUsed = $obat->detailPeriksa()->exists();

            if ($isUsed) {
                return redirect()->route('admin.obat.index')
                    ->with('warning', 'Obat tidak dapat dihapus karena sudah digunakan dalam resep.');
            }

            $obatName = $obat->nama_obat;
            $obatId = $obat->id;

            // Delete medicine
            $obat->delete();

            Log::info('Medicine deleted successfully', [
                'obat_id' => $obatId,
                'obat_name' => $obatName
            ]);

            return redirect()->route('admin.obat.index')
                ->with('message', 'Data obat berhasil dihapus')
                ->with('type', 'success');

        } catch (Exception $e) {
            Log::error('Failed to delete medicine: ' . $e->getMessage(), [
                'obat_id' => $obat->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.obat.index')
                ->with('error', 'Gagal menghapus data obat. Silakan coba lagi.');
        }
    }

    /**
     * Adjust medicine stock (Admin function) - CAPSTONE FEATURE
     * 
     * Manually adjust stock with three actions:
     * - add: Increase stock by specified amount
     * - subtract: Decrease stock by specified amount
     * - set: Set stock to specific value
     *
     * @param Request $request
     * @param Obat $obat
     * @return RedirectResponse
     */
    public function adjustStock(Request $request, Obat $obat): RedirectResponse
    {
        try {
            // Validate request
            $validatedData = $request->validate([
                'action' => 'required|in:add,subtract,set',
                'jumlah' => 'required|integer|min:1|max:10000',
            ], [
                'action.required' => 'Aksi wajib dipilih.',
                'action.in' => 'Aksi tidak valid.',
                'jumlah.required' => 'Jumlah wajib diisi.',
                'jumlah.integer' => 'Jumlah harus berupa angka bulat.',
                'jumlah.min' => 'Jumlah minimal 1.',
                'jumlah.max' => 'Jumlah maksimal 10,000.',
            ]);

            $action = $validatedData['action'];
            $jumlah = $validatedData['jumlah'];
            $oldStock = $obat->stok;

            // Execute action based on type
            switch ($action) {
                case 'add':
                    // Add stock
                    $success = $obat->increaseStock($jumlah);
                    $message = $success
                        ? "Stok berhasil ditambah {$jumlah} unit (dari {$oldStock} menjadi {$obat->stok})"
                        : 'Gagal menambah stok';
                    break;

                case 'subtract':
                    // Subtract stock (with validation)
                    if ($obat->stok < $jumlah) {
                        return redirect()->route('admin.obat.index')
                            ->with('error', "Gagal mengurangi stok. Stok saat ini ({$obat->stok}) kurang dari jumlah yang akan dikurangi ({$jumlah}).");
                    }
                    $success = $obat->decreaseStock($jumlah);
                    $message = $success
                        ? "Stok berhasil dikurangi {$jumlah} unit (dari {$oldStock} menjadi {$obat->stok})"
                        : 'Gagal mengurangi stok';
                    break;

                case 'set':
                    // Set stock to specific value
                    $success = $obat->setStock($jumlah);
                    $message = $success
                        ? "Stok berhasil diatur menjadi {$jumlah} unit (sebelumnya {$oldStock})"
                        : 'Gagal mengatur stok';
                    break;

                default:
                    return redirect()->route('admin.obat.index')
                        ->with('error', 'Aksi tidak valid.');
            }

            if ($success) {
                Log::info('Medicine stock adjusted', [
                    'obat_id' => $obat->id,
                    'obat_name' => $obat->nama_obat,
                    'action' => $action,
                    'old_stock' => $oldStock,
                    'new_stock' => $obat->stok,
                    'amount' => $jumlah
                ]);

                return redirect()->route('admin.obat.index')
                    ->with('message', $message)
                    ->with('type', 'success');
            } else {
                return redirect()->route('admin.obat.index')
                    ->with('error', $message)
                    ->with('type', 'error');
            }

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors());

        } catch (Exception $e) {
            Log::error('Failed to adjust medicine stock: ' . $e->getMessage(), [
                'obat_id' => $obat->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.obat.index')
                ->with('error', 'Gagal mengatur stok obat. Silakan coba lagi.');
        }
    }
}
