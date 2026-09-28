<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'pesanan_id', 'produk_id', 'user_id', 'rating', 'ulasan', 'foto', 'is_visible',
    ];

    protected $casts = [
        'foto'       => 'array',
        'is_visible' => 'boolean',
        'rating'     => 'integer',
    ];

    public function pesanan() { return $this->belongsTo(Pesanan::class); }
    public function produk()  { return $this->belongsTo(Produk::class); }
    public function user()    { return $this->belongsTo(User::class); }

    public function scopeVisible($query) { return $query->where('is_visible', true); }
}
