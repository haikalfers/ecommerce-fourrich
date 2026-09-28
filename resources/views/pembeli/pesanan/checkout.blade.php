<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Pesanan — SMEKIV Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen pb-28">
    {{-- Header --}}
    <header class="sticky top-0 bg-white/90 backdrop-blur-md z-40 border-b border-gray-100 px-4 py-3">
        <div class="max-w-md mx-auto flex items-center justify-between">
            <a href="{{ route('pembeli.keranjang.index') }}" class="p-2 rounded-full hover:bg-gray-100 text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="font-bold text-gray-800 text-base">Checkout Pesanan</h1>
            <div class="w-10"></div>
        </div>
    </header>

    <main class="max-w-md mx-auto p-4 space-y-4">
        <form method="POST" action="{{ route('pembeli.pesanan.store') }}" class="space-y-4">
            @csrf

            {{-- Ringkasan Pesanan --}}
            <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm space-y-3">
                <h2 class="font-bold text-gray-800 text-sm">Ringkasan Produk</h2>
                <div class="space-y-2">
                    @foreach($items as $item)
                        @php $p = $item['produk']; @endphp
                        <div class="flex items-center justify-between text-xs py-1">
                            <span class="text-gray-700 font-medium">{{ $p->nama_produk }} <b class="text-gray-400">x{{ $item['jumlah'] }}</b></span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                    <span class="font-bold text-gray-700 text-xs">Total Pembayaran</span>
                    <span class="font-black text-indigo-600 text-base">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Form Pembeli / Guest --}}
            <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm space-y-4">
                <h2 class="font-bold text-gray-800 text-sm">Informasi Pemesan</h2>

                @guest
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Pemesan</label>
                        <input type="text" name="nama_guest" value="{{ old('nama_guest') }}" required class="input-field w-full text-xs" placeholder="Nama lengkap Anda">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">No. WhatsApp / HP</label>
                        <input type="text" name="hp_guest" value="{{ old('hp_guest') }}" required class="input-field w-full text-xs" placeholder="08123456789">
                    </div>
                @else
                    <div class="text-xs space-y-1">
                        <p class="text-gray-400">Nama: <b class="text-gray-800">{{ auth()->user()->name }}</b></p>
                        <p class="text-gray-400">Email: <b class="text-gray-800">{{ auth()->user()->email }}</b></p>
                    </div>
                @endguest
            </div>

            {{-- Metode Kirim & Bayar --}}
            <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm space-y-4">
                <h2 class="font-bold text-gray-800 text-sm">Pengiriman & Pembayaran</h2>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Metode Pengiriman</label>
                    <select name="metode_kirim" class="input-field w-full text-xs">
                        <option value="cod">COD (Bayar & Ambil di Toko Siswa)</option>
                        <option value="antar">Antar Langsung (Di Lingkungan Sekolah)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Metode Pembayaran</label>
                    <select name="metode_bayar" class="input-field w-full text-xs">
                        <option value="cash">Tunai / Cash saat Serah Terima</option>
                        <option value="qris">QRIS (Segera Hadir)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                    <textarea name="catatan" rows="2" class="input-field w-full text-xs" placeholder="Contoh: Titip di piket kelas XI RPL..."></textarea>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-indigo-600/30 hover:bg-indigo-700 transition">
                Konfirmasi & Buat Pesanan
            </button>
        </form>
    </main>
</body>
</html>
