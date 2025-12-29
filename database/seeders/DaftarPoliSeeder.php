<?php

namespace Database\Seeders;

use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\User;
use Illuminate\Database\Seeder;

class DaftarPoliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Membuat pendaftaran pasien ke poli:
     * - Setiap pasien mendaftar 2x ke jadwal berbeda
     * - Auto-generate nomor antrian per jadwal
     * - Keluhan realistic sesuai jenis poli
     */
    public function run(): void
    {
        $pasiens = User::where('role', 'pasien')->get();
        $jadwalPeriksas = JadwalPeriksa::where('aktif', 'Y')->get(); // Hanya jadwal aktif

        // Keluhan realistic per jenis poli
        $keluhanList = [
            'Demam tinggi sudah 3 hari, disertai batuk dan pilek',
            'Sakit kepala yang tidak kunjung sembuh sejak kemarin',
            'Nyeri perut setelah makan, mual dan muntah',
            'Flu berat, hidung tersumbat dan badan lemas',
            'Mata merah, berair, dan perih',
            'Sakit gigi berlubang, nyeri sampai tidak bisa tidur',
            'Sesak nafas ringan, terutama saat beraktivitas',
            'Nyeri punggung bawah yang mengganggu aktivitas',
            'Gangguan pencernaan, sering diare',
            'Batuk kering berkepanjangan lebih dari 2 minggu',
            'Alergi kulit, gatal dan kemerahan',
            'Check up rutin dan konsultasi kesehatan',
        ];

        $antrianCounter = [];

        foreach ($pasiens as $index => $pasien) {
            // Setiap pasien daftar 2 kali ke jadwal berbeda
            for ($i = 0; $i < 2; $i++) {
                $jadwal = $jadwalPeriksas->random();

                // Initialize antrian counter per jadwal
                if (!isset($antrianCounter[$jadwal->id])) {
                    $antrianCounter[$jadwal->id] = 1;
                }

                DaftarPoli::create([
                    'id_pasien' => $pasien->id,
                    'id_jadwal' => $jadwal->id,
                    'keluhan' => $keluhanList[($index * 2 + $i) % count($keluhanList)],
                    'no_antrian' => $antrianCounter[$jadwal->id],
                ]);

                $antrianCounter[$jadwal->id]++;
            }
        }
    }
}
