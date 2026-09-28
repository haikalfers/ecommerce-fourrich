<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@smekiv.sch.id'],
            [
                'name'              => 'Administrator Utama',
                'password'          => Hash::make('admin123'),
                'role'              => 'admin',
                'no_hp'             => '08100000001',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ AdminSeeder: Berhasil menabur Akun Super Admin!');
        $this->command->line('   🔑 Admin Login: admin@smekiv.sch.id / admin123');
    }
}
