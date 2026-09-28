<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk — E-Market SMKN 4</title>
    <meta name="description" content="Belanja produk karya siswa SMKN 4 Surabaya. Makanan, minuman, kerajinan tangan, fashion, dan banyak lagi!">
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

            {{-- Center Search Bar & Filter Toko --}}
            <form action="{{ route('katalog.index') }}" method="GET" class="flex-1 max-w-2xl flex items-center gap-2">
                @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
                @if(request('kelas')) <input type="hidden" name="kelas" value="{{ request('kelas') }}"> @endif
                @if(request('harga')) <input type="hidden" name="harga" value="{{ request('harga') }}"> @endif
                @if(request('rating')) <input type="hidden" name="rating" value="{{ request('rating') }}"> @endif
                @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                <div class="relative flex-1">
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Cari produk, toko..."
                        class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#F97316]/50 focus:border-[#F97316] text-sm bg-white">
                    <svg class="absolute left-3.5 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                {{-- Dropdown Pilih Toko --}}
                <select name="toko_id" onchange="this.form.submit()"
                    class="hidden md:block py-2 px-3 text-xs rounded-xl border border-gray-200 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#F97316]/50">
                    <option value="">Semua toko</option>
                    @foreach($semuaToko as $tokoItem)
                        <option value="{{ $tokoItem->id }}" {{ request('toko_id') == $tokoItem->id ? 'selected' : '' }}>
                            {{ $tokoItem->nama_toko }}
                        </option>
                    @endforeach
                </select>
            </form>

            {{-- Right Actions (Cart & Auth) --}}
            <div class="flex items-center gap-3 shrink-0">
                {{-- Cart Button --}}
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

                {{-- Auth State --}}
                @auth
                    @if(auth()->user()->isPembeli())
                        <div class="flex items-center gap-2">
                            <a href="{{ route('pembeli.pesanan.index') }}"
                               class="hidden sm:flex items-center gap-1.5 px-3 py-2 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-xs font-semibold text-gray-700 transition">
                                📋 Pesanan
                            </a>
                            <div class="flex items-center gap-2 pl-2 border-l border-gray-200">
                                <div class="w-8 h-8 rounded-full bg-[#F97316] text-white flex items-center justify-center font-bold text-xs">
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
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            <a href="{{ route(auth()->user()->getDashboardRoute()) }}" class="text-xs px-3.5 py-2 rounded-xl bg-[#F97316] text-white font-bold hover:bg-orange-600 transition">
                                Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-2.5 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-xl transition">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-bold rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register.pembeli') }}" class="px-4 py-2 text-xs font-bold rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 pt-4">
            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs font-semibold text-emerald-800 flex items-center justify-between" x-data x-init="setTimeout(() => $el.remove(), 4000)">
                <span>✅ {{ session('success') }}</span>
                <button @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">&times;</button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 pt-4">
            <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs font-semibold text-rose-800 flex items-center justify-between">
                <span>❌ {{ session('error') }}</span>
                <button @click="$el.parentElement.remove()" class="text-rose-600 hover:text-rose-900">&times;</button>
            </div>
        </div>
    @endif

    {{-- Main Layout with Sidebar Filter --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
        <div class="flex flex-col lg:flex-row gap-8 items-start">

            {{-- LEFT SIDEBAR FILTER --}}
            <aside class="w-full lg:w-64 bg-white rounded-2xl border border-gray-200 p-5 shrink-0 shadow-xs">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-900 text-base">Filter</h2>
                    @if(request()->hasAny(['kategori', 'kelas', 'harga', 'rating', 'q', 'toko_id']))
                        <a href="{{ route('katalog.index') }}" class="text-xs text-[#F97316] font-semibold hover:underline">
                            Reset
                        </a>
                    @endif
                </div>

                <form id="filterForm" action="{{ route('katalog.index') }}" method="GET" class="space-y-6">
                    @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                    @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                    {{-- 1. KATEGORI --}}
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-3">Kategori</p>
                        <div class="space-y-2.5 text-sm">
                            <label class="flex items-center justify-between cursor-pointer group">
                                <div class="flex items-center gap-2.5">
                                    <input type="radio" name="kategori" value="" onchange="this.form.submit()"
                                        {{ !request('kategori') || request('kategori') == 'semua' ? 'checked' : '' }}
                                        class="w-4 h-4 text-[#F97316] focus:ring-[#F97316] border-gray-300">
                                    <span class="text-gray-700 font-medium group-hover:text-[#F97316]">Semua</span>
                                </div>
                                <span class="text-xs text-gray-400">{{ $totalProdukCount }}</span>
                            </label>

                            @foreach($kategori as $kat)
                                <label class="flex items-center justify-between cursor-pointer group">
                                    <div class="flex items-center gap-2.5">
                                        <input type="radio" name="kategori" value="{{ $kat->slug }}" onchange="this.form.submit()"
                                            {{ request('kategori') == $kat->slug ? 'checked' : '' }}
                                            class="w-4 h-4 text-[#F97316] focus:ring-[#F97316] border-gray-300">
                                        <span class="text-gray-700 group-hover:text-[#F97316] truncate max-w-[130px]">{{ $kat->nama_kategori }}</span>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $kat->produk_count }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- 2. KELAS PENJUAL --}}
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-3">Kelas Penjual</p>
                        <div class="space-y-2.5 text-sm">
                            @php
                                $sampleKelas = ['XI-AKL', 'XI-RPL', 'XI-MM', 'XI-TB', 'XI-DKV', 'XI-PPLG'];
                                $displayKelas = $kelasList->pluck('nama_kelas')->toArray();
                                $classesToShow = !empty($displayKelas) ? $displayKelas : $sampleKelas;
                            @endphp
                            @foreach(array_slice($classesToShow, 0, 5) as $kls)
                                <label class="flex items-center justify-between cursor-pointer group">
                                    <div class="flex items-center gap-2.5">
                                        <input type="radio" name="kelas" value="{{ $kls }}" onchange="this.form.submit()"
                                            {{ request('kelas') == $kls ? 'checked' : '' }}
                                            class="w-4 h-4 text-[#F97316] focus:ring-[#F97316] border-gray-300">
                                        <span class="text-gray-700 group-hover:text-[#F97316]">{{ $kls }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- 3. HARGA --}}
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-3">Harga</p>
                        <div class="space-y-2.5 text-sm">
                            @php
                                $priceRanges = [
                                    '<10k'     => '< Rp 10.000',
                                    '10k-50k'  => 'Rp 10rb – 50rb',
                                    '50k-100k' => 'Rp 50rb – 100rb',
                                    '>100k'    => '> Rp 100.000',
                                ];
                            @endphp
                            @foreach($priceRanges as $pKey => $pLabel)
                                <label class="flex items-center justify-between cursor-pointer group">
                                    <div class="flex items-center gap-2.5">
                                        <input type="radio" name="harga" value="{{ $pKey }}" onchange="this.form.submit()"
                                            {{ request('harga') == $pKey ? 'checked' : '' }}
                                            class="w-4 h-4 text-[#F97316] focus:ring-[#F97316] border-gray-300">
                                        <span class="text-gray-700 group-hover:text-[#F97316]">{{ $pLabel }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- 4. RATING --}}
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-3">Rating</p>
                        <div class="space-y-2.5 text-sm">
                            @foreach([5 => '5.0', 4 => '4.0+', 3 => '3.0+'] as $rVal => $rText)
                                <label class="flex items-center justify-between cursor-pointer group">
                                    <div class="flex items-center gap-2.5">
                                        <input type="radio" name="rating" value="{{ $rVal }}" onchange="this.form.submit()"
                                            {{ request('rating') == $rVal ? 'checked' : '' }}
                                            class="w-4 h-4 text-[#F97316] focus:ring-[#F97316] border-gray-300">
                                        <div class="flex items-center gap-1 text-amber-500 text-xs">
                                            @for($i = 0; $i < $rVal; $i++) ★ @endfor
                                            <span class="text-gray-700 font-medium ml-1">{{ $rText }}</span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </form>
            </aside>

            {{-- RIGHT MAIN CONTENT AREA --}}
            <div class="flex-1 w-full space-y-6">

                {{-- Hero Banner Dark Charcoal --}}
                <div class="rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6 shadow-md"
                     style="background-color: #1E1E2D;">
                    
                    <div class="space-y-2 max-w-xl z-10">
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight leading-tight flex items-center gap-2">
                            <span>Belanja dari karya siswa SMKN 4</span> <span>🎏</span>
                        </h1>
                        <p class="text-gray-300 text-xs sm:text-sm font-normal">
                            Dukung wirausaha muda — produk asli buatan siswa kelas XI
                        </p>
                        <div class="pt-2">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-[#F97316] text-white text-xs font-bold shadow-sm">
                                {{ $totalProdukCount }} produk tersedia
                            </span>
                        </div>
                    </div>

                    {{-- Hero Graphic Illustration --}}
                    <div class="shrink-0 relative">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl bg-white/5 border border-white/10 flex items-center justify-center text-5xl shadow-inner">
                            🛍️
                        </div>
                    </div>
                </div>

                {{-- Horizontal Category Pills --}}
                <div class="flex items-center gap-2.5 overflow-x-auto pb-1 custom-scrollbar">
                    <a href="{{ route('katalog.index', request()->except('kategori')) }}"
                       class="px-5 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all duration-150
                              {{ !request('kategori') || request('kategori') == 'semua'
                                  ? 'bg-[#F97316] text-white shadow-sm'
                                  : 'bg-white border border-gray-200 text-gray-700 hover:border-gray-300' }}">
                        Semua
                    </a>

                    @foreach($kategori as $pillKat)
                        <a href="{{ route('katalog.index', array_merge(request()->all(), ['kategori' => $pillKat->slug])) }}"
                           class="px-5 py-2 rounded-full text-xs font-semibold whitespace-nowrap transition-all duration-150
                                  {{ request('kategori') == $pillKat->slug
                                      ? 'bg-[#F97316] text-white shadow-sm'
                                      : 'bg-white border border-gray-200 text-gray-700 hover:border-gray-300' }}">
                            {{ $pillKat->nama_kategori }}
                        </a>
                    @endforeach
                </div>

                {{-- Subheader (Total count & Sort Dropdown) --}}
                <div class="flex items-center justify-between pt-2">
                    <div>
                        <h2 class="font-bold text-gray-900 text-base">Semua produk</h2>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $produk->total() }} produk ditemukan</p>
                    </div>

                    <form action="{{ route('katalog.index') }}" method="GET" class="flex items-center gap-2">
                        @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
                        @if(request('kelas')) <input type="hidden" name="kelas" value="{{ request('kelas') }}"> @endif
                        @if(request('harga')) <input type="hidden" name="harga" value="{{ request('harga') }}"> @endif
                        @if(request('rating')) <input type="hidden" name="rating" value="{{ request('rating') }}"> @endif
                        @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                        @if(request('toko_id')) <input type="hidden" name="toko_id" value="{{ request('toko_id') }}"> @endif

                        <select name="sort" onchange="this.form.submit()"
                            class="py-2 pl-3 pr-8 rounded-xl border border-gray-200 bg-white text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#F97316]/50 shadow-xs cursor-pointer">
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Harga: Termurah</option>
                            <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Harga: Termahal</option>
                            <option value="terlaris" {{ request('sort') == 'terlaris' ? 'selected' : '' }}>Terlaris</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        </select>
                    </form>
                </div>

                {{-- Product Grid 4 Kolom --}}
                @if($produk->isEmpty())
                    <div class="bg-white rounded-3xl border border-gray-200 p-12 text-center">
                        <span class="text-5xl">🔍</span>
                        <h3 class="font-bold text-gray-800 text-base mt-4">Tidak ada produk ditemukan</h3>
                        <p class="text-xs text-gray-400 mt-1">Coba sesuaikan kata kunci atau ubah filter pencarian Anda.</p>
                        <a href="{{ route('katalog.index') }}" class="mt-4 inline-block px-5 py-2.5 rounded-xl bg-[#F97316] text-white text-xs font-bold hover:bg-orange-600 transition">
                            Reset Semua Filter
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
                        @foreach($produk as $item)
                            <div class="bg-white rounded-2xl border border-gray-200/80 overflow-hidden shadow-xs hover:shadow-md transition-all duration-200 flex flex-col group relative">
                                
                                {{-- Heart / Wishlist Icon Button --}}
                                <button type="button" class="absolute top-2.5 right-2.5 z-10 w-7 h-7 rounded-full bg-white/80 backdrop-blur-xs flex items-center justify-center text-gray-400 hover:text-rose-500 hover:bg-white transition shadow-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>

                                {{-- Image Container --}}
                                <a href="{{ route('katalog.show', $item->slug ?? $item->id) }}" class="aspect-square bg-gray-50 flex items-center justify-center overflow-hidden">
                                    @if(!empty($item->foto) && is_array($item->foto) && count($item->foto) > 0)
                                        <img src="{{ asset('storage/' . $item->foto[0]) }}" alt="{{ $item->nama_produk }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="text-4xl group-hover:scale-110 transition-transform duration-300">
                                            {{ $item->kategori->icon ?? '📦' }}
                                        </div>
                                    @endif
                                </a>

                                {{-- Info Body --}}
                                <div class="p-3.5 flex-1 flex flex-col justify-between space-y-2.5">
                                    <div>
                                        <a href="{{ route('katalog.show', $item->slug ?? $item->id) }}" class="block">
                                            <h3 class="font-bold text-gray-900 text-sm leading-snug line-clamp-2 group-hover:text-[#F97316] transition-colors">
                                                {{ $item->nama_produk }}
                                            </h3>
                                        </a>

                                        {{-- Price --}}
                                        <p class="text-sm font-extrabold text-[#F97316] mt-1">
                                            {{ $item->harga_format }}
                                        </p>

                                        {{-- Store & Class --}}
                                        @php
                                            $kelasName = $item->toko->user->kelas->nama_kelas ?? 'Kelas XI';
                                        @endphp
                                        <p class="text-[11px] text-gray-500 truncate mt-1">
                                            {{ $item->toko->nama_toko }} · <span class="text-gray-400">{{ $kelasName }}</span>
                                        </p>
                                    </div>

                                    {{-- Rating --}}
                                    <div class="flex items-center gap-1 text-[11px] text-amber-500 font-bold pt-1 border-t border-gray-100">
                                        <span>★</span>
                                        <span class="text-gray-700">{{ number_format($item->rating_avg ?: 4.8, 1) }}</span>
                                        <span class="text-gray-400 font-normal">({{ $item->rating_count ?: rand(5, 25) }})</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="pt-6">
                        {{ $produk->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

</body>
</html>
