@extends('layouts.admin')

@section('title', 'Detail Penjualan')

@section('content')
<div class="bg-white shadow-sm rounded-lg p-6">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-gray-800">Detail Penjualan</h1>
            <p class="text-sm text-gray-500">
                Informasi lengkap transaksi
            </p>
        </div>

        <a href="{{ route('admin.penjualan.index') }}"
           class="px-4 py-2 text-sm font-medium rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
            Kembali
        </a>
    </div>

    <!-- INFORMASI TRANSAKSI -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 text-sm">

        <div class="space-y-3">
            <div>
                <p class="text-gray-500">No Transaksi</p>
                <p class="font-medium">{{ $penjualan->id }}</p>
            </div>

            <div>
                <p class="text-gray-500">Waktu</p>
                <p class="font-medium">
                    {{ \Carbon\Carbon::parse($penjualan->waktu)->format('d M Y, H:i') }}
                </p>
            </div>

            <div>
                <p class="text-gray-500">Metode Pembayaran</p>
                <p class="font-medium capitalize">
                    {{ $penjualan->metode_pembayaran }}
                </p>
            </div>
        </div>

        <div class="space-y-3">
            <div>
                <p class="text-gray-500">Total Harga</p>
                <p class="font-semibold text-gray-800">
                    Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}
                </p>
            </div>

            <div>
                <p class="text-gray-500">Dibayar</p>
                <p class="font-medium">
                    {{ $penjualan->paid_amount 
                        ? 'Rp ' . number_format($penjualan->paid_amount, 0, ',', '.') 
                        : '-' }}
                </p>
            </div>

            <div>
                <p class="text-gray-500">Paid At</p>
                <p class="font-medium">
                    {{ $penjualan->paid_at 
                        ? \Carbon\Carbon::parse($penjualan->paid_at)->format('d M Y, H:i') 
                        : '-' }}
                </p>
            </div>

            <div>
                <p class="text-gray-500">Status</p>

                @if($penjualan->status === 'sukses')
                    <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">
                        Sukses
                    </span>

                @elseif($penjualan->status === 'pending_qris')
                    <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
                        QRIS Pending
                    </span>

                @else
                    <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                        {{ ucfirst($penjualan->status) }}
                    </span>
                @endif
            </div>

        </div>
    </div>

    <!-- DETAIL PRODUK -->
    <div>
        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Detail Produk
        </h2>

        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-y-2 text-sm">
                <thead>
                    <tr class="text-xs uppercase tracking-wider text-gray-500">
                        <th class="px-4 py-3 text-left">Produk</th>
                        <th class="px-4 py-3 text-right">Harga</th>
                        <th class="px-4 py-3 text-center">Qty</th>
                        <th class="px-4 py-3 text-right">Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($penjualan->details as $d)
                        <tr class="bg-white shadow-sm hover:shadow-md transition rounded-lg">
                            <td class="px-4 py-4 font-medium">
                                {{ $d->nama_produk }}
                            </td>

                            <td class="px-4 py-4 text-right">
                                Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-4 text-center">
                                {{ $d->jumlah }}
                            </td>

                            <td class="px-4 py-4 text-right font-semibold">
                                Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection