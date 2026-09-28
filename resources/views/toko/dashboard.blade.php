@extends('toko.layouts.app')
@section('title', 'Dashboard Toko')
@section('content')
<div class="space-y-5">

    {{-- Hero Saldo Wallet --}}
    <div class="rounded-2xl p-6 text-white relative overflow-hidden"
         style="background: linear-gradient(135deg, #F97316 0%, #EA580C 60%, #C2410C 100%);">
        {{-- Decorative circles --}}
        <div class="absolute -top-6 -right-6 w-36 h-36 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-8 -right-2 w-24 h-24 rounded-full bg-white/10"></div>
        <div class="absolute top-4 right-24 w-12 h-12 rounded-full bg-white/10"></div>

        <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
            <div>
                <p class="text-xs font-semibold text-orange-200 uppercase tracking-wider">Saldo wallet kamu</p>
                <p class="text-4xl font-black mt-1.5 tracking-tight">
                    Rp {{ number_format($stats['wallet_saldo'], 0, ',', '.') }}
                </p>
                <p class="text-sm text-orange-200 mt-1">
                    Omzet bulan ini · Rp {{ number_format($stats['omzet_bulan_ini'], 0, ',', '.') }}
                </p>
            </div>

            {{-- Quick stats inside hero --}}
            <div class="flex items-center gap-4 sm:gap-6 flex-shrink-0">
                <div class="text-center">
                    <p class="text-2xl font-black">{{ $stats['total_terjual'] ?? '-' }}</p>
                    <p class="text-xs text-orange-200 mt-0.5">Terjual</p>
                </div>
                <div class="w-px h-10 bg-white/20"></div>
                <div class="text-center">
                    <p class="text-2xl font-black flex items-center justify-center gap-1">
                        {{ number_format($stats['avg_rating'] ?? 0, 1) }}
                        <span class="text-yellow-300 text-lg">★</span>
                    </p>
                    <p class="text-xs text-orange-200 mt-0.5">Rating</p>
                </div>
                <div class="w-px h-10 bg-white/20"></div>
                <div class="text-center">
                    <p class="text-2xl font-black">{{ $stats['total_produk'] }}</p>
                    <p class="text-xs text-orange-200 mt-0.5">Produk</p>
                </div>
            </div>
        </div>
    </div>

    {{-- 4 Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        {{-- Pesanan Baru --}}
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <p class="text-3xl font-black text-amber-500">{{ $stats['pesanan_masuk'] }}</p>
            <p class="text-xs font-semibold text-gray-500 mt-1">Pesanan baru</p>
            <p class="text-[11px] text-amber-500 font-medium mt-0.5">perlu dikonfirmasi</p>
        </div>

        {{-- Pesanan Diproses --}}
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-black text-emerald-500">{{ $stats['pesanan_diproses'] }}</p>
            <p class="text-xs font-semibold text-gray-500 mt-1">Pesanan selesai</p>
            <p class="text-[11px] text-emerald-500 font-medium mt-0.5">bulan ini</p>
        </div>

        {{-- Produk Aktif --}}
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <p class="text-3xl font-black text-blue-500">{{ $stats['total_produk'] }}</p>
            <p class="text-xs font-semibold text-gray-500 mt-1">Produk aktif</p>
            @if($stats['produk_pending'] > 0)
                <p class="text-[11px] text-amber-500 font-medium mt-0.5">{{ $stats['produk_pending'] }} pending approve</p>
            @else
                <p class="text-[11px] text-gray-400 font-medium mt-0.5">semua sudah aktif</p>
            @endif
        </div>

        {{-- Omzet Hari Ini --}}
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-9 h-9 rounded-xl bg-orange-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-lg font-black text-orange-500 leading-tight">
                Rp {{ number_format($stats['omzet_hari_ini'], 0, ',', '.') }}
            </p>
            <p class="text-xs font-semibold text-gray-500 mt-1">Omzet hari ini</p>
            <p class="text-[11px] text-gray-400 font-medium mt-0.5">dari pesanan selesai</p>
        </div>
    </div>

    {{-- Alert Produk Pending --}}
    @if($stats['produk_pending'] > 0)
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl text-sm text-amber-700 flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                <span class="text-base">📦</span>
            </div>
            <div>
                Ada <strong>{{ $stats['produk_pending'] }} produk</strong> yang sedang menunggu persetujuan admin.
                <a href="{{ route('toko.produk.index') }}" class="ml-1 underline font-semibold">Lihat produk →</a>
            </div>
        </div>
    @endif

    {{-- Konten bawah: Pesanan Terbaru + Info Toko --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Pesanan Terbaru --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800">Pesanan terbaru</h3>
                <a href="{{ route('toko.pesanan.index') }}"
                   class="text-xs font-semibold text-orange-500 hover:text-orange-600 transition">
                    Lihat semua →
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($pesananTerbaru as $pesanan)
                    <a href="{{ route('toko.pesanan.show', $pesanan) }}"
                       class="flex items-center justify-between px-5 py-3.5 hover:bg-orange-50/50 transition group">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800 group-hover:text-orange-600 transition">
                                #{{ $pesanan->kode_pesanan }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5 truncate">
                                {{ $pesanan->nama_pembeli ?? ($pesanan->pembeli->name ?? 'Guest') }}
                                · {{ $pesanan->metode_bayar ?? 'Cash' }} · {{ $pesanan->metode_kirim ?? 'COD' }}
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            <p class="text-sm font-bold text-gray-800">
                                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                            </p>
                            <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-full mt-0.5
                                {{ $pesanan->status === 'pending' ? 'bg-amber-100 text-amber-700' :
                                   ($pesanan->status === 'dikonfirmasi' ? 'bg-blue-100 text-blue-700' :
                                   ($pesanan->status === 'selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600')) }}">
                                {{ ucfirst($pesanan->status) }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="px-5 py-10 text-center">
                        <div class="text-4xl mb-3">🛒</div>
                        <p class="text-sm text-gray-400">Belum ada pesanan masuk</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Info Toko --}}
        <div class="space-y-4">
            {{-- Card Info Toko --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-4 text-sm">Info toko</h3>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-14 h-14 rounded-2xl overflow-hidden bg-orange-50 border-2 border-orange-100 flex-shrink-0">
                        @if($toko->logo)
                            <img src="{{ $toko->logo_url }}" class="w-full h-full object-cover" alt="">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-2xl">🏪</div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-sm text-gray-800 truncate">{{ $toko->nama_toko }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ auth()->user()->name }}</p>
                        @if($toko->kelas)
                            <p class="text-xs text-orange-500 font-semibold mt-0.5">{{ $toko->kelas }}</p>
                        @endif
                    </div>
                </div>
                <p class="text-xs text-gray-500 line-clamp-3">
                    {{ $toko->deskripsi ?? 'Belum ada deskripsi toko.' }}
                </p>
                <a href="{{ route('toko.profil.edit') }}"
                   class="mt-4 w-full flex items-center justify-center gap-2 py-2 px-4 text-xs font-semibold rounded-xl border-2 border-orange-200 text-orange-500 hover:bg-orange-50 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Edit profil toko
                </a>
            </div>

            {{-- Quick Links --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-bold text-slate-800 mb-3 text-sm">Aksi cepat</h3>
                <div class="space-y-2">
                    <a href="{{ route('toko.produk.create') }}"
                       class="flex items-center gap-3 p-3 rounded-xl bg-orange-50 text-orange-600 hover:bg-orange-100 transition text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah produk baru
                    </a>
                    <a href="{{ route('toko.pesanan.index', ['status' => 'pending']) }}"
                       class="flex items-center gap-3 p-3 rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-100 transition text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Lihat pesanan pending
                    </a>
                    <a href="{{ route('toko.wallet.index') }}"
                       class="flex items-center gap-3 p-3 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Cek saldo wallet
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
