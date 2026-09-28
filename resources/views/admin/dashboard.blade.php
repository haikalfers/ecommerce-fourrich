@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Top Metrics Grid (4 Cards) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total Toko Aktif --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:border-slate-300 transition">
            <p class="text-xs font-semibold text-slate-500">Total toko aktif</p>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['total_toko_aktif'] }}</span>
            </div>
            <p class="mt-2 text-xs font-semibold text-emerald-600 flex items-center gap-1">
                <span>+{{ $stats['toko_baru_bulan_ini'] }}</span>
                <span class="text-slate-400 font-normal">bulan ini</span>
            </p>
        </div>

        {{-- Card 2: Produk Menunggu --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:border-slate-300 transition">
            <p class="text-xs font-semibold text-slate-500">Produk menunggu</p>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['produk_pending'] }}</span>
            </div>
            <p class="mt-2 text-xs font-normal text-slate-400">perlu di-approve</p>
        </div>

        {{-- Card 3: Omzet Bulan Ini --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:border-slate-300 transition">
            <p class="text-xs font-semibold text-slate-500">Omzet bulan ini</p>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    @if($stats['omzet_bulan_ini'] >= 1000000)
                        Rp {{ number_format($stats['omzet_bulan_ini'] / 1000000, 1, ',', '.') }}jt
                    @else
                        Rp {{ number_format($stats['omzet_bulan_ini'] / 1000, 0, ',', '.') }}rb
                    @endif
                </span>
            </div>
            <p class="mt-2 text-xs font-semibold text-emerald-600 flex items-center gap-1">
                <span>+{{ $stats['growth_omzet'] }}%</span>
                <span class="text-slate-400 font-normal">vs bulan lalu</span>
            </p>
        </div>

        {{-- Card 4: Fee Sekolah --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:border-slate-300 transition">
            <p class="text-xs font-semibold text-slate-500">Fee sekolah</p>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    @if($stats['fee_sekolah'] >= 1000000)
                        Rp {{ number_format($stats['fee_sekolah'] / 1000000, 1, ',', '.') }}jt
                    @else
                        Rp {{ number_format($stats['fee_sekolah'] / 1000, 0, ',', '.') }}rb
                    @endif
                </span>
            </div>
            <p class="mt-2 text-xs font-normal text-slate-400">bulan ini</p>
        </div>
    </div>

    {{-- Middle Row (2 Cards) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Card: Omzet per kelas --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-bold text-slate-900 tracking-tight">Omzet per kelas</h3>
                <a href="{{ route('admin.laporan.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition">Lihat semua</a>
            </div>

            {{-- Bar Chart Visual --}}
            <div class="h-44 flex items-end justify-between gap-3 px-2 pt-4">
                @php
                    $maxOmzet = $omzetPerKelas->max('total_omzet') ?: 1;
                @endphp
                @forelse($omzetPerKelas as $item)
                    @php
                        $heightPercent = max(20, min(100, round(($item->total_omzet / $maxOmzet) * 100)));
                    @endphp
                    <div class="flex-1 flex flex-col items-center gap-2 group h-full justify-end">
                        <div class="w-full bg-blue-50 rounded-lg transition-all relative flex items-end justify-center"
                             style="height: {{ $heightPercent }}%;">
                            <div class="w-full bg-blue-600 group-hover:bg-blue-700 rounded-lg transition-all" style="height: 100%;"></div>
                        </div>
                        <span class="text-[11px] font-medium text-slate-500 group-hover:text-slate-900 transition truncate max-w-full">
                            {{ Str::limit($item->nama_kelas, 8) }}
                        </span>
                    </div>
                @empty
                    <div class="w-full h-full flex items-center justify-center text-xs text-slate-400">
                        Belum ada data omzet per kelas.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Card: Produk menunggu approval --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-900 tracking-tight">Produk menunggu approval</h3>
                <a href="{{ route('admin.produk.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition">Lihat semua</a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($produkPending as $produk)
                    <div class="py-3 flex items-center justify-between first:pt-0 last:pb-0">
                        <div>
                            <p class="text-sm font-semibold text-slate-800 hover:text-blue-600 transition">{{ $produk->nama_produk }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $produk->toko->user->kelas->nama_kelas ?? 'Umum' }}</p>
                        </div>
                        <span class="px-2.5 py-1 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-medium rounded-full">
                            Pending
                        </span>
                    </div>
                @empty
                    <div class="py-12 text-center text-xs text-slate-400">
                        Tidak ada produk menunggu approval.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Bottom Row (2 Cards) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Card: Toko terlaris bulan ini --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-900 tracking-tight">Toko terlaris bulan ini</h3>
                <a href="{{ route('admin.toko.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition">Lihat semua</a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($tokoTerlaris as $index => $toko)
                    <div class="py-3 flex items-center justify-between first:pt-0 last:pb-0">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-slate-400 w-4">{{ $index + 1 }}</span>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $toko->nama_toko }}</p>
                                <p class="text-xs text-slate-500">{{ $toko->user->name ?? '' }} ({{ $toko->user->kelas->nama_kelas ?? '' }})</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-slate-900">
                            @if(($toko->omzet_bulan_ini ?? 0) >= 1000000)
                                Rp {{ number_format(($toko->omzet_bulan_ini ?? 0) / 1000000, 1, ',', '.') }}jt
                            @else
                                Rp {{ number_format(($toko->omzet_bulan_ini ?? 0) / 1000, 0, ',', '.') }}rb
                            @endif
                        </span>
                    </div>
                @empty
                    <div class="py-12 text-center text-xs text-slate-400">
                        Belum ada transaksi toko bulan ini.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Card: Award bulan ini --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-900 tracking-tight">Award bulan ini</h3>
                <a href="{{ route('admin.awards.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition">Lihat semua</a>
            </div>

            <div class="space-y-3">
                @forelse($awardsBulanIni as $award)
                    <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center text-lg shrink-0">
                            {{ $award->emoji }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-900 truncate">{{ $award->toko->nama_toko ?? '-' }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ $award->label }} · {{ $award->toko->user->kelas->nama_kelas ?? 'Umum' }}</p>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-xs text-slate-400">
                        Belum ada award yang diberikan bulan ini.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
