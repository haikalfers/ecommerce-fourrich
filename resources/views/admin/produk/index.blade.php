@extends('admin.layouts.app')
@section('title', 'Persetujuan Produk')
@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Review & Approval Produk</h1>
        <p class="text-sm text-gray-500 mt-1">Review produk yang diupload oleh toko siswa sebelum tayang di katalog</p>
    </div>

    {{-- Filter & Search --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('admin.produk.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama produk..." class="input-field w-full">
            </div>
            <div class="w-full sm:w-48">
                <select name="status" class="input-field w-full" onchange="this.form.submit()">
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                </select>
            </div>
            <button type="submit" class="btn-primary">Cari</button>
        </form>
    </div>

    {{-- Produk Table --}}
    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-gray-50 text-gray-600 border-b border-gray-100">
                    <tr>
                        <th class="py-3 px-4 font-semibold">Produk</th>
                        <th class="py-3 px-4 font-semibold">Toko / Kategori</th>
                        <th class="py-3 px-4 font-semibold">Harga Pokok</th>
                        <th class="py-3 px-4 font-semibold">Harga Final</th>
                        <th class="py-3 px-4 font-semibold">Stok</th>
                        <th class="py-3 px-4 font-semibold">Status</th>
                        <th class="py-3 px-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($produks as $p)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.produk.show', $p) }}" class="flex items-center gap-3 group">
                                    @if(!empty($p->foto_urls))
                                        <img src="{{ $p->foto_urls[0] }}" class="w-12 h-12 rounded-xl object-cover" alt="">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-xl">
                                            {{ $p->kategori->icon ?? '📦' }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-800 group-hover:text-blue-600 transition">{{ $p->nama_produk }}</p>
                                        <p class="text-xs text-gray-400 line-clamp-1 max-w-xs">{{ $p->deskripsi }}</p>
                                    </div>
                                </a>
                            </td>
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.toko.show', $p->toko) }}" class="font-medium text-gray-800 hover:text-blue-600 transition">{{ $p->toko->nama_toko }}</a>
                                <div><span class="text-xs text-indigo-600 font-medium">{{ $p->kategori->nama_kategori }}</span></div>
                            </td>
                            <td class="py-3 px-4 text-gray-500">
                                Rp {{ number_format($p->harga_pokok, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 font-bold text-gray-800">
                                Rp {{ number_format($p->harga_final, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-gray-600 font-medium">
                                {{ $p->stok }}
                            </td>
                            <td class="py-3 px-4">
                                @if($p->isPending())
                                    <span class="badge-warning">Pending</span>
                                @elseif($p->isApproved())
                                    <span class="badge-success">Approved</span>
                                @else
                                    <span class="badge-danger">Rejected</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.produk.show', $p) }}" class="btn-secondary py-1 px-3 text-xs inline-block">Detail</a>
                                    @if($p->isPending() || $p->isRejected())
                                        <form method="POST" action="{{ route('admin.produk.approve', $p) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-primary py-1 px-3 text-xs">Approve</button>
                                        </form>
                                    @endif

                                    @if($p->isPending() || $p->isApproved())
                                        <button onclick="document.getElementById('reject-modal-{{ $p->id }}').classList.remove('hidden')" class="text-red-500 hover:text-red-700 text-xs font-medium">
                                            Reject
                                        </button>
                                    @endif
                                </div>

                                {{-- Modal Reject --}}
                                <div id="reject-modal-{{ $p->id }}" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4 text-left [&:not(.hidden)]:flex">
                                    <div class="bg-white rounded-2xl p-6 max-w-md w-full space-y-4">
                                        <h3 class="font-bold text-lg text-gray-800">Tolak Produk: {{ $p->nama_produk }}</h3>
                                        <form method="POST" action="{{ route('admin.produk.reject', $p) }}" class="space-y-4">
                                            @csrf
                                            @method('PATCH')
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Alasan Penolakan</label>
                                                <textarea name="alasan_reject" required rows="3" class="input-field w-full" placeholder="Contoh: Foto kurang jelas, harga tidak wajar..."></textarea>
                                            </div>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" onclick="document.getElementById('reject-modal-{{ $p->id }}').classList.add('hidden')" class="btn-secondary text-xs">Batal</button>
                                                <button type="submit" class="btn-primary bg-red-600 hover:bg-red-700 text-xs">Kirim & Tolak</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400">Tidak ada produk dalam status ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($produks->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $produks->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
