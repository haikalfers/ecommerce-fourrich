<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = ['nama_kelas', 'jurusan', 'tahun_ajaran', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    // ─── Relationships ──────────────────────────────────────────────
    public function siswa()
    {
        return $this->hasMany(User::class);
    }

    // ─── Scopes ────────────────────────────────────────────────────
    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopeKelas11($query) { return $query->where('nama_kelas', 'like', 'XI%'); }
}
