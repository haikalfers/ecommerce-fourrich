@extends('toko.layouts.app')
@section('title', 'Detail Pesanan #' . $pesanan->kode_pesanan)
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('toko.pesanan.index') }}" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Pesanan #{{ $pesanan->kode_pesanan }}</h1>
            <p class="text-xs text-gray-400">{{ $pesanan->created_at->format('d M Y H:i WIB') }}</p>
        </div>
    </div>

    <div class="card p-6 space-y-6">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pb-6 border-b border-gray-100 text-sm">
            <div>
                <p class="text-xs text-gray-400">Pembeli</p>
                <p class="font-bold text-gray-800 mt-0.5">{{ $pesanan->pembeli->name ?? $pesanan->nama_guest ?? 'Guest' }}</p>
                <p class="text-xs text-gray-500">{{ $pesanan->pembeli->no_hp ?? $pesanan->hp_guest ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Metode Pengiriman</p>
                <p class="font-bold text-gray-800 mt-0.5 uppercase">{{ $pesanan->metode_kirim }}</p>
                <p class="text-xs text-gray-500 truncate">{{ $pesanan->alamat_kirim ?? 'Diambil di toko' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Pembayaran</p>
                <p class="font-bold text-gray-800 mt-0.5 uppercase">{{ $pesanan->metode_bayar }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Status Pesanan</p>
                <span class="mt-1 inline-block font-bold text-indigo-600 uppercase text-xs">{{ $pesanan->status }}</span>
            </div>
        </div>

        <div>
            <h3 class="font-bold text-gray-800 mb-3 text-sm">Item Produk Dipesan</h3>
            <div class="space-y-3">
                @foreach($pesanan->detailPesanan as $item)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">{{ $item->nama_produk_snapshot }}</p>
                            <p class="text-xs text-gray-400">{{ $item->jumlah }} x Rp {{ number_format($item->harga_snapshot, 0, ',', '.') }}</p>
                        </div>
                        <p class="font-bold text-gray-800 text-sm">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
            <span class="font-bold text-gray-700">Total Tagihan</span>
            <span class="text-2xl font-black text-indigo-600">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
        </div>

        @if($pesanan->isPending())
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <form method="POST" action="{{ route('toko.pesanan.tolak', $pesanan) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-secondary text-red-600 hover:bg-red-50">Tolak Pesanan</button>
                </form>
                <form method="POST" action="{{ route('toko.pesanan.konfirmasi', $pesanan) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-primary">Konfirmasi & Proses</button>
                </form>
            </div>
        @elseif($pesanan->isDikonfirmasi())
            <div class="flex justify-end pt-4 border-t border-gray-100">
                <form method="POST" action="{{ route('toko.pesanan.selesai', $pesanan) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-primary bg-emerald-600 hover:bg-emerald-700">Tandai Selesai (Cairkan Wallet)</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
