@extends('toko.layouts.app')

@section('title', 'Detail Produk — ' . $produk->nama_produk)

@section('content')
<div class="space-y-6">
    {{-- Header Navigation & Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('toko.produk.index') }}" class="p-2 rounded-xl bg-white border border-gray-200 text-gray-500 hover:text-gray-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $produk->nama_produk }}</h1>
                    @if($produk->isApproved())
                        <span class="badge-success">Approved</span>
                    @elseif($produk->isPending())
                        <span class="badge-warning">Pending Approval</span>
                    @else
                        <span class="badge-danger">Rejected</span>
                    @endif
                </div>
                <p class="text-sm text-gray-500">Dibuat pada {{ $produk->created_at ? $produk->created_at->translatedFormat('d F Y H:i') : '-' }}</p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('toko.produk.edit', $produk) }}" class="btn-primary flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Produk
            </a>
            <form method="POST" action="{{ route('toko.produk.destroy', $produk) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-secondary text-red-600 hover:bg-red-50 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    {{-- Status Banner Notice --}}
    @if($produk->isRejected())
        <div class="card p-5 bg-red-50 border-2 border-red-200 text-red-900 space-y-3">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center text-xl shrink-0">
                    ⚠️
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-base text-red-800">Produk Ditolak oleh Pihak Sekolah / Admin</h3>
                    <div class="mt-2 p-3 bg-white/80 rounded-xl border border-red-200">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Alasan Penolakan dari Admin:</p>
                        <p class="text-sm font-medium text-red-700 leading-relaxed">{{ $produk->alasan_reject ?? 'Tidak ada catatan alasan penolakan.' }}</p>
                    </div>
                    <p class="text-xs text-red-600 mt-2">
                        💡 <strong>Saran:</strong> Silakan klik tombol <strong>"Edit Produk"</strong> di atas untuk memperbaiki foto, harga, atau deskripsi sesuai catatan penolakan admin.
                    </p>
                </div>
            </div>
        </div>
    @elseif($produk->isApproved())
        <div class="card p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between text-xs sm:text-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Produk telah <strong>Disetujui Sekolah</strong> dan aktif tampil di katalog publik.</span>
            </div>
            @if($produk->approved_at)
                <span class="text-emerald-700 text-xs hidden sm:inline">Disetujui: {{ $produk->approved_at->translatedFormat('d F Y H:i') }}</span>
            @endif
        </div>
    @elseif($produk->isPending())
        <div class="card p-4 bg-amber-50 border border-amber-200 text-amber-800 flex items-center gap-2 text-xs sm:text-sm">
            <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Produk sedang <strong>Menunggu Persetujuan Admin Sekolah</strong>. Produk akan otomatis tampil di katalog setelah disetujui.</span>
        </div>
    @endif

    {{-- Main Grid Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Column: Photos & Description --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Photos --}}
            <div class="card p-6 space-y-4">
                <h3 class="font-bold text-gray-800 text-base border-b border-gray-100 pb-3">Foto Produk</h3>
                @php
                    $fotos = $produk->foto ?? [];
                @endphp
                @if(!empty($fotos))
                    <div class="space-y-3">
                        <div class="w-full h-80 rounded-2xl bg-gray-50 border border-gray-100 overflow-hidden flex items-center justify-center">
                            <img id="toko-preview" src="{{ asset('storage/' . $fotos[0]) }}" class="w-full h-full object-contain" alt="{{ $produk->nama_produk }}">
                        </div>

                        @if(count($fotos) > 1)
                            <div class="flex items-center gap-3 overflow-x-auto pb-2">
                                @foreach($fotos as $idx => $fotoPath)
                                    <button type="button" onclick="document.getElementById('toko-preview').src = '{{ asset('storage/' . $fotoPath) }}'" class="w-16 h-16 rounded-xl border border-gray-200 overflow-hidden hover:border-blue-500 shrink-0 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <img src="{{ asset('storage/' . $fotoPath) }}" class="w-full h-full object-cover" alt="">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="w-full h-64 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col items-center justify-center text-gray-400">
                        <span class="text-4xl mb-2">{{ $produk->kategori->icon ?? '📦' }}</span>
                        <p class="text-xs">Belum ada foto produk diunggah.</p>
                    </div>
                @endif
            </div>

            {{-- Deskripsi Produk --}}
            <div class="card p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="font-bold text-gray-800 text-base">Deskripsi Produk</h3>
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full flex items-center gap-1">
                        <span>{{ $produk->kategori->icon ?? '🏷️' }}</span>
                        <span>{{ $produk->kategori->nama_kategori ?? 'Umum' }}</span>
                    </span>
                </div>
                <div class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">
                    {{ $produk->deskripsi ?? 'Belum ada deskripsi untuk produk ini.' }}
                </div>
            </div>

        </div>

        {{-- Right Column: Pricing & Stock --}}
        <div class="space-y-6">

            {{-- Price Breakdown Card --}}
            <div class="card p-6 space-y-4">
                <h3 class="font-bold text-gray-800 text-base border-b border-gray-100 pb-3">Rincian Harga & Stok</h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Harga Pokok (Modal/Bahan)</span>
                        <span class="font-semibold text-gray-800">Rp {{ number_format($produk->harga_pokok, 0, ',', '.') }}</span>
                    </div>

                    @php
                        $margin = max(0, $produk->harga_final - $produk->harga_pokok);
                    @endphp
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-gray-500">Estimasi Margin Keuntungan</span>
                        <span class="font-semibold text-emerald-600">+Rp {{ number_format($margin, 0, ',', '.') }}</span>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex justify-between items-baseline">
                        <span class="font-bold text-gray-800">Harga Jual Final</span>
                        <span class="text-xl font-extrabold text-gray-900">Rp {{ number_format($produk->harga_final, 0, ',', '.') }}</span>
                    </div>

                    <div class="pt-2 flex justify-between items-center text-xs text-gray-500">
                        <span>Stok Produk</span>
                        <span class="font-bold text-gray-800">{{ $produk->stok }} {{ $produk->satuan ?? 'pcs' }}</span>
                    </div>
                </div>
            </div>

            {{-- Reseller Program Card --}}
            @if($produk->is_reseller_product)
                <div class="card p-5 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 space-y-2">
                    <p class="font-bold text-sm text-blue-900 flex items-center gap-1">
                        <span>🤝</span> Program Reseller Aktif
                    </p>
                    <p class="text-xs text-blue-800">Harga untuk Reseller Siswa Lain: <strong>Rp {{ number_format($produk->harga_reseller, 0, ',', '.') }}</strong></p>
                </div>
            @endif

        </div>

    </div>
</div>
@endsection
