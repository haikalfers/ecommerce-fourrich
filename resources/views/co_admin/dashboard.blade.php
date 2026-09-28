@extends('co_admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Top Metrics Grid (3 Cards) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Card 1: Toko aktif binaan --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:border-slate-300 transition">
            <p class="text-xs font-semibold text-slate-500">Toko aktif binaan</p>
            <div class="mt-3">
                <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['toko_aktif_binaan'] }}</span>
            </div>
            <p class="mt-2 text-xs font-normal text-slate-400">dari {{ $stats['total_siswa'] }} siswa</p>
        </div>

        {{-- Card 2: Omzet kelas bulan ini --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:border-slate-300 transition">
            <p class="text-xs font-semibold text-slate-500">Omzet kelas bulan ini</p>
            <div class="mt-3">
                <span class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    @if($stats['omzet_kelas_bulan_ini'] >= 1000000)
                        Rp {{ number_format($stats['omzet_kelas_bulan_ini'] / 1000000, 1, ',', '.') }}jt
                    @else
                        Rp {{ number_format($stats['omzet_kelas_bulan_ini'] / 1000, 0, ',', '.') }}rb
                    @endif
                </span>
            </div>
            <p class="mt-2 text-xs font-semibold text-emerald-600 flex items-center gap-1">
                <span>+{{ $stats['omzet_growth'] }}%</span>
                <span class="text-slate-400 font-normal">vs bulan lalu</span>
            </p>
        </div>

        {{-- Card 3: Produk aktif --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:border-slate-300 transition">
            <p class="text-xs font-semibold text-slate-500">Produk aktif</p>
            <div class="mt-3">
                <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['produk_aktif'] }}</span>
            </div>
            <p class="mt-2 text-xs font-normal text-slate-400">dari {{ $stats['toko_aktif_binaan'] }} toko</p>
        </div>
    </div>

    {{-- Bottom Grid (2 Columns: Ranking & Info Kelas) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Left Column (7 cols): Ranking toko binaan --}}
        <div class="lg:col-span-7 bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900 tracking-tight">Ranking toko binaan</h3>
                    <a href="{{ route('co-admin.toko.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition">Lihat semua</a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($rankingTokoBinaan as $index => $toko)
                        <div class="py-3.5 flex items-center justify-between first:pt-1 last:pb-1">
                            <div class="flex items-center gap-4">
                                <span class="text-xs font-bold text-slate-400 w-4">{{ $index + 1 }}</span>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-semibold text-slate-900">{{ $toko->nama_toko }}</span>
                                    @if($toko->status === 'aktif')
                                        <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full">Aktif</span>
                                    @elseif($toko->status === 'suspend')
                                        <span class="px-2.5 py-0.5 bg-rose-100 text-rose-800 text-xs font-semibold rounded-full">Suspend</span>
                                    @else
                                        <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full">Pending</span>
                                    @endif
                                </div>
                            </div>

                            <span class="text-sm font-bold text-slate-800">
                                @if(($toko->omzet_bulan_ini ?? 0) >= 1000000)
                                    Rp {{ number_format(($toko->omzet_bulan_ini ?? 0) / 1000000, 1, ',', '.') }}jt
                                @else
                                    Rp {{ number_format(($toko->omzet_bulan_ini ?? 0) / 1000, 0, ',', '.') }}rb
                                @endif
                            </span>
                        </div>
                    @empty
                        <div class="py-12 text-center text-xs text-slate-400">
                            Belum ada data toko binaan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Column (5 cols): Info kelas --}}
        <div class="lg:col-span-5 bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs">
            <h3 class="text-base font-bold text-slate-900 tracking-tight mb-4 pb-2 border-b border-slate-100">Info kelas</h3>

            <div class="space-y-3.5 text-sm">
                <div class="flex items-center justify-between py-1 border-b border-slate-100">
                    <span class="text-slate-500">Kelas</span>
                    <span class="font-bold text-slate-900">{{ $kelasBinaanNames }}</span>
                </div>
                <div class="flex items-center justify-between py-1 border-b border-slate-100">
                    <span class="text-slate-500">Jumlah siswa</span>
                    <span class="font-bold text-slate-900">{{ $stats['total_siswa'] }} siswa</span>
                </div>
                <div class="flex items-center justify-between py-1 border-b border-slate-100">
                    <span class="text-slate-500">Toko aktif</span>
                    <span class="font-bold text-slate-900">{{ $stats['toko_aktif_binaan'] }} toko</span>
                </div>
                <div class="flex items-center justify-between py-1 border-b border-slate-100">
                    <span class="text-slate-500">Toko pending</span>
                    <span class="font-bold text-slate-900">{{ $stats['toko_pending'] }} toko</span>
                </div>
                <div class="flex items-center justify-between py-1 border-b border-slate-100">
                    <span class="text-slate-500">Toko suspend</span>
                    <span class="font-bold text-slate-900">{{ $stats['toko_suspend'] }} toko</span>
                </div>
                <div class="flex items-center justify-between py-1 pt-1">
                    <span class="text-slate-500">Award bulan ini</span>
                    <span class="font-bold text-slate-900">{{ $stats['award_bulan_ini'] }} siswa</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
