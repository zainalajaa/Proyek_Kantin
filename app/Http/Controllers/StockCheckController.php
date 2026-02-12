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
        $mode = $request->mode ?? 'harian';
        $tanggal = $request->tanggal;
        $minggu = $request->minggu;
        $bulan = $request->bulan;

        $query = StockCheck::with('produk');

        if ($mode == 'harian' && $tanggal) {

            $query->whereDate('tanggal', $tanggal);

        } elseif ($mode == 'mingguan' && $minggu) {

            $tahun = date('Y');

            $query->whereRaw('WEEK(tanggal, 1) = ?', [$minggu])
                ->whereYear('tanggal', $tahun);

        } elseif ($mode == 'bulanan' && $bulan) {

            $query->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', date('Y'));
        }

        $checks = $query->orderBy('tanggal', 'desc')->get();

        return view('admin.riwayat_stok', compact(
            'checks',
            'mode',
            'tanggal',
            'minggu',
            'bulan'
        ));
    }

}
