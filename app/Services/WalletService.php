<?php

namespace App\Services;

use App\Models\Toko;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Tambah saldo wallet toko saat pesanan selesai.
     */
    public function kreditWallet(Toko $toko, float $jumlah, int $pesananId, string $keterangan): WalletTransaction
    {
        return DB::transaction(function () use ($toko, $jumlah, $pesananId, $keterangan) {
            $toko->increment('wallet_saldo', $jumlah);

            return WalletTransaction::create([
                'toko_id'    => $toko->id,
                'pesanan_id' => $pesananId,
                'tipe'       => 'kredit',
                'jumlah'     => $jumlah,
                'keterangan' => $keterangan,
            ]);
        });
    }

    /**
     * Tarik/potong saldo wallet toko.
     */
    public function debitWallet(Toko $toko, float $jumlah, string $keterangan): WalletTransaction
    {
        return DB::transaction(function () use ($toko, $jumlah, $keterangan) {
            if ($toko->wallet_saldo < $jumlah) {
                throw new \Exception('Saldo wallet toko tidak mencukupi.');
            }

            $toko->decrement('wallet_saldo', $jumlah);

            return WalletTransaction::create([
                'toko_id'    => $toko->id,
                'pesanan_id' => null,
                'tipe'       => 'debit',
                'jumlah'     => $jumlah,
                'keterangan' => $keterangan,
            ]);
        });
    }
}
