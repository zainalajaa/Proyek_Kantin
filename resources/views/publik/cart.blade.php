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
                     data-stok="{{ (int) ($item['stok'] ?? 1) }}">

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
                                max="{{ $item['stok'] ?? 1 }}"
                                readonly
                                class="qty-input w-12 text-center text-sm outline-none bg-white cursor-default">

                            <button type="button"
                                class="btn-plus px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-sm">
                                +
                            </button>
                        </div>

                        <div class="subtotal-cell text-emerald-600 font-bold whitespace-nowrap">
                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </div>
                    </div>

                    <p class="stok-warning text-red-600 text-xs mt-2 hidden font-medium">
                        Jumlah melebihi stok tersedia
                    </p>

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
                        data-stok="{{ (int) ($item['stok'] ?? 1) }}">

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
                                max="{{ $item['stok'] ?? 1 }}"
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

    const formTunai   = document.getElementById('cartForm');
    const totalHidden = document.getElementById('totalCheckout');
    const inputBayar  = document.getElementById('jumlah_bayar');
    const kembaliText = document.getElementById('kembaliText');
    const qrisBox     = document.getElementById('qrisBox');
    const tunaiBox    = document.getElementById('tunaiBox');
    const btnBayar    = document.getElementById('btnBayar');
    const errorTunai  = document.getElementById('errorTunai');

    let currentTotal = parseInt(totalHidden.value) || 0;

    // ===============================
    // FORMAT
    // ===============================
    const format = n =>
        'Rp ' + (n || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    // ===============================
    // HITUNG TOTAL (SAMA CART)
    // ===============================
    function hitungUlangTotal() {

        let total = 0;

        document.querySelectorAll('.cart-row:not([style*="display: none"])').forEach(tr => {

            const harga = parseInt(tr.dataset.harga) || 0;

            let stok = parseInt(tr.dataset.stok);
            if (isNaN(stok) || stok < 1) stok = 1;

            const qtyInput = tr.querySelector('.qty-input');
            const warning  = tr.querySelector('.stok-warning');

            if (!qtyInput) return;

            let qty = parseInt(qtyInput.value);
            if (isNaN(qty) || qty < 1) qty = 1;

            let originalQty = qty;

            if (originalQty > stok) {
                warning?.classList.remove('hidden');
                qty = stok;
            } else {
                warning?.classList.add('hidden');
            }

            qtyInput.value = qty;

            const subtotal = harga * qty;

            // 🔥 FIX TOTAL BERDASARKAN CHECKBOX
            const checkbox = tr.querySelector('.item-check');
            if (checkbox && checkbox.checked) {
                total += subtotal;
            }

            const subtotalCell = tr.querySelector('.subtotal-cell');
            if (subtotalCell) {
                subtotalCell.textContent = format(subtotal);
            }

        });

        currentTotal = total;

        document.getElementById('selectedTotal').textContent = format(total);
        totalHidden.value = total;

        updateKembali();
    }

    // ===============================
    // KEMBALIAN
    // ===============================
    function updateKembali() {

        // 🔥 CEK DULU
        if (!inputBayar || !kembaliText) return;

        const bayar = parseInt(inputBayar.value) || 0;
        const kembali = bayar - currentTotal;

        kembaliText.textContent = kembali > 0 ? format(kembali) : 'Rp 0';
    }

    // ===============================
    // TOGGLE METODE
    // ===============================
    function toggleMetode() {

        const metode = document.querySelector('input[name="metode_pembayaran"]:checked').value;

        if (metode === 'qris') {

            qrisBox.classList.remove('hidden');
            tunaiBox.classList.add('hidden');

            inputBayar.value = '';
            inputBayar.disabled = true;

            btnBayar.textContent = 'Lanjutkan Pembayaran QRIS';

        } else {

            qrisBox.classList.add('hidden');
            tunaiBox.classList.remove('hidden');

            inputBayar.disabled = false;

            btnBayar.textContent = 'Bayar';
        }
    }

    // ===============================
    // 🔥 EVENT DELEGATION (FIX UTAMA)
    // ===============================
formTunai.addEventListener('click', function(e) {

    const target = e.target.closest('button, .item-check, .btn-plus, .btn-minus, .btn-remove');
    if (!target) return;

    // CHECKBOX
    if (target.classList.contains('item-check')) {
        hitungUlangTotal();
        return;
    }

    const tr = target.closest('.cart-row');
    if (!tr) return;

    const btnPlus   = target.closest('.btn-plus');
    const btnMinus  = target.closest('.btn-minus');
    const btnRemove = target.closest('.btn-remove');

    // =====================
    // HAPUS ITEM
    // =====================
    if (btnRemove) {

        tr.style.display = 'none';

        const rows = document.querySelectorAll('.cart-row:not([style*="display: none"])');

        if (rows.length === 0) {
            document.querySelector('table')?.style.setProperty('display', 'none');
        }

        hitungUlangTotal();
        return;
    }

    if (!btnPlus && !btnMinus) return;

    const qtyInput = tr.querySelector('.qty-input');

    // 🔥 PERBAIKAN: selector warning dipastikan spesifik
    const warning = tr.querySelector('.stok-warning');

    let qty  = parseInt(qtyInput.value);
    let stok = parseInt(tr.dataset.stok);

    if (isNaN(stok) || stok < 1) stok = 1;
    if (isNaN(qty) || qty < 1) qty = 1;

    let showWarning = false;

    // ➕ TAMBAH
    if (btnPlus) {

        if (qty < stok) {
            qty++;
        } else {
            showWarning = true;
        }
    }

    // ➖ KURANG
    if (btnMinus) {
        qty--;
        if (qty < 1) qty = 1;
    }

    // VALIDASI FINAL
    if (qty > stok) {
        qty = stok;
        showWarning = true;
    }

    qtyInput.value = qty;

    // HITUNG TOTAL
    hitungUlangTotal();

    // 🔥 FORCE RENDER (khusus mobile)
    requestAnimationFrame(() => {
        if (warning) {
            if (showWarning) {
                warning.classList.remove('hidden');
            } else {
                warning.classList.add('hidden');
            }
        }
    });

});
        

    // ===============================
    // LOCK INPUT
    // ===============================
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('keydown', e => e.preventDefault());
    });

    // ===============================
    // INPUT BAYAR
    // ===============================
    if (inputBayar) {
        inputBayar.addEventListener('input', updateKembali);
    }

    // ===============================
    // CHANGE METODE
    // ===============================
    const metodeInputs = document.querySelectorAll('input[name="metode_pembayaran"]');

    if (metodeInputs.length) {
        metodeInputs.forEach(r => r.addEventListener('change', toggleMetode));
        toggleMetode();
    }

    // ===============================
    // VALIDASI SUBMIT
    // ===============================
    if (formTunai) {
        formTunai.addEventListener('submit', function(e) {

            const metodeEl = document.querySelector('input[name="metode_pembayaran"]:checked');
            const metode = metodeEl ? metodeEl.value : null;

            const action = document.activeElement?.value;

            if (action === 'pay' && metode === 'qris') return true;

            if (action === 'pay') {

                const bayar = parseInt(inputBayar?.value) || 0;

                if (bayar < currentTotal) {

                    e.preventDefault();

                    if (errorTunai) errorTunai.classList.remove('hidden');
                    if (inputBayar) inputBayar.classList.add('border-red-500');
                }
            }
        });
    }

    // INIT
    if (document.querySelector('input[name="metode_pembayaran"]')) {
        toggleMetode();
    }
    hitungUlangTotal();

});
</script>
@endpush