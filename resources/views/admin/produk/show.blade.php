@extends('admin.layouts.app')

@section('title', 'Detail Approval Produk — ' . $produk->nama_produk)

@section('content')
<div class="space-y-6">
    {{-- Header Navigation & Quick Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.produk.index') }}" class="p-2 rounded-xl bg-white border border-gray-200 text-gray-500 hover:text-gray-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $produk->nama_produk }}</h1>
                    @if($produk->isPending())
                        <span class="badge-warning">Pending Approval</span>
                    @elseif($produk->isApproved())
                        <span class="badge-success">Approved</span>
                    @else
                        <span class="badge-danger">Rejected</span>
                    @endif
                </div>
                <p class="text-sm text-gray-500">Diajukan oleh <a href="{{ route('admin.toko.show', $produk->toko) }}" class="font-medium text-blue-600 hover:underline">{{ $produk->toko->nama_toko }}</a> pada {{ $produk->created_at ? $produk->created_at->translatedFormat('d F Y H:i') : '-' }}</p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2">
            @if($produk->isPending() || $produk->isRejected())
                <form method="POST" action="{{ route('admin.produk.approve', $produk) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-primary flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Approve (Setujui)
                    </button>
                </form>
            @endif

            @if($produk->isPending() || $produk->isApproved())
                <button type="button" onclick="document.getElementById('reject-modal').classList.remove('hidden')" class="btn-danger flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Tolak Produk
                </button>
            @endif
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Column: Photos & Details --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Photos & Gallery --}}
            <div class="card p-6 space-y-4">
                <h3 class="font-bold text-gray-800 text-base border-b border-gray-100 pb-3">Foto Produk</h3>
                @php
                    $fotos = $produk->foto ?? [];
                @endphp
                @if(!empty($fotos))
                    <div class="space-y-3">
                        <div class="w-full h-80 rounded-2xl bg-gray-50 border border-gray-100 overflow-hidden flex items-center justify-center">
                            <img id="main-preview" src="{{ asset('storage/' . $fotos[0]) }}" class="w-full h-full object-contain" alt="{{ $produk->nama_produk }}">
                        </div>

                        @if(count($fotos) > 1)
                            <div class="flex items-center gap-3 overflow-x-auto pb-2">
                                @foreach($fotos as $idx => $fotoPath)
                                    <button type="button" onclick="document.getElementById('main-preview').src = '{{ asset('storage/' . $fotoPath) }}'" class="w-16 h-16 rounded-xl border border-gray-200 overflow-hidden hover:border-blue-500 shrink-0 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <img src="{{ asset('storage/' . $fotoPath) }}" class="w-full h-full object-cover" alt="">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="w-full h-64 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col items-center justify-center text-gray-400">
                        <span class="text-4xl mb-2">{{ $produk->kategori->icon ?? '📦' }}</span>
                        <p class="text-xs">Tidak ada foto produk yang diunggah.</p>
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
                <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $produk->deskripsi ?? 'Tidak ada deskripsi rinci untuk produk ini.' }}
                </div>
            </div>

            {{-- Reseller Product Info (if applicable) --}}
            @if($produk->is_reseller_product)
                <div class="card p-6 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 border border-blue-100 space-y-2">
                    <div class="flex items-center gap-2 text-blue-800 font-bold text-sm">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>Produk Reseller (Program Kemitraan)</span>
                    </div>
                    <p class="text-xs text-blue-700">Produk ini dapat dijual kembali oleh siswa lain dengan harga reseller: <strong class="text-blue-900 font-bold">Rp {{ number_format($produk->harga_reseller, 0, ',', '.') }}</strong></p>
                </div>
            @endif

        </div>

        {{-- Right Column: Price Breakdown, Toko Info & Status --}}
        <div class="space-y-6">

            {{-- Price Breakdown Card --}}
            <div class="card p-6 space-y-4">
                <h3 class="font-bold text-gray-800 text-base border-b border-gray-100 pb-3">Rincian Harga & Margin</h3>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Harga Pokok Toko</span>
                        <span class="font-semibold text-gray-800">Rp {{ number_format($produk->harga_pokok, 0, ',', '.') }}</span>
                    </div>

                    @php
                        $margin = max(0, $produk->harga_final - $produk->harga_pokok);
                    @endphp
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-gray-500">Estimasi Margin / Selisih</span>
                        <span class="font-semibold text-indigo-600">+Rp {{ number_format($margin, 0, ',', '.') }}</span>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex justify-between items-baseline">
                        <span class="font-bold text-gray-800">Harga Final (Katalog)</span>
                        <span class="text-xl font-extrabold text-emerald-600">Rp {{ number_format($produk->harga_final, 0, ',', '.') }}</span>
                    </div>

                    <div class="pt-2 flex justify-between items-center text-xs text-gray-500">
                        <span>Stok Tersedia</span>
                        <span class="font-bold text-gray-800">{{ $produk->stok }} {{ $produk->satuan ?? 'pcs' }}</span>
                    </div>
                </div>
            </div>

            {{-- Toko & Owner Info Card --}}
            <div class="card p-6 space-y-4">
                <h3 class="font-bold text-gray-800 text-base border-b border-gray-100 pb-3">Informasi Toko Pengaju</h3>

                <div class="flex items-center gap-3">
                    <img src="{{ $produk->toko->logo_url }}" class="w-12 h-12 rounded-xl object-cover border border-gray-100" alt="">
                    <div class="min-w-0 flex-1">
                        <a href="{{ route('admin.toko.show', $produk->toko) }}" class="font-bold text-gray-800 hover:text-blue-600 text-sm truncate block">
                            {{ $produk->toko->nama_toko }}
                        </a>
                        <p class="text-xs text-gray-500">Status Toko: <span class="font-semibold text-emerald-600">{{ ucfirst($produk->toko->status) }}</span></p>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Pemilik (Siswa)</span>
                        <span class="font-semibold text-gray-800">{{ $produk->toko->user->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Kelas</span>
                        <span class="font-medium text-blue-600">{{ $produk->toko->user->kelas->nama_kelas ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Guru Pembimbing</span>
                        <span class="font-semibold text-gray-800">{{ $produk->toko->coAdmin->name ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Status Log / Reason --}}
            @if($produk->isApproved())
                <div class="card p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 space-y-1 text-xs">
                    <p class="font-bold flex items-center gap-1">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Produk Telah Disetujui</span>
                    </p>
                    <p class="text-emerald-700">Disetujui oleh: <strong>{{ $produk->approvedBy->name ?? 'Admin' }}</strong> pada {{ $produk->approved_at ? $produk->approved_at->translatedFormat('d F Y H:i') : '-' }}</p>
                </div>
            @elseif($produk->isRejected())
                <div class="card p-4 bg-red-50 border border-red-200 text-red-800 space-y-2 text-xs">
                    <p class="font-bold flex items-center gap-1 text-red-900">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Produk Ditolak</span>
                    </p>
                    <p class="text-red-700"><strong>Alasan Penolakan:</strong> {{ $produk->alasan_reject ?? 'Tidak ada alasan khusus.' }}</p>
                </div>
            @endif

        </div>

    </div>
</div>

{{-- Reject Modal --}}
<div id="reject-modal" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4 text-left [&:not(.hidden)]:flex">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full space-y-4 shadow-xl">
        <h3 class="font-bold text-lg text-gray-800">Tolak Produk: {{ $produk->nama_produk }}</h3>
        <form method="POST" action="{{ route('admin.produk.reject', $produk) }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Alasan Penolakan</label>
                <textarea name="alasan_reject" required rows="4" class="input-field w-full" placeholder="Contoh: Foto produk tidak sesuai, penjelasan deskripsi kurang jelas..."></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')" class="btn-secondary text-xs">Batal</button>
                <button type="submit" class="btn-primary bg-red-600 hover:bg-red-700 text-xs">Kirim & Tolak Produk</button>
            </div>
        </form>
    </div>
</div>
@endsection
