<div class="bg-white rounded-xl shadow border overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <!-- HEADER -->
            <thead class="bg-gray-100 border-b sticky top-0 z-10">
                <tr class="text-left text-gray-700">
                    <th class="px-4 py-3 font-semibold">No</th>
                    <th class="px-4 py-3 font-semibold">Nama Produk</th>
                    <th class="px-4 py-3 font-semibold">Kategori</th>
                    <th class="px-4 py-3 font-semibold">Gambar</th>
                    <th class="px-4 py-3 font-semibold text-right">Stok</th>
                    <th class="px-4 py-3 font-semibold text-right">Harga</th>
                    <th class="px-4 py-3 font-semibold text-right">Total Nilai</th>
                    <th class="px-4 py-3 font-semibold text-center">Aksi</th>
                </tr>
            </thead>

            <!-- BODY -->
            <tbody class="divide-y">

                @foreach ($produks as $i => $produk)
                    <tr class="hover:bg-gray-50 transition">

                        <!-- Nomor -->
                        <td class="px-4 py-3">
                            {{ $produks->firstItem() + $i }}
                        </td>

                        <!-- Nama Produk -->
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $produk->nama_produk }}
                        </td>

                        <!-- Nama Kategori -->
                        <td class="px-4 py-3">
                            @if($produk->kategori)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $produk->kategori->nama_kategori == 'Makanan'
                                    ? 'bg-amber-100 text-amber-700'
                                    : 'bg-cyan-100 text-cyan-700' }}">
    
                                    {{ $produk->kategori->nama_kategori == 'Makanan' ? '🍜' : '🥤' }}
                                    {{ $produk->kategori->nama_kategori }}
                                </span>

                            @else
                                <span class="text-xs text-gray-400 italic">
                                    Tidak ada kategori
                                </span>
                            @endif
                        </td>

                        <!-- Gambar Produk -->
                        <td class="px-4 py-3">
                            @if ($produk->gambar_produk)
                                <img src="{{ asset('storage/'. $produk->gambar_produk) }}"
                                    class="w-16 h-16 object-cover rounded-lg shadow-sm border">
                            @else
                                <span class="text-xs text-gray-400 italic">
                                    Tidak ada gambar
                                </span>
                            @endif
                        </td>


                        <!-- Stok -->
                        <td class="px-4 py-3 text-right font-semibold
                            @if($produk->stok == 0)
                                text-red-500
                            @elseif($produk->stok < 5)
                                text-yellow-500
                            @else
                                text-gray-700
                            @endif
                        ">
                            {{ $produk->stok }}
                        </td>

                        <!-- Harga -->
                        <td class="px-4 py-3 text-right text-gray-700">
                            Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </td>

                        <!-- Total Nilai -->
                        <td class="px-4 py-3 text-right font-semibold text-gray-800">
                            Rp {{ number_format($produk->stok * $produk->harga, 0, ',', '.') }}
                        </td>

                        <!-- Aksi -->
                        <td class="px-4 py-3 text-center space-x-2">

                            <a href="{{ route('admin.produk.edit', $produk) }}"
                               class="inline-flex items-center px-3 py-1.5 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 text-xs shadow-sm transition">
                               ✏️ Edit
                            </a>

                            <form action="{{ route('admin.produk.destroy', $produk) }}"
                                  method="POST"
                                  class="inline-block"
                                  onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')

                                <button
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg bg-red-500 text-white hover:bg-red-600 text-xs shadow-sm transition">
                                    🗑️ Hapus
                                </button>
                            </form>

                        </td>

                    </tr>
                @endforeach

            </tbody>
        </table>

    </div>

    <!-- PAGINATION -->
    <div class="px-4 py-3 border-t bg-gray-50">
        {{ $produks->links() }}
    </div>

</div>

