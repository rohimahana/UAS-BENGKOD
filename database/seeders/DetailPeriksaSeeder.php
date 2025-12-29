<?php

namespace Database\Seeders;

use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Database\Seeder;

class DetailPeriksaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * ⭐ CAPSTONE FEATURE: Stock Management Integration
     * 
     * Seeder ini mengimplementasikan:
     * 1. Membuat detail resep obat untuk setiap pemeriksaan
     * 2. AUTO-DEDUCT stok obat sesuai jumlah yang diresepkan
     * 3. Menghitung ulang biaya periksa (jasa dokter + biaya obat)
     * 4. Validasi stok sebelum mengurangi
     */
    public function run(): void
    {
        $periksas = Periksa::all();
        $obats = Obat::where('stok', '>', 0)->get(); // Hanya obat yang ada stoknya

        $jasaDokter = 150000; // Fixed biaya jasa dokter

        foreach ($periksas as $periksa) {
            $totalBiayaObat = 0;

            // Setiap pemeriksaan dapat 1-3 obat berbeda
            $numObats = rand(1, min(3, $obats->count()));
            $selectedObats = $obats->random($numObats);

            foreach ($selectedObats as $obat) {
                // Jumlah obat yang diresepkan (1-10 unit)
                $jumlahObat = rand(1, min(10, $obat->stok));

                // Skip jika stok tidak cukup
                if ($obat->stok < $jumlahObat) {
                    continue;
                }

                // Buat detail periksa dengan jumlah
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat' => $obat->id,
                    'jumlah' => $jumlahObat,
                ]);

                // ⭐ AUTO-DEDUCT STOK (CAPSTONE FEATURE)
                $obat->stok -= $jumlahObat;
                $obat->save();

                // Hitung total biaya obat
                $totalBiayaObat += ($obat->harga * $jumlahObat);
            }

            // Update biaya periksa = Jasa Dokter + Biaya Obat
            $periksa->biaya_periksa = $jasaDokter + $totalBiayaObat;
            $periksa->save();
        }

        // Log hasil seeding
        echo "\n✅ DetailPeriksa seeded successfully!\n";
        echo "📊 Total pemeriksaan: " . $periksas->count() . "\n";
        echo "💊 Stok obat telah dikurangi sesuai resep\n";
        echo "💰 Biaya periksa telah dihitung ulang\n\n";
    }
}
