<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja — SMEKIV Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen pb-28">
    {{-- Header --}}
    <header class="sticky top-0 bg-white/90 backdrop-blur-md z-40 border-b border-gray-100 px-4 py-3">
        <div class="max-w-md mx-auto flex items-center justify-between">
            <a href="{{ route('katalog.index') }}" class="p-2 rounded-full hover:bg-gray-100 text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="font-bold text-gray-800 text-base">Keranjang Belanja</h1>
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
        @forelse($items as $item)
            @php $p = $item['produk']; @endphp
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
                <div class="w-16 h-16 rounded-xl bg-gray-100 flex items-center justify-center overflow-hidden shrink-0">
                    @if(!empty($p->foto_urls))
                        <img src="{{ $p->foto_urls[0] }}" class="w-full h-full object-cover" alt="">
                    @else
                        <span class="text-2xl">{{ $p->kategori->icon ?? '📦' }}</span>
                    @endif
                </div>

                <div class="flex-1 min-w-0 space-y-1">
                    <h3 class="font-bold text-gray-800 text-sm truncate">{{ $p->nama_produk }}</h3>
                    <p class="text-xs text-indigo-600 font-semibold">Rp {{ number_format($p->harga_final, 0, ',', '.') }}</p>

                    <div class="flex items-center gap-2 pt-1">
                        <form method="POST" action="{{ route('pembeli.keranjang.update', $p->id) }}" class="flex items-center gap-1">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="jumlah" value="{{ $item['jumlah'] }}" min="1" max="{{ $p->stok }}" class="w-14 text-xs p-1 rounded-lg border border-gray-200 text-center">
                            <button type="submit" class="text-[10px] text-indigo-600 font-bold">Update</button>
                        </form>
                    </div>
                </div>

                <div class="text-right flex flex-col justify-between h-full">
                    <form method="POST" action="{{ route('pembeli.keranjang.destroy', $p->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-600 p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                    <span class="font-bold text-xs text-gray-900 mt-4">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 space-y-3">
                <div class="text-5xl">🛒</div>
                <h3 class="font-bold text-gray-800">Keranjang Anda Kosong</h3>
                <p class="text-xs text-gray-400">Jelajahi katalog dan temukan produk kreatif siswa favorit Anda!</p>
                <a href="{{ route('katalog.index') }}" class="inline-block mt-2 px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-600/30">Mulai Belanja</a>
            </div>
        @endforelse
    </main>

    @if(count($items) > 0)
        {{-- Bottom Fixed Checkout Bar --}}
        <div class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-gray-100 p-4 z-50">
            <div class="max-w-md mx-auto flex items-center justify-between">
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-semibold">Total Belanja</p>
                    <p class="text-xl font-black text-indigo-600">Rp {{ number_format($totalHarga, 0, ',', '.') }}</p>
                </div>
                <a href="{{ route('pembeli.pesanan.checkout') }}" class="py-3 px-6 bg-indigo-600 text-white rounded-2xl font-bold text-xs shadow-lg shadow-indigo-600/30 hover:bg-indigo-700 transition">
                    Lanjut Checkout →
                </a>
            </div>
        </div>
    @endif
</body>
</html>
