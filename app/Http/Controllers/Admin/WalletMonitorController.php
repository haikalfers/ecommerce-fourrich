<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletMonitorController extends Controller
{
    public function index(Request $request): View
    {
        $tokos = Toko::with(['user.kelas', 'coAdmin'])
            ->orderByDesc('wallet_saldo')
            ->paginate(15);

        $transactions = WalletTransaction::with(['toko', 'pesanan'])
            ->latest()
            ->take(20)
            ->get();

        $totalSaldoSiswa = Toko::sum('wallet_saldo');

        return view('admin.wallet.index', compact('tokos', 'transactions', 'totalSaldoSiswa'));
    }
}
