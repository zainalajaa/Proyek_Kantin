<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

// Halaman publik
Route::get('/', function () {
    return view('index');
})->name('home');

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
    Route::post('/admin/logout', [AuthController::class, 'logout'])
        ->name('admin.logout');
});
