<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function edit(): View
    {
        $toko = auth()->user()->toko;

        if (!$toko) {
            abort(403, 'Anda belum memiliki toko.');
        }

        return view('toko.profil.index', compact('toko'));
    }

    public function update(Request $request): RedirectResponse
    {
        $toko = auth()->user()->toko;

        $validated = $request->validate([
            'nama_toko' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'logo'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            if ($toko->logo) {
                Storage::disk('public')->delete($toko->logo);
            }
            $validated['logo'] = $request->file('logo')->store('toko_logos', 'public');
        }

        $toko->update($validated);

        return back()->with('success', 'Profil toko berhasil diperbarui.');
    }
}
