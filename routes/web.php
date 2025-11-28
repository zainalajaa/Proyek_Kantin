<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukMasukController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PengaturanController;


use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\ProdukController;

// Halaman publik
// Route::get('/', function () {
//     return view('index');
// })->name('home');

// =====================
//  ROUTE ADMIN (AUTH)
// =====================

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

        Route::get('/produk', [ProdukController::class, 'index'])->name('produk.lihat');
        Route::get('/produk/tambah', [ProdukController::class, 'create'])->name('produk.tambah');
        Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
        // Halaman lain…
        Route::view('/penjualan',  'admin.penjualan')->name('penjualan');
        Route::view('/pengguna',   'admin.pengguna')->name('pengguna');
        Route::view('/pengaturan', 'admin.pengaturan')->name('pengaturan');
    });

// Halaman pembeli (public)
Route::get('/', [ProdukController::class, 'publicPage'])->name('publik.index');




