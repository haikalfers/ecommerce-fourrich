@extends('co_admin.layouts.app')

@section('title', 'Detail Produk Siswa — ' . $produk->nama_produk)

@section('content')
<div class="space-y-6">
    {{-- Header Navigation --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('co-admin.produk.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-slate-800 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $produk->nama_produk }}</h1>
                @if($produk->isApproved())
                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">Approved</span>
                @elseif($produk->isPending())
                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">Pending</span>
                @else
                    <span class="px-2.5 py-1 bg-rose-100 text-rose-800 text-xs font-bold rounded-full">Rejected</span>
                @endif
            </div>
            <p class="text-sm text-slate-500">Toko: <a href="{{ route('co-admin.toko.show', $produk->toko) }}" class="font-bold text-slate-800 hover:text-[#F97316] underline">{{ $produk->toko->nama_toko }}</a> ({{ $produk->toko->user->name ?? '' }} - {{ $produk->toko->user->kelas->nama_kelas ?? '' }})</p>
        </div>
    </div>

    {{-- Rejection Warning Box for Teacher --}}
    @if($produk->isRejected())
        <div class="bg-rose-50 border-2 border-rose-200 rounded-2xl p-5 space-y-3 text-rose-900 shadow-xs">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl shrink-0">
                    ⚠️
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-base text-rose-900">Catatan Penolakan dari Admin Sekolah</h3>
                    <div class="mt-2 p-3.5 bg-white/80 rounded-xl border border-rose-200">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Alasan Penolakan:</p>
                        <p class="text-sm font-semibold text-rose-800 leading-relaxed">{{ $produk->alasan_reject ?? 'Tidak ada penjelasan khusus.' }}</p>
                    </div>
                    <p class="text-xs text-rose-700 mt-2">
                        💡 <strong>Petunjuk untuk Guru Pembimbing:</strong> Silakan bimbing siswa untuk memperbarui produknya melalui akun toko milik siswa sesuai dengan catatan penolakan admin sekolah di atas.
                    </p>
                </div>
            </div>
        </div>
    @elseif($produk->isApproved())
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 text-emerald-800 flex items-center justify-between text-xs sm:text-sm shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Produk telah <strong>Disetujui Admin Sekolah</strong> dan aktif tampil di katalog publik.</span>
            </div>
            @if($produk->approved_at)
                <span class="text-emerald-700 text-xs hidden sm:inline">Disetujui: {{ $produk->approved_at->translatedFormat('d F Y H:i') }}</span>
            @endif
        </div>
    @elseif($produk->isPending())
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 text-amber-800 flex items-center gap-2 text-xs sm:text-sm shadow-xs">
            <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Produk saat ini sedang <strong>Menunggu Persetujuan Admin Sekolah</strong>.</span>
        </div>
    @endif

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Column: Photos & Description --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Photos --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-4 shadow-xs">
                <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3">Foto Produk</h3>
                @php
                    $fotos = $produk->foto ?? [];
                @endphp
                @if(!empty($fotos))
                    <div class="space-y-3">
                        <div class="w-full h-80 rounded-2xl bg-slate-50 border border-slate-100 overflow-hidden flex items-center justify-center">
                            <img id="co-preview" src="{{ asset('storage/' . $fotos[0]) }}" class="w-full h-full object-contain" alt="{{ $produk->nama_produk }}">
                        </div>

                        @if(count($fotos) > 1)
                            <div class="flex items-center gap-3 overflow-x-auto pb-2">
                                @foreach($fotos as $idx => $fotoPath)
                                    <button type="button" onclick="document.getElementById('co-preview').src = '{{ asset('storage/' . $fotoPath) }}'" class="w-16 h-16 rounded-xl border border-slate-200 overflow-hidden hover:border-[#F97316] shrink-0 focus:outline-none focus:ring-2 focus:ring-[#F97316]">
                                        <img src="{{ asset('storage/' . $fotoPath) }}" class="w-full h-full object-cover" alt="">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="w-full h-64 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center text-slate-400">
                        <span class="text-4xl mb-2">{{ $produk->kategori->icon ?? '📦' }}</span>
                        <p class="text-xs">Belum ada foto produk diunggah.</p>
                    </div>
                @endif
            </div>

            {{-- Deskripsi --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-4 shadow-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base">Deskripsi Produk</h3>
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full flex items-center gap-1">
                        <span>{{ $produk->kategori->icon ?? '🏷️' }}</span>
                        <span>{{ $produk->kategori->nama_kategori ?? 'Umum' }}</span>
                    </span>
                </div>
                <div class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">
                    {{ $produk->deskripsi ?? 'Belum ada deskripsi untuk produk ini.' }}
                </div>
            </div>

        </div>

        {{-- Right Column: Pricing & Owner Info --}}
        <div class="space-y-6">

            {{-- Price Breakdown --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-4 shadow-xs">
                <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3">Rincian Harga & Stok</h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Harga Pokok Toko</span>
                        <span class="font-semibold text-slate-800">Rp {{ number_format($produk->harga_pokok, 0, ',', '.') }}</span>
                    </div>

                    @php
                        $margin = max(0, $produk->harga_final - $produk->harga_pokok);
                    @endphp
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500">Estimasi Margin Keuntungan</span>
                        <span class="font-semibold text-emerald-600">+Rp {{ number_format($margin, 0, ',', '.') }}</span>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                        <span class="font-bold text-slate-900">Harga Final Jual</span>
                        <span class="text-xl font-extrabold text-[#F97316]">Rp {{ number_format($produk->harga_final, 0, ',', '.') }}</span>
                    </div>

                    <div class="pt-2 flex justify-between items-center text-xs text-slate-500">
                        <span>Stok Produk</span>
                        <span class="font-bold text-slate-800">{{ $produk->stok }} {{ $produk->satuan ?? 'pcs' }}</span>
                    </div>
                </div>
            </div>

            {{-- Owner Info Card --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-4 shadow-xs">
                <h3 class="font-bold text-slate-900 text-base border-b border-slate-100 pb-3">Informasi Siswa Pemilik</h3>

                <div class="flex items-center gap-3">
                    <img src="{{ $produk->toko->user->avatar_url }}" class="w-12 h-12 rounded-full object-cover border border-slate-100" alt="">
                    <div class="min-w-0 flex-1">
                        <p class="font-bold text-slate-900 text-sm truncate">{{ $produk->toko->user->name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ $produk->toko->user->email }}</p>
                        <p class="text-xs text-indigo-600 font-semibold mt-0.5">Kelas: {{ $produk->toko->user->kelas->nama_kelas ?? '-' }}</p>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-between items-center text-xs">
                    <span class="text-slate-500">Toko Siswa</span>
                    <a href="{{ route('co-admin.toko.show', $produk->toko) }}" class="font-bold text-slate-900 hover:text-[#F97316] underline">
                        {{ $produk->toko->nama_toko }} &rarr;
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
