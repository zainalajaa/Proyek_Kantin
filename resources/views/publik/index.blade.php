@extends('layouts.publik')

@section('title', 'Daftar Produk')

@section('content')

<div class="bg-gradient-to-b from-white to-gray-50 min-h-screen">

    <div class="max-w-7xl mx-auto px-4 py-6">

        {{-- HEADER SECTION --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        Kantin Kejujuran PLN
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Belanja mudah & praktis setiap hari
                    </p>
                </div>

                {{-- SEARCH --}}
                <div class="w-full md:w-96">
                    <form method="GET" action="{{ route('publik.index') }}" class="flex">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Cari produk..."
                               class="flex-1 border border-gray-300 rounded-l-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
                        <button type="submit"
                                class="bg-teal-600 hover:bg-teal-700 text-white px-4 rounded-r-md text-sm transition">
                            🔍
                        </button>
                    </form>
                </div>

            </div>

        </div>

        {{-- SEARCH RESULT INFO --}}
        @if(request('search'))
            <div class="mb-4 text-sm text-gray-600">
                Hasil pencarian untuk:
                <span class="text-teal-600 font-semibold">
                    "{{ request('search') }}"
                </span>
            </div>
        @endif

        {{-- PRODUCT SECTION --}}
       <div class="bg-white/80 backdrop-blur-sm rounded-lg p-4">

            <div class="flex items-center justify-between mb-5 border-b pb-3">
                <h2 class="text-lg font-bold text-teal-600 uppercase tracking-wide">
                    Produk Tersedia
                </h2>
                <span class="text-sm text-gray-500">
                    {{ $produks->total() }} Produk
                </span>
            </div>

            @if ($produks->count() == 0)

                @if(request('search'))
                    <div class="text-center py-12">
                        <div class="text-lg font-semibold text-gray-700">
                            Produk tidak tersedia
                        </div>
                        <p class="text-sm text-gray-500 mt-2">
                            Tidak ditemukan produk dengan nama 
                            "<span class="text-teal-600">{{ request('search') }}</span>"
                        </p>
                    </div>
                @else
                    <div class="text-center py-10 text-gray-500">
                        Belum ada produk tersedia
                    </div>
                @endif

            @endif

            {{-- GRID --}}
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">

                @foreach ($produks as $p)

                <div class="border rounded-md hover:shadow-lg hover:border-teal-500 transition duration-300 bg-white group overflow-hidden">

                    {{-- IMAGE --}}
                    <div class="relative aspect-square bg-gray-50">

                        @if ($p->gambar_produk && Storage::disk('public')->exists($p->gambar_produk))
                            <img src="{{ asset('storage/' . $p->gambar_produk) }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @endif

                        {{-- TOP BADGE --}}
                        @if($p->stok > 5)
                            <span class="absolute top-2 left-2 bg-teal-600 text-white text-[10px] px-2 py-1 rounded">
                                TOP
                            </span>
                        @endif

                        {{-- HABIS BADGE --}}
                        @if($p->stok == 0)
                            <span class="absolute top-2 right-2 bg-gray-600 text-white text-[10px] px-2 py-1 rounded">
                                Habis
                            </span>
                        @endif

                    </div>

                    {{-- CONTENT --}}
                    <div class="p-3">

                        <h3 class="text-sm text-gray-700 line-clamp-2 min-h-[38px]">
                            {{ $p->nama_produk }}
                        </h3>

                        {{-- HARGA --}}
                        <div class="mt-2">
                            <span class="text-red-500 font-bold text-base">
                                Rp {{ number_format($p->harga, 0, ',', '.') }}
                            </span>
                        </div>

                        {{-- INFO TAMBAHAN --}}
                        <div class="text-xs text-gray-500 mt-1">
                            Stok: {{ $p->stok }}
                        </div>

                        {{-- BUTTONS --}}
                        <div class="mt-3 flex gap-2">

                            @if ($p->stok > 0)

                                <form action="{{ route('cart.add', $p->id_produk) }}" 
                                      method="POST" 
                                      class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-xs bg-gray-200 hover:bg-gray-300 py-1.5 rounded transition">
                                        Keranjang
                                    </button>
                                </form>

                                <form action="{{ route('publik.beli', ['id' => $p->id_produk]) }}" 
                                      method="POST" 
                                      class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-xs bg-teal-600 hover:bg-teal-700 text-white py-1.5 rounded transition">
                                        Beli
                                    </button>
                                </form>

                            @else
                                <button disabled
                                    class="w-full text-xs bg-gray-300 text-gray-500 py-1.5 rounded">
                                    Stok Habis
                                </button>
                            @endif

                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

        {{-- PAGINATION --}}
        <div class="mt-6">
            {{ $produks->links() }}
        </div>

    </div>

</div>

@endsection
