<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\StockCheck;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StockCheckController extends Controller
{
    public function index()
    {
        $produk = Produk::all();

        $checks = StockCheck::with('produk')
                    ->whereDate('tanggal', date('Y-m-d'))
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admin.monitoring_stok', compact('produk', 'checks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_produk'  => 'required|exists:produk,id_produk',
            'stok_fisik' => 'required|integer|min:0'
        ]);

        $today = date('Y-m-d');

        // 🔒 CEK DUPLIKASI HARI INI
        $exists = StockCheck::where('id_produk', $request->id_produk)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['Produk sudah dimonitor hari ini.'])
                ->withInput();
        }

        $produk = Produk::where('id_produk', $request->id_produk)
            ->firstOrFail();

        $stok_sistem = $produk->stok;
        $stok_fisik  = $request->stok_fisik;

        $selisih = $stok_sistem - $stok_fisik;

        StockCheck::create([
            'id_produk'   => $produk->id_produk,
            'stok_sistem' => $stok_sistem,
            'stok_fisik'  => $stok_fisik,
            'selisih'     => $selisih,
            'tanggal'     => $today
        ]);

        return back()->with('success', 'Selisih stok berhasil dihitung');
    }   

    public function riwayat(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $query = StockCheck::with('produk');

        // Filter berdasarkan date range
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {

            $query->whereBetween('tanggal', [
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);

        } elseif ($request->filled('tanggal_mulai')) {

            // Jika hanya tanggal mulai diisi
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);

        } elseif ($request->filled('tanggal_selesai')) {

            // Jika hanya tanggal selesai diisi
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $checks = $query->orderBy('tanggal', 'desc')->get();

        return view('admin.riwayat_stok', compact('checks'));
    }


}
