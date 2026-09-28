<?php

namespace App\Http\Controllers\CoAdmin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProdukController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $produks = Produk::whereHas('toko', function ($q) {
                $q->where('co_admin_id', auth()->id());
            })
            ->with(['toko.user.kelas', 'kategori'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, fn($q) => $q->where(function($s) use ($search) {
                $s->where('nama_produk', 'like', "%{$search}%")
                  ->orWhereHas('toko', fn($t) => $t->where('nama_toko', 'like', "%{$search}%"))
                  ->orWhereHas('toko.user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            }))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('co_admin.produk.index', compact('produks', 'status', 'search'));
    }

    public function show(Produk $produk): View
    {
        // Enforce scoped access to co-admin's mentored students
        if ($produk->toko->co_admin_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengakses produk dari toko ini.');
        }

        $produk->load(['toko.user.kelas', 'kategori', 'approvedBy']);

        return view('co_admin.produk.show', compact('produk'));
    }
}
