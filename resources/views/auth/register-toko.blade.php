<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Toko — SMEKIV Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800 flex items-center justify-center p-4 font-sans">

    <div class="w-full max-w-lg my-8">
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 backdrop-blur rounded-2xl mb-4 border border-white/20">
                <span class="text-3xl">🏪</span>
            </div>
            <h1 class="text-2xl font-bold text-white">Buka Toko di SMEKIV</h1>
            <p class="text-primary-300 text-sm mt-1">Khusus siswa kelas XI — program KIK</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-2xl p-8">
            <div class="flex items-center gap-3 mb-6 p-4 bg-primary-50 rounded-2xl border border-primary-100">
                <span class="text-2xl">📋</span>
                <div>
                    <p class="text-sm font-semibold text-primary-800">Syarat Pendaftaran Toko</p>
                    <p class="text-xs text-primary-600">Hanya siswa kelas XI • Butuh persetujuan admin • 1 siswa = 1 toko</p>
                </div>
            </div>

            <form method="POST" action="{{ route('register.toko.store') }}" class="space-y-4">
                @csrf

                {{-- Informasi Pribadi --}}
                <div class="pb-2 border-b border-gray-100">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Informasi Pribadi</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }}"
                                placeholder="Nama sesuai rapor">
                            @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }}"
                                    placeholder="email@gmail.com">
                                @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. HP <span class="text-red-500">*</span></label>
                                <input type="text" name="no_hp" value="{{ old('no_hp') }}" required
                                    class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm {{ $errors->has('no_hp') ? 'border-red-400' : 'border-gray-200' }}"
                                    placeholder="08xxxxxxxxxx">
                                @error('no_hp') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                                <input type="password" name="password" required
                                    class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }}"
                                    placeholder="Min. 8 karakter">
                                @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                                <input type="password" name="password_confirmation" required
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm"
                                    placeholder="Ulangi password">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Informasi Sekolah --}}
                <div class="pb-2 border-b border-gray-100">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Informasi Sekolah</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kelas <span class="text-red-500">*</span></label>
                            <select name="kelas_id" required
                                class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm bg-white {{ $errors->has('kelas_id') ? 'border-red-400' : 'border-gray-200' }}">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Guru Pembimbing <span class="text-red-500">*</span></label>
                            <select name="co_admin_id" required
                                class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm bg-white {{ $errors->has('co_admin_id') ? 'border-red-400' : 'border-gray-200' }}">
                                <option value="">Pilih Guru</option>
                                @foreach($coAdmins as $guru)
                                    <option value="{{ $guru->id }}" {{ old('co_admin_id') == $guru->id ? 'selected' : '' }}>
                                        {{ $guru->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('co_admin_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Informasi Toko --}}
                <div>
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Informasi Toko</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Toko <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_toko" value="{{ old('nama_toko') }}" required maxlength="100"
                                class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm {{ $errors->has('nama_toko') ? 'border-red-400' : 'border-gray-200' }}"
                                placeholder="Nama unik untuk tokomu">
                            @error('nama_toko') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Toko <span class="text-gray-400 font-normal">(opsional)</span></label>
                            <textarea name="deskripsi_toko" rows="2" maxlength="500"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm resize-none"
                                placeholder="Ceritakan sedikit tentang tokomu...">{{ old('deskripsi_toko') }}</textarea>
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition-all duration-200 hover:shadow-lg hover:shadow-primary-200 active:scale-[0.98]">
                    Daftar & Buat Toko
                </button>

                <p class="text-center text-xs text-gray-400">
                    Tokomu akan aktif setelah disetujui oleh admin sekolah.
                </p>
            </form>
        </div>

        <p class="text-center text-primary-400 text-xs mt-4">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-white font-medium hover:underline">Masuk di sini</a>
        </p>
    </div>

</body>
</html>
