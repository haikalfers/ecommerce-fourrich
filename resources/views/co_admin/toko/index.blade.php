@extends('co_admin.layouts.app')

@section('title', 'Toko Siswa Binaan')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Toko Siswa Binaan</h1>
        <p class="text-sm text-slate-500 mt-1">Daftar toko siswa yang diampu di bawah bimbingan Anda</p>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
        <form method="GET" action="{{ route('co-admin.toko.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama toko..." class="input-field w-full">
            </div>
            <div class="w-full sm:w-48">
                <select name="status" class="input-field w-full" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="suspend" {{ $status === 'suspend' ? 'selected' : '' }}>Suspend</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition">Cari</button>
        </form>
    </div>

    {{-- Toko Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tokos as $t)
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:border-slate-300 transition space-y-4">
                <div class="flex items-center gap-3">
                    <img src="{{ $t->logo_url }}" class="w-12 h-12 rounded-2xl object-cover border border-slate-100" alt="">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-slate-900 truncate text-base">{{ $t->nama_toko }}</h3>
                        <p class="text-xs text-slate-500">{{ $t->user->name }} ({{ $t->user->kelas->nama_kelas ?? '-' }})</p>
                    </div>
                    @if($t->isAktif())
                        <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full">Aktif</span>
                    @elseif($t->isPending())
                        <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full">Pending</span>
                    @else
                        <span class="px-2.5 py-0.5 bg-rose-100 text-rose-800 text-xs font-semibold rounded-full">Suspend</span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-2 pt-3 border-t border-slate-100 text-xs">
                    <div>
                        <p class="text-slate-400">Total Produk</p>
                        <p class="font-bold text-slate-800 mt-0.5">{{ $t->produk->count() }} produk</p>
                    </div>
                    <div>
                        <p class="text-slate-400">Saldo Wallet</p>
                        <p class="font-bold text-emerald-600 mt-0.5">Rp {{ number_format($t->wallet_saldo, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <a href="{{ route('co-admin.toko.show', $t) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-xs transition">Detail Toko</a>
                    @if($t->isAktif())
                        <form method="POST" action="{{ route('co-admin.toko.suspend', $t) }}" onsubmit="return confirm('Suspend toko binaan ini?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-rose-600 hover:text-rose-700 text-xs font-semibold">Suspend</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white border border-slate-200/80 rounded-2xl p-12 text-center text-slate-400 text-sm">
                Belum ada toko siswa di bawah bimbingan Anda.
            </div>
        @endforelse
    </div>

    @if($tokos->hasPages())
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
            {{ $tokos->links() }}
        </div>
    @endif
</div>
@endsection
