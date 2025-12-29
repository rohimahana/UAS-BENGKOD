<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Membuat user untuk semua role:
     * - 1 Admin
     * - 4 Dokter (untuk 4 poli berbeda)
     * - 5 Pasien (dengan No. RM auto-generate)
     */
    public function run(): void
    {
        // 1. ADMIN
        User::create([
            'nama' => 'Administrator',
            'email' => 'admin@poliklinik.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'alamat' => 'Jl. Pleburan No. 123, Semarang',
            'no_ktp' => '3374010101900001',
            'no_hp' => '081234567890'
        ]);

        // 2. DOKTER (4 dokter untuk 4 poli berbeda)
        $dokters = [
            [
                'nama' => 'Dr. Ahmad Wijaya, Sp.PD',
                'email' => 'dokter@poliklinik.com',
                'password' => Hash::make('password'),
                'role' => 'dokter',
                'alamat' => 'Jl. Pandanaran No. 45, Semarang',
                'no_ktp' => '3374010202850001',
                'no_hp' => '081234567891',
                'id_poli' => 1 // Poli Umum
            ],
            [
                'nama' => 'Dr. Siti Nurhaliza, Sp.KG',
                'email' => 'dokter.gigi@poliklinik.com',
                'password' => Hash::make('password'),
                'role' => 'dokter',
                'alamat' => 'Jl. Pemuda No. 78, Semarang',
                'no_ktp' => '3374010303880002',
                'no_hp' => '081234567892',
                'id_poli' => 2 // Poli Gigi
            ],
            [
                'nama' => 'Dr. Budi Santoso, Sp.A',
                'email' => 'dokter.anak@poliklinik.com',
                'password' => Hash::make('password'),
                'role' => 'dokter',
                'alamat' => 'Jl. Gajah Mada No. 90, Semarang',
                'no_ktp' => '3374010404870003',
                'no_hp' => '081234567893',
                'id_poli' => 3 // Poli Anak
            ],
            [
                'nama' => 'Dr. Rina Kartika, Sp.M',
                'email' => 'dokter.mata@poliklinik.com',
                'password' => Hash::make('password'),
                'role' => 'dokter',
                'alamat' => 'Jl. Pahlawan No. 56, Semarang',
                'no_ktp' => '3374010505860004',
                'no_hp' => '081234567894',
                'id_poli' => 4 // Poli Mata
            ],
        ];

        foreach ($dokters as $dokter) {
            User::create($dokter);
        }

        // 3. PASIEN (5 pasien dengan No. RM realistic)
        $pasiens = [
            [
                'nama' => 'Andi Prasetyo',
                'email' => 'andi.prasetyo@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'pasien',
                'alamat' => 'Jl. Sriwijaya No. 12, Semarang',
                'no_ktp' => '3374020101950001',
                'no_hp' => '081234567895',
                'no_rm' => date('Ym') . '-001'
            ],
            [
                'nama' => 'Dewi Lestari',
                'email' => 'dewi.lestari@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'pasien',
                'alamat' => 'Jl. Diponegoro No. 34, Semarang',
                'no_ktp' => '3374020202920002',
                'no_hp' => '081234567896',
                'no_rm' => date('Ym') . '-002'
            ],
            [
                'nama' => 'Rudi Hartono',
                'email' => 'rudi.hartono@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'pasien',
                'alamat' => 'Jl. Sudirman No. 56, Semarang',
                'no_ktp' => '3374020303880003',
                'no_hp' => '081234567897',
                'no_rm' => date('Ym') . '-003'
            ],
            [
                'nama' => 'Maya Sari',
                'email' => 'maya.sari@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'pasien',
                'alamat' => 'Jl. Imam Bonjol No. 78, Semarang',
                'no_ktp' => '3374020404900004',
                'no_hp' => '081234567898',
                'no_rm' => date('Ym') . '-004'
            ],
            [
                'nama' => 'Fajar Ramadhan',
                'email' => 'fajar.ramadhan@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'pasien',
                'alamat' => 'Jl. Ahmad Yani No. 90, Semarang',
                'no_ktp' => '3374020505930005',
                'no_hp' => '081234567899',
                'no_rm' => date('Ym') . '-005'
            ],
        ];

        foreach ($pasiens as $pasien) {
            User::create($pasien);
        }
    }
}
