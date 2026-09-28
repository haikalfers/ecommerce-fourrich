<?php

namespace App\Http\Controllers\Toko;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProdukController extends Controller
{
    public function index(Request $request): View
    {
        $toko = auth()->user()->toko;

        if (!$toko) {
            abort(403, 'Anda belum memiliki akun toko.');
        }

        $produks = Produk::where('toko_id', $toko->id)
            ->with('kategori')
            ->when($request->search, fn($q) => $q->where('nama_produk', 'like', '%' . $request->search . '%'))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10);

        return view('toko.produk.index', compact('produks'));
    }

    public function create(): View
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('toko.produk.create', compact('kategoris'));
    }

    public function store(Request $request): RedirectResponse
    {
        $toko = auth()->user()->toko;

        if (!$toko || !$toko->isAktif()) {
            return redirect()->route('toko.dashboard')
                ->with('error', 'Toko Anda belum disetujui untuk menambahkan produk.');
        }

        $validated = $request->validate([
            'nama_produk'   => ['required', 'string', 'max:255'],
            'kategori_id'   => ['required', 'exists:kategori,id'],
            'harga_pokok'   => ['required', 'numeric', 'min:0'],
            'harga_final'   => ['required', 'numeric', 'min:0'],
            'stok'          => ['required', 'integer', 'min:0'],
            'deskripsi'     => ['nullable', 'string'],
            'foto.*'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('produk', 'public');
                $fotoPaths[] = $path;
            }
        }

        Produk::create([
            'toko_id'       => $toko->id,
            'kategori_id'   => $validated['kategori_id'],
            'nama_produk'   => $validated['nama_produk'],
            'slug'          => Str::slug($validated['nama_produk']) . '-' . Str::random(6),
            'harga_pokok'   => $validated['harga_pokok'],
            'harga_final'   => $validated['harga_final'],
            'stok'          => $validated['stok'],
            'deskripsi'     => $validated['deskripsi'] ?? null,
            'foto'          => $fotoPaths,
            'status'        => 'pending', // Perlu approval admin
        ]);

        return redirect()->route('toko.produk.index')
            ->with('success', 'Produk berhasil dibuat dan dikirim untuk peninjauan admin.');
    }

    public function show(Produk $produk): View
    {
        $this->authorizeToko($produk);

        $produk->load(['kategori', 'approvedBy']);

        return view('toko.produk.show', compact('produk'));
    }

    public function edit(Produk $produk): View
    {
        $this->authorizeToko($produk);
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('toko.produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, Produk $produk): RedirectResponse
    {
        $this->authorizeToko($produk);

        $validated = $request->validate([
            'nama_produk'   => ['required', 'string', 'max:255'],
            'kategori_id'   => ['required', 'exists:kategori,id'],
            'harga_pokok'   => ['required', 'numeric', 'min:0'],
            'harga_final'   => ['required', 'numeric', 'min:0'],
            'stok'          => ['required', 'integer', 'min:0'],
            'deskripsi'     => ['nullable', 'string'],
            'foto.*'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $fotoPaths = $produk->foto ?? [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('produk', 'public');
                $fotoPaths[] = $path;
            }
        }

        $produk->update([
            'kategori_id'   => $validated['kategori_id'],
            'nama_produk'   => $validated['nama_produk'],
            'slug'          => Str::slug($validated['nama_produk']) . '-' . Str::random(6),
            'harga_pokok'   => $validated['harga_pokok'],
            'harga_final'   => $validated['harga_final'],
            'stok'          => $validated['stok'],
            'deskripsi'     => $validated['deskripsi'] ?? null,
            'foto'          => $fotoPaths,
        ]);

        return redirect()->route('toko.produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk): RedirectResponse
    {
        $this->authorizeToko($produk);

        if (!empty($produk->foto)) {
            foreach ($produk->foto as $p) {
                Storage::disk('public')->delete($p);
            }
        }

        $produk->delete();

        return redirect()->route('toko.produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    private function authorizeToko(Produk $produk): void
    {
        if ($produk->toko_id !== auth()->user()->toko->id) {
            abort(403, 'Akses ditolak.');
        }
    }
}
