<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'kode_pesanan', 'toko_id', 'pembeli_id', 'nama_guest', 'hp_guest',
        'metode_kirim', 'alamat_kirim', 'metode_bayar', 'status_bayar',
        'bayar_dikonfirmasi_at', 'status', 'alasan_batal', 'selesai_at',
        'subtotal', 'ongkir', 'total_harga', 'catatan',
    ];

    protected $casts = [
        'subtotal'               => 'decimal:2',
        'ongkir'                 => 'decimal:2',
        'total_harga'            => 'decimal:2',
        'bayar_dikonfirmasi_at'  => 'datetime',
        'selesai_at'             => 'datetime',
    ];

    // ─── Relationships ──────────────────────────────────────────────
    public function toko()         { return $this->belongsTo(Toko::class); }
    public function pembeli()      { return $this->belongsTo(User::class, 'pembeli_id'); }
    public function items()        { return $this->hasMany(DetailPesanan::class); }
    public function detailPesanan(){ return $this->hasMany(DetailPesanan::class); } // alias untuk views
    public function reviews()      { return $this->hasMany(Review::class); }
    public function walletTx()     { return $this->hasMany(WalletTransaction::class); }

    // ─── Scopes ────────────────────────────────────────────────────
    public function scopePending($query)       { return $query->where('status', 'pending'); }
    public function scopeDikonfirmasi($query)  { return $query->where('status', 'dikonfirmasi'); }
    public function scopeSelesai($query)       { return $query->where('status', 'selesai'); }
    public function scopeDibatalkan($query)    { return $query->where('status', 'dibatalkan'); }

    // ─── Accessors ─────────────────────────────────────────────────
    public function getNamaPembeliAttribute(): string
    {
        if ($this->pembeli) return $this->pembeli->name;
        return $this->nama_guest ?? 'Pembeli';
    }

    public function getIsGuestAttribute(): bool { return is_null($this->pembeli_id); }
    public function getIsSelesaiAttribute(): bool { return $this->status === 'selesai'; }

    public function isPending(): bool { return $this->status === 'pending'; }
    public function isDikonfirmasi(): bool { return $this->status === 'dikonfirmasi'; }
    public function isSelesai(): bool { return $this->status === 'selesai'; }
    public function isDibatalkan(): bool { return $this->status === 'dibatalkan'; }

    // ─── Static Helper ─────────────────────────────────────────────
    public static function generateKode(): string
    {
        $tanggal = now()->format('Ymd');
        $urutan  = static::whereDate('created_at', today())->count() + 1;
        return 'ORD-' . $tanggal . '-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);
    }
}
