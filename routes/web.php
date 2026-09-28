<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterTokoController;
use App\Http\Controllers\Auth\RegisterPembeliController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\CoAdmin\DashboardController as CoAdminDashboard;
use App\Http\Controllers\Toko\DashboardController as TokoDashboard;
use App\Http\Controllers\Toko\ProdukController as TokoProduk;
use App\Http\Controllers\Toko\PesananController as TokoPesanan;
use App\Http\Controllers\Toko\WalletController as TokoWallet;
use App\Http\Controllers\Pembeli\KatalogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Direct Katalog Publik (tanpa landing page)
Route::get('/', [KatalogController::class, 'index'])->name('home');

// Katalog publik (bisa diakses tanpa login)
Route::prefix('katalog')->name('katalog.')->group(function () {
    Route::get('/', [KatalogController::class, 'index'])->name('index');
    Route::get('/{slug}', [KatalogController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Login, Register)
|--------------------------------------------------------------------------
*/

// Override redirect setelah login
Route::get('/dashboard', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->getDashboardRoute());
    }
    return redirect()->route('login');
})->middleware('auth')->name('dashboard');

// Register Toko (siswa kelas 11)
Route::prefix('register')->name('register.')->group(function () {
    Route::get('/toko', [RegisterTokoController::class, 'showForm'])->name('toko');
    Route::post('/toko', [RegisterTokoController::class, 'register'])->name('toko.store');
    Route::get('/pembeli', [RegisterPembeliController::class, 'showForm'])->name('pembeli');
    Route::post('/pembeli', [RegisterPembeliController::class, 'register'])->name('pembeli.store');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // User management
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

        // Toko management
        Route::get('/toko', [\App\Http\Controllers\Admin\TokoController::class, 'index'])->name('toko.index');
        Route::get('/toko/{toko}', [\App\Http\Controllers\Admin\TokoController::class, 'show'])->name('toko.show');
        Route::patch('/toko/{toko}/approve', [\App\Http\Controllers\Admin\TokoController::class, 'approve'])->name('toko.approve');
        Route::patch('/toko/{toko}/suspend', [\App\Http\Controllers\Admin\TokoController::class, 'suspend'])->name('toko.suspend');
        Route::patch('/toko/{toko}/unsuspend', [\App\Http\Controllers\Admin\TokoController::class, 'unsuspend'])->name('toko.unsuspend');

        // Produk approval
        Route::get('/produk', [\App\Http\Controllers\Admin\ProdukApprovalController::class, 'index'])->name('produk.index');
        Route::get('/produk/{produk}', [\App\Http\Controllers\Admin\ProdukApprovalController::class, 'show'])->name('produk.show');
        Route::patch('/produk/{produk}/approve', [\App\Http\Controllers\Admin\ProdukApprovalController::class, 'approve'])->name('produk.approve');
        Route::patch('/produk/{produk}/reject', [\App\Http\Controllers\Admin\ProdukApprovalController::class, 'reject'])->name('produk.reject');

        // Kategori
        Route::resource('kategori', \App\Http\Controllers\Admin\KategoriController::class);

        // Laporan
        Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export', [\App\Http\Controllers\Admin\LaporanController::class, 'export'])->name('laporan.export');

        // Awards
        Route::get('/awards', [\App\Http\Controllers\Admin\AwardController::class, 'index'])->name('awards.index');
        Route::post('/awards', [\App\Http\Controllers\Admin\AwardController::class, 'store'])->name('awards.store');
        Route::delete('/awards/{award}', [\App\Http\Controllers\Admin\AwardController::class, 'destroy'])->name('awards.destroy');

        // Wallet monitor
        Route::get('/wallet', [\App\Http\Controllers\Admin\WalletMonitorController::class, 'index'])->name('wallet.index');
    });

/*
|--------------------------------------------------------------------------
| Co-Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('co-admin')
    ->name('co-admin.')
    ->middleware(['auth', 'role:co_admin'])
    ->group(function () {
        Route::get('/dashboard', [CoAdminDashboard::class, 'index'])->name('dashboard');

        // Toko binaan
        Route::get('/toko', [\App\Http\Controllers\CoAdmin\TokoController::class, 'index'])->name('toko.index');
        Route::get('/toko/{toko}', [\App\Http\Controllers\CoAdmin\TokoController::class, 'show'])->name('toko.show');
        Route::patch('/toko/{toko}/suspend', [\App\Http\Controllers\CoAdmin\TokoController::class, 'suspend'])->name('toko.suspend');

        // Monitoring Produk Siswa Binaan
        Route::get('/produk', [\App\Http\Controllers\CoAdmin\ProdukController::class, 'index'])->name('produk.index');
        Route::get('/produk/{produk}', [\App\Http\Controllers\CoAdmin\ProdukController::class, 'show'])->name('produk.show');

        // Laporan kelas binaan
        Route::get('/laporan', [\App\Http\Controllers\CoAdmin\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/awards', [\App\Http\Controllers\CoAdmin\AwardController::class, 'index'])->name('awards.index');
    });

/*
|--------------------------------------------------------------------------
| Toko (Siswa) Routes  — PWA
|--------------------------------------------------------------------------
*/

Route::prefix('toko')
    ->name('toko.')
    ->middleware(['auth', 'role:toko'])
    ->group(function () {
        Route::get('/dashboard', [TokoDashboard::class, 'index'])->name('dashboard');

        // Produk
        Route::resource('produk', TokoProduk::class);

        // Pesanan
        Route::get('/pesanan', [TokoPesanan::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/{pesanan}', [TokoPesanan::class, 'show'])->name('pesanan.show');
        Route::patch('/pesanan/{pesanan}/konfirmasi', [TokoPesanan::class, 'konfirmasi'])->name('pesanan.konfirmasi');
        Route::patch('/pesanan/{pesanan}/selesai', [TokoPesanan::class, 'selesai'])->name('pesanan.selesai');
        Route::patch('/pesanan/{pesanan}/tolak', [TokoPesanan::class, 'tolak'])->name('pesanan.tolak');

        // Wallet
        Route::get('/wallet', [TokoWallet::class, 'index'])->name('wallet.index');

        // Profil toko
        Route::get('/profil', [\App\Http\Controllers\Toko\ProfilController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [\App\Http\Controllers\Toko\ProfilController::class, 'update'])->name('profil.update');
    });

/*
|--------------------------------------------------------------------------
| Pembeli Routes — PWA
|--------------------------------------------------------------------------
*/

Route::prefix('pembeli')
    ->name('pembeli.')
    ->middleware(['auth', 'role:pembeli'])
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Pembeli\DashboardController::class, 'index'])->name('dashboard');

        // Keranjang
        Route::get('/keranjang', [\App\Http\Controllers\Pembeli\KeranjangController::class, 'index'])->name('keranjang.index');
        Route::post('/keranjang', [\App\Http\Controllers\Pembeli\KeranjangController::class, 'store'])->name('keranjang.store');
        Route::patch('/keranjang/{id}', [\App\Http\Controllers\Pembeli\KeranjangController::class, 'update'])->name('keranjang.update');
        Route::delete('/keranjang/{id}', [\App\Http\Controllers\Pembeli\KeranjangController::class, 'destroy'])->name('keranjang.destroy');

        // Pesanan
        Route::get('/pesanan', [\App\Http\Controllers\Pembeli\PesananController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/checkout', [\App\Http\Controllers\Pembeli\PesananController::class, 'checkout'])->name('pesanan.checkout');
        Route::post('/pesanan', [\App\Http\Controllers\Pembeli\PesananController::class, 'store'])->name('pesanan.store');
        Route::get('/pesanan/{pesanan}', [\App\Http\Controllers\Pembeli\PesananController::class, 'show'])->name('pesanan.show');

        // Review
        Route::post('/produk/{produk}/review', [\App\Http\Controllers\Pembeli\ReviewController::class, 'store'])->name('review.store');
    });

/*
|--------------------------------------------------------------------------
| Shared Auth Routes (Profile)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
