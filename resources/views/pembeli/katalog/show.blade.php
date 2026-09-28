<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produk->nama_produk }} — E-Market SMKN 4</title>
    <meta name="description" content="{{ Str::limit($produk->deskripsi, 150) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#F8F8F8] font-sans text-gray-800 antialiased min-h-screen">

    {{-- Topbar Header --}}
    <header class="bg-white border-b border-gray-200/80 sticky top-0 z-30 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between gap-4">
            
            {{-- Brand Logo --}}
            <a href="{{ route('katalog.index') }}" class="flex items-center gap-2.5 shrink-0">
                <div class="w-9 h-9 rounded-xl bg-[#F97316] flex items-center justify-center text-white font-black text-lg shadow-sm">
                    🛍️
                </div>
                <div class="font-bold text-base tracking-tight text-gray-900">
                    E-Market <span class="text-[#F97316]">SMKN 4</span>
                </div>
            </a>

            {{-- Center Search Bar --}}
            <form action="{{ route('katalog.index') }}" method="GET" class="flex-1 max-w-xl hidden sm:block">
                <div class="relative">
                    <input type="text" name="q" placeholder="Cari produk..."
                        class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#F97316]/50 focus:border-[#F97316] text-sm bg-white">
                    <svg class="absolute left-3.5 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </form>

            {{-- Right Actions (Cart & Auth) --}}
            <div class="flex items-center gap-3 shrink-0">
                @php $cartCount = count(session('cart', [])); @endphp
                <a href="{{ route('pembeli.keranjang.index') }}"
                   class="p-2 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 relative transition flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-[#F97316] text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                @auth
                    <div class="flex items-center gap-2 pl-2 border-l border-gray-200">
                        <div class="w-8 h-8 rounded-full bg-[#7C3AED] text-white flex items-center justify-center font-bold text-xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <span class="text-xs font-bold text-gray-800 hidden md:block max-w-[120px] truncate">
                            {{ auth()->user()->name }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}" class="inline ml-1">
                            @csrf
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 rounded-lg transition" title="Keluar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-bold rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Main Container --}}
    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-6"
          x-data="{
              jumlah: 1,
              hargaSatuan: {{ $produk->harga_final }},
              metodeBayar: 'cash',
              metodeKirim: 'cod',
              activeImage: 0,
              get total() {
                  return this.jumlah * this.hargaSatuan;
              },
              formatRupiah(num) {
                  return 'Rp ' + num.toLocaleString('id-ID');
              }
          }">

        {{-- Breadcrumbs --}}
        <nav class="flex items-center gap-2 text-xs font-medium text-gray-500 mb-6">
            <a href="{{ route('katalog.index') }}" class="text-[#F97316] hover:underline">Katalog</a>
            <span>/</span>
            <a href="{{ route('katalog.index', ['kategori' => $produk->kategori->slug]) }}" class="text-gray-600 hover:underline">
                {{ $produk->kategori->nama_kategori }}
            </a>
            <span>/</span>
            <span class="text-gray-400 truncate max-w-xs">{{ $produk->nama_produk }}</span>
        </nav>

        {{-- 2 Columns Grid: Product Details & Order Form --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- LEFT COLUMN: VISUAL & DETAIL --}}
            <div class="lg:col-span-7 space-y-6">
                
                {{-- Main Big Image --}}
                <div class="aspect-4/3 sm:aspect-square max-h-[420px] w-full rounded-3xl bg-white border border-gray-200 overflow-hidden shadow-xs flex items-center justify-center relative">
                    @if(!empty($produk->foto) && is_array($produk->foto) && count($produk->foto) > 0)
                        @foreach($produk->foto as $idx => $img)
                            <img src="{{ asset('storage/' . $img) }}" alt="{{ $produk->nama_produk }}"
                                 x-show="activeImage === {{ $idx }}"
                                 class="w-full h-full object-contain p-4 transition-all duration-300">
                        @endforeach
                    @else
                        <div class="text-8xl">
                            {{ $produk->kategori->icon ?? '📦' }}
                        </div>
                    @endif
                </div>

                {{-- Image Thumbnails List --}}
                @if(!empty($produk->foto) && is_array($produk->foto) && count($produk->foto) > 1)
                    <div class="flex items-center gap-3">
                        @foreach($produk->foto as $idx => $img)
                            <button type="button" @click="activeImage = {{ $idx }}"
                                class="w-16 h-16 rounded-2xl border-2 overflow-hidden bg-white p-1 transition"
                                :class="activeImage === {{ $idx }} ? 'border-[#F97316]' : 'border-gray-200 hover:border-gray-300'">
                                <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover rounded-xl" alt="">
                            </button>
                        @endforeach
                    </div>
                @endif

                {{-- Product Information --}}
                <div class="space-y-3 pt-2">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight">
                        {{ $produk->nama_produk }}
                    </h1>

                    <div class="flex items-center gap-3">
                        <p class="text-2xl sm:text-3xl font-black text-[#F97316]">
                            {{ $produk->harga_format }}
                        </p>
                        @if($produk->stok > 0)
                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold">
                                Stok tersedia ({{ $produk->stok }})
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold">
                                Stok habis
                            </span>
                        @endif
                    </div>

                    {{-- Ratings summary --}}
                    <div class="flex items-center gap-2 text-xs text-gray-500 pt-1">
                        <div class="flex text-amber-500">
                            ★★★★★
                        </div>
                        <span class="font-bold text-gray-800">{{ number_format($produk->rating_avg ?: 4.9, 1) }}</span>
                        <span>·</span>
                        <span>{{ $produk->rating_count ?: 12 }} review</span>
                        <span>·</span>
                        <span>{{ $produk->total_terjual ?: 24 }} terjual</span>
                    </div>

                    {{-- Description --}}
                    <p class="text-sm text-gray-600 leading-relaxed pt-2">
                        {{ $produk->deskripsi ?: 'Casing HP custom desain eksklusif pilihan sendiri. Bahan berkualitas, tahan lama, cocok untuk semua tipe HP.' }}
                    </p>
                </div>

                {{-- Store Info Card --}}
                <div class="pt-4 border-t border-gray-200">
                    <div class="flex items-center gap-3 p-4 rounded-2xl bg-white border border-gray-200">
                        <div class="w-11 h-11 rounded-2xl bg-orange-100 border border-orange-200 text-[#F97316] font-bold text-sm flex items-center justify-center shrink-0">
                            {{ strtoupper(substr($produk->toko->nama_toko, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-900 text-sm truncate">{{ $produk->toko->nama_toko }}</h3>
                            <p class="text-xs text-gray-500 truncate">
                                {{ $produk->toko->user->kelas->nama_kelas ?? 'Kelas XI' }} · <span class="text-gray-400">{{ $produk->toko->coAdmin->name ?? 'Guru Pembimbing' }}</span>
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN: ORDER FORM --}}
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm sticky top-24 space-y-5">
                    <h2 class="text-base font-bold text-gray-900">Form Pemesanan</h2>

                    <form action="{{ route('pembeli.pesanan.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $produk->id }}">

                        {{-- Jumlah Counter --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2">Jumlah</label>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="if (jumlah > 1) jumlah--"
                                    class="w-10 h-10 rounded-xl border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 font-bold text-base flex items-center justify-center transition">
                                    –
                                </button>
                                <input type="number" name="jumlah" x-model.number="jumlah" min="1" max="{{ $produk->stok ?: 99 }}"
                                    class="flex-1 h-10 text-center font-bold text-sm rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#F97316]/50">
                                <button type="button" @click="if (jumlah < {{ $produk->stok ?: 99 }}) jumlah++"
                                    class="w-10 h-10 rounded-xl border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 font-bold text-base flex items-center justify-center transition">
                                    +
                                </button>
                            </div>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2">Metode pembayaran</label>
                            <div class="grid grid-cols-2 gap-2.5">
                                <label class="cursor-pointer">
                                    <input type="radio" name="metode_bayar" value="cash" x-model="metodeBayar" class="hidden">
                                    <div class="py-2.5 px-3 rounded-xl border text-center text-xs font-bold transition flex items-center justify-center gap-1.5"
                                         :class="metodeBayar === 'cash' ? 'border-[#F97316] bg-orange-50/50 text-[#F97316]' : 'border-gray-200 text-gray-700 hover:border-gray-300'">
                                        <span>💵</span> Cash
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="metode_bayar" value="qris" x-model="metodeBayar" class="hidden">
                                    <div class="py-2.5 px-3 rounded-xl border text-center text-xs font-bold transition flex items-center justify-center gap-1.5"
                                         :class="metodeBayar === 'qris' ? 'border-[#F97316] bg-orange-50/50 text-[#F97316]' : 'border-gray-200 text-gray-700 hover:border-gray-300'">
                                        <span>📱</span> QRIS
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Metode Pengiriman --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2">Metode pengiriman</label>
                            <div class="grid grid-cols-2 gap-2.5">
                                <label class="cursor-pointer">
                                    <input type="radio" name="metode_kirim" value="cod" x-model="metodeKirim" class="hidden">
                                    <div class="py-2.5 px-3 rounded-xl border text-center text-xs font-bold transition flex items-center justify-center gap-1.5"
                                         :class="metodeKirim === 'cod' ? 'border-[#F97316] bg-orange-50/50 text-[#F97316]' : 'border-gray-200 text-gray-700 hover:border-gray-300'">
                                        <span>🤝</span> COD
                                    </div>
                                </label>

                                <label class="cursor-pointer">
                                    <input type="radio" name="metode_kirim" value="antar" x-model="metodeKirim" class="hidden">
                                    <div class="py-2.5 px-3 rounded-xl border text-center text-xs font-bold transition flex items-center justify-center gap-1.5"
                                         :class="metodeKirim === 'antar' ? 'border-[#F97316] bg-orange-50/50 text-[#F97316]' : 'border-gray-200 text-gray-700 hover:border-gray-300'">
                                        <span>🛵</span> Antar
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Catatan (opsional) --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2">Catatan (opsional)</label>
                            <textarea name="catatan" rows="3"
                                placeholder="Contoh: desain warna merah, tipe HP Samsung A51..."
                                class="w-full p-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#F97316]/50 text-xs text-gray-700 resize-none"></textarea>
                        </div>

                        {{-- Price Calculation --}}
                        <div class="pt-3 border-t border-gray-100 space-y-2 text-sm">
                            <div class="flex items-center justify-between text-gray-500 text-xs">
                                <span>Subtotal</span>
                                <span class="font-bold text-gray-800" x-text="formatRupiah(total)"></span>
                            </div>
                            <div class="flex items-center justify-between text-base font-bold">
                                <span class="text-gray-900">Total</span>
                                <span class="text-[#F97316] font-black" x-text="formatRupiah(total)"></span>
                            </div>
                        </div>

                        {{-- Submit Order Button --}}
                        <button type="submit"
                            class="w-full py-3.5 px-4 rounded-2xl bg-[#F97316] text-white font-bold text-sm shadow-md shadow-orange-950/20 hover:bg-orange-600 transition flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                            </svg>
                            Pesan sekarang
                        </button>

                        <p class="text-[11px] text-gray-400 text-center">
                            Pesanan akan langsung masuk ke toko penjual
                        </p>
                    </form>
                </div>
            </div>

        </div>
    </main>

</body>
</html>
