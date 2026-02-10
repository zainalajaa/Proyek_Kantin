<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranPublikController extends Controller
{
    // Menampilkan halaman QRIS
    public function showQris(Request $request, $id)
    {
        $penjualan = Penjualan::with('details.produk')->findOrFail($id);

        // Ambil total dari parameter URL jika ada
        $total = $request->get('total');

        if ($total) {
            // Update total di database agar sinkron
            $penjualan->update([
                'total_harga' => $total
            ]);
        } else {
            // Kalau tidak ada, hitung manual
            $total = $penjualan->details->sum(function($d) {
                return $d->jumlah * $d->harga_satuan;
            });

            $penjualan->update([
                'total_harga' => $total
            ]);
        }

        return view('publik.qris', [
            'penjualan' => $penjualan,
            'total'     => $total
        ]);
    }

    // Tombol "Saya Sudah Membayar"
    public function submitQris(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $penjualan = Penjualan::with('details')->findOrFail($id);

            // HITUNG TOTAL SEMUA DETAIL
            $totalReal = 0;

            foreach ($penjualan->details as $detail) {
                $totalReal += $detail->jumlah * $detail->harga_satuan;
            }

            // UPDATE TOTAL DI DATABASE
            $penjualan->update([
                'total_harga' => $totalReal
            ]);

            // UPDATE STATUS
            $penjualan->update([
                'metode_pembayaran' => 'qris',
                'paid_amount'       => $totalReal,
                'paid_at'           => now(),
                'status'            => 'sukses',
            ]);

            DB::commit();

            return redirect()->route('publik.transaksi.selesai', $penjualan->id);

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->with('error', 'Terjadi kesalahan.');
        }
    }



    // Halaman selesai
    public function transaksiSelesai($id)
    {
        $penjualan = Penjualan::with('details')->findOrFail($id);

        return view('publik.transaksi_selesai', compact('penjualan'));
    }

}
