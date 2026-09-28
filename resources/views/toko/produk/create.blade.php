@extends('toko.layouts.app')
@section('title', 'Tambah Produk Baru')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('toko.produk.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Tambah Produk Baru</h1>
    </div>

    <div class="card p-6">
        <form method="POST" action="{{ route('toko.produk.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Produk</label>
                <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" required class="input-field w-full" placeholder="Contoh: Es Teh Solo Jumbo / Keripik Singkong Pedas">
                @error('nama_produk') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori Produk</label>
                <select name="kategori_id" required class="input-field w-full">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->icon }} {{ $k->nama_kategori }} (Fee Sekolah: {{ $k->fee_persen }}%)
                        </option>
                    @endforeach
                </select>
                @error('kategori_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Pokok (Modal)</label>
                    <input type="number" name="harga_pokok" value="{{ old('harga_pokok') }}" required class="input-field w-full" placeholder="5000">
                    @error('harga_pokok') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Jual (Final)</label>
                    <input type="number" name="harga_final" value="{{ old('harga_final') }}" required class="input-field w-full" placeholder="8000">
                    @error('harga_final') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Stok Awal</label>
                    <input type="number" name="stok" value="{{ old('stok', 10) }}" required class="input-field w-full" placeholder="10">
                    @error('stok') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Foto Produk (Opsional, Max 3)</label>
                <input type="file" name="foto[]" multiple accept="image/*" class="input-field w-full py-2">
                <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WEBP (Maksimal 2MB per foto)</p>
                @error('foto.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Produk</label>
                <textarea name="deskripsi" rows="4" class="input-field w-full" placeholder="Jelaskan keunggulan, bahan, atau varian rasa produk Anda...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('toko.produk.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Kirim untuk Peninjauan</button>
            </div>
        </form>
    </div>
</div>
@endsection
