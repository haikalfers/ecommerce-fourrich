<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProdukApprovalController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'pending');
        $search = $request->query('search');

        $produks = Produk::with(['toko', 'kategori'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, fn($q) => $q->where('nama_produk', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.produk.index', compact('produks', 'status', 'search'));
    }

    public function show(Produk $produk): View
    {
        $produk->load(['toko.user.kelas', 'toko.coAdmin', 'kategori', 'approvedBy']);

        return view('admin.produk.show', compact('produk'));
    }

    public function approve(Produk $produk): RedirectResponse
    {
        $produk->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'alasan_reject' => null
        ]);

        return back()->with('success', "Produk {$produk->nama_produk} berhasil disetujui.");
    }

    public function reject(Request $request, Produk $produk): RedirectResponse
    {
        $request->validate([
            'alasan_reject' => ['required', 'string', 'max:500'],
        ]);

        $produk->update([
            'status' => 'rejected',
            'alasan_reject' => $request->alasan_reject
        ]);

        return back()->with('success', "Produk {$produk->nama_produk} telah ditolak.");
    }
}
