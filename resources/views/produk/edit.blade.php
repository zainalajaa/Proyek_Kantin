@extends('layouts.admin')
@section('title', 'Ubah Produk')

@section('content')
<div class="min-h-[calc(100vh-80px)] flex items-start md:items-center justify-center px-4 py-6">

    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-lg border border-gray-100">
        
        {{-- Header --}}
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h2 class="text-base md:text-lg font-semibold text-gray-800">
                Form Ubah Produk
            </h2>
            <a href="{{ route('admin.produk.lihat') }}"
               class="text-gray-400 hover:text-gray-600 text-xl leading-none">
                &times;
            </a>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.produk.update', $produk->id_produk) }}"
              method="POST"
              enctype="multipart/form-data"
              class="px-6 py-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Info wajib --}}
            <p class="text-xs text-gray-400">
                <span class="text-red-500">*</span> wajib diisi
            </p>

            {{-- Error --}}
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 text-xs px-4 py-3 rounded-xl">
                    <ul class="list-disc ml-4 space-y-1">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Nama Produk --}}
            <div class="space-y-1">
                <label class="text-xs font-medium text-gray-600">
                    Nama Produk <span class="text-red-500">*</span>
                </label>
                <input type="text"
                    name="nama_produk"
                    value="{{ old('nama_produk', $produk->nama_produk) }}"
                    required
                    oninvalid="this.setCustomValidity('Nama produk wajib diisi')"
                    oninput="this.setCustomValidity('')"
                    class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            {{-- Kategori --}}
            <div class="space-y-1">
                <label class="text-xs font-medium text-gray-600">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <select name="kategori_id"
                    required
                    oninvalid="this.setCustomValidity('Kategori wajib dipilih')"
                    oninput="this.setCustomValidity('')"
                    class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm bg-white
                           focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">

                    <option value="">-- Pilih Kategori --</option>

                    @foreach($kategori as $k)
                        <option value="{{ $k->id }}"
                            {{ old('kategori_id', $produk->kategori_id) == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- Stok --}}
            <div class="space-y-1">
                <label class="text-xs font-medium text-gray-600">
                    Stok <span class="text-red-500">*</span>
                </label>
                <input type="number"
                    name="stok"
                    min="0"
                    value="{{ old('stok', $produk->stok) }}"
                    required
                    oninvalid="this.setCustomValidity('Stok wajib diisi')"
                    oninput="this.setCustomValidity('')"
                    class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            {{-- Harga --}}
            <div class="space-y-1">
                <label class="text-xs font-medium text-gray-600">
                    Harga (Rp) <span class="text-red-500">*</span>
                </label>
                <input type="number"
                    name="harga"
                    min="0"
                    value="{{ old('harga', $produk->harga) }}"
                    required
                    oninvalid="this.setCustomValidity('Harga wajib diisi')"
                    oninput="this.setCustomValidity('')"
                    class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">

                <p class="text-[11px] text-gray-400">
                    Masukkan angka tanpa titik/koma, contoh: 3000
                </p>
            </div>

            {{-- Gambar --}}
            <div class="space-y-2">
                <label class="text-xs font-medium text-gray-600">
                    Gambar Produk <span class="text-red-500">*</span>
                </label>

                @if ($produk->gambar_produk)
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('storage/'.$produk->gambar_produk) }}"
                             class="w-16 h-16 object-cover rounded-lg shadow">

                        <span class="text-xs text-gray-500">
                            Gambar saat ini. Upload ulang jika ingin mengganti.
                        </span>
                    </div>
                @endif

                <input type="file"
                    name="gambar_produk"
                    accept="image/*"
                    {{ !$produk->gambar_produk ? 'required' : '' }}
                    oninvalid="this.setCustomValidity('Gambar wajib diupload')"
                    oninput="this.setCustomValidity('')"
                    class="w-full text-sm border border-gray-300 rounded-xl
                           file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                           file:text-sm file:font-medium
                           file:bg-emerald-50 file:text-emerald-700
                           hover:file:bg-emerald-100 cursor-pointer">

                <p class="text-[11px] text-gray-400">
                    Format: JPG, JPEG, PNG. Maks 2MB.
                </p>
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.produk.lihat') }}"
                   class="px-4 py-2 text-sm rounded-full bg-red-100 text-red-700 hover:bg-red-200">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-2 text-sm rounded-full bg-emerald-500 text-white hover:bg-emerald-600 shadow-sm">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection