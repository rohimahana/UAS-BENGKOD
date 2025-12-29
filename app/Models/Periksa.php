<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model Periksa - Data pemeriksaan pasien
 */
class Periksa extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_daftar_poli',
        'tgl_periksa',
        'catatan',
        'biaya_periksa'
    ];

    protected $casts = [
        'tgl_periksa' => 'datetime',
        'biaya_periksa' => 'integer'
    ];

    /**
     * Relasi ke pendaftaran poli
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<DaftarPoli, Periksa>
     */
    public function daftarPoli()
    {
        return $this->belongsTo(DaftarPoli::class, 'id_daftar_poli');
    }

    /**
     * Relasi ke detail periksa (obat yang diresepkan)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<DetailPeriksa>
     */
    public function detailPeriksa()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_periksa');
    }
}
