<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoAdminRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected User $coAdmin;
    protected Toko $toko;

    protected function setUp(): void
    {
        parent::setUp();

        $kelas = Kelas::create([
            'nama_kelas'   => 'XI AK 1',
            'jurusan'      => 'Akuntansi',
            'tahun_ajaran' => '2025/2026',
        ]);

        $this->coAdmin = User::create([
            'name'     => 'Guru Pembimbing',
            'email'    => 'guru@smekiv.sch.id',
            'password' => bcrypt('password'),
            'role'     => 'co_admin',
            'is_active'=> true,
        ]);

        $siswa = User::create([
            'name'        => 'Siswa Binaan',
            'email'       => 'siswa@smekiv.sch.id',
            'password'    => bcrypt('password'),
            'role'        => 'toko',
            'co_admin_id' => $this->coAdmin->id,
            'kelas_id'    => $kelas->id,
        ]);

        $this->toko = Toko::create([
            'user_id'     => $siswa->id,
            'co_admin_id' => $this->coAdmin->id,
            'nama_toko'   => 'Toko Siswa',
            'status'      => 'aktif',
        ]);
    }

    public function test_co_admin_can_access_all_sidebar_pages(): void
    {
        $this->actingAs($this->coAdmin);

        // 1. Dashboard
        $this->get(route('co-admin.dashboard'))
            ->assertStatus(200);

        // 2. Toko Binaan Index
        $this->get(route('co-admin.toko.index'))
            ->assertStatus(200);

        // 3. Detail Toko Binaan
        $this->get(route('co-admin.toko.show', $this->toko))
            ->assertStatus(200);

        // 4. Laporan Kelas Binaan
        $this->get(route('co-admin.laporan.index'))
            ->assertStatus(200);

        // 5. Award Siswa Binaan
        $this->get(route('co-admin.awards.index'))
            ->assertStatus(200);
    }
}
