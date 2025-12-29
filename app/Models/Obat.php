<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model Obat - Data obat-obatan
 */
class Obat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_obat',
        'kemasan',
        'harga'
    ];

    protected $casts = [
        'harga' => 'integer'
    ];

    public function detailPeriksa()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_obat');
    }
}
