<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\PenjualanController;
use App\Http\Controllers\PenjualanPublikController;
use App\Http\Controllers\PembayaranPublikController;
use App\Http\Controllers\StockCheckController;


/*
|--------------------------------------------------------------------------
| AUTH ADMIN ROUTES
|--------------------------------------------------------------------------
*/

// Hanya boleh diakses jika BELUM login sebagai admin
Route::middleware('guest:admin')->group(function () {

    Route::get('/admin/login', [AuthController::class, 'showLoginForm'])
        ->name('admin.login.form');

    Route::post('/admin/login', [AuthController::class, 'login'])
        ->name('admin.login');
});


// Hanya boleh diakses jika SUDAH login sebagai admin
Route::middleware('auth:admin')->group(function () {

    // Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
    //     ->name('admin.dashboard');
    

    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->name('admin.dashboard');


    Route::post('/admin/logout', [AuthController::class, 'logout'])
        ->name('admin.logout');
});


/*
|--------------------------------------------------------------------------
| ADMIN AREA ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth:admin')
    ->group(function () {

        // PRODUK
        Route::get('/produk', [ProdukController::class, 'index'])
            ->name('produk.lihat');

        Route::get('/produk/tambah', [ProdukController::class, 'create'])
            ->name('produk.tambah');

        Route::post('/produk', [ProdukController::class, 'store'])
            ->name('produk.store');

        Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])
            ->name('produk.edit');

        Route::put('/produk/{produk}', [ProdukController::class, 'update'])
            ->name('produk.update');

        Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])
            ->name('produk.destroy');


        // PENJUALAN (ADMIN)
        Route::get('/penjualan', [PenjualanController::class, 'index'])
            ->name('penjualan.index');

        Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])
            ->name('penjualan.show');


        // HALAMAN TAMBAHAN ADMIN
        // Route::view('/pengguna', 'admin.pengguna')
        //     ->name('pengguna');

        // Route::view('/pengaturan', 'admin.pengaturan')
        //     ->name('pengaturan');

        Route::get('/monitoring-stock', [StockCheckController::class, 'index'])
            ->name('monitoring_stok');

        Route::post('/monitoring-stock', [StockCheckController::class, 'store'])
            ->name('monitoring_stok.store');



        Route::get('/monitoring-stock/riwayat',[StockCheckController::class, 'riwayat']
            )->name('monitoring_stok.riwayat');
});


/*
|--------------------------------------------------------------------------
| PUBLIC (PEMBELI) ROUTES
|--------------------------------------------------------------------------
*/

// Halaman utama pembeli
Route::get('/', [ProdukController::class, 'publicPage'])
    ->name('publik.index');



// BELI PRODUK LANGSUNG
Route::post('/beli/{id}', [PenjualanPublikController::class, 'beli'])
    ->name('publik.beli');


/*
|--------------------------------------------------------------------------
| CART & CHECKOUT ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/cart', [CheckoutController::class, 'index'])
    ->name('cart.index');

Route::post('/cart/add/{id}', [CheckoutController::class, 'add'])
    ->name('cart.add');

Route::post('/cart/update', [CheckoutController::class, 'update'])
    ->name('cart.update');

Route::post('/cart/checkout', [CheckoutController::class, 'checkout'])
    ->name('cart.checkout');

Route::post('/cart/remove', [CheckoutController::class, 'remove'])
    ->name('cart.remove');


Route::post('/publik/transaksi/{penjualan}/tambah-produk', 
    [PenjualanPublikController::class, 'tambahProduk']
)->name('publik.transaksi.tambahProduk');


/*
|--------------------------------------------------------------------------
| PEMBAYARAN (TUNAI & QRIS)
|--------------------------------------------------------------------------
*/

// DETAIL PEMBAYARAN TUNAI
Route::get('/tunai/{penjualan}', 
    [PenjualanPublikController::class, 'tunaiDetail']
)->name('publik.tunai.detail');

Route::post('/tunai/{penjualan}/bayar', 
    [PenjualanPublikController::class, 'bayar']
)->name('publik.tunai.bayar');


// PEMBAYARAN QRIS
Route::get('/pembayaran/{id}/qris', 
    [PembayaranPublikController::class, 'showQris']
)->name('publik.qris.show');

Route::post('/pembayaran/{id}/qris', 
    [PembayaranPublikController::class, 'submitQris']
)->name('publik.qris.submit');


// TRANSAKSI SELESAI
Route::get('/publik/transaksi/selesai/{id}', 
    [PembayaranPublikController::class, 'transaksiSelesai']
)->name('publik.transaksi.selesai');


