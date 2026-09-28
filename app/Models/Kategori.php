<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'nama_kategori', 'slug', 'deskripsi', 'icon', 'warna',
        'fee_persen', 'is_active', 'urutan',
    ];

    protected $casts = [
        'fee_persen' => 'decimal:2',
        'is_active'  => 'boolean',
    ];

    public function produk() { return $this->hasMany(Produk::class); }

    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopeOrdered($query) { return $query->orderBy('urutan'); }
}
