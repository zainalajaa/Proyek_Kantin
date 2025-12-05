<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanPublikController extends Controller
{
    public function beli(Request $request, $id)
    {
        $request->validate([
            'metode' => 'required|in:tunai,qris',
        ]);

        // Ambil produk
        $produk = Produk::findOrFail($id);

        if ($produk->stok <= 0) {
            return back()->with('error', 'Stok habis.');
        }

        if ($request->metode === 'qris') {
            // Proses instant (QRIS) -> kurangi stok langsung dan tandai sukses
            DB::beginTransaction();
            try {
                // kurangi stok
                $produk->decrement('stok');

                $penjualan = Penjualan::create([
                    'waktu'             => now(),
                    'total_harga'       => $produk->harga,
                    'metode_pembayaran' => 'qris',
                    'status'            => 'sukses',
                ]);

                PenjualanDetail::create([
                    'id_penjualan' => $penjualan->id,
                    'id_produk'    => $produk->id_produk,
                    'nama_produk'  => $produk->nama_produk,
                    'harga_satuan' => $produk->harga,
                    'jumlah'       => 1,
                    'subtotal'     => $produk->harga,
                ]);

                DB::commit();
                return back()->with('success', 'Pembelian berhasil via QRIS.');
            } catch (\Throwable $e) {
                DB::rollBack();
                report($e);
                return back()->with('error', 'Terjadi kesalahan saat memproses pembelian.');
            }
        }

        // Jika tunai -> buat transaksi pending dan redirect ke halaman input jumlah bayar
        DB::beginTransaction();
        try {
            $penjualan = Penjualan::create([
                'waktu'             => now(),
                'total_harga'       => $produk->harga,
                'metode_pembayaran' => 'tunai',
                'status'            => 'pending', // belum dibayar
            ]);

            PenjualanDetail::create([
                'id_penjualan' => $penjualan->id,
                'id_produk'    => $produk->id_produk,
                'nama_produk'  => $produk->nama_produk,
                'harga_satuan' => $produk->harga,
                'jumlah'       => 1,
                'subtotal'     => $produk->harga,
            ]);

            DB::commit();

            // redirect ke halaman tunai (form input jumlah bayar)
            return redirect()->route('publik.tunai.detail', $penjualan->id);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat memproses pembelian.');
        }
    }

    // Menampilkan halaman detail pembayaran tunai
    public function tunaiDetail(Penjualan $penjualan)
    {
        // Pastikan ini transaksi tunai & pending
        if ($penjualan->metode_pembayaran !== 'tunai' || $penjualan->status !== 'pending') {
            return redirect()->route('publik.index')->with('error', 'Transaksi tidak valid untuk pembayaran tunai.');
        }

        $details = $penjualan->details()->get();

        return view('publik.tunai_detail', compact('penjualan', 'details'));
    }

    // Proses akhir pembayaran tunai
    public function tunaiBayar(Request $request, Penjualan $penjualan)
    {
        $request->validate([
            'jumlah_bayar' => 'required|integer|min:0',
        ]);

        // Pastikan masih pending dan metode tunai
        if ($penjualan->metode_pembayaran !== 'tunai' || $penjualan->status !== 'pending') {
            return back()->with('error', 'Transaksi tidak valid.');
        }

        $jumlahBayar = (int) $request->jumlah_bayar;
        $total = (int) $penjualan->total_harga;

        if ($jumlahBayar < $total) {
            return back()->with('error', 'Jumlah bayar kurang dari total.');
        }

        DB::beginTransaction();
        try {
            // kurangi stok sekarang (untuk tiap detail)
            foreach ($penjualan->details as $det) {
                $produk = Produk::where('id_produk', $det->id_produk)->first();
                if (!$produk) {
                    throw new \Exception("Produk tidak ditemukan.");
                }
                if ($produk->stok < $det->jumlah) {
                    throw new \Exception("Stok produk '{$produk->nama_produk}' tidak mencukupi.");
                }
                $produk->decrement('stok', $det->jumlah);
            }

            // update penjualan
            $penjualan->update([
                'paid_amount' => $jumlahBayar,
                'paid_at'     => now(),
                'status'      => 'sukses',
            ]);

            DB::commit();

            $kembalian = $jumlahBayar - $total;
            return redirect()->route('publik.index')->with('success', "Pembayaran sukses. Kembalian: Rp {$kembalian}");
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran tunai: ' . $e->getMessage());
        }
    }
}
