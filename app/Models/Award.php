<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    protected $table = 'awards';

    protected $fillable = [
        'toko_id', 'jenis_award', 'bulan', 'tahun', 'nilai_pencapaian', 'keterangan',
    ];

    protected $casts = [
        'nilai_pencapaian' => 'decimal:2',
        'bulan'            => 'integer',
        'tahun'            => 'integer',
    ];

    // Label & emoji untuk setiap jenis award
    public static array $labels = [
        'toko_terlaris'  => ['emoji' => '🥇', 'label' => 'Toko Terlaris'],
        'best_newcomer'  => ['emoji' => '🚀', 'label' => 'Best Newcomer'],
        'most_reviewed'  => ['emoji' => '⭐', 'label' => 'Most Reviewed'],
        'most_consistent'=> ['emoji' => '📦', 'label' => 'Most Consistent'],
        'top_per_kelas'  => ['emoji' => '🏆', 'label' => 'Top Per Kelas'],
    ];

    public function toko() { return $this->belongsTo(Toko::class); }

    public function getLabelAttribute(): string
    {
        return self::$labels[$this->jenis_award]['label'] ?? $this->jenis_award;
    }

    public function getEmojiAttribute(): string
    {
        return self::$labels[$this->jenis_award]['emoji'] ?? '🏅';
    }

    public function scopeBulanIni($query)
    {
        return $query->where('bulan', now()->month)->where('tahun', now()->year);
    }
}
