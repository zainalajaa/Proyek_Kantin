<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;

class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman keranjang
     */
    public function index()
    {
        $cartItems = Session::get('cartItems', []);
        $cartTotal = Session::get('cart_total', 0);

        return view('publik.cart', compact('cartItems', 'cartTotal'));
    }

    /**
     * Tombol "Tambah ke Keranjang" dari halaman produk
     * route: POST /cart/add/{id}  name: cart.add
     */
    public function add(Request $request, $id)
    {
        // cari produk berdasarkan id_produk
        $produk = Produk::where('id_produk', $id)->firstOrFail();

        $cart = Session::get('cartItems', []);

        $key = $produk->id_produk; // dipakai jadi key array

        if (!isset($cart[$key])) {
            $cart[$key] = [
                'id_produk'    => $produk->id_produk,
                'nama_produk'  => $produk->nama_produk,
                'harga_satuan' => (int) $produk->harga,
                'jumlah'       => 0,
                'subtotal'     => 0,
            ];
        }

        // qty dari form (boleh hidden / input kecil)
        $qty = max((int) $request->input('qty', 1), 1);

        $cart[$key]['jumlah']  += $qty;
        $cart[$key]['subtotal'] = $cart[$key]['jumlah'] * $cart[$key]['harga_satuan'];

        // === HITUNG ULANG TOTAL & JUMLAH JENIS PRODUK ===
        $cart_total = collect($cart)->sum('subtotal'); // total rupiah
        $cart_count = count($cart);                    // JUMLAH PRODUK BERBEDA

        Session::put('cartItems', $cart);
        Session::put('cart_total', $cart_total);
        Session::put('cart_count', $cart_count);

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /**
     * PROSES dari tombol "Bayar Produk Terpilih" di keranjang
     */
    public function checkout(Request $request)
    {
        $cart = Session::get('cartItems', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Keranjang kosong.');
        }

        $itemsInput = $request->input('items', []);

        $selectedItems = [];
        $newCart = [];
        $total = 0;

        foreach ($cart as $key => $item) {

            $row = $itemsInput[$key] ?? null;

            // Kalau tidak ada data dari frontend, anggap tidak dicentang
            if (!$row) {
                $newCart[$key] = $item;
                continue;
            }

            $qty = max((int)($row['jumlah'] ?? $item['jumlah']), 1);
            $isChecked = isset($row['checked']);

            $line = [
                'id_produk'    => $item['id_produk'],
                'nama_produk'  => $item['nama_produk'],
                'harga_satuan' => $item['harga_satuan'],
                'jumlah'       => $qty,
                'subtotal'     => $item['harga_satuan'] * $qty,
            ];

            if ($isChecked) {
                $selectedItems[] = $line;
                $total += $line['subtotal'];
            } else {
                $newCart[$key] = $line;
            }
        }

        // Update sisa keranjang
        Session::put('cartItems', $newCart);
        Session::put('cart_total', collect($newCart)->sum('subtotal'));
        Session::put('cart_count', count($newCart));

        if (empty($selectedItems)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Pilih minimal satu produk untuk dibayar.');
        }

        DB::beginTransaction();
        try {

            $penjualan = Penjualan::create([
                'waktu'             => now(),
                'total_harga'       => $total,
                'metode_pembayaran' => 'tunai',
                'status'            => 'pending',
            ]);

            foreach ($selectedItems as $item) {
                PenjualanDetail::create([
                    'id_penjualan' => $penjualan->id,
                    'id_produk'    => $item['id_produk'],
                    'nama_produk'  => $item['nama_produk'],
                    'harga_satuan' => $item['harga_satuan'],
                    'jumlah'       => $item['jumlah'],
                    'subtotal'     => $item['subtotal'],
                ]);
            }

            DB::commit();

            return redirect()->route('publik.tunai.detail', $penjualan->id);

        } catch (\Throwable $e) {

            DB::rollBack();
            report($e);

            return redirect()
                ->route('cart.index')
                ->with('error', 'Terjadi kesalahan saat membuat transaksi.');
        }
    }


    public function remove(Request $request)
    {
        $key = $request->key;

        $cart = session('cartItems', []);

        if (!array_key_exists($key, $cart)) {
            return response()->json(['success' => false], 404);
        }

        unset($cart[$key]);
        session()->put('cartItems', $cart);

        return response()->json(['success' => true]);
    }


}
