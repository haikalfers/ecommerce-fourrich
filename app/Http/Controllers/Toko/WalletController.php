<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function index(): View
    {
        $toko = auth()->user()->toko;

        if (!$toko) {
            abort(403, 'Anda belum memiliki toko.');
        }

        $transactions = WalletTransaction::where('toko_id', $toko->id)
            ->with('pesanan')
            ->latest()
            ->paginate(15);

        return view('toko.wallet.index', compact('toko', 'transactions'));
    }
}
