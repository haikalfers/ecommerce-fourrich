{{-- Toko Sidebar — Panel Penjual Siswa --}}
<aside class="w-64 bg-white text-gray-700 flex flex-col shrink-0
              fixed inset-y-0 left-0 z-20 lg:static lg:translate-x-0
              border-r border-gray-200/80 shadow-md lg:shadow-none transition-transform duration-300"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

    {{-- Brand Header / Logo Toko --}}
    <div class="px-5 py-5 border-b border-gray-100">
        <div class="flex items-center gap-3">
            @php $toko = auth()->user()->toko; @endphp
            <div class="w-11 h-11 rounded-2xl overflow-hidden bg-orange-50 border-2 flex-shrink-0"
                 style="border-color: #F97316;">
                @if($toko && $toko->logo)
                    <img src="{{ $toko->logo_url }}" class="w-full h-full object-cover" alt="">
                @else
                    <div class="w-full h-full flex items-center justify-center text-xl">🏪</div>
                @endif
            </div>
            <div class="min-w-0">
                <h1 class="font-bold text-sm text-slate-900 leading-tight truncate">
                    {{ $toko ? $toko->nama_toko : 'Toko Saya' }}
                </h1>
                <p class="text-[11px] text-gray-400 mt-0.5">E-Market SMKN 4</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-5 overflow-y-auto">

        {{-- TOKO --}}
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-1.5">Toko</p>
            <div class="space-y-0.5">
                <a href="{{ route('toko.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                          {{ request()->routeIs('toko.dashboard') ? 'toko-sidebar-active' : 'toko-sidebar-link' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('toko.produk.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                          {{ request()->routeIs('toko.produk.*') ? 'toko-sidebar-active' : 'toko-sidebar-link' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Produk saya
                    @php
                        $pendingProduk = $toko ? $toko->produk()->pending()->count() : 0;
                    @endphp
                    @if($pendingProduk > 0)
                        <span class="ml-auto toko-badge">{{ $pendingProduk }}</span>
                    @endif
                </a>
            </div>
        </div>

        {{-- TRANSAKSI --}}
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-1.5">Transaksi</p>
            <div class="space-y-0.5">
                <a href="{{ route('toko.pesanan.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                          {{ request()->routeIs('toko.pesanan.*') ? 'toko-sidebar-active' : 'toko-sidebar-link' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Pesanan masuk
                    @php
                        $pendingPesanan = $toko ? $toko->pesanan()->where('status', 'pending')->count() : 0;
                    @endphp
                    @if($pendingPesanan > 0)
                        <span class="ml-auto toko-badge">{{ $pendingPesanan }}</span>
                    @endif
                </a>

                <a href="{{ route('toko.wallet.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                          {{ request()->routeIs('toko.wallet.*') ? 'toko-sidebar-active' : 'toko-sidebar-link' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Wallet saya
                </a>
            </div>
        </div>

        {{-- LAINNYA --}}
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-1.5">Lainnya</p>
            <div class="space-y-0.5">
                {{-- Produk Reseller (placeholder / future feature) --}}
                <a href="#"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium toko-sidebar-link opacity-50 cursor-not-allowed"
                   title="Segera hadir">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Produk reseller
                    <span class="ml-auto text-[9px] font-bold bg-gray-100 text-gray-400 px-1.5 py-0.5 rounded-full">Soon</span>
                </a>

                {{-- Award --}}
                <a href="#"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium toko-sidebar-link opacity-50 cursor-not-allowed"
                   title="Segera hadir">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    Award saya
                    <span class="ml-auto text-[9px] font-bold bg-gray-100 text-gray-400 px-1.5 py-0.5 rounded-full">Soon</span>
                </a>

                {{-- Laporan --}}
                <a href="#"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium toko-sidebar-link opacity-50 cursor-not-allowed"
                   title="Segera hadir">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Laporan toko
                    <span class="ml-auto text-[9px] font-bold bg-gray-100 text-gray-400 px-1.5 py-0.5 rounded-full">Soon</span>
                </a>
            </div>
        </div>

    </nav>

    {{-- Bottom: Profil & Keluar --}}
    <div class="px-3 py-3 border-t border-gray-100 space-y-0.5">
        <a href="{{ route('toko.profil.edit') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                  {{ request()->routeIs('toko.profil.*') ? 'toko-sidebar-active' : 'toko-sidebar-link' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profil &amp; toko
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-500 hover:bg-red-50 hover:text-red-500 transition-all duration-150">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>
