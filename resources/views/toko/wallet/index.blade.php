@extends('toko.layouts.app')
@section('title', 'Dompet / Wallet Toko')
@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Wallet / Saldo Toko</h1>
        <p class="text-sm text-gray-500 mt-1">Saldo hasil penjualan produk siswa setelah dipotong fee sekolah</p>
    </div>

    {{-- Wallet Balance Card --}}
    <div class="card bg-gradient-to-r from-emerald-600 to-teal-700 text-white p-6 rounded-2xl shadow-lg flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-wider opacity-80 font-semibold">Total Saldo Wallet Toko Anda</p>
            <p class="text-4xl font-black mt-2">Rp {{ number_format($toko->wallet_saldo, 0, ',', '.') }}</p>
            <p class="text-xs opacity-75 mt-2">Dapat dicairkan sesuai kebijakan guru KIK / sekolah</p>
        </div>
        <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl text-xs space-y-1">
            <p>💡 <b>Ketentuan Wallet:</b></p>
            <p>• Otomatis bertambah saat pesanan dikonfirmasi selesai.</p>
            <p>• Sudah termasuk pemotongan kas sekolah per kategori produk.</p>
        </div>
    </div>

    {{-- Mutation History Table --}}
    <div class="card p-0 overflow-hidden">
        <div class="p-4 border-b border-gray-100 font-bold text-gray-800">
            Riwayat Mutasi Saldo
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-gray-50 text-gray-600 border-b border-gray-100">
                    <tr>
                        <th class="py-3 px-4 font-semibold">Tanggal</th>
                        <th class="py-3 px-4 font-semibold">Keterangan</th>
                        <th class="py-3 px-4 font-semibold">Tipe</th>
                        <th class="py-3 px-4 font-semibold text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-4 text-xs text-gray-400">
                                {{ $tx->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="py-3 px-4 font-medium text-gray-800">
                                {{ $tx->keterangan }}
                            </td>
                            <td class="py-3 px-4">
                                @if($tx->tipe === 'kredit')
                                    <span class="badge-success">Pemasukan</span>
                                @else
                                    <span class="badge-danger">Penarikan</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right font-bold {{ $tx->tipe === 'kredit' ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $tx->tipe === 'kredit' ? '+' : '-' }} Rp {{ number_format($tx->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-400">Belum ada transaksi wallet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
