<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            KelasSeeder::class,     // harus duluan karena users FK ke kelas
            KategoriSeeder::class,  // sebelum produk
            UserSeeder::class,      // admin, co-admin, toko, pembeli & sampel produk
        ]);
    }
}
