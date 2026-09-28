<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $toko = auth()->user()->toko;

        // Jika toko masih pending atau suspend
        if (!$toko || $toko->status !== 'aktif') {
            return view('toko.dashboard-pending', compact('toko'));
        }

        $pesananSelesaiBulanIni = $toko->pesanan()->selesai()
            ->whereMonth('selesai_at', now()->month)
            ->whereYear('selesai_at', now()->year);

        $stats = [
            'total_produk'      => $toko->produk()->count(),
            'produk_pending'    => $toko->produk()->pending()->count(),
            'pesanan_masuk'     => $toko->pesanan()->where('status', 'pending')->count(),
            'pesanan_diproses'  => (clone $pesananSelesaiBulanIni)->count(),
            'omzet_hari_ini'    => $toko->pesanan()->selesai()->whereDate('selesai_at', today())->sum('total_harga'),
            'omzet_bulan_ini'   => (clone $pesananSelesaiBulanIni)->sum('total_harga'),
            'wallet_saldo'      => $toko->wallet_saldo,
            'total_terjual'     => $toko->pesanan()->selesai()->count(),
            'avg_rating'        => 0, // placeholder — akan diisi saat fitur rating diimplementasi
        ];

        $pesananTerbaru = $toko->pesanan()
            ->with('items')
            ->whereIn('status', ['pending', 'dikonfirmasi'])
            ->latest()
            ->take(5)
            ->get();

        return view('toko.dashboard', compact('toko', 'stats', 'pesananTerbaru'));
    }
}
