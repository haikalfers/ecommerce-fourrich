<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Services\PesananService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesananController extends Controller
{
    public function __construct(
        protected PesananService $pesananService
    ) {}

    public function checkout(): View|RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('pembeli.keranjang.index')
                ->with('error', 'Keranjang Anda kosong.');
        }

        $items = [];
        $totalHarga = 0;

        foreach ($cart as $id => $details) {
            $produk = Produk::find($id);
            if ($produk) {
                $subtotal = $produk->harga_final * $details['jumlah'];
                $totalHarga += $subtotal;
                $items[] = [
                    'produk'   => $produk,
                    'jumlah'   => $details['jumlah'],
                    'subtotal' => $subtotal,
                ];
            }
        }

        return view('pembeli.pesanan.checkout', compact('items', 'totalHarga'));
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);

        // Jika dikirim langsung dari Form Pemesanan Detail Produk
        if ($request->filled('produk_id')) {
            $cart = [
                $request->produk_id => [
                    'produk_id' => $request->produk_id,
                    'jumlah'    => (int) ($request->jumlah ?? 1),
                ]
            ];
        }

        if (empty($cart)) {
            return redirect()->route('pembeli.keranjang.index')
                ->with('error', 'Keranjang Anda kosong.');
        }

        $validated = $request->validate([
            'produk_id'    => ['nullable', 'exists:produk,id'],
            'jumlah'       => ['nullable', 'integer', 'min:1'],
            'nama_guest'   => ['nullable', 'string', 'max:255'],
            'hp_guest'     => ['nullable', 'string', 'max:20'],
            'metode_bayar' => ['required', 'in:cash,qris'],
            'metode_kirim' => ['required', 'in:cod,antar'],
            'alamat_kirim' => ['nullable', 'string', 'max:500'],
            'catatan'      => ['nullable', 'string', 'max:500'],
        ]);

        if (!auth()->check() && empty($validated['nama_guest'])) {
            return back()->withInput()->with('error', 'Silakan isi nama pemesan.');
        }

        $pesanan = $this->pesananService->createOrder($validated, array_values($cart));

        // Clear cart session
        session()->forget('cart');

        return redirect()->route('katalog.index')
            ->with('success', "Pesanan #{$pesanan->kode_pesanan} berhasil dibuat! Silakan hubungi penjual untuk pengiriman/pembayaran.");
    }

    public function index(): View
    {
        $pesanans = Pesanan::where('pembeli_id', auth()->id())
            ->with(['toko', 'detailPesanan'])
            ->latest()
            ->paginate(10);

        return view('pembeli.pesanan.index', compact('pesanans'));
    }

    public function show(Pesanan $pesanan): View
    {
        if (auth()->check() && $pesanan->pembeli_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $pesanan->load(['toko', 'detailPesanan.produk']);

        return view('pembeli.pesanan.show', compact('pesanan'));
    }
}
