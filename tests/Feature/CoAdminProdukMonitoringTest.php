<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Kelas;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoAdminProdukMonitoringTest extends TestCase
{
    use RefreshDatabase;

    protected User $coAdmin;
    protected Produk $produkBinaan;

    protected function setUp(): void
    {
        parent::setUp();

        $kelas = Kelas::create([
            'nama_kelas'   => 'XI TKJ 1',
            'jurusan'      => 'TKJ',
            'tahun_ajaran' => '2025/2026',
        ]);

        $this->coAdmin = User::create([
            'name'      => 'Guru Pembimbing TKJ',
            'email'     => 'guru.tkj@smekiv.sch.id',
            'password'  => bcrypt('password'),
            'role'      => 'co_admin',
            'is_active' => true,
        ]);

        $siswa = User::create([
            'name'        => 'Siswa TKJ',
            'email'       => 'siswa.tkj@smekiv.sch.id',
            'password'    => bcrypt('password'),
            'role'        => 'toko',
            'co_admin_id' => $this->coAdmin->id,
            'kelas_id'    => $kelas->id,
            'is_active'   => true,
        ]);

        $toko = Toko::create([
            'user_id'     => $siswa->id,
            'co_admin_id' => $this->coAdmin->id,
            'nama_toko'   => 'Toko TKJ Networking',
            'status'      => 'aktif',
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => 'Jasa',
            'slug'          => 'jasa',
            'icon'          => '🛠️',
        ]);

        $this->produkBinaan = Produk::create([
            'toko_id'       => $toko->id,
            'kategori_id'   => $kategori->id,
            'nama_produk'   => 'Crimping Kabel UTP',
            'deskripsi'     => 'Jasa crimping kabel RJ45',
            'harga_pokok'   => 10000,
            'harga_final'   => 15000,
            'stok'          => 50,
            'status'        => 'rejected',
            'alasan_reject' => 'Deskripsi kurang lengkap dan tidak mencantumkan spesifikasi kabel.',
        ]);
    }

    public function test_co_admin_can_view_produk_index(): void
    {
        $this->actingAs($this->coAdmin)
            ->get(route('co-admin.produk.index'))
            ->assertStatus(200)
            ->assertSee('Crimping Kabel UTP')
            ->assertSee('Toko TKJ Networking');
    }

    public function test_co_admin_can_view_produk_detail_and_rejection_reason(): void
    {
        $this->actingAs($this->coAdmin)
            ->get(route('co-admin.produk.show', $this->produkBinaan))
            ->assertStatus(200)
            ->assertSee('Crimping Kabel UTP')
            ->assertSee('Catatan Penolakan dari Admin Sekolah')
            ->assertSee('Deskripsi kurang lengkap dan tidak mencantumkan spesifikasi kabel.');
    }

    public function test_co_admin_cannot_view_unrelated_student_product(): void
    {
        $otherGuru = User::create([
            'name'      => 'Guru Lain',
            'email'     => 'guru.lain@smekiv.sch.id',
            'password'  => bcrypt('password'),
            'role'      => 'co_admin',
            'is_active' => true,
        ]);

        $this->actingAs($otherGuru)
            ->get(route('co-admin.produk.show', $this->produkBinaan))
            ->assertStatus(403);
    }
}
