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
            <div class="bg-slate-800 border border-slate-700 rounded-2xl p-5">

                {{-- ================= MOBILE VIEW ================= --}}
                <div class="md:hidden space-y-4">
                    @foreach($cartItems as $key => $item)
                    <div class="cart-row bg-slate-800 border border-slate-700 p-4 rounded-xl space-y-3"
                        data-key="{{ $key }}"
                        data-harga="{{ (int) $item['harga_satuan'] }}">

                        <div class="flex justify-between items-center">
                            <label class="flex items-center gap-2">
                                <input type="checkbox"
                                    name="items[{{ $key }}][checked]"
                                    class="item-check accent-emerald-500">

                                <span class="font-semibold text-slate-100">
                                    {{ $item['nama_produk'] }}
                                </span>
                            </label>

                            <button type="button"
                                class="btn-remove px-3 py-1 rounded-lg bg-red-500/10 text-red-400 text-xs font-semibold hover:bg-red-500 hover:text-white transition"
                                title="Hapus dari keranjang">
                                Hapus
                            </button>


                        </div>

                        <div class="flex justify-between text-sm text-slate-300">
                            <span>Harga</span>
                            <span class="whitespace-nowrap">
                                Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">

                            <div class="inline-flex items-center bg-slate-700 rounded-lg overflow-hidden">

                                <button type="button"
                                    class="btn-minus px-3 py-1 text-slate-200 hover:bg-slate-600 transition">
                                    −
                                </button>

                                <input type="number"
                                    name="items[{{ $key }}][jumlah]"
                                    value="{{ $item['jumlah'] }}"
                                    min="1"
                                    class="qty-input w-12 text-center text-sm bg-slate-900 text-white outline-none">

                                <button type="button"
                                    class="btn-plus px-3 py-1 text-slate-200 hover:bg-slate-600 transition">
                                    +
                                </button>
                            </div>

                            <div class="text-emerald-400 font-bold subtotal-cell whitespace-nowrap">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </div>
                        </div>

                        <input type="hidden" name="items[{{ $key }}][id_produk]" value="{{ $item['id_produk'] }}">
                        <input type="hidden" name="items[{{ $key }}][harga]" value="{{ (int) $item['harga_satuan'] }}">
                        <input type="hidden" name="items[{{ $key }}][nama_produk]" value="{{ $item['nama_produk'] }}">

                    </div>
                    @endforeach
                </div>


                {{-- ================= DESKTOP VIEW ================= --}}
                <div class="hidden md:block overflow-x-auto desktop-view">
                    <table class="w-full text-sm">
                        <thead class="text-slate-400 border-b border-slate-700">
                            <tr>
                                <th class="py-3 text-left">Produk</th>
                                <th class="py-3 text-right">Harga</th>
                                <th class="py-3 text-center">Jumlah</th>
                                <th class="py-3 text-right">Subtotal</th>
                                <th class="py-3 text-center">✕</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-700">
                        @foreach($cartItems as $key => $item)
                        <tr class="cart-row"
                            data-key="{{ $key }}"
                            data-harga="{{ (int) $item['harga_satuan'] }}">

                            <td class="py-3 text-slate-100">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox"
                                        name="items[{{ $key }}][checked]"
                                        class="item-check accent-emerald-500">
                                    {{ $item['nama_produk'] }}
                                </label>
                            </td>

                            <td class="py-3 text-right whitespace-nowrap text-slate-300">
                                Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}
                            </td>

                            <td class="py-3 text-center">
                                <div class="inline-flex items-center bg-slate-700 rounded-lg overflow-hidden">
                                    <button type="button"
                                        class="btn-minus px-2 py-1 text-slate-200 hover:bg-slate-600 transition">
                                        −
                                    </button>

                                    <input type="number"
                                        name="items[{{ $key }}][jumlah]"
                                        value="{{ $item['jumlah'] }}"
                                        min="1"
                                        class="qty-input w-12 text-center text-sm bg-slate-900 text-white outline-none">

                                    <button type="button"
                                        class="btn-plus px-2 py-1 text-slate-200 hover:bg-slate-600 transition">
                                        +
                                    </button>
                                </div>
                            </td>

                            <td class="py-3 text-right font-semibold text-emerald-400 whitespace-nowrap subtotal-cell">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </td>

                            <td class="py-3 text-center">
                                <button type="button"
                                    class="btn-remove px-3 py-1 rounded-lg bg-red-500/10 text-red-400 text-xs font-semibold hover:bg-red-500 hover:text-white transition"
                                    title="Hapus dari keranjang">
                                    Hapus
                                </button>
                            </td>

                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>


                    {{-- FOOTER --}}
                    <div class="mt-6 text-center space-y-4">

                        <div class="text-lg font-bold text-emerald-400">
                            Total Terpilih:
                            <span id="selectedTotal">Rp 0</span>
                            <input type="hidden" name="total_checkout" id="totalCheckout" value="0">
                        </div>

                        <button type="submit"
                            class="bg-emerald-500 text-white px-8 py-3 rounded-xl font-semibold hover:bg-emerald-600 transition">
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
    const selectedTotal = document.getElementById('selectedTotal');
    const totalCheckout = document.getElementById('totalCheckout');

    if (!cartForm) return;

        function syncActiveInputs() {
        const isMobile = window.innerWidth < 768;

        document.querySelectorAll('.md\\:hidden input').forEach(input => {
            if (!input.classList.contains('item-check')) {
                input.disabled = !isMobile;
            }
        });

        document.querySelectorAll('.desktop-view input').forEach(input => {
            if (!input.classList.contains('item-check')) {
                input.disabled = isMobile;
            }
        });
    }

    syncActiveInputs();
    window.addEventListener('resize', syncActiveInputs);


    const formatRupiah = n =>
        'Rp ' + (n || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    function hitungTotalTerpilih() {
        let total = 0;

        document.querySelectorAll('.cart-row').forEach(row => {

            const check = row.querySelector('.item-check');
            const harga = parseInt(row.dataset.harga || '0', 10);
            const qtyInput = row.querySelector('.qty-input');

            if (!qtyInput) return;

            let qty = parseInt(qtyInput.value || '0', 10);
            if (isNaN(qty) || qty < 1) {
                qty = 1;
                qtyInput.value = 1;
            }

            const subtotal = harga * qty;

            const subtotalCell = row.querySelector('.subtotal-cell');
            if (subtotalCell) {
                subtotalCell.textContent = formatRupiah(subtotal);
            }

            if (check && check.checked) {
                total += subtotal;
            }
        });

        selectedTotal.textContent = formatRupiah(total);
        totalCheckout.value = total;
    }

    // ===============================
    // EVENT DELEGATION (LEBIH STABIL)
    // ===============================
    cartForm.addEventListener('click', function(e) {

        const row = e.target.closest('.cart-row');
        if (!row) return;

        const qtyInput = row.querySelector('.qty-input');
        if (!qtyInput) return;

        // tombol +
        if (e.target.classList.contains('btn-plus')) {
            qtyInput.value = (parseInt(qtyInput.value || '0', 10) || 0) + 1;
            hitungTotalTerpilih();
        }

        // tombol -
        if (e.target.classList.contains('btn-minus')) {
            qtyInput.value = Math.max(1, (parseInt(qtyInput.value || '1', 10) || 1) - 1);
            hitungTotalTerpilih();
        }

        // tombol hapus
        if (e.target.classList.contains('btn-remove')) {

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
                    showToast('Produk dihapus dari keranjang');
                }
            });
        }
    });

    // edit manual qty
    cartForm.addEventListener('input', function(e) {

        if (!e.target.classList.contains('qty-input')) return;

        if (!e.target.value || parseInt(e.target.value) < 1) {
            e.target.value = 1;
        }

        hitungTotalTerpilih();
    });

    // ceklis item
    cartForm.addEventListener('change', function(e) {
        if (e.target.classList.contains('item-check')) {
            hitungTotalTerpilih();
        }
    });

    // validasi submit
    cartForm.addEventListener('submit', function(e) {

        const checkedItems = Array.from(
            cartForm.querySelectorAll('.item-check')
        ).filter(cb => cb.checked && !cb.disabled);

        if (checkedItems.length === 0) {
            e.preventDefault();
            alert('Pilih minimal satu produk untuk dibayar.');
            return false;
        }
    });



    // hitung awal
    hitungTotalTerpilih();

});
</script>
@endpush


