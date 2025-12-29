<?php

namespace Database\Seeders;

use App\Models\JadwalPeriksa;
use App\Models\User;
use Illuminate\Database\Seeder;

class JadwalPeriksaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Membuat jadwal praktek untuk setiap dokter:
     * - Setiap dokter punya 2 jadwal (1 aktif, 1 tidak aktif)
     * - Jadwal realistis dengan hari kerja dan jam praktek
     */
    public function run(): void
    {
        $dokters = User::where('role', 'dokter')->get();

        // Data jadwal praktek yang realistic
        $jadwalTemplates = [
            // Dokter 1 - Poli Umum
            [
                ['hari' => 'Senin', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'aktif' => 'Y'],
                ['hari' => 'Rabu', 'jam_mulai' => '13:00', 'jam_selesai' => '17:00', 'aktif' => 'T'],
            ],
            // Dokter 2 - Poli Gigi
            [
                ['hari' => 'Selasa', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'aktif' => 'Y'],
                ['hari' => 'Kamis', 'jam_mulai' => '14:00', 'jam_selesai' => '18:00', 'aktif' => 'T'],
            ],
            // Dokter 3 - Poli Anak
            [
                ['hari' => 'Rabu', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'aktif' => 'Y'],
                ['hari' => 'Jumat', 'jam_mulai' => '08:00', 'jam_selesai' => '11:00', 'aktif' => 'T'],
            ],
            // Dokter 4 - Poli Mata
            [
                ['hari' => 'Kamis', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'aktif' => 'Y'],
                ['hari' => 'Sabtu', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00', 'aktif' => 'T'],
            ],
        ];

        foreach ($dokters as $index => $dokter) {
            $jadwals = $jadwalTemplates[$index] ?? $jadwalTemplates[0];

            foreach ($jadwals as $jadwal) {
                JadwalPeriksa::create([
                    'id_dokter' => $dokter->id,
                    'hari' => $jadwal['hari'],
                    'jam_mulai' => $jadwal['jam_mulai'],
                    'jam_selesai' => $jadwal['jam_selesai'],
                    'aktif' => $jadwal['aktif'],
                ]);
            }
        }
    }
}
