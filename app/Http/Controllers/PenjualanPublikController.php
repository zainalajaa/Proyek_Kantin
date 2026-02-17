<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PenjualanPublikController extends Controller
{
    /**
     * Membuat transaksi baru saat klik BELI produk
     */
    public function beli(Request $request, $id)
    {
        $produk = Produk::where('id_produk', $id)->firstOrFail();

        if ($produk->stok <= 0) {
            return back()->with('error', 'Stok produk habis.');
        }

        DB::beginTransaction();

        try {
            $penjualan = Penjualan::create([
                'waktu'             => now(),
                'total_harga'       => $produk->harga,
                'metode_pembayaran' => 'tunai',
                'status'            => 'pending',
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

            return redirect()->route('publik.tunai.detail', $penjualan->id);

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->with('error', 'Terjadi kesalahan saat memproses pembelian.');
        }
    }

    /**
     * Menampilkan halaman detail checkout
     */
    public function tunaiDetail(Penjualan $penjualan)
    {
        if ($penjualan->status !== 'pending') {
            return redirect()->route('publik.index')
                ->with('error', 'Transaksi tidak valid untuk pembayaran.');
        }

        $details = $penjualan->details()->get();

        return view('publik.tunai_detail', compact('penjualan', 'details'));
    }


    public function tambahProduk(Request $request, Penjualan $penjualan)
    {
        $request->validate([
            'id_produk' => 'required'
        ]);

        $produk = Produk::where('id_produk', $request->id_produk)->firstOrFail();

        if ($produk->stok <= 0) {
            return back()->with('error', 'Stok produk habis.');
        }

        DB::beginTransaction();

        try {

            // Cek apakah produk sudah ada di detail
            $detail = $penjualan->details()
                ->where('id_produk', $produk->id_produk)
                ->first();

            if ($detail) {
                // Jika sudah ada, tambah qty
                $detail->jumlah += 1;
                $detail->subtotal = $detail->jumlah * $detail->harga_satuan;
                $detail->save();
            } else {
                // Jika belum ada, buat detail baru
                $penjualan->details()->create([
                    'id_produk'    => $produk->id_produk,
                    'nama_produk'  => $produk->nama_produk,
                    'harga_satuan' => $produk->harga,
                    'jumlah'       => 1,
                    'subtotal'     => $produk->harga,
                ]);
            }

            // Update total harga transaksi
            $total = $penjualan->details()->sum('subtotal');

            $penjualan->update([
                'total_harga' => $total
            ]);

            DB::commit();

            return back()->with('success', 'Produk berhasil ditambahkan ke transaksi.');

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->with('error', 'Gagal menambahkan produk.');
        }
    }

    /**
     * Proses tombol BAYAR atau KIRIM KE KERANJANG
     */
    public function bayar(Request $request, Penjualan $penjualan)
    {
        $action = $request->input('action', 'pay');

        // ====== KIRIM KE KERANJANG ======
        if ($action === 'cart') {
            return $this->kirimKeKeranjang($request, $penjualan);
        }

        // ====== PROSES PEMBAYARAN ======
        return $this->prosesPembayaran($request, $penjualan);
    }

    /**
     * Fungsi kirim produk ke session keranjang
     */
    private function kirimKeKeranjang(Request $request, Penjualan $penjualan)
    {
        $itemsInput = $request->input('items', []);
        $cart = Session::get('cartItems', []);

        foreach ($itemsInput as $detailId => $row) {

            $jumlah = max((int)($row['jumlah'] ?? 1), 1);

            $detail = PenjualanDetail::find($detailId);

            if (!$detail) continue;

            $key = $detail->id_produk;

            if (isset($cart[$key])) {
                $cart[$key]['jumlah'] += $jumlah;
            } else {
                $cart[$key] = [
                    'id_produk'    => $detail->id_produk,
                    'nama_produk'  => $detail->nama_produk,
                    'harga_satuan' => $detail->harga_satuan,
                    'jumlah'       => $jumlah,
                ];
            }

            $cart[$key]['subtotal'] =
                $cart[$key]['jumlah'] * $cart[$key]['harga_satuan'];
        }

        Session::put('cartItems', $cart);
        Session::put('cart_total', collect($cart)->sum('subtotal'));
        Session::put('cart_count', collect($cart)->sum('jumlah'));


        return redirect()
            ->route('cart.index')
            ->with('success', 'Produk berhasil dikirim ke keranjang.');
    }

    /**
     * Proses utama pembayaran (Tunai / QRIS)
     */
    private function prosesPembayaran(Request $request, Penjualan $penjualan)
    {
        $metode = $request->input('metode_pembayaran', 'tunai');

        DB::beginTransaction();

        try {

            // Lock penjualan agar tidak race condition
            $penjualan = Penjualan::with('details')
                ->lockForUpdate()
                ->findOrFail($penjualan->id);

            if (!$request->has('items') || empty($request->items)) {
                throw new \Exception("Data item tidak ditemukan.");
            }

            // =========================
            // UPDATE DETAIL & VALIDASI
            // =========================
            foreach ($penjualan->details as $detail) {

                if (!isset($request->items[$detail->id]['jumlah'])) {
                    throw new \Exception("Jumlah produk tidak lengkap.");
                }

                $qty = (int)$request->items[$detail->id]['jumlah'];

                if ($qty < 1) {
                    throw new \Exception("Jumlah produk tidak valid.");
                }

                // Cek stok dengan lock
                $produk = Produk::where('id_produk', $detail->id_produk)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($produk->stok < $qty) {
                    throw new \Exception("Stok {$produk->nama_produk} tidak mencukupi.");
                }

                // Update detail
                $detail->update([
                    'jumlah'   => $qty,
                    'subtotal' => $qty * $detail->harga_satuan
                ]);
            }

            // =========================
            // HITUNG TOTAL FINAL DARI DB
            // =========================
            $totalFinal = $penjualan->details()->sum('subtotal');

            // =========================
            // METODE TUNAI
            // =========================
            if ($metode === 'tunai') {

                $request->validate([
                    'jumlah_bayar' => 'required|integer|min:' . $totalFinal,
                ]);

                // Kurangi stok
                foreach ($penjualan->details as $detail) {
                    Produk::where('id_produk', $detail->id_produk)
                        ->decrement('stok', $detail->jumlah);
                }

                $penjualan->update([
                    'total_harga'       => $totalFinal,
                    'metode_pembayaran' => 'tunai',
                    'paid_amount'       => $request->jumlah_bayar,
                    'paid_at'           => now(),
                    'status'            => 'sukses',
                ]);

            }
            // =========================
            // METODE QRIS
            // =========================
            else {

                $penjualan->update([
                    'total_harga'       => $totalFinal,
                    'metode_pembayaran' => 'qris',
                    'paid_amount'       => null,
                    'paid_at'           => null,
                    'status'            => 'pending_qris',
                ]);
            }

            DB::commit();

            return $metode === 'tunai'
                ? redirect()->route('publik.transaksi.selesai', $penjualan->id)
                : redirect()->route('publik.qris.show', $penjualan->id);

        } catch (\Throwable $e) {

            DB::rollBack();
            report($e);

            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran.');
        }
    }



}
