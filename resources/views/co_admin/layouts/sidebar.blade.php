{{-- Co-Admin Sidebar — Design System: Charcoal + Orange --}}
<aside class="w-64 flex flex-col shrink-0 transition-transform duration-300
              fixed inset-y-0 left-0 z-20 lg:static lg:translate-x-0 border-r"
    style="background-color: #1E1E2D; border-color: rgba(255, 255, 255, 0.07);"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

    {{-- Brand Header --}}
    <div class="px-6 py-5 border-b" style="border-color: rgba(255, 255, 255, 0.07);">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-[#F97316] flex items-center justify-center text-white font-black text-lg shadow-md shadow-orange-950/40 shrink-0">
                🛒
            </div>
            <div>
                <h1 class="text-white font-bold text-base tracking-tight leading-tight">E-Market</h1>
                <p class="text-xs text-orange-400 font-medium">Co-Admin / Guru</p>
            </div>
        </div>
    </div>

    {{-- Nav Sections --}}
    <nav class="flex-1 px-4 py-4 space-y-6 overflow-y-auto custom-scrollbar">

        {{-- UTAMA --}}
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider px-3 mb-2">Utama</p>
            <div class="space-y-1">
                <a href="{{ route('co-admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                          {{ request()->routeIs('co-admin.dashboard')
                              ? 'bg-[#F97316] text-white font-semibold shadow-md shadow-orange-950/30'
                              : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('co-admin.toko.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                          {{ request()->routeIs('co-admin.toko.*')
                              ? 'bg-[#F97316] text-white font-semibold shadow-md shadow-orange-950/30'
                              : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h6m-6 4h6m-6 4h6"/>
                    </svg>
                    Toko binaan
                </a>
                <a href="{{ route('co-admin.produk.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                          {{ request()->routeIs('co-admin.produk.*')
                              ? 'bg-[#F97316] text-white font-semibold shadow-md shadow-orange-950/30'
                              : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Produk siswa
                </a>
            </div>
        </div>

        {{-- LAPORAN --}}
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider px-3 mb-2">Laporan</p>
            <div class="space-y-1">
                <a href="{{ route('co-admin.laporan.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                          {{ request()->routeIs('co-admin.laporan.*')
                              ? 'bg-[#F97316] text-white font-semibold shadow-md shadow-orange-950/30'
                              : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Laporan kelas
                </a>
                <a href="{{ route('co-admin.awards.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                          {{ request()->routeIs('co-admin.awards.*')
                              ? 'bg-[#F97316] text-white font-semibold shadow-md shadow-orange-950/30'
                              : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm-7 4h14M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2H5z"/>
                    </svg>
                    Award siswa
                </a>
            </div>
        </div>

        {{-- AKUN --}}
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider px-3 mb-2">Akun</p>
            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                          {{ request()->routeIs('profile.edit')
                              ? 'bg-[#F97316] text-white font-semibold shadow-md shadow-orange-950/30'
                              : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profil saya
                </a>
            </div>
        </div>

    </nav>

    {{-- Bottom Section --}}
    <div class="px-4 py-4 border-t" style="border-color: rgba(255, 255, 255, 0.07);">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-300 hover:bg-rose-500/10 hover:text-rose-400 transition">
                <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

{{-- Overlay (mobile) --}}
<div class="fixed inset-0 bg-black/50 backdrop-blur-xs z-10 lg:hidden"
     x-show="sidebarOpen"
     x-transition:enter="transition-opacity duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false">
</div>
