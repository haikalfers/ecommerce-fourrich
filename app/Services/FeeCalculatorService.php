<?php

namespace App\Services;

use App\Models\Kategori;

class FeeCalculatorService
{
    /**
     * Hitung pemotongan fee admin (kas sekolah) & penerimaan toko.
     *
     * @param float $totalHarga
     * @param int|null $kategoriId
     * @return array{fee_admin: float, fee_toko: float}
     */
    public function calculateFee(float $totalHarga, ?int $kategoriId = null): array
    {
        $feePersen = 5.0; // default 5%

        if ($kategoriId) {
            $kategori = Kategori::find($kategoriId);
            if ($kategori && isset($kategori->fee_persen)) {
                $feePersen = (float) $kategori->fee_persen;
            }
        }

        $feeAdmin = round(($totalHarga * $feePersen) / 100, 2);
        $feeToko  = round($totalHarga - $feeAdmin, 2);

        return [
            'fee_admin'  => $feeAdmin,
            'fee_toko'   => $feeToko,
            'fee_persen' => $feePersen,
        ];
    }
}
