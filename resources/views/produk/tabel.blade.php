<div class="bg-white shadow rounded p-4 sm:p-6">

    {{-- ================= DESKTOP TABLE ================= --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">

            <thead class="bg-gray-50 text-gray-700 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Nama Produk</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-left">Gambar</th>
                    <th class="px-4 py-3 text-right">Stok</th>
                    <th class="px-4 py-3 text-right">Harga</th>
                    <th class="px-4 py-3 text-right">Total Nilai</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($produks as $i => $produk)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-4 py-3">
                        {{ $produks->firstItem() + $i }}
                    </td>

                    <td class="px-4 py-3 font-medium text-gray-800">
                        {{ $produk->nama_produk }}
                    </td>

                    <td class="px-4 py-3">
                        @if($produk->kategori)
                            <span class="inline-flex px-2 py-1 rounded-full text-xs
                                {{ $produk->kategori->nama_kategori == 'Makanan'
                                    ? 'bg-amber-100 text-amber-700'
                                    : 'bg-cyan-100 text-cyan-700' }}">
                                {{ $produk->kategori->nama_kategori }}
                            </span>
                        @else
                            <span class="text-xs text-gray-400 italic">
                                Tidak ada kategori
                            </span>
                        @endif
                    </td>

                    <td class="px-4 py-3">
                        @if ($produk->gambar_produk)
                            <img src="{{ asset('storage/'. $produk->gambar_produk) }}"
                                 class="w-12 h-12 object-cover rounded border">
                        @endif
                    </td>

                    <td class="px-4 py-3 text-right font-semibold">
                        {{ $produk->stok }}
                    </td>

                    <td class="px-4 py-3 text-right">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </td>

                    <td class="px-4 py-3 text-right font-semibold">
                        Rp {{ number_format($produk->stok * $produk->harga, 0, ',', '.') }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.produk.edit', $produk) }}"
                               class="text-xs px-3 py-1 rounded bg-yellow-500 text-white hover:bg-yellow-600 transition">
                                Edit
                            </a>

                            <form action="{{ route('admin.produk.destroy', $produk) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="btn-delete text-xs px-3 py-1 rounded bg-red-500 text-white hover:bg-red-600 transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ================= MOBILE CARD VIEW ================= --}}
    <div class="md:hidden space-y-4">

        @foreach ($produks as $i => $produk)
        <div class="border rounded-xl p-4 shadow-sm">

            <div class="flex items-start gap-4">

                {{-- IMAGE --}}
                @if ($produk->gambar_produk)
                    <img src="{{ asset('storage/'. $produk->gambar_produk) }}"
                         class="w-16 h-16 object-cover rounded-lg border">
                @endif

                <div class="flex-1">

                    <div class="font-semibold text-gray-800">
                        {{ $produk->nama_produk }}
                    </div>

                    <div class="text-xs text-gray-500 mt-1">
                        Kategori:
                        {{ $produk->kategori->nama_kategori ?? 'Tidak ada' }}
                    </div>

                    <div class="text-xs text-gray-500">
                        Stok: {{ $produk->stok }}
                    </div>

                    <div class="text-sm font-semibold text-gray-700 mt-1">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </div>

                    <div class="text-xs text-gray-500">
                        Total:
                        Rp {{ number_format($produk->stok * $produk->harga, 0, ',', '.') }}
                    </div>

                </div>

            </div>

            {{-- ACTION --}}
            <div class="flex justify-end gap-2 mt-3">

                <a href="{{ route('admin.produk.edit', $produk) }}"
                   class="text-xs px-3 py-1 rounded bg-yellow-500 text-white hover:bg-yellow-600 transition">
                    Edit
                </a>

                <form action="{{ route('admin.produk.destroy', $produk) }}"
                      method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                        class="btn-delete text-xs px-3 py-1 rounded bg-red-500 text-white hover:bg-red-600 transition">
                        Hapus
                    </button>
                </form>

            </div>

        </div>
        @endforeach

    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $produks->links() }}
    </div>

</div>
