@extends('layouts.admin')

@section('title', 'Detail Penjualan')

@section('content')
<div class="bg-white shadow rounded p-6">
    <h1 class="text-xl font-bold mb-4">Detail Penjualan</h1>

    {{-- Informasi Transaksi --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div>
            <p><strong>No Transaksi:</strong> {{ $penjualan->id }}</p>
            <p><strong>Waktu:</strong> {{ $penjualan->waktu }}</p>
            <p><strong>Metode Pembayaran:</strong> {{ ucfirst($penjualan->metode_pembayaran) }}</p>
        </div>

        <div>
            <p><strong>Total Harga:</strong> Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</p>
            <p><strong>Dibayar:</strong>
                @if($penjualan->paid_amount)
                    Rp {{ number_format($penjualan->paid_amount, 0, ',', '.') }}
                @else
                    -
                @endif
            </p>
            <p><strong>Paid At:</strong>
                {{ $penjualan->paid_at ? $penjualan->paid_at : '-' }}
            </p>
            <p><strong>Status:</strong>
                @if($penjualan->status === 'sukses')
                    <span class="px-2 py-1 bg-emerald-100 text-emerald-800 rounded text-xs">Sukses</span>
                @elseif($penjualan->status === 'pending')
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs">Pending</span>
                @else
                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs">{{ $penjualan->status }}</span>
                @endif
            </p>
        </div>
    </div>

    {{-- Detail Produk --}}
    <h2 class="text-lg font-semibold mb-3">Detail Produk</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 mb-4">
            <thead class="bg-gray-50 text-xs text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Produk</th>
                    <th class="px-4 py-3 text-right">Harga Satuan</th>
                    <th class="px-4 py-3 text-center">Jumlah</th>
                    <th class="px-4 py-3 text-right">Subtotal</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200 text-sm">
                @foreach($penjualan->details as $d)
                <tr>
                    <td class="px-4 py-3">{{ $d->nama_produk }}</td>
                    <td class="px-4 py-3 text-right">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-center">{{ $d->jumlah }}</td>
                    <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Tombol Kembali --}}
    <a href="{{ route('admin.penjualan.index') }}"
       class="inline-block mt-4 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-500">
        Kembali
    </a>
</div>
@endsection
