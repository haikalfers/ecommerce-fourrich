<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pembeli — SMEKIV Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-accent-700 via-orange-600 to-primary-800 flex items-center justify-center p-4 font-sans">

    <div class="w-full max-w-md my-8">
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 backdrop-blur rounded-2xl mb-4 border border-white/20">
                <span class="text-3xl">🛒</span>
            </div>
            <h1 class="text-2xl font-bold text-white">Daftar sebagai Pembeli</h1>
            <p class="text-orange-100 text-sm mt-1">Belanja produk karya siswa SMKN 4</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-2xl p-8">
            <form method="POST" action="{{ route('register.pembeli.store') }}" class="space-y-4"
                x-data="{ tipePembeli: '{{ old('tipe_pembeli', 'warga_sekolah') }}' }">
                @csrf

                {{-- Tipe Pembeli --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Saya adalah <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="tipe_pembeli" value="warga_sekolah"
                                x-model="tipePembeli" class="sr-only peer">
                            <div class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 border-gray-200 peer-checked:border-primary-500 peer-checked:bg-primary-50 transition-all duration-200">
                                <span class="text-2xl">🎒</span>
                                <div class="text-center">
                                    <p class="text-xs font-semibold text-gray-700">Warga Sekolah</p>
                                    <p class="text-[10px] text-gray-400">Siswa / Guru / Staff</p>
                                </div>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="tipe_pembeli" value="publik"
                                x-model="tipePembeli" class="sr-only peer">
                            <div class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 border-gray-200 peer-checked:border-accent-500 peer-checked:bg-accent-50 transition-all duration-200">
                                <span class="text-2xl">👤</span>
                                <div class="text-center">
                                    <p class="text-xs font-semibold text-gray-700">Publik Umum</p>
                                    <p class="text-[10px] text-gray-400">Dari luar sekolah</p>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('tipe_pembeli') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }}"
                        placeholder="Nama lengkap kamu">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Email & HP --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }}"
                            placeholder="email@gmail.com">
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. HP <span class="text-gray-400 text-xs font-normal">(opsional)</span></label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm"
                            placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                {{-- Kelas (hanya jika warga sekolah) --}}
                <div x-show="tipePembeli === 'warga_sekolah'" x-transition>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas / Jabatan <span class="text-red-500">*</span></label>
                    <select name="kelas_id"
                        :required="tipePembeli === 'warga_sekolah'"
                        class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm bg-white {{ $errors->has('kelas_id') ? 'border-red-400' : 'border-gray-200' }}">
                        <option value="">Pilih Kelas / Jabatan</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                    @error('kelas_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }}"
                            placeholder="Min. 8 karakter">
                        @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm"
                            placeholder="Ulangi password">
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-3 px-4 bg-accent-500 hover:bg-accent-600 text-white font-semibold rounded-xl transition-all duration-200 hover:shadow-lg hover:shadow-amber-200 active:scale-[0.98]">
                    Daftar Sekarang
                </button>
            </form>
        </div>

        <p class="text-center text-orange-100 text-xs mt-4">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-white font-medium hover:underline">Masuk di sini</a>
        </p>
    </div>

</body>
</html>
