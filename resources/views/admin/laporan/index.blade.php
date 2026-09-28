@extends('admin.layouts.app')
@section('title', 'Laporan Omzet & Fee')
@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Laporan Omzet & Fee Kas Sekolah</h1>
        <p class="text-sm text-gray-500 mt-1">Rekapitulasi penjualan produk siswa dan kontribusi fee kas sekolah</p>
    </div>

    {{-- Filter Date & Kelas --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="input-field w-full">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="input-field w-full">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Filter Kelas</label>
                <select name="kelas_id" class="input-field w-full">
                    <option value="">Semua Kelas</option>
                    @foreach($kelases as $k)
                        <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-primary w-full">Tampilkan Laporan</button>
            </div>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card bg-gradient-to-br from-indigo-500 to-purple-600 text-white p-5">
            <p class="text-xs font-medium opacity-80">Total Omzet Penjualan</p>
            <p class="text-3xl font-extrabold mt-1">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</p>
            <p class="text-xs mt-2 opacity-75">Periode {{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }}</p>
        </div>
        <div class="card bg-gradient-to-br from-emerald-500 to-teal-600 text-white p-5">
            <p class="text-xs font-medium opacity-80">Total Kas Sekolah (Fee Admin)</p>
            <p class="text-3xl font-extrabold mt-1">Rp {{ number_format($totalFeeKasSekolah, 0, ',', '.') }}</p>
            <p class="text-xs mt-2 opacity-75">Hasil pemotongan fee per kategori</p>
        </div>
        <div class="card bg-gradient-to-br from-slate-700 to-gray-800 text-white p-5">
            <p class="text-xs font-medium opacity-80">Jumlah Transaksi Selesai</p>
            <p class="text-3xl font-extrabold mt-1">{{ number_format($totalPesananCount) }}</p>
            <p class="text-xs mt-2 opacity-75">Pesanan terkonfirmasi selesai</p>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="card p-0 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Detail Transaksi Selesai</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-gray-50 text-gray-600 border-b border-gray-100">
                    <tr>
                        <th class="py-3 px-4 font-semibold">Kode Pesanan</th>
                        <th class="py-3 px-4 font-semibold">Tanggal</th>
                        <th class="py-3 px-4 font-semibold">Toko Siswa</th>
                        <th class="py-3 px-4 font-semibold">Kelas</th>
                        <th class="py-3 px-4 font-semibold">Total Harga</th>
                        <th class="py-3 px-4 font-semibold">Fee Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pesanans as $p)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-4 font-mono font-semibold text-indigo-600">
                                #{{ $p->kode_pesanan }}
                            </td>
                            <td class="py-3 px-4 text-gray-500 text-xs">
                                {{ $p->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="py-3 px-4 font-medium text-gray-800">
                                {{ $p->toko->nama_toko }}
                            </td>
                            <td class="py-3 px-4 text-gray-600">
                                {{ $p->toko->user->kelas->nama_kelas ?? '-' }}
                            </td>
                            <td class="py-3 px-4 font-bold text-gray-800">
                                Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 font-semibold text-emerald-600">
                                Rp {{ number_format($p->detailPesanan->sum('fee_admin'), 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">Tidak ada transaksi pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pesanans->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $pesanans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
