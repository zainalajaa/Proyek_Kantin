@extends('layouts.admin')

@section('title', 'Edit Monitoring Stok')

@section('content')

<div class="p-4 sm:p-6">

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-gray-800">
            Pastikan Stok Fisik sudah di hitung dengan benar 
        </h1>
        <a href="{{ route('admin.monitoring_stok') }}"
           class="text-sm text-blue-600 hover:underline">
            ← Kembali
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-xl p-6">

        <form action="{{ route('admin.monitoring_stok.update', $check->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- PRODUK (READONLY) -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        Produk
                    </label>
                    <input type="text"
                           value="{{ $check->produk->nama_produk }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-sm"
                           readonly>
                </div>

                <!-- STOK SISTEM (READONLY) -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        Stok Sistem
                    </label>
                    <input type="number"
                           value="{{ $check->stok_sistem }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-sm"
                           readonly>
                </div>

                <!-- STOK FISIK (EDITABLE) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        Stok Fisik
                    </label>
                    <input type="number"
                           name="stok_fisik"
                           value="{{ old('stok_fisik', $check->stok_fisik) }}"
                           min="0"
                           required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-300 outline-none">
                </div>

            </div>

            <div class="mt-6">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                    Update Data
                </button>
            </div>

        </form>

    </div>

</div>

@endsection