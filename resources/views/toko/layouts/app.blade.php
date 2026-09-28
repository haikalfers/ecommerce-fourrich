<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Toko') — E-Market SMKN 4</title>
    <meta name="description" content="Panel manajemen toko siswa E-Market SMKN 4">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --toko-primary: #F97316;
            --toko-primary-dark: #EA580C;
            --toko-primary-light: #FED7AA;
        }
        .toko-sidebar-active {
            background-color: var(--toko-primary);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(249,115,22,0.35);
        }
        .toko-sidebar-link {
            color: #6b7280;
            transition: all 0.15s ease;
        }
        .toko-sidebar-link:hover {
            background-color: #fff7ed;
            color: var(--toko-primary);
        }
        .toko-badge {
            background-color: #ef4444;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 9999px;
            min-width: 18px;
            text-align: center;
            line-height: 18px;
        }
    </style>
</head>
<body class="h-full bg-gray-50 text-slate-800 font-sans antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-full">
        {{-- Sidebar --}}
        @include('toko.layouts.sidebar')

        {{-- Overlay mobile --}}
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-10 lg:hidden"
             x-show="sidebarOpen"
             x-transition:enter="transition-opacity duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false">
        </div>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            {{-- Topbar --}}
            <header class="bg-white border-b border-gray-200/80 px-4 sm:px-6 py-3.5 flex items-center justify-between sticky top-0 z-10 shadow-sm">
                <div class="flex items-center gap-3">
                    {{-- Mobile menu toggle --}}
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden p-2 rounded-xl bg-orange-50 text-orange-500 hover:bg-orange-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-bold text-slate-900 tracking-tight leading-none">
                            @yield('title', 'Dashboard Toko')
                        </h1>
                        <p class="text-xs text-gray-400 mt-0.5">E-Market SMKN 4</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Notification bell --}}
                    <button class="relative p-2 rounded-xl bg-gray-100 text-gray-500 hover:text-orange-500 hover:bg-orange-50 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @php
                            $pendingCount = optional(auth()->user()->toko)->pesanan()->where('status','pending')->count() ?? 0;
                        @endphp
                        @if($pendingCount > 0)
                            <span class="absolute -top-0.5 -right-0.5 toko-badge">{{ $pendingCount }}</span>
                        @endif
                    </button>

                    {{-- User info --}}
                    <div class="flex items-center gap-2.5 pl-3 border-l border-gray-200">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-slate-800 leading-none">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ optional(auth()->user()->toko)->kelas ?? 'Kelas XI' }}
                                @if(auth()->user()->guru)
                                    · {{ auth()->user()->guru->name }}
                                @endif
                            </p>
                        </div>
                        <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm text-white shadow-sm flex-shrink-0"
                             style="background: linear-gradient(135deg, #F97316, #EA580C);">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                    </div>
                </div>
            </header>

            {{-- Flash Messages --}}
            <div class="px-4 sm:px-6 pt-4 space-y-2">
                @if(session('success'))
                    <div class="p-3.5 bg-emerald-50 border border-emerald-200 rounded-2xl text-sm text-emerald-800 flex items-center gap-2"
                         x-data x-init="setTimeout(() => $el.remove(), 4500)">
                        <span>✅</span> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-2xl text-sm text-rose-800 flex items-center gap-2"
                         x-data x-init="setTimeout(() => $el.remove(), 4500)">
                        <span>❌</span> {{ session('error') }}
                    </div>
                @endif
                @if(session('warning'))
                    <div class="p-3.5 bg-amber-50 border border-amber-200 rounded-2xl text-sm text-amber-800 flex items-center gap-2"
                         x-data x-init="setTimeout(() => $el.remove(), 4500)">
                        <span>⚠️</span> {{ session('warning') }}
                    </div>
                @endif
            </div>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
