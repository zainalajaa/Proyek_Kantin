<?php

namespace App\Http\Controllers;

use App\Models\Produk;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk = Produk::sum('stok');

        $totalMakanan = Produk::whereHas('kategori', function ($q) {
            $q->where('nama_kategori', 'Makanan');
        })->sum('stok');

        $totalMinuman = Produk::whereHas('kategori', function ($q) {
            $q->where('nama_kategori', 'Minuman');
        })->sum('stok');

        return view('admin.dashboard', compact(
            'totalProduk',
            'totalMakanan',
            'totalMinuman'
        ));
    }
}
