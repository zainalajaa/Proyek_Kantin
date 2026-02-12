@extends('layouts.publik')

@section('title', 'Pembayaran QRIS')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100 py-6">
    <div class="max-w-md w-full bg-white p-6 rounded-lg shadow text-center">

        <h2 class="text-xl font-semibold mb-4">Pembayaran QRIS</h2>

        {{-- TOTAL FIX DARI DATABASE --}}
        <p class="mb-3 text-sm">
            Total yang harus dibayar:
            <span class="font-bold text-blue-600 text-lg">
                Rp {{ number_format($penjualan->total_harga,0,',','.') }}
            </span>
        </p>

        <div class="flex justify-center mb-3">
            <img src="{{ asset('storage/images/qris.jpeg') }}" 
                 alt="QRIS Pembayaran" 
                 class="w-72 rounded border">
        </div>

        <p class="mt-3 text-sm text-emerald-600 font-semibold">
            Menunggu pembayaran...
        </p>

        <!-- FORM KONFIRMASI PEMBAYARAN -->
        <form action="{{ route('publik.qris.submit', $penjualan->id) }}" method="POST" class="mt-4">
            @csrf

            <button type="submit" 
                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                Saya Sudah Membayar
            </button>
        </form>

    </div>
</div>

@endsection
