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
     * Tambah ke keranjang
     */
    public function add(Request $request, $id)
    {
        $produk = Produk::where('id_produk', $id)->firstOrFail();

        $cart = Session::get('cartItems', []);
        $key  = $produk->id_produk;

        // qty input
        $qty = max((int) $request->input('qty', 1), 1);

        // 🔥 VALIDASI STOK AWAL
        if ($qty > $produk->stok) {
            return back()->with('error', 'Jumlah melebihi stok tersedia.');
        }

        // jika belum ada di cart
        if (!isset($cart[$key])) {
            $cart[$key] = [
                'id_produk'    => $produk->id_produk,
                'nama_produk'  => $produk->nama_produk,
                'harga_satuan' => (int) $produk->harga,
                'jumlah'       => 0,
                'subtotal'     => 0,
                'stok'         => $produk->stok,
            ];
        }

        // cek total qty di cart
        $currentQty = $cart[$key]['jumlah'];

        if (($currentQty + $qty) > $produk->stok) {
            return back()->with('error', 'Total jumlah di keranjang melebihi stok.');
        }

        // update cart
        $cart[$key]['jumlah']  += $qty;
        $cart[$key]['subtotal'] = $cart[$key]['jumlah'] * $cart[$key]['harga_satuan'];

        // hitung ulang
        $cart_total = collect($cart)->sum('subtotal');
        $cart_count = count($cart);

        Session::put('cartItems', $cart);
        Session::put('cart_total', $cart_total);
        Session::put('cart_count', $cart_count);

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /**
     * Checkout produk terpilih
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

            // jika tidak dicentang
            if (!$row) {
                $newCart[$key] = $item;
                continue;
            }

            // ambil qty terbaru
            $qty = max((int)($row['jumlah'] ?? $item['jumlah']), 1);

            // ambil produk terbaru dari DB
            $produk = Produk::where('id_produk', $item['id_produk'])->first();

            // 🔥 VALIDASI STOK REALTIME
            if (!$produk || $qty > $produk->stok) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', 'Stok produk ' . $item['nama_produk'] . ' tidak mencukupi.');
            }

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

        // update sisa cart
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

                $produk = Produk::where('id_produk', $item['id_produk'])->first();

                // 🔥 VALIDASI FINAL (double safety)
                if ($produk->stok < $item['jumlah']) {
                    throw new \Exception('Stok berubah, silakan ulangi transaksi.');
                }

                // kurangi stok
                $produk->stok -= $item['jumlah'];
                $produk->save();

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

    /**
     * Hapus item dari keranjang
     */
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