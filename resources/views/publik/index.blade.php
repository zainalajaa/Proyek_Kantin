@extends('layouts.publik') {{-- gunakan layout public/guest kamu --}}

@section('title', 'Daftar Produk')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <h1 class="text-2xl font-bold mb-6 text-gray-800">Daftar Produk</h1>

    @if ($produks->count() == 0)
        <p class="text-gray-500 text-center py-10">Belum ada produk tersedia.</p>
    @endif

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">

        @foreach ($produks as $p)
            <div class="bg-white shadow rounded-xl border p-3 hover:shadow-lg transition">

                {{-- Gambar --}}
                <div class="w-full aspect-square overflow-hidden rounded-lg border">
                    @if ($p->gambar_produk && Storage::disk('public')->exists($p->gambar_produk))
                        <img src="{{ asset('storage/' . $p->gambar_produk) }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">
                            Tidak ada gambar
                        </div>
                    @endif
                </div>

                {{-- Info Produk --}}
                <h2 class="mt-3 font-semibold text-gray-800 text-sm line-clamp-2">
                    {{ $p->nama_produk }}
                </h2>

                <p class="text-emerald-600 font-semibold mt-1">
                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                </p>

                <p class="text-xs text-gray-500 mt-1">
                    Stok: {{ $p->stok }}
                </p>

                {{-- Tombol Beli / Add to Cart (opsional) --}}
                <button class="w-full mt-3 bg-emerald-500 text-white text-sm py-2 rounded-lg hover:bg-emerald-600">
                    Beli
                </button>
            </div>
        @endforeach

    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $produks->links() }}
    </div>

</div>
@endsection
