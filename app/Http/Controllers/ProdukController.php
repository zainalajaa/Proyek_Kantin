<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Tampilkan daftar produk (admin)
     */
   public function index()
    {
        $produks = Produk::with('kategori')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('produk.lihat', compact('produks'));
    }

    /**
     * Form tambah produk
     */
    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();

        return view('produk.tambah', compact('kategori'));
    }

    /**
     * Simpan produk baru
     */
    public function store(Request $request)
    {
        // dd($request->kategori_id);
    
        $data = $request->validate([
            'nama_produk'   => 'required|string|max:255',
            'kategori_id'   => 'required|exists:kategori,id',
            'stok'          => 'required|integer|min:0',
            'harga'         => 'required|integer|min:0',
            'gambar_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('gambar_produk')) {
            $data['gambar_produk'] = $request
                ->file('gambar_produk')
                ->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()
            ->route('admin.produk.lihat')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Form edit produk
     */
    public function edit(Produk $produk)
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();

        return view('produk.edit', compact('produk', 'kategori'));
    }

    /**
     * Update produk
     */
    public function update(Request $request, Produk $produk)
    {
        $data = $request->validate([
            'nama_produk'   => 'required|string|max:255',
            'kategori_id'   => 'required|exists:kategori,id',
            'stok'          => 'required|integer|min:0',
            'harga'         => 'required|integer|min:0',
            'gambar_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Jika upload gambar baru
        if ($request->hasFile('gambar_produk')) {

            // Hapus gambar lama jika ada
            if ($produk->gambar_produk &&
                Storage::disk('public')->exists($produk->gambar_produk)) {
                Storage::disk('public')->delete($produk->gambar_produk);
            }

            $data['gambar_produk'] = $request
                ->file('gambar_produk')
                ->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()
            ->route('admin.produk.lihat')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk
     */
    public function destroy(Produk $produk)
    {
        if ($produk->gambar_produk &&
            Storage::disk('public')->exists($produk->gambar_produk)) {
            Storage::disk('public')->delete($produk->gambar_produk);
        }

        $produk->delete();

        return redirect()
            ->route('admin.produk.lihat')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Halaman publik daftar produk
     */
    public function publicPage()
    {
        $produks = Produk::with('kategori')
            ->orderBy('nama_produk')
            ->paginate(12);

        return view('publik.index', compact('produks'));
    }

    /**
     * Halaman checkout publik
     */
    public function publikCheckout()
    {
        $produks   = Produk::with('kategori')->paginate(20);
        $cartCount = session('cart_count', 0);

        return view('publik.daftar-produk', compact('produks', 'cartCount'));
    }
}



