@extends('toko.layouts.app')
@section('title', 'Pesanan Masuk Toko')
@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Pesanan Masuk</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola pesanan dari pembeli untuk toko Anda</p>
    </div>

    {{-- Filter Status --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2">
        <a href="{{ route('toko.pesanan.index') }}" class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap {{ !$status ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Semua Status</a>
        <a href="{{ route('toko.pesanan.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap {{ $status === 'pending' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Menunggu Konfirmasi</a>
        <a href="{{ route('toko.pesanan.index', ['status' => 'dikonfirmasi']) }}" class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap {{ $status === 'dikonfirmasi' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Diproses / Dikirim</a>
        <a href="{{ route('toko.pesanan.index', ['status' => 'selesai']) }}" class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap {{ $status === 'selesai' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Selesai</a>
        <a href="{{ route('toko.pesanan.index', ['status' => 'dibatalkan']) }}" class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap {{ $status === 'dibatalkan' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Dibatalkan</a>
    </div>

    {{-- Pesanan Table --}}
    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-gray-50 text-gray-600 border-b border-gray-100">
                    <tr>
                        <th class="py-3 px-4 font-semibold">Kode Pesanan</th>
                        <th class="py-3 px-4 font-semibold">Pembeli</th>
                        <th class="py-3 px-4 font-semibold">Metode Kirim / Bayar</th>
                        <th class="py-3 px-4 font-semibold">Total Harga</th>
                        <th class="py-3 px-4 font-semibold">Status</th>
                        <th class="py-3 px-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pesanans as $p)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-4 font-mono font-semibold text-indigo-600">
                                #{{ $p->kode_pesanan }}
                                <p class="text-[10px] text-gray-400 font-sans font-normal">{{ $p->created_at->format('d M Y H:i') }}</p>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-medium text-gray-800">{{ $p->pembeli->name ?? $p->nama_guest ?? 'Guest' }}</p>
                                <p class="text-xs text-gray-400">{{ $p->pembeli->no_hp ?? $p->hp_guest ?? '-' }}</p>
                            </td>
                            <td class="py-3 px-4 text-xs text-gray-600">
                                <span class="capitalize font-semibold text-gray-700">{{ $p->metode_kirim }}</span> • <span class="uppercase">{{ $p->metode_bayar }}</span>
                            </td>
                            <td class="py-3 px-4 font-bold text-gray-800">
                                Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4">
                                @if($p->isPending())
                                    <span class="badge-warning">Pending</span>
                                @elseif($p->isDikonfirmasi())
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Dikonfirmasi</span>
                                @elseif($p->isSelesai())
                                    <span class="badge-success">Selesai</span>
                                @else
                                    <span class="badge-danger">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right space-x-1">
                                <a href="{{ route('toko.pesanan.show', $p) }}" class="btn-secondary py-1 px-2.5 text-xs">Detail</a>

                                @if($p->isPending())
                                    <form method="POST" action="{{ route('toko.pesanan.konfirmasi', $p) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-primary py-1 px-2.5 text-xs">Proses</button>
                                    </form>
                                @elseif($p->isDikonfirmasi())
                                    <form method="POST" action="{{ route('toko.pesanan.selesai', $p) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-primary bg-emerald-600 hover:bg-emerald-700 py-1 px-2.5 text-xs">Selesaikan</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">Belum ada pesanan masuk.</td>
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
