<?php

namespace App\Http\Controllers\CoAdmin;

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

        $tokos = Toko::where('co_admin_id', auth()->id())
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, fn($q) => $q->where('nama_toko', 'like', "%{$search}%"))
            ->with(['user.kelas', 'produk'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('co_admin.toko.index', compact('tokos', 'status', 'search'));
    }

    public function show(Toko $toko): View
    {
        // Enforce scoped access to co-admin's own students
        if ($toko->co_admin_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengelola toko ini.');
        }

        $toko->load(['user.kelas', 'produk.kategori', 'pesanan' => fn($q) => $q->latest()->take(10)]);

        return view('co_admin.toko.show', compact('toko'));
    }

    public function suspend(Toko $toko): RedirectResponse
    {
        if ($toko->co_admin_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengelola toko ini.');
        }

        $toko->update(['status' => 'suspend']);

        return back()->with('success', "Toko {$toko->nama_toko} ditangguhkan oleh Guru Pembimbing.");
    }
}
