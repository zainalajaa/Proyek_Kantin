<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class PembayaranPublikController extends Controller
{
    // ==============================
    // HALAMAN QRIS
    // ==============================
    public function showQris($id)
    {
        $penjualan = Penjualan::with('details.produk')
            ->findOrFail($id);

        // HITUNG TOTAL DARI DETAIL (SUM SUBTOTAL)
        $total = $penjualan->details->sum(function ($detail) {
            return $detail->jumlah * $detail->harga_satuan;
        });

        // Sinkronkan total di database (jika belum sama)
        if ($penjualan->total_harga != $total) {
            $penjualan->update([
                'total_harga' => $total
            ]);
        }

        return view('publik.qris', [
            'penjualan' => $penjualan,
            'total'     => $total
        ]);
    }

    // ==============================
    // KONFIRMASI QRIS
    // ==============================
    public function submitQris($id)
    {
        DB::beginTransaction();

        try {

            $penjualan = Penjualan::with('details')
                ->lockForUpdate()
                ->findOrFail($id);

            // HITUNG TOTAL REAL DARI DETAIL
            $totalReal = $penjualan->details->sum(function ($detail) {
                return $detail->jumlah * $detail->harga_satuan;
            });

            // VALIDASI STOK SEBELUM DIKURANGI
            foreach ($penjualan->details as $detail) {

                $produk = Produk::where('id_produk', $detail->id_produk)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($produk->stok < $detail->jumlah) {
                    throw new \Exception("Stok {$produk->nama_produk} tidak mencukupi.");
                }
            }

            // KURANGI STOK
            foreach ($penjualan->details as $detail) {
                Produk::where('id_produk', $detail->id_produk)
                    ->decrement('stok', $detail->jumlah);
            }

            // UPDATE PENJUALAN (FINAL STATE)
            $penjualan->update([
                'total_harga'       => $totalReal,
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

            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran QRIS.');
        }
    }

    // ==============================
    // HALAMAN TRANSAKSI SELESAI
    // ==============================
    public function transaksiSelesai($id)
    {
        $penjualan = Penjualan::with('details.produk')
            ->findOrFail($id);

        return view('publik.transaksi_selesai', compact('penjualan'));
    }
}
