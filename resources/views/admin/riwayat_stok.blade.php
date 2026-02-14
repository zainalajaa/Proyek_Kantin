@extends('layouts.admin')

@section('title', 'Riwayat Selisih Stok')

@section('content')

<div class="container-fluid">

    <!-- Judul Section -->
    <h5 class="mb-3">Riwayat Selisih Stok</h5>

<!-- CARD FILTER MODERN -->
<div class="card border-0 shadow-sm mb-4" 
     style="border-radius:16px; background:linear-gradient(145deg,#ffffff,#f8fafc);">

    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="fw-bold mb-0">
                📅 Filter Periode Riwayat Stok
            </h6>

            <small class="text-muted">
                Pilih rentang tanggal untuk melihat riwayat
            </small>
        </div>

        <form method="GET" action="{{ route('admin.monitoring_stok.riwayat') }}">

            <div class="row g-3 align-items-end">

                <!-- TANGGAL MULAI -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small">
                        Tanggal Mulai
                    </label>
                    <input type="date"
                           name="tanggal_mulai"
                           class="form-control border-0 shadow-sm"
                           style="border-radius:12px;"
                           value="{{ old('tanggal_mulai', $tanggal_mulai ?? '') }}">
                </div>

                <!-- TANGGAL SELESAI -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-muted small">
                        Tanggal Selesai
                    </label>
                    <input type="date"
                           name="tanggal_selesai"
                           class="form-control border-0 shadow-sm"
                           style="border-radius:12px;"
                           value="{{ old('tanggal_selesai', $tanggal_selesai ?? '') }}">
                </div>

                <!-- BUTTON -->
                <div class="col-md-4 d-grid">
                    <button type="submit"
                            class="btn text-white fw-semibold"
                            style="background-color:#0f766e;
                                   border-radius:12px;
                                   padding:10px 0;">
                        🔍 Tampilkan Riwayat
                    </button>
                </div>

            </div>

            <!-- QUICK FILTER -->
            <div class="mt-4 d-flex gap-2 flex-wrap">

                <a href="?tanggal_mulai={{ now()->toDateString() }}&tanggal_selesai={{ now()->toDateString() }}"
                   class="btn btn-sm btn-light shadow-sm rounded-pill px-3">
                    Hari Ini
                </a>

                <a href="?tanggal_mulai={{ now()->subDays(7)->toDateString() }}&tanggal_selesai={{ now()->toDateString() }}"
                   class="btn btn-sm btn-light shadow-sm rounded-pill px-3">
                    7 Hari Terakhir
                </a>

                <a href="?tanggal_mulai={{ now()->startOfMonth()->toDateString() }}&tanggal_selesai={{ now()->endOfMonth()->toDateString() }}"
                   class="btn btn-sm btn-light shadow-sm rounded-pill px-3">
                    Bulan Ini
                </a>

            </div>

        </form>

    </div>
</div>



    <!-- CARD TABEL -->
    <div class="card shadow-sm">
    <div class="card-body">

        @if(request('tanggal_mulai') && request('tanggal_selesai'))
            <p class="mb-3">
                Menampilkan data selisih stok dari
                <strong>{{ request('tanggal_mulai') }}</strong>
                sampai
                <strong>{{ request('tanggal_selesai') }}</strong>
            </p>
        @endif

        <div class="table-responsive">

            <table class="table align-middle">

                <thead style="background-color: #f1f5f9;">
                    <tr>
                        <th class="py-3 px-3 text-center" style="width:5%;">No</th>
                        <th class="py-3 px-3" style="width:15%;">Tanggal</th>
                        <th class="py-3 px-3" style="width:25%;">Nama Produk</th>
                        <th class="py-3 px-3 text-center" style="width:12%;">Stok Sistem</th>
                        <th class="py-3 px-3 text-center" style="width:12%;">Stok Fisik</th>
                        <th class="py-3 px-3 text-center" style="width:10%;">Selisih</th>
                        <th class="py-3 px-3 text-center" style="width:15%;">Status</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($checks as $index => $c)

                    @php
                        if($c->selisih == 0){
                            $statusColor = '#0f766e'; // hijau modern
                            $text = 'Sesuai';
                        } elseif($c->selisih > 0){
                            $statusColor = '#b91c1c'; // merah
                            $text = 'Kurang';
                        } else {
                            $statusColor = '#d97706'; // orange
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
