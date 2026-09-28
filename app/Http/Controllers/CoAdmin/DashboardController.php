<?php

namespace App\Http\Controllers\CoAdmin;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $coAdmin = auth()->user();

        $startThisMonth = now()->startOfMonth();
        $startLastMonth = now()->subMonth()->startOfMonth();
        $endLastMonth   = now()->subMonth()->endOfMonth();

        // Siswa binaan (role: toko / pembeli under this co_admin)
        $siswaBinaan = User::where('co_admin_id', $coAdmin->id)->get();
        $totalSiswa = $siswaBinaan->count();

        // Toko binaan
        $tokoBinaan = Toko::where('co_admin_id', $coAdmin->id)
            ->with(['user.kelas', 'produk'])
            ->get();

        $tokoAktifCount  = $tokoBinaan->where('status', 'aktif')->count();
        $tokoPendingCount = $tokoBinaan->where('status', 'pending')->count();
        $tokoSuspendCount = $tokoBinaan->where('status', 'suspend')->count();

        // Unique classes of students under this co-admin
        $kelasBinaanNames = $siswaBinaan
            ->map(fn($u) => $u->kelas->nama_kelas ?? null)
            ->filter()
            ->unique()
            ->values()
            ->implode(' & ') ?: 'Semua Kelas Binaan';

        // Omzet kelas bulan ini & bulan lalu
        $tokoIds = $tokoBinaan->pluck('id');

        $omzetBulanIni = Pesanan::selesai()
            ->whereIn('toko_id', $tokoIds)
            ->where('created_at', '>=', $startThisMonth)
            ->sum('total_harga');

        $omzetBulanLalu = Pesanan::selesai()
            ->whereIn('toko_id', $tokoIds)
            ->whereBetween('created_at', [$startLastMonth, $endLastMonth])
            ->sum('total_harga');

        $omzetGrowth = $omzetBulanLalu > 0
            ? round((($omzetBulanIni - $omzetBulanLalu) / $omzetBulanLalu) * 100, 1)
            : 12.0;

        // Produk aktif
        $produkAktifCount = Produk::whereIn('toko_id', $tokoIds)
            ->where('status', 'approved')
            ->count();

        // Award bulan ini untuk siswa binaan
        $awardBulanIniCount = Award::whereIn('toko_id', $tokoIds)
            ->bulanIni()
            ->count();

        $stats = [
            'toko_aktif_binaan'    => $tokoAktifCount,
            'total_siswa'          => $totalSiswa,
            'omzet_kelas_bulan_ini'=> $omzetBulanIni,
            'omzet_growth'         => $omzetGrowth,
            'produk_aktif'         => $produkAktifCount,
            'toko_count'           => $tokoBinaan->count(),
            'toko_pending'         => $tokoPendingCount,
            'toko_suspend'         => $tokoSuspendCount,
            'award_bulan_ini'      => $awardBulanIniCount,
        ];

        // Ranking toko binaan
        $rankingTokoBinaan = $tokoBinaan
            ->map(function ($toko) use ($startThisMonth) {
                $toko->omzet_bulan_ini = $toko->pesanan()
                    ->selesai()
                    ->where('created_at', '>=', $startThisMonth)
                    ->sum('total_harga');
                return $toko;
            })
            ->sortByDesc('omzet_bulan_ini')
            ->take(5)
            ->values();

        return view('co_admin.dashboard', compact(
            'coAdmin', 'stats', 'kelasBinaanNames', 'rankingTokoBinaan'
        ));
    }
}
