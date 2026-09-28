<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            [
                'nama_kategori' => 'Makanan',
                'slug'          => 'makanan',
                'deskripsi'     => 'Produk makanan buatan siswa seperti snack, kue, dan jajanan',
                'icon'          => '🍔',
                'warna'         => '#f97316',
                'fee_persen'    => 5.00,
                'urutan'        => 1,
            ],
            [
                'nama_kategori' => 'Minuman',
                'slug'          => 'minuman',
                'deskripsi'     => 'Produk minuman seperti es, jus, teh, dan kopi buatan siswa',
                'icon'          => '🥤',
                'warna'         => '#06b6d4',
                'fee_persen'    => 5.00,
                'urutan'        => 2,
            ],
            [
                'nama_kategori' => 'Fashion & Aksesoris',
                'slug'          => 'fashion-aksesoris',
                'deskripsi'     => 'Pakaian, aksesoris, dan produk fashion buatan siswa',
                'icon'          => '👗',
                'warna'         => '#ec4899',
                'fee_persen'    => 7.00,
                'urutan'        => 3,
            ],
            [
                'nama_kategori' => 'Kerajinan Tangan',
                'slug'          => 'kerajinan-tangan',
                'deskripsi'     => 'Produk kerajinan, handmade, dan karya seni siswa',
                'icon'          => '🎨',
                'warna'         => '#8b5cf6',
                'fee_persen'    => 6.00,
                'urutan'        => 4,
            ],
            [
                'nama_kategori' => 'Elektronika',
                'slug'          => 'elektronika',
                'deskripsi'     => 'Produk elektronika, gadget, dan aksesoris elektronik',
                'icon'          => '⚡',
                'warna'         => '#f59e0b',
                'fee_persen'    => 5.00,
                'urutan'        => 5,
            ],
            [
                'nama_kategori' => 'Alat Tulis & Stationery',
                'slug'          => 'alat-tulis-stationery',
                'deskripsi'     => 'Alat tulis, buku, dan perlengkapan belajar',
                'icon'          => '✏️',
                'warna'         => '#10b981',
                'fee_persen'    => 5.00,
                'urutan'        => 6,
            ],
            [
                'nama_kategori' => 'Jasa & Layanan',
                'slug'          => 'jasa-layanan',
                'deskripsi'     => 'Layanan seperti desain grafis, fotografi, les, dan jasa lainnya',
                'icon'          => '🛠️',
                'warna'         => '#6366f1',
                'fee_persen'    => 3.00,
                'urutan'        => 7,
            ],
            [
                'nama_kategori' => 'Digital & Desain',
                'slug'          => 'digital-desain',
                'deskripsi'     => 'Produk digital seperti template, preset, dan karya desain',
                'icon'          => '💻',
                'warna'         => '#0ea5e9',
                'fee_persen'    => 4.00,
                'urutan'        => 8,
            ],
            [
                'nama_kategori' => 'Lainnya',
                'slug'          => 'lainnya',
                'deskripsi'     => 'Produk lain yang tidak masuk kategori di atas',
                'icon'          => '📦',
                'warna'         => '#94a3b8',
                'fee_persen'    => 5.00,
                'urutan'        => 99,
            ],
        ];

        foreach ($kategori as $k) {
            DB::table('kategori')->insertOrIgnore(array_merge($k, [
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command?->info('✅ KategoriSeeder: ' . count($kategori) . ' kategori berhasil di-seed.');
    }
}
