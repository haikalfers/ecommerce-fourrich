<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KategoriController extends Controller
{
    public function index(): View
    {
        $kategoris = Kategori::withCount('produk')->latest()->get();
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:kategori'],
            'icon'          => ['nullable', 'string', 'max:50'],
            'fee_persen'    => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        Kategori::create([
            'nama_kategori' => $validated['nama_kategori'],
            'slug'          => Str::slug($validated['nama_kategori']),
            'icon'          => $validated['icon'] ?? '📦',
            'fee_persen'    => $validated['fee_persen'],
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Kategori $kategori): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:kategori,nama_kategori,' . $kategori->id],
            'icon'          => ['nullable', 'string', 'max:50'],
            'fee_persen'    => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $kategori->update([
            'nama_kategori' => $validated['nama_kategori'],
            'slug'          => Str::slug($validated['nama_kategori']),
            'icon'          => $validated['icon'] ?? $kategori->icon,
            'fee_persen'    => $validated['fee_persen'],
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori): RedirectResponse
    {
        if ($kategori->produk()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh produk.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
