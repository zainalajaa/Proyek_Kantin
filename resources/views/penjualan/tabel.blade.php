@extends('layouts.admin')

@section('title', 'Penjualan')

@section('content')
<div class="bg-white shadow rounded p-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold">Penjualan Produk</h1>
        <div>
            <span class="text-sm text-gray-600 mr-4">Total: <strong>{{ $penjualan->total() }}</strong></span>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 text-xs text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Waktu</th>
                    <th class="px-4 py-3 text-left">Metode</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-right">Dibayar</th>
                    <th class="px-4 py-3 text-left">Paid At</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-sm">
                @forelse($penjualan as $p)
                    <tr>
                        <td class="px-4 py-3">{{ $penjualan->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($p->waktu)->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="px-4 py-3 capitalize">{{ $p->metode_pembayaran }}</td>
                        <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">
                            @if($p->paid_amount)
                                Rp {{ number_format($p->paid_amount, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $p->paid_at ? \Carbon\Carbon::parse($p->paid_at)->format('Y-m-d H:i:s') : '-' }}
                        </td>
                        <td class="px-4 py-3">
                            @if($p->status === 'sukses')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-emerald-100 text-emerald-800">Sukses</span>
                            @elseif($p->status === 'pending')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">Pending</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-800">{{ $p->status }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.penjualan.show', $p->id) }}" class="text-sm px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-500">Detail</a>
                                {{-- Kalau transaksi pending dan metode tunai, tampilkan tombol untuk menyelesaikan --}}
                                @if($p->status === 'pending' && $p->metode_pembayaran === 'tunai')
                                    <a href="{{ route('publik.tunai.detail', $p->id) }}" class="text-sm px-3 py-1 rounded bg-amber-500 text-white hover:bg-amber-400" target="_blank">
                                        Selesaikan
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-6 text-center text-gray-500" colspan="8">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $penjualan->links() }}
    </div>
</div>
@endsection
