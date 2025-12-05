@extends('layouts.publik')

@section('title', 'Daftar Produk')

@section('content')
<div class="bg-slate-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-6">

        {{-- ALERT PESAN --}}
        @if (session('success'))
            <div class="mb-4 rounded-lg border border-emerald-500 bg-emerald-500/10 px-4 py-2 text-sm text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-lg border border-red-500 bg-red-500/10 px-4 py-2 text-sm text-red-200">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-500 bg-red-500/10 px-4 py-2 text-sm text-red-200">
                Terjadi kesalahan, silakan coba lagi.
            </div>
        @endif

        {{-- INFORMASI --}}
        <section id="tentang" class="mb-8 grid gap-4 md:grid-cols-2">
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-4">
                <h2 class="text-sm font-semibold text-slate-100 mb-2">Tentang Kantin Kejujuran PLN</h2>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Kantin Kejujuran PLN adalah fasilitas yang mengandalkan kejujuran pengunjung.
                    Ambil produk sendiri, lalu bayar tanpa kasir. Kejujuran Anda adalah bagian dari
                    budaya integritas PLN.
                </p>
                <ul class="mt-3 text-xs text-slate-300 list-disc list-inside space-y-1">
                    <li>Ambil barang sesuai kebutuhan</li>
                    <li>Lihat harga di label</li>
                    <li>Bayar di kotak/QRIS yang tersedia</li>
                    <li>Kejujuran Anda berharga</li>
                </ul>
            </div>

            <div class="bg-slate-800 border border-slate-700 rounded-xl p-4">
                <h2 class="text-sm font-semibold text-slate-100 mb-2">Informasi Produk & Pembayaran</h2>
                <ul class="text-xs text-slate-300 space-y-1">
                    <li>Harga sudah tercantum pada setiap produk.</li>
                    <li>Stok diperbarui sesuai ketersediaan.</li>
                    <li>Laporkan jika mendapati produk rusak/kadaluarsa.</li>
                    <li>Pilih metode pembayaran <span class="font-semibold">Tunai</span> atau <span class="font-semibold">QRIS</span> sebelum menekan tombol <span class="font-semibold">Beli</span>.</li>
                    <li>Transaksi Anda akan tercatat pada sistem penjualan kantin.</li>
                </ul>
            </div>
        </section>

        {{-- JUDUL PRODUK --}}
        <section id="menu">
            <h2 class="text-xl font-bold mb-6 text-slate-100">Daftar Produk</h2>

            @if ($produks->count() == 0)
                <p class="text-slate-400 text-center py-10">Belum ada produk tersedia.</p>
            @endif

            {{-- GRID PRODUK --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                @foreach ($produks as $p)
                    <div class="bg-slate-800 shadow rounded-xl border border-slate-700 p-3 
                                hover:shadow-lg hover:-translate-y-1 transition">

                        {{-- Gambar --}}
                        <div class="w-full aspect-square overflow-hidden rounded-lg border border-slate-700 bg-slate-900">
                            @if ($p->gambar_produk && Storage::disk('public')->exists($p->gambar_produk))
                                <img src="{{ asset('storage/' . $p->gambar_produk) }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-xs text-slate-500">
                                    Tidak ada gambar
                                </div>
                            @endif
                        </div>

                        {{-- Info Produk --}}
                        <h3 class="mt-3 font-semibold text-slate-100 text-sm line-clamp-2">
                            {{ $p->nama_produk }}
                        </h3>

                        <p class="text-emerald-400 font-semibold mt-1 text-sm">
                            Rp {{ number_format($p->harga, 0, ',', '.') }}
                        </p>

                        <p class="text-[11px] text-slate-400 mt-1">Stok: {{ $p->stok }}</p>

                        @if ($p->stok > 0)
                            <form action="{{ route('publik.beli', ['id' => $p->id_produk]) }}" method="POST" class="mt-3 space-y-2">
                                @csrf

                                <select name="metode" required
                                    class="w-full p-2 rounded bg-slate-800 border border-slate-700 text-white text-xs">
                                    <option value="" disabled selected>Pilih metode pembayaran</option>
                                    <option value="tunai">Bayar Tunai</option>
                                    <option value="qris">Bayar QRIS</option>
                                </select>

                                <button type="submit"
                                    class="w-full bg-emerald-500 text-slate-900 py-2 rounded-lg text-sm font-semibold hover:bg-emerald-400">
                                    Beli
                                </button>
                            </form>
                        @else
                            <button disabled
                                class="w-full mt-3 bg-gray-600 text-slate-200 text-sm py-2 rounded-lg font-semibold cursor-not-allowed">
                                Stok Habis
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $produks->links() }}
        </div>

    </div>
</div>
@endsection
