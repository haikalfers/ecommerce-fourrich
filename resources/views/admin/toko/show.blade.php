@extends('admin.layouts.app')

@section('title', 'Detail Toko — ' . $toko->nama_toko)

@section('content')
<div class="space-y-6">
    {{-- Header Navigation & Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.toko.index') }}" class="p-2 rounded-xl bg-white border border-gray-200 text-gray-500 hover:text-gray-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $toko->nama_toko }}</h1>
                    @if($toko->isPending())
                        <span class="badge-warning">Pending Approval</span>
                    @elseif($toko->isAktif())
                        <span class="badge-success">Aktif</span>
                    @else
                        <span class="badge-danger">Suspend</span>
                    @endif
                </div>
                <p class="text-sm text-gray-500">Terdaftar pada {{ $toko->created_at ? $toko->created_at->translatedFormat('d F Y H:i') : '-' }}</p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2">
            @if($toko->isPending())
                <form method="POST" action="{{ route('admin.toko.approve', $toko) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-primary flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Setujui Toko (ACC)
                    </button>
                </form>
            @elseif($toko->isAktif())
                <form method="POST" action="{{ route('admin.toko.suspend', $toko) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menangguhkan (suspend) toko ini?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-danger flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Suspend Toko
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.toko.unsuspend', $toko) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-success flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Aktifkan Kembali Toko
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Column: Store Profile & Owner Details --}}
        <div class="space-y-6">
            {{-- Profile Card --}}
            <div class="card p-6 space-y-4">
                <div class="text-center">
                    <img src="{{ $toko->logo_url }}" class="w-24 h-24 rounded-2xl object-cover mx-auto border border-gray-100 shadow-sm" alt="{{ $toko->nama_toko }}">
                    <h2 class="font-bold text-gray-800 text-lg mt-3">{{ $toko->nama_toko }}</h2>
                    <p class="text-xs text-gray-500 mt-1">{{ $toko->deskripsi ?? 'Belum ada deskripsi toko.' }}</p>
                </div>

                <div class="pt-4 border-t border-gray-100 space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Saldo Wallet</span>
                        <span class="font-bold text-emerald-600">Rp {{ number_format($toko->wallet_saldo, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Bank</span>
                        <span class="font-medium text-gray-800">{{ $toko->nama_bank ?? 'Belum diisi' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">No. Rekening</span>
                        <span class="font-medium text-gray-800">{{ $toko->no_rekening ?? 'Belum diisi' }}</span>
                    </div>
                </div>
            </div>

            {{-- Pemilik & Mentor Info --}}
            <div class="card p-6 space-y-4">
                <h3 class="font-bold text-gray-800 text-base border-b border-gray-100 pb-3">Informasi Pemilik & Pembimbing</h3>
                
                {{-- Pemilik --}}
                <div class="space-y-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pemilik Toko (Siswa)</p>
                    <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <img src="{{ $toko->user->avatar_url }}" class="w-10 h-10 rounded-full object-cover" alt="">
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-gray-800 text-sm truncate">{{ $toko->user->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $toko->user->email }}</p>
                            <p class="text-xs text-blue-600 font-medium mt-0.5">Kelas: {{ $toko->user->kelas->nama_kelas ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Guru Pembimbing --}}
                <div class="space-y-2 pt-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Guru Pembimbing (Co-Admin)</p>
                    <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm shrink-0">
                            {{ $toko->coAdmin ? strtoupper(substr($toko->coAdmin->name, 0, 2)) : '?' }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-gray-800 text-sm truncate">{{ $toko->coAdmin->name ?? 'Belum ditugaskan' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $toko->coAdmin->email ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Stats, Products & Orders --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Summary Stats Grid --}}
            <div class="grid grid-cols-3 gap-4">
                <div class="card p-4 text-center">
                    <p class="text-xs text-gray-500 font-medium">Total Omzet</p>
                    <p class="text-lg sm:text-xl font-bold text-emerald-600 mt-1">Rp {{ number_format($stats['total_omzet'], 0, ',', '.') }}</p>
                </div>
                <div class="card p-4 text-center">
                    <p class="text-xs text-gray-500 font-medium">Total Pesanan</p>
                    <p class="text-lg sm:text-xl font-bold text-gray-800 mt-1">{{ $stats['total_pesanan'] }}</p>
                </div>
                <div class="card p-4 text-center">
                    <p class="text-xs text-gray-500 font-medium">Total Produk</p>
                    <p class="text-lg sm:text-xl font-bold text-gray-800 mt-1">{{ $stats['total_produk'] }}</p>
                </div>
            </div>

            {{-- Daftar Produk --}}
            <div class="card p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="font-bold text-gray-800 text-base">Daftar Produk Toko</h3>
                    <span class="text-xs text-gray-500">{{ $toko->produk->count() }} Produk</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @forelse($toko->produk as $p)
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center gap-3">
                            <img src="{{ $p->foto_url }}" class="w-12 h-12 rounded-lg object-cover shrink-0" alt="">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 text-sm truncate">{{ $p->nama_produk }}</p>
                                <p class="text-xs font-bold text-emerald-600">Rp {{ number_format($p->harga_final, 0, ',', '.') }}</p>
                                <p class="text-[11px] text-gray-400">Stok: {{ $p->stok }} · {{ $p->kategori->nama_kategori ?? 'Umum' }}</p>
                            </div>
                            <span class="text-[10px] px-2 py-0.5 font-bold rounded-full shrink-0 {{ $p->isApproved() ? 'bg-emerald-100 text-emerald-800' : ($p->isPending() ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-6 text-xs text-gray-400">
                            Toko ini belum menambahkan produk.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Riwayat Pesanan Terbaru --}}
            <div class="card p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="font-bold text-gray-800 text-base">Pesanan Terbaru Toko</h3>
                    <span class="text-xs text-gray-500">10 Pesanan Terakhir</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                            <tr>
                                <th class="py-2.5 px-3 font-semibold">Kode Pesanan</th>
                                <th class="py-2.5 px-3 font-semibold">Pembeli</th>
                                <th class="py-2.5 px-3 font-semibold">Total</th>
                                <th class="py-2.5 px-3 font-semibold">Status</th>
                                <th class="py-2.5 px-3 font-semibold text-right">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($toko->pesanan as $pesanan)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="py-2.5 px-3 font-mono font-bold text-gray-700">#{{ $pesanan->kode_pesanan }}</td>
                                    <td class="py-2.5 px-3 text-gray-800">{{ $pesanan->pembeli->name ?? 'Guest' }}</td>
                                    <td class="py-2.5 px-3 font-bold text-emerald-600">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                    <td class="py-2.5 px-3">
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $pesanan->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : ($pesanan->status === 'dibatalkan' || $pesanan->status === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ ucfirst($pesanan->status) }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-right text-gray-400">{{ $pesanan->created_at ? $pesanan->created_at->format('d/m/Y H:i') : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-gray-400">Belum ada pesanan masuk untuk toko ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
