@extends('layouts.admin')

@section('title', 'Monitoring Selisih Stok')

@section('content')

<div class="p-4 sm:p-6">

    <!-- HEADER -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <span class="text-sm text-gray-500">
            {{ date('d M Y') }}
        </span>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-emerald-100 text-emerald-700 px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-100 text-red-700 px-4 py-3 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @php
        $totalProduk = $produk->count();
        $totalDicek = $checks->count();
        $sisaProduk = $totalProduk - $totalDicek;
    @endphp

    @if($totalDicek == 0)
    <div class="mb-4 rounded-xl bg-red-50 border border-red-200 px-4 py-3 flex items-center justify-between">
        <div class="text-red-700 text-sm font-medium">
            ❗ Belum ada input monitoring hari ini.
        </div>
        <span class="bg-red-500 text-white text-xs px-3 py-1 rounded-full animate-pulse">
            Wajib Input
        </span>
    </div>

    @elseif($sisaProduk > 0)
        <div class="mb-4 rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 flex items-center justify-between">
            <div class="text-amber-700 text-sm font-medium">
                ⚠ Monitoring belum selesai.
                <span class="font-semibold">{{ $sisaProduk }}</span>
                produk belum dicek.
            </div>
            <span class="bg-amber-400 text-gray-800 text-xs px-3 py-1 rounded-full">
                Belum Lengkap
            </span>
        </div>

    @else
        <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 flex items-center justify-between">
            <div class="text-emerald-700 text-sm font-medium">
                ✅ Monitoring stok hari ini sudah lengkap.
            </div>
            <span class="bg-emerald-500 text-white text-xs px-3 py-1 rounded-full">
                Selesai
            </span>
        </div>
    @endif

    @if(!empty($tanggalBermasalah))
        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4">

            <h3 class="text-sm font-semibold text-red-700 mb-3">
                ⚠ Riwayat Monitoring Bermasalah (30 Hari Terakhir)
            </h3>

            <div class="space-y-2 max-h-48 overflow-y-auto">

                @foreach($tanggalBermasalah as $item)
                    <div class="flex justify-between items-center bg-white px-3 py-2 rounded-lg shadow-sm">

                        <span class="font-medium text-gray-700">
                            {{ $item['tanggal'] }}
                        </span>

                        @if($item['status'] === 'tidak_input')
                            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-red-100 text-red-700">
                                Tidak Ada Input
                            </span>
                        @else
                            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-amber-100 text-amber-700">
                                Belum Selesai
                            </span>
                        @endif

                    </div>
                @endforeach

            </div>
        </div>
    @endif

   <!-- FORM MONITORING -->
    <div class="bg-white shadow-sm rounded-xl p-4 sm:p-6 mb-6">

        <div class="mb-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">
                Monitoring Stok Harian
            </h2>
            <p class="text-sm text-gray-500">
                Input stok fisik untuk menghitung selisih dengan stok sistem.
            </p>
        </div>

        @php
            $produkSudahDicek = $checks->pluck('id_produk')->toArray();
        @endphp

        <form action="{{ route('admin.monitoring_stok') }}" method="POST" novalidate>
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 items-start">

                <!-- PRODUK -->
                <div class="lg:col-span-6 flex flex-col">
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        Pilih Produk
                    </label>

                    <select name="id_produk"
                        class="w-full border rounded-lg px-3 py-2 text-sm outline-none
                        focus:ring-2 focus:ring-gray-300
                        @error('id_produk') border-red-500 @else border-gray-300 @enderror">

                        <option value="">Pilih produk</option>

                        @foreach($produk as $p)
                            <option value="{{ $p->id_produk }}"
                                {{ old('id_produk') == $p->id_produk ? 'selected' : '' }}
                                {{ in_array($p->id_produk, $produkSudahDicek) ? 'disabled' : '' }}>
                                {{ $p->nama_produk }}
                                @if(in_array($p->id_produk, $produkSudahDicek))
                                    (Sudah dicek)
                                @endif
                            </option>
                        @endforeach
                    </select>

                    <div class="min-h-[18px]">
                        @error('id_produk')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- STOK FISIK -->
                <div class="lg:col-span-4 flex flex-col">
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        Stok Fisik
                    </label>

                    <input type="number"
                        name="stok_fisik"
                        min="0"
                        value="{{ old('stok_fisik') }}"
                        placeholder="Masukkan jumlah"
                        class="w-full border rounded-lg px-3 py-2 text-sm outline-none
                        focus:ring-2 focus:ring-gray-300
                        @error('stok_fisik') border-red-500 @else border-gray-300 @enderror">

                    <div class="min-h-[18px]">
                        @error('stok_fisik')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="lg:col-span-2 flex flex-col">
                    <!-- label transparan untuk alignment -->
                    <label class="block text-sm font-medium text-transparent mb-2">
                        Aksi
                    </label>

                    <button type="submit"
                        class="w-full bg-gray-800 text-white py-2 rounded-lg text-sm font-semibold hover:bg-gray-700 transition">
                        Simpan
                    </button>

                    <!-- spacer biar sejajar dengan error -->
                    <div class="min-h-[18px]"></div>
                </div>

            </div>
        </form>
    </div>

    <!-- INFO TANGGAL -->
    <div class="mb-4 rounded-lg bg-blue-50 text-blue-700 px-4 py-3 text-sm">
        Menampilkan data selisih stok untuk tanggal:
        <strong>{{ date('d-m-Y') }}</strong>
    </div>

    <!-- TABEL MONITORING -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">

                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="py-3 px-4 text-center">No</th>
                        <th class="py-3 px-4 text-left">Tanggal</th>
                        <th class="py-3 px-4 text-left">Nama Produk</th>
                        <th class="py-3 px-4 text-center">Stok Sistem</th>
                        <th class="py-3 px-4 text-center">Stok Fisik</th>
                        <th class="py-3 px-4 text-center">Selisih</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                @forelse($checks as $index => $c)

                    @php
                        if($c->selisih == 0){
                            $badgeClass = 'bg-emerald-100 text-emerald-700';
                            $text = 'Sesuai';
                        } elseif($c->selisih > 0){
                            $badgeClass = 'bg-red-100 text-red-700';
                            $text = 'Kurang';
                        } else {
                            $badgeClass = 'bg-blue-100 text-blue-700';
                            $text = 'Lebih';
                        }
                    @endphp

                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-4 text-center">
                            {{ $index + 1 }}
                        </td>

                        <td class="py-3 px-4">
                            {{ $c->tanggal }}
                        </td>

                        <td class="py-3 px-4">
                            {{ $c->produk->nama_produk }}
                        </td>

                        <td class="py-3 px-4 text-center font-semibold">
                            {{ $c->stok_sistem }}
                        </td>

                        <td class="py-3 px-4 text-center font-semibold">
                            {{ $c->stok_fisik }}
                        </td>

                        <td class="py-3 px-4 text-center font-semibold">
                            {{ $c->selisih }}
                        </td>

                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                {{ $text }}
                                @if($c->selisih != 0)
                                    <span class="ml-1">
                                        ({{ abs($c->selisih) }})
                                    </span>
                                @endif
                            </span>
                        </td>

                        <!-- AKSI EDIT -->
                        <td class="py-3 px-4 text-center">
                            @if(\Carbon\Carbon::parse($c->tanggal)->isToday())
                                <a href="{{ route('admin.monitoring_stok.edit', $c->id) }}"
                                   class="text-blue-600 hover:underline text-sm font-semibold">
                                    Edit
                                </a>
                            @else
                                <span class="text-gray-400 text-sm">
                                    Terkunci
                                </span>
                            @endif
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="8" class="text-center text-gray-400 py-6">
                            Belum ada data monitoring stok
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection