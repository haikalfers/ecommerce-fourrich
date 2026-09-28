<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Kelas;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokoProdukDetailTest extends TestCase
{
    use RefreshDatabase;

    protected User $siswa;
    protected Toko $toko;
    protected Produk $produkRejected;

    protected function setUp(): void
    {
        parent::setUp();

        $kelas = Kelas::create([
            'nama_kelas'   => 'XI AK 2',
            'jurusan'      => 'Akuntansi',
            'tahun_ajaran' => '2025/2026',
        ]);

        $guru = User::create([
            'name'      => 'Guru Pembimbing',
            'email'     => 'guru@smekiv.sch.id',
            'password'  => bcrypt('password'),
            'role'      => 'co_admin',
            'is_active' => true,
        ]);

        $this->siswa = User::create([
            'name'        => 'Siswa Toko',
            'email'       => 'siswa@smekiv.sch.id',
            'password'    => bcrypt('password'),
            'role'        => 'toko',
            'co_admin_id' => $guru->id,
            'kelas_id'    => $kelas->id,
            'is_active'   => true,
        ]);

        $this->toko = Toko::create([
            'user_id'     => $this->siswa->id,
            'co_admin_id' => $guru->id,
            'nama_toko'   => 'Toko Siswa Akuntansi',
            'status'      => 'aktif',
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => 'Kerajinan',
            'slug'          => 'kerajinan',
            'icon'          => '🎨',
        ]);

        $this->produkRejected = Produk::create([
            'toko_id'       => $this->toko->id,
            'kategori_id'   => $kategori->id,
            'nama_produk'   => 'Gantungan Kunci Rajut',
            'deskripsi'     => 'Gantungan kunci handmade dari benang wol',
            'harga_pokok'   => 5000,
            'harga_final'   => 8000,
            'stok'          => 15,
            'status'        => 'rejected',
            'alasan_reject' => 'Foto produk terlalu buram, harap perbarui dengan foto terang.',
        ]);
    }

    public function test_siswa_can_view_own_product_detail_and_rejection_reason(): void
    {
        $this->actingAs($this->siswa)
            ->get(route('toko.produk.show', $this->produkRejected))
            ->assertStatus(200)
            ->assertSee('Gantungan Kunci Rajut')
            ->assertSee('Produk Ditolak oleh Pihak Sekolah')
            ->assertSee('Foto produk terlalu buram, harap perbarui dengan foto terang.');
    }

    public function test_siswa_cannot_view_other_toko_product_detail(): void
    {
        $otherSiswa = User::create([
            'name'      => 'Siswa Lain',
            'email'     => 'lain@smekiv.sch.id',
            'password'  => bcrypt('password'),
            'role'      => 'toko',
            'is_active' => true,
        ]);

        $this->actingAs($otherSiswa);

        // Should return 403 forbidden
        $this->get(route('toko.produk.show', $this->produkRejected))
            ->assertStatus(403);
    }
}
