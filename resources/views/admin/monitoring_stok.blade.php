@extends('layouts.admin')

@section('title', 'Monitoring Selisih Stok')

@section('content')

<div class="p-4">

    <!-- HEADER HALAMAN -->
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-gray-800">
            Monitoring Stok
        </h1>
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
    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-100 text-red-700 px-4 py-3 text-sm">
            <ul class="mb-0 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <!-- FORM MONITORING -->
    <div class="bg-white shadow rounded p-6 mb-6">

        <!-- HEADER FORM -->
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

        <form action="{{ route('admin.monitoring_stok.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">

                <!-- PRODUK -->
                <div class="lg:col-span-6">
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        Pilih Produk
                    </label>
                    <select name="id_produk"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gray-300 outline-none"
                        required>
                        <option value="">Pilih produk</option>
                        @foreach($produk as $p)
                            <option value="{{ $p->id_produk }}"
                                {{ in_array($p->id_produk, $produkSudahDicek) ? 'disabled' : '' }}>
                                {{ $p->nama_produk }}
                                @if(in_array($p->id_produk, $produkSudahDicek))
                                    (Sudah dicek)
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- STOK FISIK -->
                <div class="lg:col-span-4">
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        Stok Fisik
                    </label>
                    <input type="number"
                        name="stok_fisik"
                        min="0"
                        placeholder="Masukkan jumlah"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-gray-300 outline-none"
                        required>
                </div>

                <!-- BUTTON -->
                <div class="lg:col-span-2">
                    <button type="submit"
                        class="w-full bg-gray-800 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-700 transition">
                        Simpan
                    </button>
                </div>

            </div>

        </form>

    </div>



    <div class="alert alert-info">
        Menampilkan data selisih stok untuk tanggal:
        <strong>{{ date('d-m-Y') }}</strong>
    </div>
   
    <!-- TABEL MONITORING -->
    <div class="bg-white rounded shadow-sm">

        <div class="table-responsive p-3">

            <table class="table table-bordered align-middle w-100">

                <thead style="background-color: #f1f5f9;">
                    <tr>
                        <th class="py-3 px-3 text-center" style="width:5%;">No</th>
                        <th class="py-3 px-3" style="width:15%;">Tanggal</th>
                        <th class="py-3 px-3" style="width:30%;">Nama Produk</th>
                        <th class="py-3 px-3 text-center" style="width:12%;">Stok Sistem</th>
                        <th class="py-3 px-3 text-center" style="width:12%;">Stok Fisik</th>
                        <th class="py-3 px-3 text-center" style="width:10%;">Selisih</th>
                        <th class="py-3 px-3 text-center" style="width:16%;">Status</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($checks as $index => $c)

                    @php
                        if($c->selisih == 0){
                            $statusColor = '#0f766e';   // hijau modern
                            $text = 'Sesuai';
                        } elseif($c->selisih > 0){
                            $statusColor = '#b91c1c';   // merah lebih soft
                            $text = 'Kurang';
                        } else {
                            $statusColor = '#d97706';   // orange modern
                            $text = 'Lebih';
                        }
                    @endphp


                    <tr>
                        <td class="py-3 px-3 text-center">
                            {{ $index + 1 }}
                        </td>

                        <td class="py-3 px-3">
                            {{ $c->tanggal }}
                        </td>

                        <td class="py-3 px-3">
                            {{ $c->produk->nama_produk }}
                        </td>

                        <td class="py-3 px-3 text-center fw-bold">
                            {{ $c->stok_sistem }}
                        </td>

                        <td class="py-3 px-3 text-center fw-bold">
                            {{ $c->stok_fisik }}
                        </td>

                        <td class="py-3 px-3 text-center fw-bold">
                            {{ $c->selisih }}
                        </td>

                        <td class="py-3 px-3 text-center">
                            <span style="
                                background-color: {{ $statusColor }};
                                color: white;
                                padding: 8px 18px;
                                border-radius: 12px;
                                font-weight: 600;
                                font-size: 14px;
                                min-width: 120px;
                                display: inline-block;
                                text-align: center;
                            ">
                                {{ $text }}

                                @if($c->selisih != 0)
                                    ({{ abs($c->selisih) }})
                                @endif
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
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
