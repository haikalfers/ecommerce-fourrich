<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';

    protected $fillable = [
        'pesanan_id', 'produk_id', 'nama_produk_snapshot', 'harga_snapshot',
        'harga_pokok_snapshot', 'fee_persen_snapshot', 'jumlah', 'subtotal',
        'fee_admin', 'fee_toko', 'fee_sudah_dicatat',
    ];

    protected $casts = [
        'harga_snapshot'       => 'decimal:2',
        'harga_pokok_snapshot' => 'decimal:2',
        'fee_persen_snapshot'  => 'decimal:2',
        'subtotal'             => 'decimal:2',
        'fee_admin'            => 'decimal:2',
        'fee_toko'             => 'decimal:2',
        'fee_sudah_dicatat'    => 'boolean',
    ];

    public function pesanan() { return $this->belongsTo(Pesanan::class); }
    public function produk()  { return $this->belongsTo(Produk::class); }
}
