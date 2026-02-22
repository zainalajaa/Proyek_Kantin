<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\StockCheck;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StockCheckController extends Controller
{
    public function index()
    {
        $produk = Produk::all();

        $today = Carbon::today();

        $checks = StockCheck::with('produk')
                    ->whereDate('tanggal', $today)
                    ->orderBy('created_at', 'desc')
                    ->get();

        // ===============================
        // CEK RIWAYAT MONITORING BERMASALAH
        // ===============================

        $totalProduk = $produk->count();
        $hariKeBelakang = 30; // bisa ubah jadi 7 jika ingin lebih ringan
        $tanggalBermasalah = [];

        for ($i = 1; $i <= $hariKeBelakang; $i++) {

            $tanggal = $today->copy()->subDays($i);

            $jumlahInput = StockCheck::whereDate('tanggal', $tanggal)->count();

            if ($jumlahInput == 0 && $totalProduk > 0) {
                $tanggalBermasalah[] = [
                    'tanggal' => $tanggal->format('d M Y'),
                    'status'  => 'tidak_input'
                ];
            } elseif ($jumlahInput < $totalProduk) {
                $tanggalBermasalah[] = [
                    'tanggal' => $tanggal->format('d M Y'),
                    'status'  => 'tidak_selesai'
                ];
            }
        }

        return view('monitoring.monitoring_stok', compact(
            'produk',
            'checks',
            'tanggalBermasalah'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_produk'  => 'required|exists:produk,id_produk',
            'stok_fisik' => 'required|integer|min:0'
        ]);

        $today = date('Y-m-d');

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
        $selisih     = $stok_sistem - $stok_fisik;

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
        $request->validate([
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $query = StockCheck::with('produk');

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal', [
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        } elseif ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        } elseif ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $checks = $query->orderBy('tanggal', 'desc')->get();

        // kalau riwayat juga dipindah ke monitoring folder, sesuaikan
        return view('admin.riwayat_stok', compact('checks'));
    }

    // =========================
    // FITUR EDIT HARI YANG SAMA
    // =========================

    public function edit($id)
    {
        $check = StockCheck::findOrFail($id);

        if (!Carbon::parse($check->tanggal)->isToday()) {
            return redirect()->route('admin.monitoring_stok')
                ->with('error', 'Data hanya bisa diedit di hari yang sama.');
        }

        // ✅ SESUAIKAN DENGAN FOLDER monitoring
        return view('monitoring.edit', compact('check'));
    }

    public function update(Request $request, $id)
    {
        $check = StockCheck::findOrFail($id);

        if (!Carbon::parse($check->tanggal)->isToday()) {
            return redirect()->route('admin.monitoring_stok')
                ->with('error', 'Data tidak bisa diedit karena bukan hari ini.');
        }

        $request->validate([
            'stok_fisik' => 'required|integer|min:0'
        ]);

        $check->stok_fisik = $request->stok_fisik;
        $check->selisih = $check->stok_sistem - $request->stok_fisik;
        $check->save();

        // ❗ PERBAIKAN: tadi kamu redirect ke route yang salah
        return redirect()->route('admin.monitoring_stok')
            ->with('success', 'Data berhasil diperbarui.');
    }
}