@extends('co_admin.layouts.app')

@section('title', 'Award Siswa Binaan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                <span>🏆</span> Award & Prestasi Siswa Binaan
            </h1>
            <p class="text-sm text-slate-500 mt-1">Daftar penghargaan yang diraih oleh toko siswa di bawah bimbingan Anda</p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl p-4 shadow-xs border border-slate-200/80">
        <form method="GET" action="{{ route('co-admin.awards.index') }}" class="flex flex-wrap items-center gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Bulan</label>
                <select name="bulan" class="input-field py-2 text-sm">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Tahun</label>
                <select name="tahun" class="input-field py-2 text-sm">
                    @foreach(range(now()->year - 2, now()->year + 1) as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pt-5">
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Award Table -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-xs text-slate-500 font-semibold">
                        <th class="py-3.5 px-4">Toko Siswa</th>
                        <th class="py-3.5 px-4">Penghargaan</th>
                        <th class="py-3.5 px-4">Periode</th>
                        <th class="py-3.5 px-4">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($awards as $award)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 px-4">
                                <div class="font-medium text-slate-900">{{ $award->toko->nama_toko ?? '-' }}</div>
                                <div class="text-xs text-slate-500">{{ $award->toko->user->name ?? '' }} ({{ $award->toko->user->kelas->nama_kelas ?? '' }})</div>
                            </td>
                            <td class="py-3.5 px-4 font-semibold">
                                <span>{{ $award->emoji }}</span> {{ $award->label }}
                            </td>
                            <td class="py-3.5 px-4 text-xs font-mono">
                                {{ \Carbon\Carbon::create()->month($award->bulan)->translatedFormat('F') }} {{ $award->tahun }}
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                @if($award->nilai_pencapaian > 0)
                                    <div class="font-semibold text-emerald-600">Rp {{ number_format($award->nilai_pencapaian, 0, ',', '.') }}</div>
                                @endif
                                <span class="text-slate-500">{{ $award->keterangan ?? '-' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400">
                                Belum ada data award untuk siswa binaan pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($awards->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $awards->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
