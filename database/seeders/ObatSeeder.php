<?php

namespace Database\Seeders;

use App\Models\Obat;
use Illuminate\Database\Seeder;

class ObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Seeder ini membuat data obat dengan berbagai kondisi stok:
     * - Stok Normal: Stok di atas minimum (hijau/tersedia)
     * - Stok Menipis: Stok di bawah minimum (kuning/warning)
     * - Stok Habis: Stok = 0 (merah/habis)
     */
    public function run(): void
    {
        $obats = [
            // Stok Normal (Tersedia)
            [
                'nama_obat' => 'Paracetamol',
                'kemasan' => 'Tablet 500mg',
                'harga' => 5000,
                'stok' => 100,        // Stok banyak
                'stok_minimum' => 20, // Minimum threshold
            ],
            [
                'nama_obat' => 'Amoxicillin',
                'kemasan' => 'Kapsul 500mg',
                'harga' => 8000,
                'stok' => 50,         // Stok cukup
                'stok_minimum' => 15,
            ],
            [
                'nama_obat' => 'Ibuprofen',
                'kemasan' => 'Tablet 400mg',
                'harga' => 7000,
                'stok' => 75,
                'stok_minimum' => 20,
            ],

            // Stok Menipis (Warning)
            [
                'nama_obat' => 'Cetirizine',
                'kemasan' => 'Tablet 10mg',
                'harga' => 6000,
                'stok' => 8,          // Stok di bawah minimum!
                'stok_minimum' => 10, // Warning akan muncul
            ],
            [
                'nama_obat' => 'Metformin',
                'kemasan' => 'Tablet 500mg',
                'harga' => 9000,
                'stok' => 15,         // Stok di bawah minimum!
                'stok_minimum' => 20,
            ],

            // Stok Habis (Urgent Restock)
            [
                'nama_obat' => 'Dexamethasone',
                'kemasan' => 'Tablet 0.5mg',
                'harga' => 4000,
                'stok' => 0,          // Stok habis!
                'stok_minimum' => 15,
            ],

            // Stok Normal (Tersedia)
            [
                'nama_obat' => 'Omeprazole',
                'kemasan' => 'Kapsul 20mg',
                'harga' => 12000,
                'stok' => 30,
                'stok_minimum' => 10,
            ],
            [
                'nama_obat' => 'Antasida',
                'kemasan' => 'Tablet Kunyah',
                'harga' => 3000,
                'stok' => 120,
                'stok_minimum' => 25,
            ],
            [
                'nama_obat' => 'Vitamin C',
                'kemasan' => 'Tablet 500mg',
                'harga' => 2500,
                'stok' => 200,        // Stok banyak
                'stok_minimum' => 50,
            ],
            [
                'nama_obat' => 'Salbutamol',
                'kemasan' => 'Inhaler',
                'harga' => 45000,
                'stok' => 25,
                'stok_minimum' => 10,
            ],
        ];

        foreach ($obats as $obat) {
            Obat::create($obat);
        }
    }
}
