<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model DetailPeriksa - Detail obat yang diresepkan
 */
class DetailPeriksa extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_periksa',
        'id_obat',
        'jumlah', // Quantity of medicine prescribed
    ];

    // RELATIONSHIPS
    public function periksa()
    {
        return $this->belongsTo(Periksa::class, 'id_periksa');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}
