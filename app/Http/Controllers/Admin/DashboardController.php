<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Models\DetailPesanan;
use App\Models\Kategori;
use App\Models\Kelas;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $startThisMonth = now()->startOfMonth();
        $startLastMonth = now()->subMonth()->startOfMonth();
        $endLastMonth   = now()->subMonth()->endOfMonth();

        $tokoAktifCount = Toko::aktif()->count();
        $tokoBaruBulanIni = Toko::where('created_at', '>=', $startThisMonth)->count();

        $produkPendingCount = Produk::pending()->count();

        $omzetBulanIni = Pesanan::selesai()
            ->where('created_at', '>=', $startThisMonth)
            ->sum('total_harga');

        $omzetBulanLalu = Pesanan::selesai()
            ->whereBetween('created_at', [$startLastMonth, $endLastMonth])
            ->sum('total_harga');

        $growthOmzet = $omzetBulanLalu > 0
            ? round((($omzetBulanIni - $omzetBulanLalu) / $omzetBulanLalu) * 100, 1)
            : 0;

        $feeSekolahBulanIni = DetailPesanan::whereHas('pesanan', function ($q) use ($startThisMonth) {
            $q->selesai()->where('created_at', '>=', $startThisMonth);
        })->sum('fee_admin');

        $stats = [
            'total_toko_aktif'    => $tokoAktifCount,
            'toko_baru_bulan_ini' => $tokoBaruBulanIni,
            'produk_pending'      => $produkPendingCount,
            'omzet_bulan_ini'     => $omzetBulanIni,
            'growth_omzet'        => $growthOmzet,
            'fee_sekolah'         => $feeSekolahBulanIni,
        ];

        // Omzet per kelas (top 5 kelas)
        $omzetPerKelas = DB::table('pesanan')
            ->join('toko', 'pesanan.toko_id', '=', 'toko.id')
            ->join('users', 'toko.user_id', '=', 'users.id')
            ->join('kelas', 'users.kelas_id', '=', 'kelas.id')
            ->where('pesanan.status', 'selesai')
            ->select('kelas.nama_kelas', DB::raw('SUM(pesanan.total_harga) as total_omzet'))
            ->groupBy('kelas.id', 'kelas.nama_kelas')
            ->orderByDesc('total_omzet')
            ->take(5)
            ->get();

        // Produk menunggu approval
        $produkPending = Produk::pending()
            ->with(['toko.user.kelas'])
            ->latest()
            ->take(4)
            ->get();

        // Toko terlaris bulan ini
        $tokoTerlaris = Toko::aktif()
            ->with(['user.kelas'])
            ->withSum(['pesanan as omzet_bulan_ini' => fn($q) => $q->selesai()->where('created_at', '>=', $startThisMonth)], 'total_harga')
            ->orderByDesc('omzet_bulan_ini')
            ->take(4)
            ->get();

        // Award bulan ini
        $awardsBulanIni = Award::with(['toko.user.kelas'])
            ->bulanIni()
            ->take(4)
            ->get();

        return view('admin.dashboard', compact('stats', 'omzetPerKelas', 'produkPending', 'tokoTerlaris', 'awardsBulanIni'));
    }
}
