<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KeranjangController extends Controller
{
    public function index(): View
    {
        $cart = session()->get('cart', []);

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

        return view('pembeli.keranjang.index', compact('items', 'totalHarga'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'produk_id' => ['required', 'exists:produk,id'],
            'jumlah'    => ['required', 'integer', 'min:1'],
        ]);

        $produk = Produk::findOrFail($request->produk_id);
        $cart = session()->get('cart', []);

        if (isset($cart[$produk->id])) {
            $cart[$produk->id]['jumlah'] += $request->jumlah;
        } else {
            $cart[$produk->id] = [
                'produk_id' => $produk->id,
                'jumlah'    => $request->jumlah,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('pembeli.keranjang.index')
            ->with('success', 'Produk dimasukkan ke keranjang!');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['jumlah'] = $request->jumlah;
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}
