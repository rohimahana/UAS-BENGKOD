<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User - Mengelola semua pengguna sistem
 * 
 * Tipe pengguna:
 * - Admin: Mengelola sistem poliklinik
 * - Dokter: Memiliki jadwal praktik dan melakukan pemeriksaan
 * - Pasien: Mendaftar poli dan memiliki nomor rekam medis
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'alamat',
        'id_poli',
        'no_ktp',
        'no_hp',
        'no_rm',
        'role',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ===============================
    // RELATIONSHIPS
    // ===============================

    /**
     * Get the policlinic that the doctor belongs to
     * 
     * Only applies to users with 'dokter' role.
     * Returns null for admin and pasien users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Poli, User>
     */
    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    /**
     * Get all schedule entries for this doctor
     * 
     * Only applies to users with 'dokter' role.
     * Returns empty collection for admin and pasien users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<JadwalPeriksa>
     */
    public function jadwalPeriksa()
    {
        return $this->hasMany(JadwalPeriksa::class, 'id_dokter');
    }

    /**
     * Get all clinic registrations for this patient
     * 
     * Only applies to users with 'pasien' role.
     * Returns empty collection for admin and dokter users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<DaftarPoli>
     */
    public function daftarPoli()
    {
        return $this->hasMany(DaftarPoli::class, 'id_pasien');
    }

    /**
     * Get all examinations for this patient (through registrations)
     * 
     * Only applies to users with 'pasien' role.
     * Returns empty collection for admin and dokter users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough<Periksa>
     */
    public function periksaPasien()
    {
        return $this->hasManyThrough(Periksa::class, DaftarPoli::class, 'id_pasien', 'id_daftar_poli');
    }

    // ===============================
    // ACCESSORS & MUTATORS
    // ===============================

    // Helper methods sederhana
    public function getDisplayName()
    {
        return $this->nama . ' (' . ucfirst($this->role) . ')';
    }

    public function getRoleLabel()
    {
        return match ($this->role) {
            'admin' => 'Administrator',
            'dokter' => 'Dokter',
            'pasien' => 'Pasien',
            default => 'Unknown'
        };
    }

    public function getFormattedPhone()
    {
        return preg_replace('/(\d{4})(\d{4})(\d+)/', '$1-$2-$3', $this->no_hp);
    }

    // Setter sederhana untuk nama
    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = ucwords(strtolower(trim($value)));
    }

    // ===============================
    // HELPER METHODS
    // ===============================

    /**
     * Check if user has admin role
     * 
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user has dokter role
     * 
     * @return bool
     */
    public function isDokter(): bool
    {
        return $this->role === 'dokter';
    }

    /**
     * Check if user has pasien role
     * 
     * @return bool
     */
    public function isPasien(): bool
    {
        return $this->role === 'pasien';
    }

    /**
     * Check if user has a specific role
     * 
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user can access admin features
     * 
     * @return bool
     */
    public function canAccessAdmin(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Get the dashboard route for this user based on role
     * 
     * @return string
     */
    public function getDashboardRoute(): string
    {
        return match ($this->role) {
            'admin' => 'admin.dashboard',
            'dokter' => 'dokter.dashboard',
            'pasien' => 'pasien.dashboard',
            default => 'home'
        };
    }

    // ===============================
    // QUERY SCOPES
    // ===============================

    /**
     * Scope to get only admin users
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin')->orderBy('nama');
    }

    /**
     * Scope to get only dokter users
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDokters($query)
    {
        return $query->where('role', 'dokter')->orderBy('nama');
    }

    /**
     * Scope to get only pasien users
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePasiens($query)
    {
        return $query->where('role', 'pasien')->orderBy('nama');
    }

    /**
     * Scope to get users by specific role
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role)->orderBy('nama');
    }

    /**
     * Scope to get active users (email verified)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope to search users by name or email
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('no_ktp', 'like', "%{$search}%")
                ->orWhere('no_rm', 'like', "%{$search}%");
        });
    }
}
