<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with('kategori')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('produk.lihat', compact('produks'));
    }

    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('produk.tambah', compact('kategori'));
    }

    /**
     * 🔥 STORE (DITAMBAHKAN VALIDASI UNIQUE)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_produk' => [
                'required',
                'string',
                'max:255',
                Rule::unique('produk')->where(function ($query) use ($request) {
                    return $query->where('kategori_id', $request->kategori_id);
                }),
            ],
            'kategori_id'   => 'required|exists:kategori,id',
            'stok'          => 'required|integer|min:0',
            'harga'         => 'required|integer|min:0',
            'gambar_produk' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nama_produk.unique' => 'Produk dengan nama yang sama di kategori ini sudah ada.',
            'gambar_produk.required' => 'Gambar produk wajib diupload.',
            'gambar_produk.image'    => 'File harus berupa gambar.',
            'gambar_produk.mimes'    => 'Format gambar harus JPG, JPEG, atau PNG.',
            'gambar_produk.max'      => 'Ukuran gambar maksimal 2MB.',
        ]);

        $data['gambar_produk'] = $request
            ->file('gambar_produk')
            ->store('produk', 'public');

        Produk::create($data);

        return redirect()
            ->route('admin.produk.lihat')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $produk)
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('produk.edit', compact('produk', 'kategori'));
    }

    /**
     * 🔥 UPDATE (DITAMBAHKAN VALIDASI UNIQUE + IGNORE)
     */
    public function update(Request $request, Produk $produk)
    {
        $data = $request->validate([
            'nama_produk' => [
                'required',
                'string',
                'max:255',
                Rule::unique('produk')
                    ->where(function ($query) use ($request) {
                        return $query->where('kategori_id', $request->kategori_id);
                    })
                    ->ignore($produk->id_produk, 'id_produk'),
            ],
            'kategori_id'   => 'required|exists:kategori,id',
            'stok'          => 'required|integer|min:0',
            'harga'         => 'required|integer|min:0',
            'gambar_produk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nama_produk.unique' => 'Produk dengan nama yang sama di kategori ini sudah ada.',
        ]);

        if ($request->hasFile('gambar_produk')) {

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

    public function publicPage()
    {
        $produks = Produk::with('kategori')
            ->where('stok', '>', 0)
            ->orderBy('nama_produk')
            ->paginate(12);

        return view('publik.index', compact('produks'));
    }

    public function publikCheckout()
    {
        $produks   = Produk::with('kategori')->paginate(20);
        $cartCount = session('cart_count', 0);

        return view('publik.daftar-produk', compact('produks', 'cartCount'));
    }
}