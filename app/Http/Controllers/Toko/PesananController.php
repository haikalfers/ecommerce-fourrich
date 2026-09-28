<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Services\PesananService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesananController extends Controller
{
    public function __construct(
        protected PesananService $pesananService
    ) {}

    public function index(Request $request): View
    {
        $toko = auth()->user()->toko;

        if (!$toko) {
            abort(403, 'Anda belum memiliki akun toko.');
        }

        $status = $request->query('status');

        $pesanans = Pesanan::where('toko_id', $toko->id)
            ->when($status, fn($q) => $q->where('status', $status))
            ->with(['pembeli', 'detailPesanan'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('toko.pesanan.index', compact('pesanans', 'status'));
    }

    public function show(Pesanan $pesanan): View
    {
        $this->authorizeTokoOrder($pesanan);
        $pesanan->load(['pembeli', 'detailPesanan.produk']);

        return view('toko.pesanan.show', compact('pesanan'));
    }

    public function konfirmasi(Pesanan $pesanan): RedirectResponse
    {
        $this->authorizeTokoOrder($pesanan);

        $pesanan->update(['status' => 'dikonfirmasi']);

        return back()->with('success', "Pesanan #{$pesanan->kode_pesanan} berhasil dikonfirmasi!");
    }

    public function selesai(Pesanan $pesanan): RedirectResponse
    {
        $this->authorizeTokoOrder($pesanan);

        $this->pesananService->selesaikanPesanan($pesanan);

        return back()->with('success', "Pesanan #{$pesanan->kode_pesanan} diselesaikan! Dana berhasil masuk ke wallet.");
    }

    public function tolak(Pesanan $pesanan): RedirectResponse
    {
        $this->authorizeTokoOrder($pesanan);

        $pesanan->update(['status' => 'dibatalkan']);

        return back()->with('success', "Pesanan #{$pesanan->kode_pesanan} telah ditolak/dibatalkan.");
    }

    private function authorizeTokoOrder(Pesanan $pesanan): void
    {
        if ($pesanan->toko_id !== auth()->user()->toko->id) {
            abort(403, 'Akses ditolak.');
        }
    }
}
