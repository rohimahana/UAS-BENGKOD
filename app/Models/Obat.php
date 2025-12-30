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
        'harga',
        'stok',
        'stok_minimum',
    ];

    protected $casts = [
        'harga' => 'integer',
        'stok' => 'integer',
        'stok_minimum' => 'integer',
    ];

    public function detailPeriksa()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_obat');
    }

    public function isOutOfStock(): bool
    {
        return (int) $this->stok <= 0;
    }

    public function isLowStock(): bool
    {
        return (int) $this->stok > 0 && (int) $this->stok <= (int) ($this->stok_minimum ?? 0);
    }

    public function increaseStock(int $amount): bool
    {
        if ($amount < 1) return false;

        $this->increment('stok', $amount);
        $this->refresh();
        return true;
    }

    public function decreaseStock(int $amount): bool
    {
        if ($amount < 1) return false;
        if ((int) $this->stok < $amount) return false;

        $this->decrement('stok', $amount);
        $this->refresh();
        return true;
    }

    public function setStock(int $amount): bool
    {
        if ($amount < 0) return false;

        $this->stok = $amount;
        $ok = $this->save();
        $this->refresh();
        return $ok;
    }
}
