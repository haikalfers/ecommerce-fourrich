<?php

namespace App\Services;

use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PesananService
{
    public function __construct(
        protected FeeCalculatorService $feeCalculator,
        protected WalletService $walletService
    ) {}

    /**
     * Buat pesanan baru (support login user & guest).
     */
    public function createOrder(array $data, array $cartItems): Pesanan
    {
        return DB::transaction(function () use ($data, $cartItems) {
            $kodePesanan = 'ORD-' . strtoupper(Str::random(8));

            $totalHarga = 0;
            $tokoId = null;

            foreach ($cartItems as $item) {
                $produk = Produk::findOrFail($item['produk_id']);
                $subtotal = $produk->harga_final * $item['jumlah'];
                $totalHarga += $subtotal;
                $tokoId = $produk->toko_id;
            }

            $pesanan = Pesanan::create([
                'kode_pesanan'  => $kodePesanan,
                'pembeli_id'    => auth()->check() ? auth()->id() : null,
                'toko_id'       => $tokoId,
                'nama_guest'    => $data['nama_guest'] ?? null,
                'hp_guest'      => $data['hp_guest'] ?? null,
                'metode_bayar'  => $data['metode_bayar'] ?? 'cash',
                'metode_kirim'  => $data['metode_kirim'] ?? 'cod',
                'status'        => 'pending',
                'subtotal'      => $totalHarga,
                'ongkir'        => 0,
                'total_harga'   => $totalHarga,
                'alamat_kirim'  => $data['alamat_kirim'] ?? null,
                'catatan'       => $data['catatan'] ?? null,
            ]);

            foreach ($cartItems as $item) {
                $produk = Produk::findOrFail($item['produk_id']);
                $subtotal = $produk->harga_final * $item['jumlah'];

                $fee = $this->feeCalculator->calculateFee($subtotal, $produk->kategori_id);

                DetailPesanan::create([
                    'pesanan_id'            => $pesanan->id,
                    'produk_id'             => $produk->id,
                    'nama_produk_snapshot'  => $produk->nama_produk,
                    'harga_snapshot'        => $produk->harga_final,
                    'harga_pokok_snapshot'  => $produk->harga_pokok,
                    'fee_persen_snapshot'   => $fee['fee_persen'],
                    'jumlah'                => $item['jumlah'],
                    'subtotal'              => $subtotal,
                    'fee_admin'             => $fee['fee_admin'],
                    'fee_toko'              => $fee['fee_toko'],
                    'fee_sudah_dicatat'     => false,
                ]);

                // Kurangi stok produk
                $produk->decrement('stok', $item['jumlah']);
            }

            return $pesanan;
        });
    }

    /**
     * Konfirmasi pesanan selesai & cairkan dana ke wallet toko.
     */
    public function selesaikanPesanan(Pesanan $pesanan): Pesanan
    {
        return DB::transaction(function () use ($pesanan) {
            if ($pesanan->status === 'selesai') {
                return $pesanan;
            }

            $pesanan->update([
                'status'     => 'selesai',
                'selesai_at' => now(),
            ]);

            // Hitung total penerimaan toko (setelah potong fee admin)
            $totalFeeToko = $pesanan->detailPesanan->sum('fee_toko');

            // Tandai fee sudah dicatat
            DetailPesanan::where('pesanan_id', $pesanan->id)->update(['fee_sudah_dicatat' => true]);

            // Kredit ke wallet toko
            $this->walletService->kreditWallet(
                $pesanan->toko,
                $totalFeeToko,
                $pesanan->id,
                "Hasil penjualan pesanan #{$pesanan->kode_pesanan}"
            );

            return $pesanan;
        });
    }
}
