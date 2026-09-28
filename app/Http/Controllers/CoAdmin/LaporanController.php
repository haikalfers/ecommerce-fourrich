<?php

namespace App\Http\Controllers\CoAdmin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->query('end_date', now()->format('Y-m-d'));

        $tokoIds = Toko::where('co_admin_id', auth()->id())->pluck('id');

        $pesanans = Pesanan::selesai()
            ->with(['toko.user.kelas', 'pembeli', 'detailPesanan'])
            ->whereIn('toko_id', $tokoIds)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $totalOmzet = Pesanan::selesai()
            ->whereIn('toko_id', $tokoIds)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->sum('total_harga');

        return view('co_admin.laporan.index', compact('pesanans', 'totalOmzet', 'startDate', 'endDate'));
    }
}
