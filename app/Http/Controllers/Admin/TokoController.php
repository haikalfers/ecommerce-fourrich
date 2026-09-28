<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TokoController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $tokos = Toko::with(['user.kelas', 'coAdmin'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, fn($q) => $q->where(function($s) use ($search) {
                $s->where('nama_toko', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.toko.index', compact('tokos', 'status', 'search'));
    }

    public function show(Toko $toko): View
    {
        $toko->load([
            'user.kelas',
            'coAdmin',
            'produk.kategori',
            'pesanan' => fn($q) => $q->with('pembeli')->latest()->take(10),
            'walletTransactions' => fn($q) => $q->latest()->take(10),
        ]);

        $stats = [
            'total_produk' => $toko->produk()->count(),
            'total_pesanan' => $toko->pesanan()->count(),
            'total_omzet' => $toko->total_omzet,
        ];

        return view('admin.toko.show', compact('toko', 'stats'));
    }

    public function approve(Toko $toko): RedirectResponse
    {
        $toko->update(['status' => 'aktif']);

        return back()->with('success', "Toko {$toko->nama_toko} berhasil disetujui (Aktif).");
    }

    public function suspend(Toko $toko): RedirectResponse
    {
        $toko->update(['status' => 'suspend']);

        return back()->with('success', "Toko {$toko->nama_toko} telah ditangguhkan (Suspend).");
    }

    public function unsuspend(Toko $toko): RedirectResponse
    {
        $toko->update(['status' => 'aktif']);

        return back()->with('success', "Status toko {$toko->nama_toko} dikembalikan ke Aktif.");
    }
}
