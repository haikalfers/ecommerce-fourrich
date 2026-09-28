<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $table = 'wallet_transactions';

    protected $fillable = [
        'toko_id', 'pesanan_id', 'tipe', 'jumlah',
        'saldo_sebelum', 'saldo_sesudah', 'keterangan', 'kategori', 'referensi',
    ];

    protected $casts = [
        'jumlah'         => 'decimal:2',
        'saldo_sebelum'  => 'decimal:2',
        'saldo_sesudah'  => 'decimal:2',
    ];

    public function toko()    { return $this->belongsTo(Toko::class); }
    public function pesanan() { return $this->belongsTo(Pesanan::class); }

    public function scopeKredit($query) { return $query->where('tipe', 'kredit'); }
    public function scopeDebit($query)  { return $query->where('tipe', 'debit'); }
}
