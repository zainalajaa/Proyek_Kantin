@extends('layouts.publik')

@section('title', 'Daftar Produk')

@section('content')
<div class="bg-gradient-to-b from-slate-900 to-slate-950 min-h-screen">
<div class="max-w-7xl mx-auto px-4 py-6">

    {{-- ALERT --}}
    @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-500 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-xl border border-red-500 bg-red-500/10 px-4 py-3 text-sm text-red-200">
            {{ session('error') }}
        </div>
    @endif

    {{-- HERO HEADER --}}
    <div class="bg-slate-800 border border-slate-700 rounded-2xl p-5 mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Kantin Kejujuran PLN</h1>
            <p class="text-sm text-slate-400 mt-1">
                Pilih produk favorit Anda dan lakukan pembayaran dengan mudah
            </p>
        </div>
    </div>

    {{-- INFO CARDS --}}
    <section class="grid gap-4 md:grid-cols-2 mb-6">
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-4">
            <h2 class="text-sm font-semibold text-slate-100 mb-2">Cara Berbelanja</h2>
            <ul class="text-xs text-slate-300 space-y-1">
                <li>✓ Pilih produk yang diinginkan</li>
                <li>✓ Tambahkan ke keranjang</li>
                <li>✓ Tentukan jumlah pembelian</li>
                <li>✓ Bayar menggunakan Tunai atau QRIS</li>
            </ul>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-xl p-4">
            <h2 class="text-sm font-semibold text-slate-100 mb-2">Metode Pembayaran</h2>
            <p class="text-xs text-slate-300">
                Kami mendukung pembayaran melalui:
            </p>
            <div class="flex gap-3 mt-2">
                <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 rounded-lg text-xs">Tunai</span>
                <span class="px-3 py-1 bg-blue-500/20 text-blue-300 rounded-lg text-xs">QRIS</span>
            </div>
        </div>
    </section>

    {{-- PRODUCT SECTION --}}
    <section>
        <h2 class="text-xl font-bold mb-5 text-slate-100">Produk Tersedia</h2>

        @if ($produks->count() == 0)
            <div class="text-center text-slate-400 py-10">
                Belum ada produk tersedia
            </div>
        @endif

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
            @foreach ($produks as $p)

            <div class="group bg-slate-800 border border-slate-700 rounded-2xl p-3 transition-all duration-200 hover:border-emerald-500 hover:shadow-lg hover:shadow-emerald-500/10">

                {{-- GAMBAR PRODUK --}}
                <div class="relative w-full aspect-square rounded-xl overflow-hidden bg-slate-900">
                    @if ($p->gambar_produk && Storage::disk('public')->exists($p->gambar_produk))
                        <img src="{{ asset('storage/' . $p->gambar_produk) }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-xs text-slate-500">
                            Tidak ada gambar
                        </div>
                    @endif

                    {{-- BADGE STOK --}}
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
                <div class="mt-3">
                    <h3 class="font-semibold text-slate-100 text-sm line-clamp-2 min-h-[40px]">
                        {{ $p->nama_produk }}
                    </h3>

                    <div class="flex items-center justify-between mt-2">
                        <span class="text-emerald-400 font-bold text-sm">
                            Rp {{ number_format($p->harga, 0, ',', '.') }}
                        </span>

                        <span class="text-[11px] text-slate-400">
                            Stok: {{ $p->stok }}
                        </span>
                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="mt-3">
                    @if ($p->stok > 0)

                        <div class="flex gap-2">

                            {{-- ADD TO CART --}}
                            <form action="{{ route('cart.add', $p->id_produk) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-slate-700 text-white py-2 rounded-xl text-xs font-semibold hover:bg-slate-600 transition">
                                    + Keranjang
                                </button>
                            </form>

                            {{-- BELI LANGSUNG --}}
                            <form action="{{ route('publik.beli', ['id' => $p->id_produk]) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-emerald-500 text-slate-900 py-2 rounded-xl text-xs font-semibold hover:bg-emerald-400 transition">
                                    Beli
                                </button>
                            </form>

                        </div>

                    @else
                        <button disabled
                            class="w-full bg-gray-700 text-slate-400 py-2 rounded-xl text-xs font-semibold cursor-not-allowed">
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
