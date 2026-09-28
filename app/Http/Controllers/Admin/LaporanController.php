<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use App\Models\Kelas;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->query('end_date', now()->format('Y-m-d'));
        $kelasId   = $request->query('kelas_id');

        $pesanans = Pesanan::selesai()
            ->with(['toko.user.kelas', 'pembeli', 'detailPesanan'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->when($kelasId, function($q) use ($kelasId) {
                $q->whereHas('toko.user', fn($u) => $u->where('kelas_id', $kelasId));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $totalOmzet = Pesanan::selesai()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->when($kelasId, function($q) use ($kelasId) {
                $q->whereHas('toko.user', fn($u) => $u->where('kelas_id', $kelasId));
            })->sum('total_harga');

        $totalFeeKasSekolah = DetailPesanan::whereHas('pesanan', function($q) use ($startDate, $endDate, $kelasId) {
            $q->selesai()
              ->whereDate('created_at', '>=', $startDate)
              ->whereDate('created_at', '<=', $endDate)
              ->when($kelasId, fn($k) => $k->whereHas('toko.user', fn($u) => $u->where('kelas_id', $kelasId)));
        })->sum('fee_admin');

        $totalPesananCount = $pesanans->total();

        $kelases = Kelas::orderBy('nama_kelas')->get();

        return view('admin.laporan.index', compact(
            'pesanans', 'totalOmzet', 'totalFeeKasSekolah', 'totalPesananCount',
            'startDate', 'endDate', 'kelasId', 'kelases'
        ));
    }
}
