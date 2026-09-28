<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;

    protected $table = 'toko';

    protected $fillable = [
        'user_id', 'co_admin_id', 'nama_toko', 'deskripsi', 'logo', 'banner',
        'status', 'alasan_suspend', 'wallet_saldo', 'no_rekening', 'nama_bank',
    ];

    protected $casts = [
        'wallet_saldo' => 'decimal:2',
    ];

    // ─── Relationships ──────────────────────────────────────────────
    public function user()       { return $this->belongsTo(User::class); }
    public function coAdmin()    { return $this->belongsTo(User::class, 'co_admin_id'); }
    public function produk()     { return $this->hasMany(Produk::class); }
    public function pesanan()    { return $this->hasMany(Pesanan::class); }
    public function walletTransactions() { return $this->hasMany(WalletTransaction::class); }
    public function awards()     { return $this->hasMany(Award::class); }

    // ─── Scopes ────────────────────────────────────────────────────
    public function scopeAktif($query)    { return $query->where('status', 'aktif'); }
    public function scopePending($query)  { return $query->where('status', 'pending'); }
    public function scopeSuspend($query)  { return $query->where('status', 'suspend'); }

    // ─── Accessors ─────────────────────────────────────────────────
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) return asset('storage/' . $this->logo);
        $initial = urlencode(substr($this->nama_toko, 0, 2));
        return "https://ui-avatars.com/api/?name={$initial}&background=6366f1&color=fff&bold=true&size=128";
    }

    public function getIsAktifAttribute(): bool { return $this->status === 'aktif'; }
    public function getIsPendingAttribute(): bool { return $this->status === 'pending'; }
    public function getIsSuspendAttribute(): bool { return $this->status === 'suspend'; }

    public function isAktif(): bool { return $this->status === 'aktif'; }
    public function isApproved(): bool { return $this->status === 'aktif'; }
    public function isPending(): bool { return $this->status === 'pending'; }
    public function isSuspend(): bool { return $this->status === 'suspend'; }

    // ─── Stats ─────────────────────────────────────────────────────
    public function getTotalOmzetAttribute(): float
    {
        return $this->pesanan()
            ->where('status', 'selesai')
            ->sum('total_harga');
    }
}
