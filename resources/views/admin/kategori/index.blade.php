@extends('admin.layouts.app')
@section('title', 'Manajemen Kategori & Fee')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kategori Produk & Fee Kas Sekolah</h1>
            <p class="text-sm text-gray-500 mt-1">Atur kategori produk dan persentase fee sekolah per kategori</p>
        </div>
        <button onclick="document.getElementById('modal-create-kategori').classList.remove('hidden')" class="btn-primary flex items-center justify-center gap-2 self-start sm:self-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kategori
        </button>
    </div>

    {{-- Kategori Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($kategoris as $k)
            <div class="card p-5 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-2xl flex items-center justify-center">
                        {{ $k->icon ?? '📦' }}
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">{{ $k->nama_kategori }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $k->produk_count }} produk • Fee Sekolah: <span class="font-bold text-indigo-600">{{ number_format($k->fee_persen, 1) }}%</span></p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="editKategori({{ json_encode($k) }})" class="p-2 text-gray-400 hover:text-indigo-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 210.3H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                    <form method="POST" action="{{ route('admin.kategori.destroy', $k) }}" onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Modal Create --}}
    <div id="modal-create-kategori" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4 [&:not(.hidden)]:flex">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full space-y-4">
            <h3 class="font-bold text-lg text-gray-800">Tambah Kategori Baru</h3>
            <form method="POST" action="{{ route('admin.kategori.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Kategori</label>
                    <input type="text" name="nama_kategori" required class="input-field w-full" placeholder="Contoh: Makanan Berat">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Emoji / Icon</label>
                    <input type="text" name="icon" class="input-field w-full" placeholder="Contoh: 🍱">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Fee Sekolah (%)</label>
                    <input type="number" step="0.1" name="fee_persen" value="5.0" required class="input-field w-full" placeholder="5.0">
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t">
                    <button type="button" onclick="document.getElementById('modal-create-kategori').classList.add('hidden')" class="btn-secondary text-xs">Batal</button>
                    <button type="submit" class="btn-primary text-xs">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="modal-edit-kategori" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4 [&:not(.hidden)]:flex">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full space-y-4">
            <h3 class="font-bold text-lg text-gray-800">Edit Kategori</h3>
            <form id="form-edit-kategori" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Kategori</label>
                    <input type="text" id="edit-nama" name="nama_kategori" required class="input-field w-full">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Emoji / Icon</label>
                    <input type="text" id="edit-icon" name="icon" class="input-field w-full">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Fee Sekolah (%)</label>
                    <input type="number" step="0.1" id="edit-fee" name="fee_persen" required class="input-field w-full">
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t">
                    <button type="button" onclick="document.getElementById('modal-edit-kategori').classList.add('hidden')" class="btn-secondary text-xs">Batal</button>
                    <button type="submit" class="btn-primary text-xs">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editKategori(kategori) {
    document.getElementById('form-edit-kategori').action = `/admin/kategori/${kategori.id}`;
    document.getElementById('edit-nama').value = kategori.nama_kategori;
    document.getElementById('edit-icon').value = kategori.icon || '';
    document.getElementById('edit-fee').value = kategori.fee_persen;
    document.getElementById('modal-edit-kategori').classList.remove('hidden');
}
</script>
@endsection
