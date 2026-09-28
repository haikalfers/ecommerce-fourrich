@extends('toko.layouts.app')
@section('title', 'Profil Toko')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Pengaturan Toko Siswa</h1>
        <p class="text-sm text-gray-500 mt-1">Ubah nama, deskripsi, dan logo toko jualan Anda</p>
    </div>

    <div class="card p-6">
        <form method="POST" action="{{ route('toko.profil.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PATCH')

            <div class="flex items-center gap-4 py-3">
                <img src="{{ $toko->logo_url }}" class="w-20 h-20 rounded-2xl object-cover border-2 border-indigo-100" alt="">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Ganti Logo Toko</label>
                    <input type="file" name="logo" accept="image/*" class="input-field text-xs py-1.5">
                    <p class="text-[10px] text-gray-400 mt-1">Format: JPG, PNG, WEBP (Maksimal 2MB)</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Toko</label>
                <input type="text" name="nama_toko" value="{{ old('nama_toko', $toko->nama_toko) }}" required class="input-field w-full">
                @error('nama_toko') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Toko</label>
                <textarea name="deskripsi" rows="4" class="input-field w-full" placeholder="Jelaskan mengenai produk atau usaha kreatif kelas Anda...">{{ old('deskripsi', $toko->deskripsi) }}</textarea>
                @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-gray-100">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
