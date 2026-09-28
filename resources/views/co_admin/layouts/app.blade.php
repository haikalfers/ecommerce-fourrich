<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Co-Admin') — E-Market SMKN 4</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#F8F8F8] text-[#374151] font-sans antialiased" x-data="{ sidebarOpen: false }">

    @php
        if (!isset($kelasBinaanNames)) {
            $kelasBinaanNames = auth()->user()->siswaBinaan()
                ->with('kelas')
                ->get()
                ->map(fn($u) => $u->kelas->nama_kelas ?? null)
                ->filter()
                ->unique()
                ->values()
                ->implode(' & ') ?: 'Guru Pembimbing';
        }
    @endphp

    <div class="flex h-full">
        {{-- Sidebar --}}
        @include('co_admin.layouts.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            {{-- Topbar --}}
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between sticky top-0 z-10 shadow-xs">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-xl bg-gray-100 text-gray-600 hover:bg-gray-200 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <h1 class="text-xl font-bold text-[#1E1E2D] tracking-tight flex items-center gap-2">
                        <span>@yield('title', 'Dashboard') — {{ auth()->user()->name }}</span>
                    </h1>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:block text-xs font-semibold text-gray-500">
                        Kelas binaan: <span class="text-[#1E1E2D] font-bold">{{ $kelasBinaanNames }}</span>
                    </div>

                    @php
                        $words = explode(' ', auth()->user()->name);
                        $initials = strtoupper(substr($words[0] ?? 'C', 0, 1) . substr($words[1] ?? 'A', 0, 1));
                    @endphp
                    <div class="w-9 h-9 rounded-full bg-[#F97316] flex items-center justify-center font-bold text-xs text-white shadow-xs">
                        {{ $initials }}
                    </div>
                </div>
            </header>

            {{-- Flash Messages --}}
            <div class="px-6 pt-4">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-sm text-emerald-800 flex items-center gap-2" x-data x-init="setTimeout(() => $el.remove(), 4000)">
                        <span>✅</span> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-sm text-rose-800 flex items-center gap-2">
                        <span>❌</span> {{ session('error') }}
                    </div>
                @endif
            </div>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
