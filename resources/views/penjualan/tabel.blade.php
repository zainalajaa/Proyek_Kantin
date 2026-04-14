@extends('layouts.admin')

@section('title', 'Penjualan')

@section('content')
<div class="bg-white shadow-sm rounded-lg p-6">

    <!-- HEADER + FILTER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div>
            <h1 class="text-xl font-semibold text-gray-800">Penjualan Produk</h1>
            <p class="text-sm text-gray-500">
                Total: <strong>{{ $penjualan->total() }}</strong> transaksi
            </p>
        </div>

        <!-- FILTER -->
        <form method="GET" action="{{ route('admin.penjualan.index') }}">
            <select name="filter"
                onchange="this.form.submit()"
                class="text-sm border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">

                <option value="">Semua Periode</option>
                <option value="harian" {{ request('filter') == 'harian' ? 'selected' : '' }}>Harian</option>
                <option value="mingguan" {{ request('filter') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                <option value="bulanan" {{ request('filter') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
            </select>
        </form>

    </div>

    <!-- TABEL -->
    <div class="overflow-x-auto">
        <table class="min-w-full border-separate border-spacing-y-2 text-sm">
            <thead>
                <tr class="text-xs uppercase tracking-wider text-gray-500">
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Waktu</th>

                    <th class="px-4 py-3 text-left hidden md:table-cell">Metode</th>
                    <th class="px-4 py-3 text-left">Bukti</th>

                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-right hidden md:table-cell">Dibayar</th>
                    <th class="px-4 py-3 text-left hidden md:table-cell">Paid At</th>

                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($penjualan as $p)
                <tr class="bg-white shadow-sm hover:shadow-md transition rounded-lg">

                    <!-- NO -->
                    <td class="px-4 py-4">
                        {{ $penjualan->firstItem() + $loop->index }}
                    </td>

                    <!-- WAKTU -->
                    <td class="px-4 py-4 text-gray-600 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($p->waktu)->format('d M Y, H:i') }}
                    </td>

                    <!-- METODE (HIDDEN MOBILE) -->
                    <td class="px-4 py-4 capitalize font-medium hidden md:table-cell">
                        {{ $p->metode_pembayaran ?? '-' }}
                    </td>

                    <!-- BUKTI QRIS -->
                    <td class="px-4 py-4 text-center">
                        @if($p->metode_pembayaran === 'qris')
                            
                            @if($p->bukti_pembayaran)
                                <a href="{{ asset('storage/' . $p->bukti_pembayaran) }}" target="_blank">
                                    <img 
                                        src="{{ asset('storage/' . $p->bukti_pembayaran) }}" 
                                        class="w-10 h-10 md:w-12 md:h-12 object-cover rounded-md border hover:scale-110 transition"
                                    >
                                </a>
                            @else
                                <span class="text-xs text-red-500">
                                    Belum upload
                                </span>
                            @endif

                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>

                    <!-- TOTAL -->
                    <td class="px-4 py-4 text-right font-semibold whitespace-nowrap">
                        <div class="flex justify-end gap-1">
                            <span>Rp</span>
                            <span class="tabular-nums">
                                {{ number_format($p->total_harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </td>

                    <!-- DIBAYAR (HIDDEN MOBILE) -->
                    <td class="px-4 py-4 text-right hidden md:table-cell whitespace-nowrap">
                        <div class="flex justify-end gap-1">
                            <span>Rp</span>
                            <span class="tabular-nums">
                                {{ number_format($p->paid_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </td>

                    <!-- PAID AT (HIDDEN MOBILE) -->
                    <td class="px-4 py-4 text-gray-500 whitespace-nowrap hidden md:table-cell">
                        {{ $p->paid_at 
                            ? \Carbon\Carbon::parse($p->paid_at)->format('d M Y, H:i') 
                            : '-' }}
                    </td>

                    <!-- STATUS -->
                    <td class="px-4 py-4 text-center">
                        @if($p->status === 'sukses')
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">
                                Sukses
                            </span>

                        @elseif($p->status === 'pending_qris')
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">
                                QRIS Pending
                            </span>

                        @else
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                                {{ ucfirst($p->status) }}
                            </span>
                        @endif
                    </td>

                    <!-- AKSI -->
                    <td class="px-4 py-4 text-center">
                        <a href="{{ route('admin.penjualan.show', $p->id) }}"
                           class="inline-block px-3 py-1.5 text-xs font-medium rounded-md bg-blue-600 text-white hover:bg-blue-500 transition">
                            Detail
                        </a>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                        Belum ada transaksi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="mt-6">
        {{ $penjualan->onEachSide(1)->links('pagination::simple-tailwind') }}
    </div>

</div>
@endsection