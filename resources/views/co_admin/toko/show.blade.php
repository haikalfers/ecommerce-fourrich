@extends('co_admin.layouts.app')

@section('title', 'Detail Toko Binaan — ' . $toko->nama_toko)

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('co-admin.toko.index') }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-slate-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $toko->nama_toko }}</h1>
            <p class="text-sm text-slate-500">Pemilik: {{ $toko->user->name }} ({{ $toko->user->kelas->nama_kelas ?? '-' }})</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-4 shadow-xs">
            <img src="{{ $toko->logo_url }}" class="w-24 h-24 rounded-2xl object-cover mx-auto border border-slate-100" alt="">
            <div class="text-center">
                <h3 class="font-bold text-slate-900 text-lg">{{ $toko->nama_toko }}</h3>
                <p class="text-xs text-slate-400 mt-1">{{ $toko->deskripsi ?? 'Belum ada deskripsi.' }}</p>
            </div>
            <div class="pt-4 border-t border-slate-100 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Status</span>
                    <span class="font-bold {{ $toko->isAktif() ? 'text-emerald-600' : 'text-amber-500' }}">{{ ucfirst($toko->status) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Saldo Wallet</span>
                    <span class="font-bold text-slate-900">Rp {{ number_format($toko->wallet_saldo, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl md:col-span-2 p-6 space-y-4 shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-slate-900 text-base">Daftar Produk Toko</h3>
                <span class="text-xs text-slate-400 font-medium">{{ $toko->produk->count() }} Produk</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($toko->produk as $p)
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-100 text-[#F97316] rounded-lg flex items-center justify-center text-lg shrink-0">
                                {{ $p->kategori->icon ?? '📦' }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('co-admin.produk.show', $p) }}" class="font-semibold text-slate-900 text-sm truncate block hover:text-[#F97316] transition">
                                    {{ $p->nama_produk }}
                                </a>
                                <p class="text-xs font-bold text-emerald-600">Rp {{ number_format($p->harga_final, 0, ',', '.') }}</p>
                            </div>
                            <span class="text-[10px] px-2 py-0.5 font-bold rounded-full shrink-0 {{ $p->isApproved() ? 'bg-emerald-100 text-emerald-800' : ($p->isPending() ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </div>
                        @if($p->isRejected())
                            <div class="p-2 bg-rose-50 border border-rose-200 rounded-lg text-[11px] text-rose-800">
                                <p class="font-bold">⚠️ Ditolak Admin:</p>
                                <p class="line-clamp-2 text-[10px] text-rose-700 mt-0.5">{{ $p->alasan_reject ?? 'Tidak ada alasan khusus.' }}</p>
                            </div>
                        @endif
                        <div class="text-right">
                            <a href="{{ route('co-admin.produk.show', $p) }}" class="text-xs font-semibold text-blue-600 hover:underline">Detail Produk &rarr;</a>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-6 text-center col-span-full">Toko ini belum menambahkan produk.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
