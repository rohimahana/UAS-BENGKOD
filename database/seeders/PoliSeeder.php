<?php

namespace Database\Seeders;

use App\Models\Poli;
use Illuminate\Database\Seeder;

class PoliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $polis = [
            [
                'nama_poli' => 'Poli Umum',
                'keterangan' => 'Pelayanan kesehatan umum untuk berbagai keluhan penyakit seperti demam, batuk, pilek, dan pemeriksaan kesehatan rutin',
            ],
            [
                'nama_poli' => 'Poli Gigi',
                'keterangan' => 'Pelayanan kesehatan gigi dan mulut meliputi pemeriksaan gigi, penambalan, pencabutan, dan perawatan gigi lainnya',
            ],
            [
                'nama_poli' => 'Poli Anak',
                'keterangan' => 'Pelayanan kesehatan khusus untuk anak-anak (0-18 tahun) termasuk imunisasi, pemeriksaan tumbuh kembang, dan pengobatan penyakit anak',
            ],
            [
                'nama_poli' => 'Poli Mata',
                'keterangan' => 'Pelayanan kesehatan mata meliputi pemeriksaan mata, pengobatan mata merah, katarak, dan pemeriksaan refraksi untuk kacamata',
            ],
            [
                'nama_poli' => 'Poli THT',
                'keterangan' => 'Pelayanan kesehatan telinga, hidung, dan tenggorokan termasuk pengobatan sinusitis, radang tenggorokan, dan gangguan pendengaran',
            ],
        ];

        foreach ($polis as $poli) {
            Poli::create($poli);
        }
    }
}
