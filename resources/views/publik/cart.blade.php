@extends('layouts.publik')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="bg-slate-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <h1 class="text-xl font-bold text-slate-100 mb-4">Keranjang Belanja</h1>

        @php
            $cartItems = session('cartItems', []);
            $cartTotal = session('cart_total', 0);
        @endphp

        @if (empty($cartItems))
            <p class="text-slate-400">Keranjang masih kosong.</p>
        @else
            <form action="{{ route('cart.checkout') }}" method="POST" id="cartForm">
                @csrf

                <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 overflow-x-auto">
                    <table class="w-full text-sm text-slate-100">
                        <thead>
                            <tr class="border-b border-slate-700">
                                <th class="py-2 text-left">Produk</th>
                                <th class="py-2 text-right">Harga</th>
                                <th class="py-2 text-center">Jumlah</th>
                                <th class="py-2 text-right">Subtotal</th>
                                <th class="py-2 text-center">Hapus</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($cartItems as $key => $item)
                                <tr class="border-b border-slate-800 cart-row"
                                    data-key="{{ $key }}"
                                    data-harga="{{ (int) $item['harga_satuan'] }}">


                                    {{-- PRODUK --}}
                                    <td class="py-2">
                                        <label class="inline-flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox"
                                                name="items[{{ $key }}][checked]"
                                                class="item-check">

                                            <span class="font-medium">
                                                {{ $item['nama_produk'] }}
                                            </span>
                                        </label>
                                    </td>

                                    {{-- HARGA --}}
                                    <td class="py-2 text-right">
                                        Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}
                                    </td>

                                    {{-- JUMLAH --}}
                                    <td class="py-2 text-center">
                                        <div class="inline-flex items-center rounded border border-slate-600 overflow-hidden">
                                            <button type="button"
                                                class="btn-minus px-2 py-0.5 bg-slate-700 text-white text-xs">
                                                −
                                            </button>

                                            <input type="number"
                                                name="items[{{ $key }}][jumlah]"
                                                value="{{ $item['jumlah'] }}"
                                                min="1"
                                                class="qty-input w-10 text-center text-xs bg-slate-800 text-white outline-none">

                                            <button type="button"
                                                class="btn-plus px-2 py-0.5 bg-slate-700 text-white text-xs">
                                                +
                                            </button>
                                        </div>

                                        {{-- HIDDEN --}}
                                        <input type="hidden" name="items[{{ $key }}][id_produk]" value="{{ $item['id_produk'] }}">
                                        <input type="hidden" name="items[{{ $key }}][harga]" value="{{ (int) $item['harga_satuan'] }}">
                                        <input type="hidden" name="items[{{ $key }}][nama_produk]" value="{{ $item['nama_produk'] }}">
                                    </td>

                                    {{-- SUBTOTAL --}}
                                    <td class="py-2 text-right subtotal-cell">
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </td>

                                    {{-- HAPUS --}}
                                    <td class="py-2 text-center">
                                        <button type="button"
                                            class="btn-remove text-red-400 hover:text-red-300 text-lg"
                                            title="Hapus dari keranjang">
                                            ✕
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- FOOTER --}}
                    <div class="mt-4 flex justify-between items-center">
                        <p class="text-lg font-bold text-emerald-400">
                            Total Terpilih: <span id="selectedTotal">Rp 0</span>
                            <input type="hidden" name="total_checkout" id="totalCheckout" value="0">
                        </p>

                        <button type="submit"
                            class="bg-emerald-500 text-white px-6 py-2 rounded hover:bg-emerald-600">
                            Bayar Produk Terpilih
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>
{{-- NOTIFICATION CONTAINER --}}
<div id="toast"
     class="fixed top-5 left-5
            bg-slate-800 border border-slate-700
            text-slate-100
            px-4 py-2 rounded-lg shadow-lg
            hidden z-50 text-sm
            transition-all duration-300">
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const cartForm      = document.getElementById('cartForm');
    const checkAll      = document.getElementById('checkAll');
    const selectedTotal = document.getElementById('selectedTotal');
    const totalCheckout = document.getElementById('totalCheckout');

    if (!cartForm) return;

    const formatRupiah = n =>
        'Rp ' + (n || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    function hitungTotalTerpilih() {
        let total = 0;

        document.querySelectorAll('.cart-row').forEach(row => {
            const check = row.querySelector('.item-check');
            const harga = parseInt(row.dataset.harga || '0', 10);
            const qty   = parseInt(row.querySelector('.qty-input').value || '0', 10);

            const subtotal = harga * (isNaN(qty) ? 0 : qty);
            row.querySelector('.subtotal-cell').textContent = formatRupiah(subtotal);

            if (check && check.checked) {
                total += subtotal;
            }
        });

        selectedTotal.textContent = formatRupiah(total);
        totalCheckout.value = total;
    }

    // ceklis per item
    document.querySelectorAll('.item-check').forEach(cb => {
        cb.addEventListener('change', hitungTotalTerpilih);
    });

    // tombol +
    document.querySelectorAll('.btn-plus').forEach(btn => {
        btn.addEventListener('click', () => {
            const qty = btn.closest('td').querySelector('.qty-input');
            qty.value = (parseInt(qty.value || '0', 10) || 0) + 1;
            hitungTotalTerpilih();
        });
    });

    // tombol -
    document.querySelectorAll('.btn-minus').forEach(btn => {
        btn.addEventListener('click', () => {
            const qty = btn.closest('td').querySelector('.qty-input');
            qty.value = Math.max(1, (parseInt(qty.value || '1', 10) || 1) - 1);
            hitungTotalTerpilih();
        });
    });

    // edit manual qty
    document.querySelectorAll('.qty-input').forEach(inp => {
        inp.addEventListener('input', () => {
            if (inp.value === '' || parseInt(inp.value, 10) < 1) {
                inp.value = 1;
            }
            hitungTotalTerpilih();
        });
    });

    // tombol hapus
    document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.addEventListener('click', () => {
            if (!confirm('Hapus produk dari keranjang?')) return;

            const row = btn.closest('.cart-row');
            const key = row.dataset.key;

            fetch("{{ route('cart.remove') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ key })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    row.remove();
                    hitungTotalTerpilih();
                    tampilkanNotifikasi('Produk berhasil dihapus');
                }
            });
        });
    });
    
    function tampilkanNotifikasi(text) {
        const toast = document.getElementById('toast');
        if (!toast) return;

        toast.textContent = text;
        toast.classList.remove('hidden', '-translate-x-2', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.add('-translate-x-2', 'opacity-0');
            setTimeout(() => toast.classList.add('hidden'), 300);
        }, 2000);
    }


    // validasi submit
    cartForm.addEventListener('submit', e => {
        if (parseInt(totalCheckout.value || '0', 10) <= 0) {
            e.preventDefault();
            alert('Pilih minimal satu produk untuk dibayar.');
        }
    });

    // hitung awal
    hitungTotalTerpilih();
});
</script>
@endpush

