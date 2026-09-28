@extends('admin.layouts.app')
@section('title', 'Manajemen User')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola pengguna sistem (Admin, Co-Admin, Toko, Pembeli)</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-primary flex items-center justify-center gap-2 self-start sm:self-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah User / Co-Admin
        </a>
    </div>

    {{-- Filter & Search --}}
    <div class="card p-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email..." class="input-field w-full">
            </div>
            <div class="w-full sm:w-48">
                <select name="role" class="input-field w-full" onchange="this.form.submit()">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="co_admin" {{ $role === 'co_admin' ? 'selected' : '' }}>Co-Admin (Guru)</option>
                    <option value="toko" {{ $role === 'toko' ? 'selected' : '' }}>Toko (Siswa)</option>
                    <option value="pembeli" {{ $role === 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                </select>
            </div>
            <button type="submit" class="btn-primary">Cari</button>
        </form>
    </div>

    {{-- User Table --}}
    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-gray-50 text-gray-600 border-b border-gray-100">
                    <tr>
                        <th class="py-3 px-4 font-semibold">User</th>
                        <th class="py-3 px-4 font-semibold">Role</th>
                        <th class="py-3 px-4 font-semibold">Kontak</th>
                        <th class="py-3 px-4 font-semibold">Terdaftar</th>
                        <th class="py-3 px-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $u->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                @if($u->isAdmin())
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">Admin</span>
                                @elseif($u->isCoAdmin())
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Co-Admin</span>
                                @elseif($u->isToko())
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Toko</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">Pembeli</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-600">
                                {{ $u->no_hp ?? '-' }}
                            </td>
                            <td class="py-3 px-4 text-gray-400 text-xs">
                                {{ $u->created_at->format('d M Y') }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                @if($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">Tidak ada user ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
