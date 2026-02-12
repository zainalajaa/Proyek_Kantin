@extends('layouts.admin')

@section('title', 'Riwayat Selisih Stok')

@section('content')

<div class="p-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Riwayat Selisih Stok</h4>
    </div>

    <div class="bg-white rounded shadow-sm mb-4">

    <div class="p-3 border-bottom">
        <h6 class="fw-bold mb-0">Filter Riwayat Selisih Stok</h6>
    </div>

    <div class="p-4">

        <form method="GET">

            <div class="row g-4">

                <!-- MODE RIWAYAT -->
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-bold">Mode Riwayat</label>

                    <select name="mode" class="form-select p-2">
                        <option value="harian" {{ $mode == 'harian' ? 'selected' : '' }}>
                            Harian
                        </option>

                        <option value="mingguan" {{ $mode == 'mingguan' ? 'selected' : '' }}>
                            Mingguan
                        </option>

                        <option value="bulanan" {{ $mode == 'bulanan' ? 'selected' : '' }}>
                            Bulanan
                        </option>
                    </select>

                    <small class="text-muted">
                        Pilih jenis periode laporan
                    </small>
                </div>


                <!-- TANGGAL -->
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-bold">Tanggal (Harian)</label>

                    <input type="date"
                           name="tanggal"
                           class="form-control p-2"
                           value="{{ $tanggal }}">

                    <small class="text-muted">
                        Aktif saat mode harian
                    </small>
                </div>


                <!-- MINGGU -->
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-bold">Minggu ke-</label>

                    <input type="number"
                           min="1"
                           max="53"
                           name="minggu"
                           class="form-control p-2"
                           placeholder="Contoh: 12"
                           value="{{ $minggu }}">

                    <small class="text-muted">
                        Aktif saat mode mingguan
                    </small>
                </div>


                <!-- BULAN -->
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-bold">Bulan</label>

                    <select name="bulan" class="form-select p-2">
                        <option value="">-- Pilih Bulan --</option>

                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}"
                                {{ $bulan == $i ? 'selected' : '' }}>
                                {{ date('F', mktime(0,0,0,$i,1)) }}
                            </option>
                        @endfor
                    </select>

                    <small class="text-muted">
                        Aktif saat mode bulanan
                    </small>
                </div>

            </div>


            <!-- TOMBOL AKSI -->
            <div class="row mt-4 g-3">

                <div class="col-md-6">
                    <button class="btn btn-primary w-100 p-2 fw-bold">
                        🔍 Tampilkan Riwayat
                    </button>
                </div>

                <div class="col-md-6">
                    <a href="{{ route('admin.monitoring_stok.riwayat') }}"
                       class="btn btn-outline-secondary w-100 p-2 fw-bold">
                        ↺ Reset Filter
                    </a>
                </div>

            </div>

        </form>

    </div>

</div>



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
                        $statusColor = '#b91c1c';   // merah
                        $text = 'Kurang';
                    } else {
                        $statusColor = '#d97706';   // orange
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
                            padding: 8px 16px;
                            border-radius: 10px;
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
                        Tidak ada data riwayat selisih stok
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>


</div>

@endsection
