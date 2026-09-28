<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAjaran = '2025/2026';

        // 5 Jurusan resmi SMKN 4 Surabaya
        $jurusan = [
            ['kode' => 'AK',  'nama' => 'Akuntansi dan Keuangan Lembaga'],
            ['kode' => 'MP',  'nama' => 'Manajemen Perkantoran'],
            ['kode' => 'BD',  'nama' => 'Bisnis Digital'],
            ['kode' => 'DKV', 'nama' => 'Desain Komunikasi Visual'],
            ['kode' => 'UPW', 'nama' => 'Usaha Perjalanan Wisata'],
        ];

        $kelas = [];

        foreach ($jurusan as $j) {
            // Setiap jurusan punya kelas X, XI, XII (masing-masing 2 rombel)
            foreach (['X', 'XI', 'XII'] as $tingkat) {
                foreach ([1, 2] as $rombel) {
                    $kelas[] = [
                        'nama_kelas'   => "{$tingkat} {$j['kode']} {$rombel}",
                        'jurusan'      => $j['nama'],
                        'tahun_ajaran' => $tahunAjaran,
                    ];
                }
            }
        }

        // Kelas khusus untuk Guru / Staff sekolah
        $kelas[] = [
            'nama_kelas'   => 'Guru / Staff',
            'jurusan'      => 'Tenaga Pendidik',
            'tahun_ajaran' => $tahunAjaran,
        ];

        // Truncate & re-seed (hapus data lama yang salah)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kelas')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        foreach ($kelas as $k) {
            DB::table('kelas')->insert(array_merge($k, [
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command?->info('✅ KelasSeeder: ' . count($kelas) . ' kelas berhasil di-seed.');
    }
}
