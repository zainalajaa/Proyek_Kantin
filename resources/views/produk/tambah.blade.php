@extends('layouts.admin')
@section('title', 'Tambah Produk')

@section('content')
<div class="min-h-[calc(100vh-80px)] flex items-start md:items-center justify-center px-4 py-6">

    <div class="w-full max-w-2xl bg-white rounded-xl shadow-lg border border-gray-100">
        
        {{-- Header --}}
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h2 class="text-base md:text-lg font-semibold text-gray-800">
                Tambah Produk
            </h2>
            <a href="{{ route('admin.produk.lihat') }}"
               class="text-gray-400 hover:text-gray-600 text-xl leading-none">
                &times;
            </a>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.produk.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="px-6 py-5 space-y-4">
            @csrf

            {{-- Error --}}
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 text-xs px-4 py-2 rounded-lg">
                    <ul class="list-disc ml-4">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Nama Produk --}}
            <div>
                <label class="block text-xs text-gray-600 mb-1">Nama Produk</label>
                <input type="text"
                       name="nama_produk"
                       value="{{ old('nama_produk') }}"
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-[#009688] focus:border-[#009688]"
                       required>
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-xs text-gray-600 mb-1">Kategori</label>
                <select name="kategori_id"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-[#009688] focus:border-[#009688]"
                        required>

                    <option value="">-- Pilih Kategori --</option>

                    @foreach($kategori as $k)
                        <option value="{{ $k->id }}"
                            {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- Stok --}}
            <div>
                <label class="block text-xs text-gray-600 mb-1">Stok</label>
                <input type="number"
                       name="stok"
                       min="0"
                       value="{{ old('stok', 0) }}"
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-[#009688] focus:border-[#009688]"
                       required>
            </div>

            {{-- Harga --}}
            <div>
                <label class="block text-xs text-gray-600 mb-1">Harga (Rp)</label>
                <input type="number"
                       name="harga"
                       min="0"
                       value="{{ old('harga', 0) }}"
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-[#009688] focus:border-[#009688]"
                       required>

                <p class="text-[11px] text-gray-400 mt-1">
                    Masukkan angka tanpa titik/koma. Contoh: 3000
                </p>
            </div>

            {{-- Gambar --}}
            <div>
                <label class="block text-xs text-gray-600 mb-1">Gambar Produk</label>
                <input type="file"
                       name="gambar_produk"
                       accept="image/*"
                       class="w-full border rounded-lg px-3 py-2 text-sm bg-white">

                <p class="text-[11px] text-gray-400 mt-1">
                    Format: JPG, JPEG, PNG. Maks 2MB.
                </p>
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-2 pt-4 border-t mt-2">
                <a href="{{ route('admin.produk.lihat') }}"
                   class="inline-flex items-center gap-1 px-4 py-2 text-sm rounded-full
                          bg-red-100 text-red-700 hover:bg-red-200">
                    Batal
                </a>

                <button type="submit"
                        class="inline-flex items-center gap-1 px-4 py-2 text-sm rounded-full
                               bg-emerald-500 text-white hover:bg-emerald-600">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection