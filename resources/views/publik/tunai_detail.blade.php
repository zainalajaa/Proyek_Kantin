@extends('layouts.publik')

@section('title', 'Checkout Pembayaran')

@section('content')

<style>
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type=number] {
    -moz-appearance: textfield;
}

@keyframes spinSlow {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.animate-spin-slow {
    animation: spinSlow 2s linear infinite;
}

</style>

<div class="min-h-screen bg-slate-50 py-8">
<div class="max-w-5xl mx-auto px-4">

    {{-- HEADER --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 mb-6">
        <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
            💳 Detail Pembayaran
        </h2>

        <div class="text-sm text-slate-500 mt-1">
            ID Transaksi:
            <span class="font-semibold">{{ $penjualan->id }}</span>
            • {{ $penjualan->waktu }}
        </div>
    </div>

    <form action="{{ route('publik.tunai.bayar', $penjualan->id) }}" method="POST" id="formTunai">
    @csrf

    {{-- ================= PRODUK ================= --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 mb-6">

        <h3 class="font-semibold text-slate-700 mb-5">
            Daftar Produk
        </h3>

        {{-- ================= MOBILE VIEW ================= --}}
        <div class="md:hidden space-y-6">
        @foreach($details as $d)
        <div class="cart-row border border-slate-100 rounded-2xl p-5"
            data-row="detail"
            data-harga="{{ (int) $d->harga_satuan }}"
            data-stok="{{ (int) ($d->stok ?? 0) }}">

            <div class="flex justify-between items-start">

                <div>
                    <div class="font-semibold text-slate-800">
                        {{ $d->nama_produk }}
                    </div>

                    <div class="text-sm text-slate-500 mt-1 space-y-1">
                        <div>
                            Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}
                        </div>

                        <div class="text-xs text-slate-400">
                            Stok:
                            @if(($d->stok ?? 0) <= 0)
                                <span class="text-red-500 font-semibold">Habis</span>
                            @else
                                {{ $d->stok }}
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex justify-between items-center mt-5">

                <div class="inline-flex items-center border border-slate-200 rounded-xl overflow-hidden">

                    <button type="button"
                        class="btn-minus px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-sm">
                        −
                    </button>

                    <input type="number"
                        name="items[{{ $d->id }}][jumlah]"
                        value="{{ $d->jumlah }}"
                        readonly
                        class="qty-input w-12 text-center text-sm outline-none bg-white">

                    <button type="button"
                        class="btn-plus px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-sm">
                        +
                    </button>
                </div>

                <div class="subtotal-cell text-emerald-600 font-bold whitespace-nowrap">
                    Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                </div>
            </div>

            <p class="stok-warning text-red-600 text-xs mt-2 hidden font-medium">
                Melebihi stok
            </p>

            <!-- HIDDEN -->
            <input type="hidden" name="items[{{ $d->id }}][id_detail]" value="{{ $d->id }}">
            <input type="hidden" name="items[{{ $d->id }}][id_produk]" value="{{ $d->id_produk }}">
            <input type="hidden" name="items[{{ $d->id }}][harga_satuan]" value="{{ $d->harga_satuan }}">

        </div>
        @endforeach
        </div>

        {{-- ================= DESKTOP VIEW ================= --}}
        <div class="hidden md:block overflow-x-auto">

            <table class="w-full text-sm">
                <thead class="border-b text-slate-500">
                    <tr>
                        <th class="py-4 text-left">Produk</th>
                        <th class="py-4 text-right">Harga</th>
                        <th class="py-4 text-center">Stok</th>
                        <th class="py-4 text-center">Jumlah</th>
                        <th class="py-4 text-right">Subtotal</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                @foreach($details as $d)
                <tr class="cart-row hover:bg-slate-50 transition"
                    data-row="detail"
                    data-harga="{{ (int) $d->harga_satuan }}"
                    data-stok="{{ (int) ($d->stok ?? 0) }}">

                    <!-- PRODUK -->
                    <td class="py-5 text-slate-800">
                        {{ $d->nama_produk }}
                    </td>

                    <!-- HARGA -->
                    <td class="py-5 text-right text-slate-600 whitespace-nowrap">
                        Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}
                    </td>

                    <!-- STOK -->
                    <td class="py-5 text-center">
                        @if(($d->stok ?? 0) <= 0)
                            <span class="text-red-500 font-semibold text-xs">
                                Habis
                            </span>
                        @else
                            <span class="text-slate-600">
                                {{ $d->stok }}
                            </span>
                        @endif
                    </td>

                    <!-- JUMLAH -->
                    <td class="py-5 text-center">
                        <div class="inline-flex items-center border border-slate-200 rounded-xl overflow-hidden">

                            <button type="button"
                                class="btn-minus px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-sm">
                                −
                            </button>

                            <input type="number"
                                name="items[{{ $d->id }}][jumlah]"
                                value="{{ $d->jumlah }}"
                                readonly
                                class="qty-input w-12 text-center text-sm outline-none bg-white">

                            <button type="button"
                                class="btn-plus px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-sm">
                                +
                            </button>
                        </div>

                        <p class="stok-warning text-red-600 text-xs mt-2 hidden font-medium">
                            Melebihi stok
                        </p>
                    </td>

                    <!-- SUBTOTAL -->
                    <td class="subtotal-cell py-5 text-right font-semibold text-emerald-600 whitespace-nowrap">
                        Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                    </td>

                    <!-- HIDDEN -->
                    <input type="hidden" name="items[{{ $d->id }}][id_detail]" value="{{ $d->id }}">
                    <input type="hidden" name="items[{{ $d->id }}][id_produk]" value="{{ $d->id_produk }}">
                    <input type="hidden" name="items[{{ $d->id }}][harga_satuan]" value="{{ $d->harga_satuan }}">

                </tr>
                @endforeach
                </tbody>
            </table>

        </div>

    </div>
    {{-- RINGKASAN --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-4">

        <h3 class="font-semibold text-slate-700 mb-6">
            Ringkasan Pembayaran
        </h3>

        <div class="flex justify-between items-center mb-4">
            <span class="text-slate-600">Total Belanja</span>
            <span id="totalText" class="text-3xl font-extrabold text-emerald-600">
                Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}
            </span>
        </div>

        <input type="hidden" id="total_hidden" value="{{ (int)$penjualan->total_harga }}">

        <div class="border-t pt-5 mt-5">

            <label class="block font-semibold mb-3">
                Metode Pembayaran
            </label>

            <div class="space-y-3 text-sm">

                <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer hover:border-emerald-400">
                    <input type="radio" name="metode_pembayaran" value="tunai" checked>
                    Tunai
                </label>

                <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer hover:border-emerald-400">
                    <input type="radio" name="metode_pembayaran" value="qris">
                    QRIS
                </label>

            </div>
        </div>

        {{-- QRIS --}}
        <div id="qrisBox"
            class="hidden mt-5 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700">
            Anda akan diarahkan ke pembayaran QRIS
        </div>

        {{-- TUNAI --}}
        <div id="tunaiBox" class="mt-5">
            <label class="text-sm block mb-2 font-medium">
                Jumlah Bayar
            </label>

            <input type="number"
                name="jumlah_bayar"
                id="jumlah_bayar"
                class="w-full border border-slate-200 p-3 rounded-xl focus:ring-2 focus:ring-emerald-400 outline-none"
                placeholder="Masukkan nominal tunai">

            <p id="errorTunai" class="text-red-500 text-sm mt-2 hidden">
                Jumlah uang tunai kurang
            </p>
        </div>

        <div class="mt-6 flex justify-between text-sm">
            <div id="kembaliWrapper">
                Kembali: <span id="kembaliText">Rp 0</span>
            </div>
        </div>

    </div>

    {{-- ACTION --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-5 flex flex-col sm:flex-row gap-4">

        <button type="submit"
            name="action"
            value="cart"
            class="border border-emerald-500 text-emerald-600 py-3 rounded-2xl hover:bg-emerald-50 transition">
            🛒 Ke Keranjang
        </button>

        <button type="submit"
            name="action"
            value="pay"
            id="btnBayar"
            class="flex-1 bg-emerald-500 text-white py-3 rounded-2xl font-semibold hover:bg-emerald-600 active:scale-95 transition shadow-md flex items-center justify-center gap-2">

            <span id="btnText">Bayar</span>

            <!-- 🔥 SPINNER -->
            <svg id="btnLoader" class="w-5 h-5 animate-spin hidden"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                    stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8v8z"></path>
            </svg>

        </button>

        <div id="loadingOverlay"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden">

            <div class="bg-white rounded-2xl p-6 flex flex-col items-center gap-4 shadow-xl">

                <!-- SPINNER -->
                <svg class="w-10 h-10 animate-spin-slow text-blue-500"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                        stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8v8z"></path>
                </svg>

                <!-- TEXT -->
                <p class="text-sm font-semibold text-blue-700">
                    Memproses pembayaran...
                </p>

            </div>
        </div>
    </form>

</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const formTunai   = document.getElementById('formTunai');
    const totalHidden = document.getElementById('total_hidden');
    const inputBayar  = document.getElementById('jumlah_bayar');
    const kembaliText = document.getElementById('kembaliText');
    const qrisBox     = document.getElementById('qrisBox');
    const tunaiBox    = document.getElementById('tunaiBox');
    const btnBayar    = document.getElementById('btnBayar');
    const errorTunai  = document.getElementById('errorTunai');
    const errorStok   = document.getElementById('errorStok');

    let currentTotal = 0;

    // ===============================
    // FORMAT RUPIAH
    // ===============================
    function format(n) {
        return 'Rp ' + (n || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // ===============================
    // HITUNG TOTAL (CORE LOGIC)
    // ===============================
    function hitungUlangTotal() {

    let total = 0;
    let adaErrorStok = false;

    document.querySelectorAll('[data-row="detail"]').forEach(tr => {

        // 🔥 FIX UTAMA: hanya hitung yang terlihat
        if (tr.offsetParent === null) return;

        const harga = parseInt(tr.dataset.harga || 0);
        const stok  = parseInt(tr.dataset.stok || 0);
        const qtyInput = tr.querySelector('.qty-input');
        const warning  = tr.querySelector('.stok-warning');
        const subtotalCell = tr.querySelector('.subtotal-cell');

        if (!qtyInput) return;

        let qty = parseInt(qtyInput.value);

        // VALIDASI MINIMAL
        if (isNaN(qty) || qty < 1) qty = 1;

        // STOK HABIS
        if (stok <= 0) {
            qty = 0;
            adaErrorStok = true;

            if (warning) {
                warning.textContent = 'Stok habis';
                warning.classList.remove('hidden');
            }
        }

        // HITUNG SUBTOTAL
        const subtotal = harga * qty;
        total += subtotal;

        if (subtotalCell) {
            subtotalCell.textContent = format(subtotal);
        }

        // BUTTON CONTROL
        const btnPlus = tr.querySelector('.btn-plus');
        const btnMinus = tr.querySelector('.btn-minus');

        if (btnPlus) {
            btnPlus.classList.toggle('opacity-50', qty >= stok);
        }

        if (btnMinus) {
            btnMinus.disabled = qty <= 1;
        }
    });

    currentTotal = total;

        document.getElementById('totalText').textContent = format(total);
        totalHidden.value = total;

        updateKembali();

        // DISABLE BAYAR
        btnBayar.disabled = adaErrorStok;
        btnBayar.classList.toggle('opacity-50', adaErrorStok);

        // RESET ERROR
        errorTunai?.classList.add('hidden');
        errorStok?.classList.add('hidden');
    }

        function updateKembali() {

        if (!inputBayar || !kembaliText) return;

        const bayar = parseInt(inputBayar.value) || 0;
        const totalNow = parseInt(totalHidden.value) || 0;

        const kembali = bayar - totalNow;

        kembaliText.textContent = kembali > 0 
            ? format(kembali) 
            : 'Rp 0';
    }
    // ===============================
    // TOGGLE METODE
    // ===============================
    function toggleMetode() {

        const metode = document.querySelector('input[name="metode_pembayaran"]:checked').value;
        const kembaliWrapper = document.getElementById('kembaliWrapper');

        if (metode === 'qris') {

            qrisBox.classList.remove('hidden');
            tunaiBox.classList.add('hidden');

            inputBayar.value = '';
            inputBayar.disabled = true;

            btnBayar.textContent = 'Lanjutkan Pembayaran QRIS';

            // 🔥 SEMBUNYIKAN KEMBALIAN
            kembaliWrapper.classList.add('hidden');

        } else {

            qrisBox.classList.add('hidden');
            tunaiBox.classList.remove('hidden');

            inputBayar.disabled = false;

            btnBayar.textContent = 'Bayar';

            // 🔥 TAMPILKAN KEMBALIAN
            kembaliWrapper.classList.remove('hidden');
        }
    }

    // ===============================
    // EVENT DELEGATION (PLUS & MINUS)
    // ===============================
    formTunai.addEventListener('click', function(e) {

        const btnPlus  = e.target.closest('.btn-plus');
        const btnMinus = e.target.closest('.btn-minus');

        if (!btnPlus && !btnMinus) return;

        const row = e.target.closest('[data-row="detail"], .cart-row');
        if (!row) return;

        const qtyInput = row.querySelector('.qty-input');
        const warning  = row.querySelector('.stok-warning');

        let qty  = parseInt(qtyInput.value || 0);
        let stok = parseInt(row.dataset.stok || 0);

        if (isNaN(qty) || qty < 1) qty = 1;

        let showWarning = false;

        // ➕ TAMBAH
        if (btnPlus) {

            if (stok <= 0) {
                showWarning = true;

            } else if (qty >= stok) {
                showWarning = true;

            } else {
                qty++;
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

        // UPDATE WARNING
        if (warning) {
            if (showWarning) {
                warning.textContent = stok <= 0 
                    ? 'Stok habis' 
                    : 'Jumlah tidak bisa melebihi stok';

                warning.classList.remove('hidden');
            } else {
                warning.classList.add('hidden');
            }
        }

        // HITUNG ULANG
        hitungUlangTotal();

    });

    // ===============================
    // CHANGE METODE
    // ===============================
    document.querySelectorAll('input[name="metode_pembayaran"]')
        .forEach(r => r.addEventListener('change', toggleMetode));

    // ===============================
    // INPUT BAYAR
    // ===============================
    inputBayar.addEventListener('input', () => {

        updateKembali();

        let bayar = parseInt(inputBayar.value || 0);
    
        const totalNow = parseInt(totalHidden.value) || 0;

        if (isNaN(bayar) || bayar < totalNow) {
            errorTunai.classList.remove('hidden');
            inputBayar.classList.add('border-red-500');
        } else {
            errorTunai.classList.add('hidden');
            inputBayar.classList.remove('border-red-500');
        }
    });

    // ===============================
    // VALIDASI SUBMIT
    // ===============================
    formTunai.addEventListener('submit', function(e) {

        const action = e.submitter?.value;
        const metode = document.querySelector('input[name="metode_pembayaran"]:checked').value;

        let adaErrorStok = false;

        document.querySelectorAll('[data-row="detail"], .cart-row').forEach(tr => {
            const qty = parseInt(tr.querySelector('.qty-input')?.value || 0);
            const stok = parseInt(tr.dataset.stok || 0);

            if (qty > stok || stok <= 0) {
                adaErrorStok = true;
            }
        });

        // ERROR STOK
        if (adaErrorStok) {
            e.preventDefault();

            if (errorStok) {
                errorStok.textContent = 'Periksa kembali jumlah produk (stok tidak mencukupi)';
                errorStok.classList.remove('hidden');
            }

            return;
        }

        // QRIS
        if (action === 'pay' && metode === 'qris') {
            return true;
        }

        // TUNAI VALIDASI
        if (action === 'pay' && metode === 'tunai') {

            let bayar = parseInt(inputBayar.value || 0);
            const totalNow = parseInt(totalHidden.value) || 0;

            if (isNaN(bayar) || bayar < totalNow) {

                e.preventDefault();

                errorTunai.classList.remove('hidden');
                inputBayar.classList.add('border-red-500');
                return;
            }
        }

        // ===============================
        // LOADING BUTTON
        // ===============================
        const btnText   = document.getElementById('btnText');
        const btnLoader = document.getElementById('btnLoader');

        if (metode === 'tunai') {

            btnBayar.disabled = true;
            btnBayar.classList.add('opacity-70', 'cursor-not-allowed');

            if (btnText) btnText.textContent = 'Memproses...';
            if (btnLoader) btnLoader.classList.remove('hidden');
        }

        const loadingOverlay = document.getElementById('loadingOverlay');

        if (action === 'pay' && metode === 'tunai') {
            loadingOverlay.classList.remove('hidden');
        
        }
    });

    // INIT
    toggleMetode();
    hitungUlangTotal();

});
</script>
@endpush