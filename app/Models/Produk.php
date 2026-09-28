<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected static function booted(): void
    {
        static::creating(function (Produk $produk) {
            if (empty($produk->slug)) {
                $produk->slug = Str::slug($produk->nama_produk) . '-' . Str::random(6);
            }
        });

        static::updating(function (Produk $produk) {
            if ($produk->isDirty('nama_produk') && empty($produk->slug)) {
                $produk->slug = Str::slug($produk->nama_produk) . '-' . Str::random(6);
            }
        });
    }

    protected $fillable = [
        'toko_id', 'kategori_id', 'nama_produk', 'slug', 'deskripsi', 'foto',
        'harga_pokok', 'harga_final', 'harga_reseller', 'stok', 'satuan',
        'is_reseller_product', 'status', 'alasan_reject', 'approved_by',
        'approved_at', 'is_active', 'total_terjual', 'rating_avg', 'rating_count',
    ];

    protected $casts = [
        'foto'               => 'array',
        'harga_pokok'        => 'decimal:2',
        'harga_final'        => 'decimal:2',
        'harga_reseller'     => 'decimal:2',
        'is_reseller_product'=> 'boolean',
        'is_active'          => 'boolean',
        'approved_at'        => 'datetime',
        'rating_avg'         => 'decimal:2',
    ];

    // ─── Relationships ──────────────────────────────────────────────
    public function toko()       { return $this->belongsTo(Toko::class); }
    public function kategori()   { return $this->belongsTo(Kategori::class); }
    public function approvedBy() { return $this->belongsTo(User::class, 'approved_by'); }
    public function reviews()    { return $this->hasMany(Review::class); }
    public function detailPesanan() { return $this->hasMany(DetailPesanan::class); }

    // ─── Scopes ────────────────────────────────────────────────────
    public function scopeApproved($query)  { return $query->where('status', 'approved'); }
    public function scopePending($query)   { return $query->where('status', 'pending'); }
    public function scopeRejected($query)  { return $query->where('status', 'rejected'); }
    public function scopePublished($query) {
        return $query->where('status', 'approved')->where('is_active', true)->where('stok', '>', 0);
    }

    // ─── Accessors ─────────────────────────────────────────────────
    public function getFotoUtamaAttribute(): string
    {
        $fotos = $this->foto ?? [];
        if (!empty($fotos)) return asset('storage/' . $fotos[0]);
        return asset('images/placeholder-produk.png');
    }

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_final, 0, ',', '.');
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'approved' && $this->is_active && $this->stok > 0;
    }

    public function isPending(): bool { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }
}
