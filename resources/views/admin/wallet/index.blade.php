@extends('admin.layouts.app')
@section('title', 'Monitor Wallet Toko')
@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Monitor Wallet Toko Siswa</h1>
        <p class="text-sm text-gray-500 mt-1">Pantau saldo wallet dan riwayat mutasi dana seluruh toko siswa</p>
    </div>

    {{-- Total Balance Summary --}}
    <div class="card bg-slate-900 text-white p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Total Saldo Wallet Semua Toko Siswa</p>
            <p class="text-4xl font-black mt-2 text-emerald-400">Rp {{ number_format($totalSaldoSiswa, 0, ',', '.') }}</p>
        </div>
        <div class="text-xs text-slate-400 bg-slate-800/80 p-3 rounded-xl">
            ℹ️ Saldo wallet bertambah otomatis setiap transaksi selesai (dikurangi fee kas sekolah).
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- List Saldo Toko --}}
        <div class="card lg:col-span-2 p-0 overflow-hidden">
            <div class="p-4 border-b border-gray-100 font-bold text-gray-800">
                Daftar Saldo Toko Siswa
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead class="bg-gray-50 text-gray-600 border-b border-gray-100">
                        <tr>
                            <th class="py-3 px-4 font-semibold">Toko</th>
                            <th class="py-3 px-4 font-semibold">Pemilik / Kelas</th>
                            <th class="py-3 px-4 font-semibold text-right">Saldo Wallet</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($tokos as $t)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $t->logo_url }}" class="w-9 h-9 rounded-xl object-cover" alt="">
                                        <span class="font-medium text-gray-800">{{ $t->nama_toko }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-gray-600">
                                    {{ $t->user->name }} ({{ $t->user->kelas->nama_kelas ?? '-' }})
                                </td>
                                <td class="py-3 px-4 text-right font-bold text-emerald-600">
                                    Rp {{ number_format($t->wallet_saldo, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-gray-400">Belum ada toko registered.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($tokos->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $tokos->links() }}
                </div>
            @endif
        </div>

        {{-- Mutasi Terakhir --}}
        <div class="card p-4 lg:col-span-1">
            <h3 class="font-bold text-gray-800 mb-4">Riwayat Mutasi Terakhir</h3>
            <div class="space-y-3 max-h-[500px] overflow-y-auto">
                @forelse($transactions as $tx)
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-semibold text-gray-700">{{ $tx->toko->nama_toko }}</span>
                            <span class="{{ $tx->tipe === 'kredit' ? 'text-emerald-600' : 'text-red-600' }} font-bold">
                                {{ $tx->tipe === 'kredit' ? '+' : '-' }} Rp {{ number_format($tx->jumlah, 0, ',', '.') }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500">{{ $tx->keterangan }}</p>
                        <p class="text-[10px] text-gray-400">{{ $tx->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-6">Belum ada mutasi wallet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
