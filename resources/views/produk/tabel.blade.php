<div class="bg-white rounded-xl shadow border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 border-b sticky top-0 z-10">
                <tr class="text-left">
                    <th class="px-4 py-3 font-semibold">No</th>
                    <th class="px-4 py-3 font-semibold">Nama Produk</th>
                    <th class="px-4 py-3 font-semibold">Gambar Produk</th>
                    <th class="px-4 py-3 font-semibold text-right">Stok</th>
                    <th class="px-4 py-3 font-semibold text-right">Harga</th>
                    <th class="px-4 py-3 font-semibold text-right">Jumlah</th>
                    <th class="px-4 py-3 font-semibold text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach ($produks as $i => $produk)
                    <tr class="hover:bg-gray-50 transition">
                        {{-- Nomor --}}
                        <td class="px-4 py-3">
                            {{ $produks->firstItem() + $i }}
                        </td>

                        {{-- Nama Produk --}}
                        <td class="px-4 py-3 font-medium text-gray-700">
                            {{ $produk->nama_produk }}
                        </td>

                        {{-- Gambar Produk --}}
                        <td class="px-4 py-3">
                            @if ($produk->gambar_produk && Storage::disk('public')->exists($produk->gambar_produk))
                                <img src="{{ asset('storage/' . $produk->gambar_produk) }}"
                                     class="w-16 h-16 object-cover rounded-lg shadow-sm border">
                            @else
                                <span class="text-xs text-gray-400 italic">Tidak ada gambar</span>
                            @endif
                        </td>

                        {{-- Stok --}}
                        <td class="px-4 py-3 text-right">
                            {{ $produk->stok }}
                        </td>

                        {{-- Harga --}}
                        <td class="px-4 py-3 text-right">
                            Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </td>

                        {{-- Total --}}
                        <td class="px-4 py-3 text-right">
                            Rp {{ number_format($produk->stok * $produk->harga, 0, ',', '.') }}
                        </td>

                        {{-- Tombol Aksi --}}
                        <td class="px-4 py-3 text-center space-x-2">
                            <a href="{{ route('admin.produk.edit', $produk) }}"
                               class="inline-flex items-center px-3 py-1.5 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 text-xs shadow-sm">
                               ✏️ Edit
                            </a>

                            <form action="{{ route('admin.produk.destroy', $produk) }}"
                                  method="POST"
                                  class="inline-block"
                                  onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg bg-red-500 text-white hover:bg-red-600 text-xs shadow-sm">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 border-t bg-gray-50">
        {{ $produks->links() }}
    </div>
</div>
