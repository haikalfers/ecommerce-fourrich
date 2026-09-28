@extends('toko.layouts.app')
@section('title', 'Manajemen Produk Toko')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Katalog Produk Toko Anda</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola daftar produk yang Anda jual di marketplace SMEKIV</p>
        </div>
        <a href="{{ route('toko.produk.create') }}" class="btn-primary flex items-center justify-center gap-2 self-start sm:self-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Produk Baru
        </a>
    </div>

    {{-- Product List Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($produks as $p)
            <div class="card p-4 space-y-3 flex flex-col justify-between hover:border-gray-300 transition">
                <div class="space-y-3">
                    <a href="{{ route('toko.produk.show', $p) }}" class="block relative aspect-square rounded-xl bg-gray-100 overflow-hidden group">
                        @if(!empty($p->foto_urls))
                            <img src="{{ $p->foto_urls[0] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-200" alt="">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl">
                                {{ $p->kategori->icon ?? '📦' }}
                            </div>
                        @endif
                        <span class="absolute top-2 right-2">
                            @if($p->isApproved())
                                <span class="badge-success shadow">Approved</span>
                            @elseif($p->isPending())
                                <span class="badge-warning shadow">Pending</span>
                            @else
                                <span class="badge-danger shadow">Rejected</span>
                            @endif
                        </span>
                    </a>

                    <div>
                        <span class="text-[10px] font-semibold uppercase text-indigo-600 tracking-wider">{{ $p->kategori->nama_kategori }}</span>
                        <a href="{{ route('toko.produk.show', $p) }}" class="block font-bold text-gray-800 text-base line-clamp-1 mt-0.5 hover:text-blue-600 transition">
                            {{ $p->nama_produk }}
                        </a>
                        <p class="text-xs text-gray-500 line-clamp-2 mt-1">{{ $p->deskripsi ?? 'Belum ada deskripsi.' }}</p>

                        @if($p->isRejected())
                            <div class="mt-2.5 p-2 bg-red-50 border border-red-200 rounded-lg text-xs text-red-700 space-y-0.5">
                                <p class="font-bold text-red-800 flex items-center gap-1">
                                    <span>⚠️</span> Ditolak Sekolah:
                                </p>
                                <p class="line-clamp-2 text-[11px]">{{ $p->alasan_reject ?? 'Tidak ada alasan khusus.' }}</p>
                                <a href="{{ route('toko.produk.show', $p) }}" class="inline-block font-semibold text-red-800 underline text-[11px] mt-1">Lihat Detail Penolakan &rarr;</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400">Harga Jual</p>
                        <p class="font-bold text-gray-800 text-sm">Rp {{ number_format($p->harga_final, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('toko.produk.show', $p) }}" title="Lihat Detail Produk" class="p-1.5 text-gray-400 hover:text-blue-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <a href="{{ route('toko.produk.edit', $p) }}" title="Edit Produk" class="p-1.5 text-gray-400 hover:text-indigo-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 210.3H3v-3.572L16.732 3.732z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('toko.produk.destroy', $p) }}" onsubmit="return confirm('Hapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Hapus Produk" class="p-1.5 text-gray-400 hover:text-red-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full card py-12 text-center text-gray-400">
                Anda belum memiliki produk. Klik "Tambah Produk Baru" untuk memasarkan jualan Anda!
            </div>
        @endforelse
    </div>

    @if($produks->hasPages())
        <div class="card p-4">
            {{ $produks->links() }}
        </div>
    @endif
</div>
@endsection
