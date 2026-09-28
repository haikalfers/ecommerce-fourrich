<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SMEKIV Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-primary-950 via-primary-900 to-primary-800 flex items-center justify-center p-4 font-sans">

    <div class="w-full max-w-md">
        {{-- Logo & Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 backdrop-blur rounded-2xl mb-4 border border-white/20">
                <span class="text-3xl">🛍️</span>
            </div>
            <h1 class="text-2xl font-bold text-white">SMEKIV Store</h1>
            <p class="text-primary-300 text-sm mt-1">Platform Jual Beli Siswa SMKN 4 Surabaya</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-2xl p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-1">Masuk ke Akun</h2>
            <p class="text-sm text-gray-500 mb-6">Selamat datang kembali!</p>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }}"
                        placeholder="nama@email.com">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }}"
                        placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-sm text-gray-600">Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                            Lupa password?
                        </a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition-all duration-200 hover:shadow-lg hover:shadow-primary-200 active:scale-[0.98]">
                    Masuk
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-gray-100"></div>
                <span class="text-xs text-gray-400">atau daftar sebagai</span>
                <div class="flex-1 h-px bg-gray-100"></div>
            </div>

            {{-- Register Options --}}
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('register.toko') }}"
                    class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 border-primary-100 hover:border-primary-400 hover:bg-primary-50 transition-all duration-200 group">
                    <span class="text-2xl">🏪</span>
                    <div class="text-center">
                        <p class="text-xs font-semibold text-gray-700 group-hover:text-primary-700">Buka Toko</p>
                        <p class="text-[10px] text-gray-400">Siswa Kelas XI</p>
                    </div>
                </a>
                <a href="{{ route('register.pembeli') }}"
                    class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 border-accent-100 hover:border-accent-400 hover:bg-accent-50 transition-all duration-200 group">
                    <span class="text-2xl">🛒</span>
                    <div class="text-center">
                        <p class="text-xs font-semibold text-gray-700 group-hover:text-accent-700">Daftar Pembeli</p>
                        <p class="text-[10px] text-gray-400">Warga Sekolah / Umum</p>
                    </div>
                </a>
            </div>
        </div>

        <p class="text-center text-primary-400 text-xs mt-6">
            © {{ date('Y') }} SMKN 4 Surabaya — KIK E-Commerce
        </p>
    </div>

</body>
</html>
