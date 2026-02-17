@extends('layouts.admin')

@section('content')
<div class="px-6 py-8 space-y-10">

    {{-- ================= CARD STATISTIK ================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-7">

        {{-- TOTAL PRODUK --}}
        <div class="relative overflow-hidden rounded-2xl p-6 text-white shadow-xl
                    bg-gradient-to-br from-blue-500 to-blue-700
                    transition duration-300 hover:scale-105">

            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-80">Total Produk</p>
                    <h2 class="text-4xl font-bold mt-2">
                        {{ $totalProduk }}
                    </h2>
                </div>
                <div class="text-4xl opacity-80">
                    🧺
                </div>
            </div>

            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white opacity-10 rounded-full"></div>
        </div>


        {{-- TOTAL MAKANAN --}}
        <div class="relative overflow-hidden rounded-2xl p-6 text-white shadow-xl
                    bg-gradient-to-br from-yellow-400 to-orange-500
                    transition duration-300 hover:scale-105">

            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-80">Total Makanan</p>
                    <h2 class="text-4xl font-bold mt-2">
                        {{ $totalMakanan }}
                    </h2>
                </div>
                <div class="text-4xl opacity-80">
                    🍜
                </div>
            </div>

            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white opacity-10 rounded-full"></div>
        </div>


        {{-- TOTAL MINUMAN --}}
        <div class="relative overflow-hidden rounded-2xl p-6 text-white shadow-xl
                    bg-gradient-to-br from-emerald-400 to-teal-600
                    transition duration-300 hover:scale-105">

            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-80">Total Minuman</p>
                    <h2 class="text-4xl font-bold mt-2">
                        {{ $totalMinuman }}
                    </h2>
                </div>
                <div class="text-4xl opacity-80">
                    🥤
                </div>
            </div>

            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white opacity-10 rounded-full"></div>
        </div>

    </div>

@endsection
