<?php
namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::orderBy('nama_produk')->paginate(10);

        return view('produk.lihat', compact('produks'));
    }

    public function create()
    {
        return view('produk.tambah');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'stok'        => 'required|integer|min:0',
            'harga'       => 'required|integer|min:0',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // handle upload
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('produk', 'public'); // -> "produk/namafile.jpg"
            $data['gambar_produk'] = $path;
        }

        unset($data['gambar']); // jangan simpan field 'gambar' ke DB

        Produk::create($data);

        return redirect()
            ->route('admin.produk.lihat')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $produk)
    {
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $data = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'stok'        => 'required|integer|min:0',
            'harga'       => 'required|integer|min:0',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // kalau upload gambar baru
        if ($request->hasFile('gambar')) {
            // hapus file lama kalau masih ada
            if ($produk->gambar_produk && Storage::disk('public')->exists($produk->gambar_produk)) {
                Storage::disk('public')->delete($produk->gambar_produk);
            }

            $path = $request->file('gambar')->store('produk', 'public');
            $data['gambar_produk'] = $path;
        }

        unset($data['gambar']);

        $produk->update($data);

        return redirect()
            ->route('admin.produk.lihat')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->gambar_produk && Storage::disk('public')->exists($produk->gambar_produk)) {
            Storage::disk('public')->delete($produk->gambar_produk);
        }

        $produk->delete();

        return redirect()
            ->route('admin.produk.lihat')
            ->with('success', 'Produk berhasil dihapus.');
    }
    
    // TAMPILAN PRODUK PUBLIK
    public function publicPage()
    {
        // misal 12 produk per halaman
        $produks = Produk::orderBy('nama_produk')->paginate(12);

        return view('publik.index', compact('produks'));
    }



}
