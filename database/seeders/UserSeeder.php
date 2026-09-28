<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin (Full Control)
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

        // 2. Co-Admin (Guru Pembimbing KIK)
        User::updateOrCreate(
            ['email' => 'sari@smekiv.sch.id'],
            [
                'name'              => 'Bu Sari, S.Pd (Guru KIK)',
                'password'          => Hash::make('guru123'),
                'role'              => 'co_admin',
                'no_hp'             => '08100000002',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'budi@smekiv.sch.id'],
            [
                'name'              => 'Pak Budi, M.T (Guru KIK)',
                'password'          => Hash::make('guru123'),
                'role'              => 'co_admin',
                'no_hp'             => '08100000003',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command?->info('✅ UserSeeder: Berhasil menabur Akun Admin & Co-Admin Guru!');
        $this->command?->line('   🔑 Admin Login: admin@smekiv.sch.id / admin123');
        $this->command?->line('   🔑 Co-Admin (Guru): sari@smekiv.sch.id / guru123');
    }
}
