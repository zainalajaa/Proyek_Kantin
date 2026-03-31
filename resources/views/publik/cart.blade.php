@extends('layouts.publik')

@section('title', 'Keranjang Belanja')

@section('content')

<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-6xl mx-auto px-4">

        <h1 class="text-2xl font-bold text-slate-800 mb-8">
            Keranjang Belanja
        </h1>

        @php
            $cartItems = session('cartItems', []);
            $cartTotal = session('cart_total', 0);
        @endphp


        @if (empty($cartItems))

            <div class="bg-white rounded-3xl shadow-sm p-10 text-center">
                <p class="text-slate-500 text-sm">
                    Keranjang masih kosong
                </p>
            </div>

        @else

        <form action="{{ route('cart.checkout') }}" method="POST" id="cartForm">
        @csrf

        <div class="bg-white rounded-3xl shadow-sm p-6">

            {{-- ================= MOBILE VIEW ================= --}}
            <div class="md:hidden space-y-6">
                @foreach($cartItems as $key => $item)
                <div class="cart-row border border-slate-100 rounded-2xl p-5 transition hover:shadow-sm"
                     data-key="{{ $key }}"
                     data-harga="{{ (int) $item['harga_satuan'] }}"
                     data-stok="{{ $item['stok'] }}">

                    <div class="flex justify-between items-start">

                        <label class="flex items-start gap-3">
                            <input type="checkbox"
                                   name="items[{{ $key }}][checked]"
                                   class="item-check accent-emerald-500 mt-1">

                            <div>
                                <div class="font-semibold text-slate-800">
                                    {{ $item['nama_produk'] }}
                                </div>

                                <div class="text-sm text-slate-500 mt-1">
                                    Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}
                                </div>
                            </div>
                        </label>

                        <button type="button"
                                class="btn-remove text-xs font-medium text-red-500 hover:text-red-600 transition">
                            Hapus
                        </button>
                    </div>

                    <div class="flex justify-between items-center mt-5">

                        <div class="inline-flex items-center border border-slate-200 rounded-xl overflow-hidden">

                            <button type="button"
                                class="btn-minus px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-sm">
                                −
                            </button>

                            <input type="number"
                                name="items[{{ $key }}][jumlah]"
                                value="{{ $item['jumlah'] }}"
                                min="1"
                                max="{{ $item['stok'] }}"
                                readonly
                                class="qty-input w-12 text-center text-sm outline-none bg-white cursor-default">

                            <button type="button"
                                class="btn-plus px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-sm">
                                +
                            </button>
                        </div>

                        <!-- 🔥 PINDAH KE SINI -->
                        <p class="stok-warning text-red-600 text-xs mt-2 hidden font-medium">
                            Jumlah melebihi stok tersedia
                        </p>

                        <div class="subtotal-cell text-emerald-600 font-bold whitespace-nowrap">
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
                    <thead class="border-b text-slate-500">
                        <tr>
                            <th class="py-4 text-left">Produk</th>
                            <th class="py-4 text-right">Harga</th>
                            <th class="py-4 text-center">Jumlah</th>
                            <th class="py-4 text-right">Subtotal</th>
                            <th class="py-4 text-center">✕</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                    @foreach($cartItems as $key => $item)
                    <tr class="cart-row hover:bg-slate-50 transition"
                        data-key="{{ $key }}"
                        data-harga="{{ (int) $item['harga_satuan'] }}"
                        data-stok="{{ $item['stok'] }}">

                        <td class="py-5 text-slate-800">
                            <label class="flex items-center gap-3">
                                <input type="checkbox"
                                       name="items[{{ $key }}][checked]"
                                       class="item-check accent-emerald-500">
                                {{ $item['nama_produk'] }}
                            </label>
                        </td>

                        <td class="py-5 text-right text-slate-600 whitespace-nowrap">
                            Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}
                        </td>

                        <td class="py-5 text-center">
                        <div class="inline-flex items-center border border-slate-200 rounded-xl overflow-hidden">

                            <button type="button"
                                class="btn-minus px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-sm">
                                −
                            </button>

                            <input type="number"
                                name="items[{{ $key }}][jumlah]"
                                value="{{ $item['jumlah'] }}"
                                min="1"
                                max="{{ $item['stok'] }}"
                                readonly
                                class="qty-input w-12 text-center text-sm outline-none bg-white cursor-default">

                            <button type="button"
                                class="btn-plus px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-sm">
                                +
                            </button>
                        </div>

                        <!-- 🔥 PINDAH KE SINI -->
                        <p class="stok-warning text-red-600 text-xs mt-2 hidden font-medium">
                            Jumlah melebihi stok tersedia
                        </p>

                        <td class="subtotal-cell py-5 text-right font-semibold text-emerald-600 whitespace-nowrap">
                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </td>

                        <td class="py-5 text-center">
                            <button type="button"
                                    class="btn-remove text-xs font-medium text-red-500 hover:text-red-600 transition">
                                Hapus
                            </button>
                        </td>

                    </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>


            {{-- FOOTER SUMMARY --}}
            <div class="mt-10 pt-8 border-t">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">

                    <div class="text-xl font-bold text-emerald-600">
                        Total Terpilih:
                        <span id="selectedTotal">Rp 0</span>
                        <input type="hidden" name="total_checkout" id="totalCheckout" value="0">
                    </div>

                    <p id="checkout-warning" class="text-red-500 text-sm mb-3 hidden">
                        Pilih minimal satu produk untuk dibayar.
                    </p>
                    
                    <button type="submit"
                            class="bg-emerald-500 text-white px-10 py-3 rounded-2xl font-semibold hover:bg-emerald-600 transition shadow-sm">
                        Lanjutkan Pembayaran
                    </button>

                </div>

            </div>

        </div>

        </form>
        @endif

    </div>
</div>

@endsection

@push('styles')
<style>
input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number] {
    -moz-appearance: textfield;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const cartForm      = document.getElementById('cartForm');
    const selectedTotal = document.getElementById('selectedTotal');
    const totalCheckout = document.getElementById('totalCheckout');
    const checkoutWarning = document.getElementById('checkout-warning');

    if (!cartForm) return;

    // ===============================
    // RESPONSIVE INPUT SYNC
    // ===============================
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

    // ===============================
    // FORMAT RUPIAH
    // ===============================
    const formatRupiah = n =>
        'Rp ' + (n || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    // ===============================
    // HITUNG TOTAL
    // ===============================
    function hitungTotalTerpilih() {
        let total = 0;

        document.querySelectorAll('.cart-row').forEach(row => {

            const check    = row.querySelector('.item-check');
            const harga    = parseInt(row.dataset.harga || '0', 10);
            const stok     = parseInt(row.dataset.stok || '0', 10);
            const qtyInput = row.querySelector('.qty-input');
            const warning  = row.querySelector('.stok-warning'); // ✅ FIX

            if (!qtyInput) return;

            let qty = parseInt(qtyInput.value, 10) || 1;

            // validasi minimal
            if (qty < 1) qty = 1;

            // validasi stok
            if (qty > stok) {
                qty = stok;
                if (warning) warning.classList.remove('hidden');
            } else {
                if (warning) warning.classList.add('hidden');
            }

            qtyInput.value = qty;

            const subtotal = harga * qty;

            const subtotalCell = row.querySelector('.subtotal-cell');
            if (subtotalCell) {
                subtotalCell.textContent = formatRupiah(subtotal);
            }

            if (check && check.checked) {
                total += subtotal;
            }

            // disable tombol +
            const btnPlus = row.querySelector('.btn-plus');
            if (btnPlus) {
                btnPlus.classList.toggle('opacity-50', qty >= stok);
            }
        });

        selectedTotal.textContent = formatRupiah(total);
        totalCheckout.value = total;
    }

    // ===============================
    // CLICK EVENTS
    // ===============================
    cartForm.addEventListener('click', function(e) {

        const row = e.target.closest('.cart-row');
        if (!row) return;

        const qtyInput = row.querySelector('.qty-input');
        const warning  = row.querySelector('.stok-warning');
        if (!qtyInput) return;

        // ➕ tombol tambah
        if (e.target.classList.contains('btn-plus')) {

            let current = parseInt(qtyInput.value, 10) || 1;
            const stok = parseInt(row.dataset.stok || '0', 10);
            const warning = row.querySelector('.stok-warning');

            if (current < stok) {
                qtyInput.value = current + 1;
                if (warning) warning.classList.add('hidden');
            } else {
                if (warning) warning.classList.remove('hidden');
            }

            hitungTotalTerpilih();
        }

        // ➖ tombol kurang
        if (e.target.classList.contains('btn-minus')) {

            let current = parseInt(qtyInput.value || '0', 10) || 0;

            let next = current - 1;
            if (next < 1) next = 1;

            qtyInput.value = next;

            if (warning) warning.classList.add('hidden');

            hitungTotalTerpilih();

        }

        // ❌ hapus item
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

    // ===============================
    // CHECKBOX
    // ===============================
    cartForm.addEventListener('change', function(e) {

        if (e.target.classList.contains('item-check')) {
            hitungTotalTerpilih();

            // hilangkan pesan submit jika user mulai pilih
            if (checkoutWarning) {
                checkoutWarning.classList.add('hidden');
            }
        }
    });

    // ===============================
    // VALIDASI SUBMIT (NO ALERT)
    // ===============================
    cartForm.addEventListener('submit', function(e) {

        const checkedItems = Array.from(
            cartForm.querySelectorAll('.item-check')
        ).filter(cb => cb.checked && !cb.disabled);

        if (checkedItems.length === 0) {
            e.preventDefault();

            if (checkoutWarning) {
                checkoutWarning.classList.remove('hidden');
            }

            return false;
        }
    });

    // INIT
    hitungTotalTerpilih();

});
</script>
@endpush


