<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'tipe_pembeli',
        'co_admin_id', 'kelas_id', 'no_hp', 'avatar', 'is_active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ─── Role Helper Methods ────────────────────────────────────────
    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isCoAdmin(): bool  { return $this->role === 'co_admin'; }
    public function isToko(): bool     { return $this->role === 'toko'; }
    public function isPembeli(): bool  { return $this->role === 'pembeli'; }

    public function getDashboardRoute(): string
    {
        return match($this->role) {
            'admin'    => 'admin.dashboard',
            'co_admin' => 'co-admin.dashboard',
            'toko'     => 'toko.dashboard',
            default    => 'pembeli.dashboard',
        };
    }

    // ─── Relationships ──────────────────────────────────────────────

    /** Toko milik user ini (role: toko) */
    public function toko()
    {
        return $this->hasOne(Toko::class);
    }

    /** Siswa binaan co-admin ini */
    public function siswaBinaan()
    {
        return $this->hasMany(User::class, 'co_admin_id');
    }

    /** Guru pembimbing user ini */
    public function coAdmin()
    {
        return $this->belongsTo(User::class, 'co_admin_id');
    }

    public function guruPembimbing()
    {
        return $this->coAdmin();
    }

    /** Kelas user ini */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /** Pesanan yang dibuat user ini */
    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'pembeli_id');
    }

    /** Reviews yang dibuat user ini */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ─── Scopes ────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    // ─── Accessors ─────────────────────────────────────────────────
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $initial = urlencode(substr($this->name, 0, 2));
        return "https://ui-avatars.com/api/?name={$initial}&background=6366f1&color=fff&bold=true";
    }
}
