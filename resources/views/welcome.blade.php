<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMEKIV Store — Platform Jual Beli Siswa SMKN 4 Surabaya</title>
    <meta name="description" content="Belanja produk karya siswa SMKN 4 Surabaya. Makanan, minuman, kerajinan tangan, fashion, dan banyak lagi!">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans antialiased">

    {{-- Navbar --}}
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-2xl">🛍️</span>
                <span class="font-bold text-primary-700 text-lg">SMEKIV Store</span>
            </div>
            <nav class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary text-sm">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary text-sm">Masuk</a>
                    <a href="{{ route('katalog.index') }}" class="btn-primary text-sm">Lihat Katalog</a>
                @endauth
            </nav>
        </div>
    </header>

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-primary-950 via-primary-800 to-accent-700 text-white">
        <div class="absolute inset-0 opacity-10"
             style="background-image: radial-gradient(circle at 20% 50%, white 1px, transparent 1px), radial-gradient(circle at 80% 20%, white 1px, transparent 1px); background-size: 60px 60px;">
        </div>
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-24 lg:py-36 text-center">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-full px-4 py-1.5 text-sm font-medium mb-6 border border-white/20">
                🎓 Platform resmi SMKN 4 Surabaya
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight mb-6 leading-tight">
                Belanja Produk<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">Karya Siswa</span>
            </h1>
            <p class="text-lg text-primary-200 max-w-xl mx-auto mb-10">
                Temukan produk unggulan dari toko-toko milik siswa SMKN 4 Surabaya. Dukung wirausaha muda!
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('katalog.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-primary-700 font-bold rounded-2xl hover:bg-primary-50 transition-all duration-200 hover:shadow-xl hover:shadow-primary-900/30 active:scale-[0.98]">
                    🛒 Belanja Sekarang
                </a>
                <a href="{{ route('register.toko') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white/10 text-white font-bold rounded-2xl border border-white/30 hover:bg-white/20 transition-all duration-200 active:scale-[0.98]">
                    🏪 Buka Toko
                </a>
            </div>
        </div>
        {{-- Wave --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 80L1440 80L1440 40C1200 80 960 0 720 20C480 40 240 80 0 40L0 80Z" fill="white"/>
            </svg>
        </div>
    </section>

    {{-- Features --}}
    <section class="py-20 max-w-6xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-14">
            <h2 class="text-3xl font-bold text-gray-900 mb-3">Kenapa SMEKIV Store?</h2>
            <p class="text-gray-500 max-w-lg mx-auto">Platform belanja yang menghubungkan pembeli dengan wirausahawan muda berbakat.</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $features = [
                ['icon' => '🏪', 'title' => 'Toko Siswa Terpercaya', 'desc' => 'Semua toko telah diverifikasi oleh admin sekolah sebelum berjualan.'],
                ['icon' => '🛡️', 'title' => 'Transaksi Aman', 'desc' => 'Pembayaran diproses dengan aman melalui sistem wallet terintegrasi.'],
                ['icon' => '🚀', 'title' => 'Cepat & Mudah', 'desc' => 'Proses pemesanan yang simpel. Pesan, bayar, dan ambil produkmu.'],
                ['icon' => '🏷️', 'title' => 'Beragam Kategori', 'desc' => 'Dari makanan, fashion, hingga kerajinan tangan — semuanya ada!'],
                ['icon' => '📊', 'title' => 'Dashboard Penjual', 'desc' => 'Kelola produk, pesanan, dan penghasilan dari satu dashboard.'],
                ['icon' => '🎓', 'title' => 'Mendukung Pendidikan', 'desc' => 'Komisi penjualan berkontribusi pada kas sekolah SMKN 4 Surabaya.'],
            ];
            @endphp
            @foreach($features as $f)
            <div class="card card-hover group cursor-default">
                <div class="text-4xl mb-4">{{ $f['icon'] }}</div>
                <h3 class="font-semibold text-gray-800 mb-2 group-hover:text-primary-600 transition-colors">{{ $f['title'] }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-gradient-to-br from-primary-50 to-accent-50 border-y border-primary-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-20 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Siap untuk mulai?</h2>
            <p class="text-gray-500 mb-8">Daftar sekarang dan bergabung dengan komunitas wirausaha SMKN 4 Surabaya.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('register.pembeli') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition-all duration-200 hover:shadow-lg hover:shadow-primary-200 active:scale-[0.98]">
                    🛒 Daftar sebagai Pembeli
                </a>
                <a href="{{ route('register.toko') }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white hover:bg-gray-50 text-gray-800 font-semibold rounded-xl border border-gray-200 hover:border-primary-300 transition-all duration-200 active:scale-[0.98]">
                    🏪 Buka Toko Saya
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-400 py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 text-center">
            <div class="flex items-center justify-center gap-2 mb-3">
                <span class="text-2xl">🛍️</span>
                <span class="font-bold text-white text-lg">SMEKIV Store</span>
            </div>
            <p class="text-sm">Platform Jual Beli Siswa SMKN 4 Surabaya — KIK E-Commerce</p>
            <p class="text-xs mt-2">© {{ date('Y') }} SMKN 4 Surabaya. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
