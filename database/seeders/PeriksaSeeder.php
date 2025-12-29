<?php

namespace Database\Seeders;

use App\Models\DaftarPoli;
use App\Models\Periksa;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PeriksaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Membuat data pemeriksaan:
     * - 70% dari pendaftaran sudah diperiksa
     * - Biaya periksa = Rp 150.000 (jasa dokter) + biaya obat
     * - Tanggal pemeriksaan realistic (1-7 hari yang lalu)
     */
    public function run(): void
    {
        $daftarPolis = DaftarPoli::all();

        // Catatan pemeriksaan yang realistic
        $catatanList = [
            'Pasien mengalami demam ringan 38°C, batuk produktif. Diagnosis: ISPA. Diberikan obat penurun panas dan antibiotik. Istirahat 3 hari.',
            'Tekanan darah 120/80 mmHg, suhu 36.5°C. Kondisi umum baik. Diberikan vitamin dan obat maag. Kontrol 1 minggu lagi.',
            'Infeksi ringan pada tenggorokan, amandel membesar. Diagnosis: Tonsilitis. Diberikan antibiotik 3x1 selama 5 hari.',
            'Pemeriksaan rutin check-up. Hasil lab normal. Diberikan multivitamin untuk meningkatkan daya tahan tubuh.',
            'Alergi rhinitis musiman. Bersin-bersin, hidung gatal. Diberikan antihistamin dan dekongestan.',
            'Gangguan pencernaan, mual muntah. Diagnosis: Gastritis. Diberikan obat maag dan antasida. Diet lunak 3 hari.',
            'Mata merah, berair, dan perih. Diagnosis: Konjungtivitis. Diberikan obat tetes mata dan salep mata.',
            'Sakit kepala tension type. Tidak ada kelainan neurologis. Diberikan analgesik dan muscle relaxant.',
            'Kontrol rutin diabetes melitus. Gula darah terkontrol. Lanjutkan obat dan diet rendah gula.',
            'Batuk kering berkepanjangan. Diagnosis: Bronkitis. Diberikan obat batuk dan bronkodilator.',
            'Hipertensi grade 1. TD 150/95 mmHg. Diberikan obat antihipertensi dan edukasi pola hidup sehat.',
            'Sakit gigi berlubang pada molar kanan atas. Akan dilakukan penambalan pada kunjungan berikutnya.',
        ];

        // Proses 70% dari total pendaftaran (sisanya belum diperiksa)
        $processedDaftarPolis = $daftarPolis->take(ceil($daftarPolis->count() * 0.7));

        foreach ($processedDaftarPolis as $index => $daftarPoli) {
            // Biaya periksa minimal = Jasa dokter Rp 150.000
            // Nanti akan ditambah biaya obat di DetailPeriksaSeeder
            $jasaDokter = 150000;
            $biayaObatEstimasi = rand(0, 100000); // Estimasi, akan dihitung ulang saat ada detail obat

            Periksa::create([
                'id_daftar_poli' => $daftarPoli->id,
                'tgl_periksa' => Carbon::now()->subDays(rand(1, 7))->setHour(rand(8, 16))->setMinute(rand(0, 59)),
                'catatan' => $catatanList[$index % count($catatanList)],
                'biaya_periksa' => $jasaDokter + $biayaObatEstimasi, // Akan diupdate di DetailPeriksaSeeder
            ]);
        }
    }
}
