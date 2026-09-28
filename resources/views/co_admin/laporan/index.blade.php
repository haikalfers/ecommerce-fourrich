@extends('co_admin.layouts.app')

@section('title', 'Laporan Omzet Kelas Binaan')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Laporan Omzet Kelas Binaan</h1>
        <p class="text-sm text-slate-500 mt-1">Rekapitulasi penjualan produk dari toko siswa di bawah bimbingan Anda</p>
    </div>

    {{-- Filter Date --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
        <form method="GET" action="{{ route('co-admin.laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="input-field w-full">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="input-field w-full">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition">Filter Laporan</button>
            </div>
        </form>
    </div>

    {{-- Summary Card --}}
    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white p-6 rounded-2xl shadow-sm">
        <p class="text-xs uppercase tracking-wider opacity-80 font-medium">Total Omzet Siswa Binaan</p>
        <p class="text-4xl font-extrabold mt-2 tracking-tight">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</p>
        <p class="text-xs mt-2 opacity-75">Periode {{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }}</p>
    </div>

    {{-- Data Table --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
        <div class="p-4 border-b border-slate-100 font-bold text-slate-900">
            Daftar Transaksi Siswa Binaan
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200/80 text-xs font-semibold">
                    <tr>
                        <th class="py-3.5 px-4">Kode Pesanan</th>
                        <th class="py-3.5 px-4">Tanggal</th>
                        <th class="py-3.5 px-4">Toko Siswa</th>
                        <th class="py-3.5 px-4">Kelas</th>
                        <th class="py-3.5 px-4 text-right">Total Harga</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($pesanans as $p)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4 font-mono font-semibold text-blue-600">#{{ $p->kode_pesanan }}</td>
                            <td class="py-3.5 px-4 text-xs text-slate-500">{{ $p->created_at->format('d M Y H:i') }}</td>
                            <td class="py-3.5 px-4 font-medium text-slate-900">{{ $p->toko->nama_toko }}</td>
                            <td class="py-3.5 px-4 text-slate-500 text-xs">{{ $p->toko->user->kelas->nama_kelas ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-right font-bold text-slate-900">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Tidak ada transaksi pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pesanans->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $pesanans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
