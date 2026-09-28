<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan — SMEKIV Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen pb-24">
    {{-- Header --}}
    <header class="sticky top-0 bg-white/90 backdrop-blur-md z-40 border-b border-gray-100 px-4 py-3">
        <div class="max-w-md mx-auto flex items-center justify-between">
            <a href="{{ route('katalog.index') }}" class="p-2 rounded-full hover:bg-gray-100 text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="font-bold text-gray-800 text-base">Riwayat Pesanan Saya</h1>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-xl text-xs font-semibold transition" title="Keluar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </header>

    <main class="max-w-md mx-auto p-4 space-y-4">
        @forelse($pesanans as $p)
            <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm space-y-3">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div>
                        <p class="font-mono font-bold text-indigo-600 text-sm">#{{ $p->kode_pesanan }}</p>
                        <p class="text-[10px] text-gray-400">{{ $p->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <span class="text-xs font-bold uppercase {{ $p->isSelesai() ? 'text-emerald-600' : 'text-amber-500' }}">{{ $p->status }}</span>
                </div>

                <div class="text-xs space-y-1 text-gray-600">
                    <p>Toko: <b class="text-gray-800">{{ $p->toko->nama_toko }}</b></p>
                    <p>Pengiriman: <span class="capitalize">{{ $p->metode_kirim }}</span></p>
                </div>

                <div class="pt-2 flex items-center justify-between">
                    <span class="font-black text-gray-900 text-sm">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</span>
                    <a href="{{ route('pembeli.pesanan.show', $p) }}" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-xs font-bold hover:bg-indigo-100">Detail Pesanan</a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 space-y-3">
                <div class="text-5xl">📦</div>
                <h3 class="font-bold text-gray-800">Belum Ada Pesanan</h3>
                <p class="text-xs text-gray-400">Anda belum pernah melakukan pemesanan produk.</p>
                <a href="{{ route('katalog.index') }}" class="inline-block mt-2 px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold">Mulai Belanja</a>
            </div>
        @endforelse
    </main>
</body>
</html>
