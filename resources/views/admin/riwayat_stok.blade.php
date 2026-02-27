@extends('layouts.admin')

@section('title', 'Riwayat Selisih Stok')

@section('content')

<div class="container-fluid">
<!-- CARD FILTER MODERN -->
<div class="card border-0 shadow rounded-4 mb-4">

   <div class="p-4">

            <!-- HEADER HALAMAN -->
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-xl font-bold text-gray-800">
                    Cek Riwayat Stok
                </h1>
                <span class="text-sm text-gray-500">
                    Data Monitoring Historis
                </span>
            </div>

            <!-- FILTER -->
            <div class="bg-white shadow rounded p-6 mb-6">

                <div class="mb-5">
                    <h2 class="text-lg font-semibold text-gray-800 mb-1">
                        Filter Periode
                    </h2>
                    <p class="text-sm text-gray-500">
                        Pilih rentang tanggal untuk menampilkan riwayat monitoring stok.
                    </p>
                </div>

                <form method="GET" action="{{ route('admin.monitoring_stok.riwayat') }}">

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">

                        <!-- TANGGAL MULAI -->
                        <div class="lg:col-span-4">
                            <label class="block text-sm font-medium text-gray-600 mb-2">
                                Tanggal Mulai
                            </label>
                            <input type="date"
                                name="tanggal_mulai"
                                value="{{ old('tanggal_mulai', $tanggal_mulai ?? '') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gray-300 outline-none">
                        </div>

                        <!-- TANGGAL SELESAI -->
                        <div class="lg:col-span-4">
                            <label class="block text-sm font-medium text-gray-600 mb-2">
                                Tanggal Selesai
                            </label>
                            <input type="date"
                                name="tanggal_selesai"
                                value="{{ old('tanggal_selesai', $tanggal_selesai ?? '') }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gray-300 outline-none">
                        </div>

                        <!-- BUTTON -->
                        <div class="lg:col-span-4">
                            <button type="submit"
                                class="w-full bg-gray-800 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-700 transition">
                                Tampilkan Riwayat
                            </button>
                        </div>

                    </div>

                    <!-- QUICK FILTER -->
                    <div class="mt-5 pt-4 border-t border-gray-200 flex flex-wrap gap-2 items-center">

                        <span class="text-sm text-gray-500">
                            Filter Cepat:
                        </span>

                        <a href="?tanggal_mulai={{ now()->toDateString() }}&tanggal_selesai={{ now()->toDateString() }}"
                        class="px-3 py-1 text-xs rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 transition">
                            Hari Ini
                        </a>

                        <a href="?tanggal_mulai={{ now()->subDays(7)->toDateString() }}&tanggal_selesai={{ now()->toDateString() }}"
                        class="px-3 py-1 text-xs rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 transition">
                            7 Hari Terakhir
                        </a>

                        <a href="?tanggal_mulai={{ now()->startOfMonth()->toDateString() }}&tanggal_selesai={{ now()->endOfMonth()->toDateString() }}"
                        class="px-3 py-1 text-xs rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 transition">
                            Bulan Ini
                        </a>

                    </div>

                </form>
            </div>


            <!-- INFO RANGE -->
            @if(request('tanggal_mulai') && request('tanggal_selesai'))
                <div class="mb-4 text-sm text-gray-600">
                    Menampilkan data dari
                    <span class="font-semibold">{{ request('tanggal_mulai') }}</span>
                    sampai
                    <span class="font-semibold">{{ request('tanggal_selesai') }}</span>
                </div>
            @endif


            <!-- TABEL -->
            <div class="bg-white shadow rounded p-6">

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200 text-sm">

                        <thead class="bg-gray-50 text-gray-700 text-xs uppercase tracking-wide">
                            <tr>
                                <th class="px-4 py-3 text-center">No</th>
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-left">Nama Produk</th>
                                <th class="px-4 py-3 text-center">Stok Sistem</th>
                                <th class="px-4 py-3 text-center">Stok Fisik</th>
                                <th class="px-4 py-3 text-center">Selisih</th>
                                <th class="px-4 py-3 text-center">Status</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

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

                                <td class="px-4 py-3 text-center text-gray-600">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ \Carbon\Carbon::parse($c->tanggal)->format('Y-m-d') }}
                                </td>

                                <td class="px-4 py-3 font-medium text-gray-800">
                                    {{ $c->produk->nama_produk }}
                                </td>

                                <td class="px-4 py-3 text-center font-semibold">
                                    {{ $c->stok_sistem }}
                                </td>

                                <td class="px-4 py-3 text-center font-semibold">
                                    {{ $c->stok_fisik }}
                                </td>

                                <td class="px-4 py-3 text-center font-semibold">
                                    {{ $c->selisih }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                        {{ $text }}
                                        @if($c->selisih != 0)
                                            ({{ abs($c->selisih) }})
                                        @endif
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                                    Tidak ada data riwayat selisih stok
                                </td>
                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


    </div>
</div>

@endsection
