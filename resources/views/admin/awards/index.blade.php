@extends('admin.layouts.app')

@section('title', 'Kelola Award Toko')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span>🏆</span> Kelola Award & Penghargaan Toko
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Berikan penghargaan bulanan kepada toko siswa berprestasi</p>
        </div>

        <button onclick="openModal()" 
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Award
        </button>
    </div>

    <!-- Filter Form -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
        <form method="GET" action="{{ route('admin.awards.index') }}" class="flex flex-wrap items-center gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Bulan</label>
                <select name="bulan" class="input-field py-2 text-sm">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Tahun</label>
                <select name="tahun" class="input-field py-2 text-sm">
                    @foreach(range(now()->year - 2, now()->year + 1) as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pt-5">
                <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white dark:bg-gray-700 text-xs font-semibold rounded-xl transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Award Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400 font-semibold">
                        <th class="py-3.5 px-4">Toko Siswa</th>
                        <th class="py-3.5 px-4">Guru Pembimbing</th>
                        <th class="py-3.5 px-4">Penghargaan</th>
                        <th class="py-3.5 px-4">Periode</th>
                        <th class="py-3.5 px-4">Nilai / Keterangan</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                    @forelse($awards as $award)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition">
                            <td class="py-3.5 px-4">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $award->toko->nama_toko ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $award->toko->user->name ?? '' }} ({{ $award->toko->user->kelas->nama_kelas ?? '' }})</div>
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                {{ $award->toko->coAdmin->name ?? '-' }}
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
                                <span class="text-gray-500">{{ $award->keterangan ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <form action="{{ route('admin.awards.destroy', $award) }}" method="POST" onsubmit="return confirm('Hapus award ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-700 transition">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                Belum ada data award untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($awards->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                {{ $awards->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Award -->
<div id="modalTambahAward" class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="font-bold text-gray-900">Tambah Award Toko</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.awards.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Toko Siswa</label>
                <select name="toko_id" class="input-field" required>
                    <option value="">-- Pilih Toko --</option>
                    @foreach($tokos as $toko)
                        <option value="{{ $toko->id }}">{{ $toko->nama_toko }} ({{ $toko->user->name ?? '' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Jenis Award</label>
                <select name="jenis_award" class="input-field" required>
                    <option value="toko_terlaris">🥇 Toko Terlaris (Omzet Tertinggi)</option>
                    <option value="best_newcomer">🚀 Best Newcomer</option>
                    <option value="most_reviewed">⭐ Most Reviewed</option>
                    <option value="most_consistent">📦 Most Consistent</option>
                    <option value="top_per_kelas">🏆 Top Per Kelas</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Bulan</label>
                    <select name="bulan" class="input-field" required>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ now()->month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tahun</label>
                    <input type="number" name="tahun" value="{{ now()->year }}" class="input-field" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nilai Pencapaian (Opsional, Rp / angka)</label>
                <input type="number" step="0.01" name="nilai_pencapaian" class="input-field" placeholder="Contoh: 1500000">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Keterangan / Catatan</label>
                <input type="text" name="keterangan" class="input-field" placeholder="Contoh: Omzet Rp 1.5 Jt di bulan ini">
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                <button type="button" onclick="closeModal()" 
                        class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-800">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                    Simpan Award
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    const modal = document.getElementById('modalTambahAward');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeModal() {
    const modal = document.getElementById('modalTambahAward');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endsection
