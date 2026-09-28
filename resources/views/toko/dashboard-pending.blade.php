@extends('toko.layouts.app')
@section('title', 'Dashboard Toko')
@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="text-center max-w-md">
        @if($toko && $toko->status === 'suspend')
            <div class="text-6xl mb-5">🚫</div>
            <h2 class="text-xl font-bold text-gray-800">Toko Kamu Disuspend</h2>
            <p class="text-gray-500 mt-2 text-sm">Toko kamu sedang dalam status suspend. Silakan hubungi guru pembimbing / KIK untuk informasi lebih lanjut.</p>
            @if($toko->alasan_suspend)
                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-2xl inline-block text-left w-full">
                    <p class="text-xs font-semibold text-red-600 uppercase tracking-wide">Alasan Suspend:</p>
                    <p class="text-sm text-red-700 mt-1">{{ $toko->alasan_suspend }}</p>
                </div>
            @endif
        @else
            <div class="w-24 h-24 mx-auto mb-5 rounded-3xl bg-orange-100 flex items-center justify-center">
                <span class="text-5xl">⏳</span>
            </div>
            <h2 class="text-xl font-bold text-gray-800">Menunggu Persetujuan Admin</h2>
            <p class="text-gray-500 mt-2 text-sm leading-relaxed">
                Pendaftaran toko <strong class="text-gray-700">{{ $toko ? $toko->nama_toko : '' }}</strong> kamu sedang dalam proses review.
                Admin sekolah akan segera mereview dan mengaktifkan tokomu.
            </p>
            <div class="mt-5 p-4 bg-orange-50 border border-orange-200 rounded-2xl text-left">
                <p class="text-xs font-semibold text-orange-700 mb-2">📋 Langkah selanjutnya:</p>
                <ul class="text-xs text-orange-600 space-y-1">
                    <li>✅ Pendaftaran toko berhasil dikirim</li>
                    <li>⏳ Menunggu verifikasi admin / guru KIK</li>
                    <li>🔓 Setelah disetujui, kamu bisa mulai berjualan</li>
                </ul>
            </div>
        @endif
    </div>
</div>
@endsection
