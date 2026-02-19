@extends('layouts.publik')

@section('title', 'Daftar Produk')

@section('content')
<div class="bg-gradient-to-b from-slate-900 to-slate-950 min-h-screen">
<div class="max-w-7xl mx-auto px-4 py-6">

    {{-- HERO HEADER --}}
    <div class="bg-slate-800/80 backdrop-blur border border-slate-700 rounded-2xl p-5 mb-6 shadow-lg">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h1 class="text-2xl font-bold text-white tracking-wide">
                    Kantin Kejujuran PLN
                </h1>
                <p class="text-sm text-slate-400 mt-1">
                    Belanja cepat, bayar mudah, tanpa ribet.
                </p>
            </div>

            {{-- SEARCH BAR (UI Only) --}}
            <div class="w-full md:w-80">
                <form method="GET" action="{{ route('publik.index') }}">
                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari produk..."
                        class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </form>
            </div>
        </div>
    </div>

    {{-- INFO SECTION --}}
    <div class="grid md:grid-cols-2 gap-4 mb-6">

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-4">
            <h2 class="text-sm font-semibold text-white mb-2">Cara Berbelanja</h2>
            <ul class="text-xs text-slate-300 space-y-1">
                <li>✓ Pilih produk</li>
                <li>✓ Tambahkan ke keranjang</li>
                <li>✓ Tentukan jumlah</li>
                <li>✓ Bayar Tunai / QRIS</li>
            </ul>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-4">
            <h2 class="text-sm font-semibold text-white mb-2">Metode Pembayaran</h2>
            <div class="flex gap-2 mt-2">
                <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 rounded-lg text-xs">
                    Tunai
                </span>
                <span class="px-3 py-1 bg-blue-500/20 text-blue-300 rounded-lg text-xs">
                    QRIS
                </span>
            </div>
        </div>

    </div>

    @if(request('search'))
    <div class="mb-4 text-sm text-slate-400">
        Hasil pencarian untuk:
        <span class="text-emerald-400 font-semibold">
            "{{ request('search') }}"
        </span>
    </div>
    @endif

    {{-- PRODUCT GRID --}}
    <section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-white">
                Produk Tersedia
            </h2>
            <span class="text-sm text-slate-400">
                {{ $produks->total() }} Produk
            </span>
        </div>
        @if ($produks->count() == 0)

            @if(request('search'))
                <div class="text-center py-14">
                    <div class="text-lg font-semibold text-red-400">
                        Produk tidak tersedia
                    </div>
                    <p class="text-sm text-slate-400 mt-2">
                        Tidak ditemukan produk dengan nama 
                        "<span class="text-emerald-400">{{ request('search') }}</span>"
                    </p>
                </div>
            @else
                <div class="text-center text-slate-400 py-10">
                    Belum ada produk tersedia
                </div>
            @endif

        @endif

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">

            @foreach ($produks as $p)

            <div class="group bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden transition-all duration-300 hover:border-emerald-500 hover:shadow-xl hover:shadow-emerald-500/10">

                {{-- IMAGE --}}
                <div class="relative aspect-square bg-slate-900 overflow-hidden">
                    @if ($p->gambar_produk && Storage::disk('public')->exists($p->gambar_produk))
                        <img src="{{ asset('storage/' . $p->gambar_produk) }}"
                             class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-xs text-slate-500">
                            Tidak ada gambar
                        </div>
                    @endif

                    {{-- BADGE --}}
                    @if($p->stok > 0)
                        <span class="absolute top-3 right-3 bg-emerald-500 text-slate-900 text-[10px] px-3 py-1 rounded-full font-semibold shadow">
                            Ready
                        </span>
                    @else
                        <span class="absolute top-3 right-3 bg-red-500 text-white text-[10px] px-3 py-1 rounded-full font-semibold shadow">
                            Habis
                        </span>
                    @endif
                </div>

                {{-- CONTENT --}}
                <div class="p-4">

                    <h3 class="font-semibold text-white text-sm line-clamp-2 min-h-[40px]">
                        {{ $p->nama_produk }}
                    </h3>

                    <div class="flex items-center justify-between mt-2">
                        <span class="text-emerald-400 font-bold text-sm">
                            Rp {{ number_format($p->harga, 0, ',', '.') }}
                        </span>
                        <span class="text-xs text-slate-400">
                            Stok: {{ $p->stok }}
                        </span>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="mt-4 flex gap-2">

                        @if ($p->stok > 0)
                            <form action="{{ route('cart.add', $p->id_produk) }}" 
                                method="POST" 
                                class="flex-1">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-slate-700 hover:bg-slate-600 text-white py-2 rounded-xl text-xs font-semibold transition">
                                    + Keranjang
                                </button>
                            </form>

                            <form action="{{ route('publik.beli', ['id' => $p->id_produk]) }}" 
                                method="POST" 
                                class="flex-1">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-900 py-2 rounded-xl text-xs font-bold transition">
                                    Beli
                                </button>
                            </form>

                        @else
                            <button disabled
                                class="w-full bg-gray-700 text-slate-400 py-2 rounded-xl text-xs font-semibold cursor-not-allowed">
                                Stok Habis
                            </button>
                        @endif

                    </div>

                </div>

            </div>

            @endforeach

        </div>
    </section>

    {{-- PAGINATION --}}
    <div class="mt-8">
        {{ $produks->links() }}
    </div>

</div>
</div>
@endsection

