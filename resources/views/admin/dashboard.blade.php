@extends('layouts.admin')

@section('content')
<div class="px-4 md:px-6 py-4 space-y-6">

    {{-- Ringkasan Stok Makanan & Minuman --}}
    <div class="space-y-4">
        <h2 class="text-lg font-semibold text-gray-800">
            Ringkasan Stok Makanan & Minuman
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Total Produk --}}
            <div class="bg-white text-gray-800 rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Produk</h3>
                    <div class="w-9 h-9 rounded-full bg-[#E3F2FD] flex items-center justify-center text-[#0D8ABC] text-lg">
                        🧺
                    </div>
                </div>
                <p class="text-3xl font-bold text-[#0D8ABC]">128</p>
                <p class="text-xs text-gray-500 mt-1">Total varian makanan & minuman di kantin.</p>
            </div>

            {{-- Stok Makanan --}}
            <div class="bg-white text-gray-800 rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Stok Makanan</h3>
                    <div class="w-9 h-9 rounded-full bg-[#FFF8E1] flex items-center justify-center text-[#FBC02D] text-lg">
                        🍜
                    </div>
                </div>
                <p class="text-3xl font-bold text-[#FBC02D]">56</p>
                <p class="text-xs text-gray-500 mt-1">
                    Contoh: Mie goreng, mie kuah, coklat batangan, wafer.
                </p>
            </div>

            {{-- Stok Minuman --}}
            <div class="bg-white text-gray-800 rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Stok Minuman</h3>
                    <div class="w-9 h-9 rounded-full bg-[#E0F2F1] flex items-center justify-center text-[#009688] text-lg">
                        🥤
                    </div>
                </div>
                <p class="text-3xl font-bold text-[#009688]">72</p>
                <p class="text-xs text-gray-500 mt-1">
                    Contoh: Golda, Teh Pucuk, Aqua, dan minuman kemasan lainnya.
                </p>
            </div>
        </div>
    </div>

    {{-- Aktivitas Terbaru --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h3>
        <ul class="space-y-2 text-gray-700 text-sm md:text-base">
            <li>🍜 Stok <b>Mie Goreng</b> ditambah 20 bungkus ke rak utama.</li>
            <li>🍜 <b>Mie Kuah</b> terjual 10 bungkus pada jam istirahat pertama.</li>
            <li>🥤 <b>Golda</b> dan <b>Teh Pucuk</b> diisi ulang ke kulkas minuman.</li>
            <li>💧 Stok <b>Aqua</b> botol 600ml diperbarui untuk keperluan siswa.</li>
            <li>🍫 Produk <b>coklat</b> dan <b>wafer</b> baru ditambahkan ke etalase snack.</li>
        </ul>
    </div>

</div>
@endsection
