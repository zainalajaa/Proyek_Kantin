<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk = Produk::count();

        $totalMakanan = Produk::whereHas('kategori', function ($q) {
            $q->where('nama_kategori', 'Makanan');
        })->count();

        $totalMinuman = Produk::whereHas('kategori', function ($q) {
            $q->where('nama_kategori', 'Minuman');
        })->count();

        return view('admin.dashboard', compact(
            'totalProduk',
            'totalMakanan',
            'totalMinuman'
        ));
    }
}
