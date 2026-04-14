<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
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
   public function submitQris(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            // VALIDASI FILE
            $request->validate([
                'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048'
            ]);

            $penjualan = Penjualan::with('details')
                ->lockForUpdate()
                ->findOrFail($id);

            // HITUNG TOTAL
            $totalReal = $penjualan->details->sum(function ($detail) {
                return $detail->jumlah * $detail->harga_satuan;
            });

            // 🔥 SIMPAN FILE (INI YANG PALING PENTING)
            $path = $request->file('payment_proof')
                ->store('bukti_pembayaran', 'public');

            // 🔥 UPDATE PENJUALAN
            $penjualan->update([
                'total_harga'       => $totalReal,
                'metode_pembayaran' => 'qris',
                'bukti_pembayaran'  => $path,
                'paid_amount'       => $totalReal, // 🔥 isi seperti tunai
                'paid_at'           => now(),      // 🔥 isi waktu bayar
                'status'            => 'sukses',   // 🔥 langsung sukses
            ]);

            DB::commit();

            return redirect()->route('publik.transaksi.selesai', $penjualan->id);

        } catch (\Throwable $e) {

            DB::rollBack();
            report($e);

            return back()->with('error', 'Gagal upload bukti pembayaran');
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
