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
</style>

<div class="min-h-screen bg-slate-50 py-8">
<div class="max-w-5xl mx-auto px-4">

    {{-- HEADER --}}
    <div class="bg-white rounded-3xl shadow-sm p-6 mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Detail Pembayaran
        </h2>

        <div class="text-sm text-slate-500 mt-1">
            ID Transaksi:
            <span class="font-semibold">{{ $penjualan->id }}</span>
            • {{ $penjualan->waktu }}
        </div>
    </div>


    <form action="{{ route('publik.tunai.bayar', $penjualan->id) }}" method="POST" id="formTunai">
    @csrf


    {{-- PRODUK CARD --}}
    <div class="bg-white rounded-3xl shadow-sm p-6 mb-6">

        <h3 class="font-semibold text-slate-700 mb-5">
            Daftar Produk
        </h3>

        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-slate-500 border-b">
                <tr>
                    <th class="py-3 text-left">Produk</th>
                    <th class="py-3 text-right">Harga</th>
                    <th class="py-3 text-center">Jumlah</th>
                    <th class="py-3 text-right">Subtotal</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
            @foreach($details as $d)
            <tr data-row="detail"
                data-harga="{{ (int) $d->harga_satuan }}"
                class="hover:bg-slate-50 transition">

                <td class="py-4 font-medium text-slate-700">
                    {{ $d->nama_produk }}
                </td>

                <td class="py-4 text-right text-slate-600">
                    Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}
                </td>

                <td class="py-4 text-center">

                    <div class="inline-flex items-center border border-slate-200 rounded-xl overflow-hidden">

                        <button type="button"
                            class="btn-minus px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-sm">
                            −
                        </button>

                        <input type="number"
                            name="items[{{ $d->id }}][jumlah]"
                            class="qty-input w-12 text-center outline-none text-sm"
                            min="1"
                            value="{{ $d->jumlah }}">

                        <button type="button"
                            class="btn-plus px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-sm">
                            +
                        </button>
                    </div>

                    <input type="hidden" name="items[{{ $d->id }}][id_detail]" value="{{ $d->id }}">
                    <input type="hidden" name="items[{{ $d->id }}][id_produk]" value="{{ $d->id_produk }}">
                    <input type="hidden" name="items[{{ $d->id }}][harga_satuan]" value="{{ $d->harga_satuan }}">
                </td>

                <td class="py-4 text-right font-semibold text-emerald-600 subtotal-cell">
                    Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                </td>

            </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>


    {{-- RINGKASAN --}}
    <div class="bg-white rounded-3xl shadow-sm p-6 mb-6">

        <h3 class="font-semibold text-slate-700 mb-6">
            Ringkasan Pembayaran
        </h3>

        <div class="flex justify-between items-center mb-4">
            <span class="text-slate-600">Total Belanja</span>
            <span id="totalText" class="text-2xl font-bold text-emerald-600">
                Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}
            </span>
        </div>

        <input type="hidden" id="total_hidden" value="{{ (int)$penjualan->total_harga }}">

        <div class="border-t pt-5 mt-5">

            <label class="block font-semibold mb-3">
                Metode Pembayaran
            </label>

            <div class="space-y-3 text-sm">

                <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer hover:border-emerald-400 transition">
                    <input type="radio" name="metode_pembayaran" value="tunai" checked>
                    Tunai
                </label>

                <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer hover:border-emerald-400 transition">
                    <input type="radio" name="metode_pembayaran" value="qris">
                    QRIS
                </label>

            </div>
        </div>


        {{-- QRIS --}}
        <div id="qrisBox"
            class="hidden mt-5 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700">
            Anda akan diarahkan ke halaman pembayaran QRIS.
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
                Jumlah uang tunai untuk bayar harus lebih besar atau sama dengan total belanja.
            </p>
        </div>


        <div class="mt-6 flex justify-between text-sm">
            <span class="font-semibold">Kembali</span>
            <span id="kembaliText" class="text-emerald-600 font-bold">
                Rp 0
            </span>
        </div>

    </div>


    {{-- ACTION --}}
    <div class="bg-white rounded-3xl shadow-sm p-5 flex flex-col sm:flex-row gap-4">

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
            class="flex-1 bg-emerald-500 text-white py-3 rounded-2xl font-semibold hover:bg-emerald-600 transition shadow-sm">
            Bayar
        </button>

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

    let currentTotal = parseInt(totalHidden.value) || 0;

    function format(n) {
        return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function hitungUlangTotal() {
        let total = 0;

        document.querySelectorAll('tr[data-row="detail"]').forEach(tr => {

            const harga = parseInt(tr.dataset.harga);
            const qtyInput = tr.querySelector('.qty-input');

            let qty = parseInt(qtyInput.value || 0);

            if (qty < 1) {
                qty = 1;
                qtyInput.value = 1;
            }

            const subtotal = harga * qty;
            total += subtotal;

            tr.querySelector('.subtotal-cell').textContent = format(subtotal);
        });

        currentTotal = total;

        document.getElementById('totalText').textContent = format(total);
        totalHidden.value = total;

        updateKembali();
    }

    function updateKembali() {
        const bayar = parseInt(inputBayar.value || 0);
        const kembali = bayar - currentTotal;

        kembaliText.textContent =
            kembali > 0 ? format(kembali) : 'Rp 0';
    }

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

    document.querySelectorAll('.btn-plus').forEach(btn => {
        btn.addEventListener('click', () => {
            const qty = btn.closest('tr').querySelector('.qty-input');
            qty.value = parseInt(qty.value) + 1;
            hitungUlangTotal();
        });
    });

    document.querySelectorAll('.btn-minus').forEach(btn => {
        btn.addEventListener('click', () => {
            const qty = btn.closest('tr').querySelector('.qty-input');
            if (parseInt(qty.value) > 1) qty.value--;
            hitungUlangTotal();
        });
    });

    document.querySelectorAll('.qty-input').forEach(i =>
        i.addEventListener('input', hitungUlangTotal)
    );

    document.querySelectorAll('input[name="metode_pembayaran"]')
        .forEach(r => r.addEventListener('change', toggleMetode));

    inputBayar.addEventListener('input', () => {

        updateKembali();

        const bayar = parseInt(inputBayar.value || 0);

        if (bayar >= currentTotal) {

            errorTunai.classList.add('hidden');
            inputBayar.classList.remove('border-red-500');

        }
    });

    formTunai.addEventListener('submit', function(e) {

        const action = document.activeElement.value;
        const metode = document.querySelector('input[name="metode_pembayaran"]:checked').value;

        if (action === 'pay' && metode === 'qris') {
            return true;
        }

        if (action === 'pay' && metode === 'tunai') {

            const bayar = parseInt(inputBayar.value || 0);

            if (!bayar || bayar < currentTotal) {

                e.preventDefault();

                errorTunai.classList.remove('hidden');
                inputBayar.classList.add('border-red-500');

            }
        }
    });

    toggleMetode();
});
</script>
@endpush
