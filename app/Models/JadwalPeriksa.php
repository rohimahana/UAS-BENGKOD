<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model JadwalPeriksa - Jadwal praktek dokter
 */
class JadwalPeriksa extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_dokter',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'aktif'
    ];

    // Removed boolean cast - let it stay as 'Y'/'T' enum to match database
    // protected $casts = [
    //     'aktif' => 'boolean'
    // ];

    // Relasi
    public function dokter()
    {
        return $this->belongsTo(User::class, 'id_dokter');
    }

    public function daftarPoli()
    {
        return $this->hasMany(DaftarPoli::class, 'id_jadwal');
    }

    public function poli()
    {
        return $this->hasOneThrough(
            Poli::class,
            User::class,
            'id', // Foreign key on users table
            'id', // Foreign key on polis table
            'id_dokter', // Local key on jadwal_periksas table
            'id_poli' // Local key on users table
        );
    }
}
