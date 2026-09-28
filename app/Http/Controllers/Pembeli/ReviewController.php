<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Produk $produk): RedirectResponse
    {
        $validated = $request->validate([
            'rating'   => ['required', 'integer', 'between:1,5'],
            'komentar' => ['nullable', 'string', 'max:500'],
        ]);

        Review::updateOrCreate(
            [
                'produk_id'  => $produk->id,
                'pembeli_id' => auth()->id(),
            ],
            [
                'rating'   => $validated['rating'],
                'komentar' => $validated['komentar'] ?? null,
            ]
        );

        return back()->with('success', 'Terima kasih atas ulasan dan rating produk!');
    }
}
