@extends('admin.layouts.app')
@section('title', 'Manajemen Toko')
@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Toko Siswa</h1>
        <p class="text-sm text-gray-500 mt-1">Review dan persetujuan registrasi toko siswa</p>
    </div>

    {{-- Filter & Search --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('admin.toko.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama toko atau pemilik..." class="input-field w-full">
            </div>
            <div class="w-full sm:w-48">
                <select name="status" class="input-field w-full" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="suspend" {{ $status === 'suspend' ? 'selected' : '' }}>Suspend</option>
                </select>
            </div>
            <button type="submit" class="btn-primary">Cari</button>
        </form>
    </div>

    {{-- Toko Table --}}
    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-gray-50 text-gray-600 border-b border-gray-100">
                    <tr>
                        <th class="py-3 px-4 font-semibold">Toko</th>
                        <th class="py-3 px-4 font-semibold">Pemilik / Kelas</th>
                        <th class="py-3 px-4 font-semibold">Guru Pembimbing</th>
                        <th class="py-3 px-4 font-semibold">Saldo Wallet</th>
                        <th class="py-3 px-4 font-semibold">Status</th>
                        <th class="py-3 px-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tokos as $t)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.toko.show', $t) }}" class="flex items-center gap-3 group">
                                    <img src="{{ $t->logo_url }}" class="w-10 h-10 rounded-xl object-cover" alt="">
                                    <div>
                                        <p class="font-medium text-gray-800 group-hover:text-blue-600 transition">{{ $t->nama_toko }}</p>
                                        <p class="text-xs text-gray-400 truncate max-w-xs">{{ $t->deskripsi }}</p>
                                    </div>
                                </a>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-medium text-gray-800">{{ $t->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $t->user->kelas->nama_kelas ?? '-' }}</p>
                            </td>
                            <td class="py-3 px-4 text-gray-600">
                                {{ $t->coAdmin->name ?? '-' }}
                            </td>
                            <td class="py-3 px-4 font-semibold text-emerald-600">
                                Rp {{ number_format($t->wallet_saldo, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4">
                                @if($t->isPending())
                                    <span class="badge-warning">Pending</span>
                                @elseif($t->isAktif())
                                    <span class="badge-success">Aktif</span>
                                @else
                                    <span class="badge-danger">Suspend</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right space-x-2">
                                <a href="{{ route('admin.toko.show', $t) }}" class="btn-secondary py-1 px-3 text-xs inline-block">Detail</a>
                                @if($t->isPending())
                                    <form method="POST" action="{{ route('admin.toko.approve', $t) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-primary py-1 px-3 text-xs">Setujui</button>
                                    </form>
                                @elseif($t->isAktif())
                                    <form method="POST" action="{{ route('admin.toko.suspend', $t) }}" class="inline" onsubmit="return confirm('Suspend toko ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Suspend</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.toko.unsuspend', $t) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-emerald-600 hover:text-emerald-800 text-xs font-medium">Aktifkan Kembali</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">Tidak ada toko ditemukan.</td>
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
</div>
@endsection
