@extends('layouts.admin')

@section('title', 'Monitoring Selisih Stok')

@section('content')

<div class="p-4">

    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Monitoring Stok</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<!-- FORM INPUT PENGECEKAN -->
<div class="card border-0 shadow-sm mb-5">

    <div class="card-body p-5">

        <div class="mb-4">
            <h5 class="fw-semibold mb-1">
                Monitoring Stok Harian
            </h5>
            <p class="text-muted mb-0" style="font-size: 14px;">
                Input stok fisik untuk menghitung selisih dengan stok sistem.
            </p>
        </div>

        @php
            $produkSudahDicek = $checks->pluck('id_produk')->toArray();
        @endphp

        <form action="{{ route('admin.monitoring_stok.store') }}" method="POST">
            @csrf

            <div class="row gy-4">

                <!-- PILIH PRODUK -->
                <div class="col-md-6">
                    <label class="form-label text-secondary small mb-2">
                        Produk
                    </label>

                    <select name="id_produk"
                            class="form-select py-2"
                            required>

                        <option value="">
                            Pilih produk
                        </option>

                        @foreach($produk as $p)
                            <option value="{{ $p->id_produk }}"
                                {{ in_array($p->id_produk, $produkSudahDicek) ? 'disabled' : '' }}>
                                
                                {{ $p->nama_produk }}

                                @if(in_array($p->id_produk, $produkSudahDicek))
                                    - (Sudah dicek)
                                @endif
                            </option>
                        @endforeach

                    </select>
                </div>

                <!-- INPUT STOK FISIK -->
                <div class="col-md-4">
                    <label class="form-label text-secondary small mb-2">
                        Stok Fisik
                    </label>

                    <input type="number"
                        name="stok_fisik"
                        class="form-control py-2"
                        placeholder="Masukkan jumlah"
                        required>
                </div>

                <!-- TOMBOL -->
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-dark w-100 py-2">
                        Simpan
                    </button>
                </div>

            </div>

        </form>

    </div>
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
