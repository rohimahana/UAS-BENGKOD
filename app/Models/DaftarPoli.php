<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model DaftarPoli - Pendaftaran pasien ke poli
 */
class DaftarPoli extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pasien',
        'id_jadwal',
        'keluhan',
        'no_antrian'
    ];

    /**
     * Relasi ke pasien yang mendaftar
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, DaftarPoli>
     */
    public function pasien()
    {
        return $this->belongsTo(User::class, 'id_pasien');
    }

    /**
     * Relasi ke jadwal periksa yang dipilih
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<JadwalPeriksa, DaftarPoli>
     */
    public function jadwalPeriksa()
    {
        return $this->belongsTo(JadwalPeriksa::class, 'id_jadwal');
    }

    /**
     * Relasi ke hasil pemeriksaan
     * Satu pendaftaran hanya memiliki satu pemeriksaan
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Periksa>
     */
    public function periksa()
    {
        return $this->hasOne(Periksa::class, 'id_daftar_poli');
    }
}
