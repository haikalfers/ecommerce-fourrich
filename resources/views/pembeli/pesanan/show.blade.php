<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #{{ $pesanan->kode_pesanan }} — SMEKIV Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen pb-24">
    {{-- Header --}}
    <header class="sticky top-0 bg-white/90 backdrop-blur-md z-40 border-b border-gray-100 px-4 py-3">
        <div class="max-w-md mx-auto flex items-center justify-between">
            <a href="{{ route('pembeli.pesanan.index') }}" class="p-2 rounded-full hover:bg-gray-100 text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="font-bold text-gray-800 text-base">Detail Pesanan</h1>
            <div class="w-10"></div>
        </div>
    </header>

    <main class="max-w-md mx-auto p-4 space-y-4">
        <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div>
                    <h2 class="font-mono font-bold text-indigo-600 text-base">#{{ $pesanan->kode_pesanan }}</h2>
                    <p class="text-[10px] text-gray-400">{{ $pesanan->created_at->format('d M Y H:i WIB') }}</p>
                </div>
                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold uppercase">{{ $pesanan->status }}</span>
            </div>

            <div class="space-y-2">
                <h3 class="font-bold text-gray-800 text-xs uppercase tracking-wider">Item Produk</h3>
                @foreach($pesanan->detailPesanan as $item)
                    <div class="flex items-center justify-between text-xs py-1.5 border-b border-gray-50 last:border-0">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $item->nama_produk_snapshot }}</p>
                            <p class="text-[10px] text-gray-400">{{ $item->jumlah }} x Rp {{ number_format($item->harga_snapshot, 0, ',', '.') }}</p>
                        </div>
                        <span class="font-bold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                <span class="font-bold text-gray-700 text-xs">Total Pembayaran</span>
                <span class="font-black text-indigo-600 text-base">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm space-y-2 text-xs">
            <h3 class="font-bold text-gray-800 text-xs uppercase tracking-wider mb-2">Informasi Penjual</h3>
            <p class="text-gray-600">Toko: <b class="text-gray-800">{{ $pesanan->toko->nama_toko }}</b></p>
            <p class="text-gray-600">Penjual: <b class="text-gray-800">{{ $pesanan->toko->user->name }}</b></p>
            <p class="text-gray-600">WhatsApp: <b class="text-indigo-600">{{ $pesanan->toko->user->no_hp ?? '-' }}</b></p>
        </div>
    </main>
</body>
</html>
