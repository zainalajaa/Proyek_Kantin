@extends('layouts.publik')

@section('title', 'Pembayaran QRIS')

@section('content')

<div class="min-h-screen bg-gray-100 pt-16 pb-10">

    <div class="max-w-md mx-auto">

        <div class="bg-white p-6 rounded-2xl shadow-lg text-center">

            <h2 class="text-xl font-semibold mb-4 text-gray-800">
                Pembayaran QRIS
            </h2>

            {{-- TOTAL --}}
            <p class="mb-4 text-sm text-gray-600">
                Total yang harus dibayar:
                <span class="block mt-1 font-bold text-2xl text-blue-600">
                    Rp {{ number_format($penjualan->total_harga,0,',','.') }}
                </span>
            </p>

            {{-- QR IMAGE --}}
            <div class="flex justify-center mb-4">
                <img src="{{ asset('storage/images/qris.jpeg') }}"
                     alt="QRIS Pembayaran"
                     class="w-72 rounded-xl border border-gray-200 shadow-sm">
            </div>

            {{-- STATUS --}}
            <p class="text-sm text-emerald-600 font-semibold mb-5">
                Menunggu pembayaran...
            </p>

            {{-- BUTTON --}}
            <form action="{{ route('publik.qris.submit', $penjualan->id) }}" method="POST">
                @csrf

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2.5 rounded-xl font-semibold hover:bg-blue-700 transition">
                    Saya Sudah Membayar
                </button>
            </form>

        </div>

    </div>

</div>


@endsection
