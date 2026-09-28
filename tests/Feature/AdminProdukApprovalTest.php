<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Kelas;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProdukApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Produk $produkPending;

    protected function setUp(): void
    {
        parent::setUp();

        $kelas = Kelas::create([
            'nama_kelas'   => 'XI PPLG 1',
            'jurusan'      => 'PPLG',
            'tahun_ajaran' => '2025/2026',
        ]);

        $this->admin = User::create([
            'name'      => 'Admin Utama',
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

        $siswa = User::create([
            'name'        => 'Siswa Pemilik',
            'email'       => 'siswa@smekiv.sch.id',
            'password'    => bcrypt('password'),
            'role'        => 'toko',
            'co_admin_id' => $guru->id,
            'kelas_id'    => $kelas->id,
            'is_active'   => true,
        ]);

        $toko = Toko::create([
            'user_id'     => $siswa->id,
            'co_admin_id' => $guru->id,
            'nama_toko'   => 'Toko Siswa PPLG',
            'status'      => 'aktif',
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => 'Makanan',
            'slug'          => 'makanan',
            'icon'          => '🍱',
        ]);

        $this->produkPending = Produk::create([
            'toko_id'      => $toko->id,
            'kategori_id'  => $kategori->id,
            'nama_produk'  => 'Nasi Goreng Spesial',
            'deskripsi'    => 'Nasi goreng dengan telur dan ayam suwir',
            'harga_pokok'  => 10000,
            'harga_final'  => 12000,
            'stok'         => 20,
            'status'       => 'pending',
        ]);
    }

    public function test_admin_can_view_produk_index(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.produk.index'))
            ->assertStatus(200)
            ->assertSee('Nasi Goreng Spesial');
    }

    public function test_admin_can_view_produk_detail(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.produk.show', $this->produkPending))
            ->assertStatus(200)
            ->assertSee('Nasi Goreng Spesial')
            ->assertSee('Toko Siswa PPLG')
            ->assertSee('Rp 10.000')
            ->assertSee('Approve (Setujui)');
    }

    public function test_admin_can_approve_produk(): void
    {
        $this->actingAs($this->admin)
            ->patch(route('admin.produk.approve', $this->produkPending))
            ->assertRedirect();

        $this->assertDatabaseHas('produk', [
            'id'          => $this->produkPending->id,
            'status'      => 'approved',
            'approved_by' => $this->admin->id,
        ]);
    }

    public function test_admin_can_reject_produk(): void
    {
        $this->actingAs($this->admin)
            ->patch(route('admin.produk.reject', $this->produkPending), [
                'alasan_reject' => 'Foto tidak jelas dan deskripsi singkat',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('produk', [
            'id'            => $this->produkPending->id,
            'status'        => 'rejected',
            'alasan_reject' => 'Foto tidak jelas dan deskripsi singkat',
        ]);
    }
}
