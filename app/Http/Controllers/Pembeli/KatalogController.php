<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Kelas;
use App\Models\Produk;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KatalogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Produk::approved()
            ->with([
                'toko' => fn($q) => $q->with(['user.kelas', 'coAdmin']),
                'kategori:id,nama_kategori,slug,icon'
            ]);

        // Filter kategori
        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->whereHas('kategori', fn($q) => $q->where('slug', $request->kategori));
        }

        // Filter Kelas Penjual
        if ($request->filled('kelas')) {
            $kelasQuery = $request->kelas;
            $query->whereHas('toko.user.kelas', function ($q) use ($kelasQuery) {
                $q->where('nama_kelas', 'like', "%{$kelasQuery}%")
                  ->orWhere('jurusan', 'like', "%{$kelasQuery}%");
            });
        }

        // Filter Harga
        if ($request->filled('harga')) {
            match ($request->harga) {
                '<10k'      => $query->where('harga_final', '<', 10000),
                '10k-50k'   => $query->whereBetween('harga_final', [10000, 50000]),
                '50k-100k'  => $query->whereBetween('harga_final', [50000, 100000]),
                '>100k'     => $query->where('harga_final', '>', 100000),
                default     => null,
            };
        }

        // Filter Rating
        if ($request->filled('rating')) {
            $minRating = (float) $request->rating;
            $query->where('rating_avg', '>=', $minRating);
        }

        // Filter Toko spesifik
        if ($request->filled('toko_id')) {
            $query->where('toko_id', $request->toko_id);
        }

        // Search
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(fn($q) => $q
                ->where('nama_produk', 'like', "%{$keyword}%")
                ->orWhere('deskripsi', 'like', "%{$keyword}%")
                ->orWhereHas('toko', fn($tq) => $tq->where('nama_toko', 'like', "%{$keyword}%"))
            );
        }

        // Sort
        $sort = $request->get('sort', 'terbaru');
        match ($sort) {
            'termurah'  => $query->orderBy('harga_final', 'asc'),
            'termahal'  => $query->orderBy('harga_final', 'desc'),
            'terlaris'  => $query->orderByDesc('total_terjual'),
            'rating'    => $query->orderByDesc('rating_avg'),
            default     => $query->latest(),
        };

        $totalProdukCount = Produk::approved()->count();
        $produk = $query->paginate(12)->withQueryString();

        // Data filter sidebar
        $kategori = Kategori::withCount(['produk' => fn($q) => $q->approved()])->orderBy('nama_kategori')->get();
        $kelasList = Kelas::where('is_active', true)->get();
        $semuaToko = Toko::where('status', 'aktif')->orderBy('nama_toko')->get();

        return view('pembeli.katalog.index', compact('produk', 'kategori', 'kelasList', 'semuaToko', 'totalProdukCount'));
    }

    public function show(string $slug): View
    {
        $produk = Produk::approved()
            ->with(['toko.user.kelas', 'toko.coAdmin', 'kategori', 'reviews.user'])
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug)
                  ->orWhere('id', $slug);
            })
            ->firstOrFail();

        $produkLain = Produk::approved()
            ->where('toko_id', $produk->toko_id)
            ->where('id', '!=', $produk->id)
            ->take(4)
            ->get();

        return view('pembeli.katalog.show', compact('produk', 'produkLain'));
    }
}
