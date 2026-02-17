@extends('layouts.publik')

@section('title', 'Daftar Produk')

@section('content')
<div class="bg-gradient-to-b from-slate-900 to-slate-950 min-h-screen">
<div class="max-w-7xl mx-auto px-4 py-6">

    {{-- HERO HEADER --}}
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-4 sm:p-5 mb-5 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-white">
                Kantin Kejujuran PLN
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                Pilih produk favorit Anda dan lakukan pembayaran dengan mudah
            </p>
        </div>
    </div>

    {{-- INFO CARDS --}}
    <section class="grid gap-3 md:grid-cols-2 mb-5">
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-3 sm:p-4">
            <h2 class="text-sm font-semibold text-slate-100 mb-2">Cara Berbelanja</h2>
            <ul class="text-[11px] sm:text-xs text-slate-300 space-y-1">
                <li>✓ Pilih produk</li>
                <li>✓ Tambahkan ke keranjang</li>
                <li>✓ Tentukan jumlah</li>
                <li>✓ Bayar Tunai / QRIS</li>
            </ul>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-3 sm:p-4">
            <h2 class="text-sm font-semibold text-slate-100 mb-2">Metode Pembayaran</h2>
            <p class="text-[11px] sm:text-xs text-slate-300">
                Mendukung pembayaran:
            </p>
            <div class="flex gap-2 mt-2">
                <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 rounded-lg text-[11px]">
                    Tunai
                </span>
                <span class="px-3 py-1 bg-blue-500/20 text-blue-300 rounded-lg text-[11px]">
                    QRIS
                </span>
            </div>
        </div>
    </section>

    {{-- PRODUCT SECTION --}}
    <section>
        <h2 class="text-lg sm:text-xl font-bold mb-4 text-slate-100">
            Produk Tersedia
        </h2>

        @if ($produks->count() == 0)
            <div class="text-center text-slate-400 py-10">
                Belum ada produk tersedia
            </div>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">
            @foreach ($produks as $p)

            <div class="group bg-slate-800 border border-slate-700 rounded-2xl p-2.5 sm:p-4 transition-all duration-200 hover:border-emerald-500 hover:shadow-lg hover:shadow-emerald-500/10">

                {{-- IMAGE --}}
                <div class="relative w-full aspect-square rounded-xl overflow-hidden bg-slate-900">
                    @if ($p->gambar_produk && Storage::disk('public')->exists($p->gambar_produk))
                        <img src="{{ asset('storage/' . $p->gambar_produk) }}"
                             class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-[10px] text-slate-500">
                            Tidak ada gambar
                        </div>
                    @endif

                    {{-- BADGE --}}
                    @if($p->stok > 0)
                        <span class="absolute top-2 right-2 bg-emerald-500 text-slate-900 text-[10px] px-2 py-1 rounded-lg font-semibold">
                            Ready
                        </span>
                    @else
                        <span class="absolute top-2 right-2 bg-red-500 text-white text-[10px] px-2 py-1 rounded-lg font-semibold">
                            Habis
                        </span>
                    @endif
                </div>

                {{-- INFO --}}
                <div class="mt-2.5">
                    <h3 class="font-semibold text-slate-100 text-xs sm:text-sm line-clamp-2 min-h-[32px] sm:min-h-[40px]">
                        {{ $p->nama_produk }}
                    </h3>

                    <div class="flex items-center justify-between mt-1.5 text-xs sm:text-sm">
                        <span class="text-emerald-400 font-bold">
                            Rp {{ number_format($p->harga, 0, ',', '.') }}
                        </span>
                        <span class="text-[10px] sm:text-xs text-slate-400">
                            Stok: {{ $p->stok }}
                        </span>
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="mt-2.5">
                    @if ($p->stok > 0)

                        <div class="flex gap-1.5">

                            <form action="{{ route('cart.add', $p->id_produk) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-slate-700 text-white py-2 rounded-lg text-[11px] sm:text-sm font-semibold hover:bg-slate-600 transition">
                                    + Keranjang
                                </button>
                            </form>

                            <form action="{{ route('publik.beli', ['id' => $p->id_produk]) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-emerald-500 text-slate-900 py-2 rounded-lg text-[11px] sm:text-sm font-semibold hover:bg-emerald-400 transition">
                                    Beli
                                </button>
                            </form>

                        </div>

                    @else
                        <button disabled
                            class="w-full bg-gray-700 text-slate-400 py-2 rounded-lg text-[11px] font-semibold cursor-not-allowed">
                            Stok Habis
                        </button>
                    @endif
                </div>

            </div>

            @endforeach
        </div>
    </section>

    {{-- PAGINATION --}}
    <div class="mt-6">
        {{ $produks->links() }}
    </div>

</div>
</div>
@endsection
