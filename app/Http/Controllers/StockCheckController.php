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
                    ->latest()
                    ->get();

        // ===============================
        // CEK RIWAYAT MONITORING BERMASALAH
        // ===============================
        $totalProduk = $produk->count();
        $hariKeBelakang = 30;
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
        // ✅ VALIDASI + PESAN INDONESIA
        $request->validate([
            'id_produk'  => 'required|exists:produk,id_produk',
            'stok_fisik' => 'required|integer|min:0'
        ], [
            'id_produk.required'  => 'Produk wajib dipilih',
            'id_produk.exists'    => 'Produk tidak valid',
            'stok_fisik.required' => 'Stok fisik wajib diisi',
            'stok_fisik.integer'  => 'Stok harus berupa angka',
            'stok_fisik.min'      => 'Stok tidak boleh kurang dari 0',
        ]);

        $today = Carbon::today();

        $exists = StockCheck::where('id_produk', $request->id_produk)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['id_produk' => 'Produk sudah dimonitor hari ini.'])
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
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai',
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

        return view('admin.riwayat_stok', compact('checks'));
    }

    public function edit($id)
    {
        $check = StockCheck::findOrFail($id);

        if (!Carbon::parse($check->tanggal)->isToday()) {
            return redirect()->route('admin.monitoring_stok')
                ->with('error', 'Data hanya bisa diedit di hari yang sama.');
        }

        return view('monitoring.edit', compact('check'));
    }

    public function update(Request $request, $id)
    {
        $check = StockCheck::findOrFail($id);

        if (!Carbon::parse($check->tanggal)->isToday()) {
            return redirect()->route('admin.monitoring_stok')
                ->with('error', 'Data tidak bisa diedit karena bukan hari ini.');
        }

        // ✅ VALIDASI INDONESIA
        $request->validate([
            'stok_fisik' => 'required|integer|min:0'
        ], [
            'stok_fisik.required' => 'Stok fisik wajib diisi',
            'stok_fisik.integer'  => 'Stok harus berupa angka',
            'stok_fisik.min'      => 'Stok tidak boleh kurang dari 0',
        ]);

        $check->stok_fisik = $request->stok_fisik;
        $check->selisih = $check->stok_sistem - $request->stok_fisik;
        $check->save();

        return redirect()->route('admin.monitoring_stok')
            ->with('success', 'Data berhasil diperbarui.');
    }
}