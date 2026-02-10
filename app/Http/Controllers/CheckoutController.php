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
        $itemsInput = $request->input('items', []);

        // kalau user hapus semua di frontend
        if (empty($itemsInput)) {
            Session::forget(['cartItems', 'cart_total', 'cart_count']);

            return redirect()
                ->route('cart.index')
                ->with('error', 'Tidak ada produk yang dikirim dari keranjang.');
        }

        $newCart       = []; // sisa keranjang
        $selectedItems = []; // yang dicentang
        $total         = 0;

        foreach ($itemsInput as $key => $row) {
            $idProduk = $row['id_produk']   ?? null;
            $nama     = $row['nama_produk'] ?? '';
            $harga    = (int)($row['harga'] ?? 0);
            $qty      = max((int)($row['jumlah'] ?? 0), 1);

            if (!$idProduk || $harga <= 0) {
                continue;
            }

            $line = [
                'id_produk'    => $idProduk,
                'nama_produk'  => $nama,
                'harga_satuan' => $harga,
                'jumlah'       => $qty,
                'subtotal'     => $harga * $qty,
            ];

            $isChecked = isset($row['checked']);

            if ($isChecked) {
                // masuk transaksi
                $selectedItems[] = $line;
                $total          += $line['subtotal'];
            } else {
                // tetap disimpan di keranjang
                $newCart[$key] = $line;
            }
        }

        // update isi keranjang di session (setelah ada yang dipilih / dihapus)
        $cart_total = collect($newCart)->sum('subtotal');
        $cart_count = count($newCart); // JUMLAH PRODUK BERBEDA YANG MASIH DI KERANJANG

        Session::put('cartItems', $newCart);
        Session::put('cart_total', $cart_total);
        Session::put('cart_count', $cart_count);

        if (empty($selectedItems)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Pilih minimal satu produk untuk dibayar.');
        }

        DB::beginTransaction();
        try {
            // buat penjualan pending (default tunai)
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
                ->with('error', 'Terjadi kesalahan saat membuat transaksi dari keranjang.');
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
