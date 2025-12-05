<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PenjualanPublikController;
use App\Http\Controllers\Admin\PenjualanController;

// Hanya boleh diakses kalau BELUM login sebagai admin
Route::middleware('guest:admin')->group(function () {
    // Form login admin
    Route::get('/admin/login', [AuthController::class, 'showLoginForm'])
        ->name('admin.login.form');

    // Proses login admin
    Route::post('/admin/login', [AuthController::class, 'login'])
        ->name('admin.login');
});

// Hanya boleh diakses kalau SUDAH login sebagai admin
Route::middleware('auth:admin')->group(function () {
    // Dashboard admin
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    // Logout admin
    Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

});

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth:admin')
    ->group(function () {

        // PRODUK
        Route::get('/produk', [ProdukController::class, 'index'])->name('produk.lihat');
        Route::get('/produk/tambah', [ProdukController::class, 'create'])->name('produk.tambah');
        Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');

        // PENJUALAN
        Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');

        // Halaman lain
        Route::view('/pengguna',   'admin.pengguna')->name('pengguna');
        Route::view('/pengaturan', 'admin.pengaturan')->name('pengaturan');
});

// Halaman pembeli (public)
Route::get('/', [ProdukController::class, 'publicPage'])->name('publik.index');
// pembeli (tanpa login)
Route::post('/beli/{id}', [PenjualanPublikController::class, 'beli'])->name('publik.beli');

// Halaman detail pembayaran tunai (form untuk memasukkan jumlah bayar)
Route::get('/tunai/{penjualan}', [PenjualanPublikController::class, 'tunaiDetail'])->name('publik.tunai.detail');

// Proses finalisasi pembayaran tunai
Route::post('/tunai/{penjualan}/bayar', [PenjualanPublikController::class, 'tunaiBayar'])->name('publik.tunai.bayar');





