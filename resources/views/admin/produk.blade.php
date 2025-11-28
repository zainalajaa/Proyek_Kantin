@extends('layouts.admin')
@section('title', 'Data Produk')

@section('content')
<div class="px-4 md:px-6 py-4 space-y-4">

    {{-- Flash sukses (tambah / edit / hapus) --}}
    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 text-sm px-4 py-2 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error validasi (untuk tambah & edit) --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 text-sm px-4 py-2 rounded-lg">
            <ul class="list-disc ml-4">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-800">Data Produk</h2>

        {{-- Tombol buka modal tambah --}}
        <button id="btnOpenCreateModal"
                class="px-4 py-2 rounded-lg bg-[#009688] text-white text-sm hover:bg-[#00796B]">
            + Tambah Produk
        </button>
    </div>

    {{-- TABEL PRODUK --}}
    @include('produk.tabel')
</div>


@endsection
