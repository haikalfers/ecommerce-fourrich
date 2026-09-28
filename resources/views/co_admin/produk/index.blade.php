@extends('co_admin.layouts.app')

@section('title', 'Monitoring Produk Siswa Binaan')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Monitoring Produk Siswa Binaan</h1>
        <p class="text-sm text-slate-500 mt-1">Pantau status persetujuan, harga, dan catatan penolakan produk milik toko siswa binaan Anda.</p>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
        <form method="GET" action="{{ route('co-admin.produk.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama produk, toko, atau siswa..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#F97316]/40 focus:border-[#F97316] transition placeholder-slate-400">
            </div>
            <div class="w-full sm:w-48">
                <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-[#F97316]/40 focus:border-[#F97316] transition" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved (Disetujui)</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-[#F97316] hover:bg-orange-600 text-white font-semibold text-sm rounded-xl transition shadow-xs">Cari</button>
        </form>
    </div>

    {{-- Produk Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($produks as $p)
            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 space-y-3 flex flex-col justify-between shadow-xs hover:border-slate-300 transition">
                <div class="space-y-3">
                    <a href="{{ route('co-admin.produk.show', $p) }}" class="block relative aspect-square rounded-xl bg-slate-100 overflow-hidden group">
                        @if(!empty($p->foto_urls))
                            <img src="{{ $p->foto_urls[0] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-200" alt="">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl">
                                {{ $p->kategori->icon ?? '📦' }}
                            </div>
                        @endif
                        <span class="absolute top-2 right-2">
                            @if($p->isApproved())
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full shadow-xs">Approved</span>
                            @elseif($p->isPending())
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full shadow-xs">Pending</span>
                            @else
                                <span class="px-2.5 py-1 bg-rose-100 text-rose-800 text-xs font-bold rounded-full shadow-xs">Rejected</span>
                            @endif
                        </span>
                    </a>

                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-400">
                            <span class="font-semibold text-indigo-600 uppercase text-[10px] tracking-wider">{{ $p->kategori->nama_kategori }}</span>
                            <span>{{ $p->toko->user->kelas->nama_kelas ?? '' }}</span>
                        </div>
                        <a href="{{ route('co-admin.produk.show', $p) }}" class="block font-bold text-slate-900 text-base line-clamp-1 mt-0.5 hover:text-[#F97316] transition">
                            {{ $p->nama_produk }}
                        </a>
                        <p class="text-xs text-slate-500 mt-0.5">Toko: <strong class="text-slate-800">{{ $p->toko->nama_toko }}</strong> ({{ $p->toko->user->name }})</p>

                        @if($p->isRejected())
                            <div class="mt-2.5 p-2.5 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-800 space-y-1">
                                <p class="font-bold flex items-center gap-1 text-rose-900">
                                    <span>⚠️</span> Catatan Penolakan Admin:
                                </p>
                                <p class="line-clamp-2 text-[11px] text-rose-700">{{ $p->alasan_reject ?? 'Tidak ada alasan khusus.' }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-slate-400 font-medium">Harga Final</p>
                        <p class="font-bold text-slate-900 text-sm">Rp {{ number_format($p->harga_final, 0, ',', '.') }}</p>
                    </div>
                    <a href="{{ route('co-admin.produk.show', $p) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition">
                        Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white border border-slate-200/80 rounded-2xl py-12 text-center text-xs text-slate-400">
                Tidak ada produk ditemukan untuk kriteria ini.
            </div>
        @endforelse
    </div>

    @if($produks->hasPages())
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4">
            {{ $produks->links() }}
        </div>
    @endif
</div>
@endsection
