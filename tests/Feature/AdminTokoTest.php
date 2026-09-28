<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTokoTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Toko $tokoPending;
    protected Toko $tokoAktif;

    protected function setUp(): void
    {
        parent::setUp();

        $kelas = Kelas::create([
            'nama_kelas'   => 'XI RPL 1',
            'jurusan'      => 'RPL',
            'tahun_ajaran' => '2025/2026',
        ]);

        $this->admin = User::create([
            'name'      => 'Administrator',
            'email'     => 'admin@smekiv.sch.id',
            'password'  => bcrypt('password'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        $guru = User::create([
            'name'      => 'Guru Pembimbing',
            'email'     => 'guru@smekiv.sch.id',
            'password'  => bcrypt('password'),
            'role'      => 'co_admin',
            'is_active' => true,
        ]);

        $siswa1 = User::create([
            'name'        => 'Siswa 1',
            'email'       => 'siswa1@smekiv.sch.id',
            'password'    => bcrypt('password'),
            'role'        => 'toko',
            'co_admin_id' => $guru->id,
            'kelas_id'    => $kelas->id,
            'is_active'   => true,
        ]);

        $siswa2 = User::create([
            'name'        => 'Siswa 2',
            'email'       => 'siswa2@smekiv.sch.id',
            'password'    => bcrypt('password'),
            'role'        => 'toko',
            'co_admin_id' => $guru->id,
            'kelas_id'    => $kelas->id,
            'is_active'   => true,
        ]);

        $this->tokoPending = Toko::create([
            'user_id'     => $siswa1->id,
            'co_admin_id' => $guru->id,
            'nama_toko'   => 'Toko Pending',
            'deskripsi'   => 'Jual jajanan sekolah',
            'status'      => 'pending',
        ]);

        $this->tokoAktif = Toko::create([
            'user_id'     => $siswa2->id,
            'co_admin_id' => $guru->id,
            'nama_toko'   => 'Toko Aktif',
            'deskripsi'   => 'Jual kerajinan tangan',
            'status'      => 'aktif',
        ]);
    }

    public function test_admin_can_view_toko_index(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.toko.index'))
            ->assertStatus(200)
            ->assertSee('Toko Pending')
            ->assertSee('Toko Aktif');
    }

    public function test_admin_can_view_toko_detail(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.toko.show', $this->tokoPending))
            ->assertStatus(200)
            ->assertSee('Toko Pending')
            ->assertSee('Jual jajanan sekolah')
            ->assertSee('Setujui Toko (ACC)');
    }

    public function test_admin_can_approve_toko(): void
    {
        $this->actingAs($this->admin)
            ->patch(route('admin.toko.approve', $this->tokoPending))
            ->assertRedirect();

        $this->assertDatabaseHas('toko', [
            'id'     => $this->tokoPending->id,
            'status' => 'aktif',
        ]);
    }

    public function test_admin_can_suspend_and_unsuspend_toko(): void
    {
        $this->actingAs($this->admin)
            ->patch(route('admin.toko.suspend', $this->tokoAktif))
            ->assertRedirect();

        $this->assertDatabaseHas('toko', [
            'id'     => $this->tokoAktif->id,
            'status' => 'suspend',
        ]);

        $this->actingAs($this->admin)
            ->patch(route('admin.toko.unsuspend', $this->tokoAktif))
            ->assertRedirect();

        $this->assertDatabaseHas('toko', [
            'id'     => $this->tokoAktif->id,
            'status' => 'aktif',
        ]);
    }
}
