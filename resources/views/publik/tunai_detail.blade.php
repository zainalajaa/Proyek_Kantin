@extends('layouts.publik')

@section('title', 'Checkout Pembayaran')

@section('content')

<style>
/* Hilangkan spinner input number */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type=number] {
    -moz-appearance: textfield;
}
</style>

<div class="min-h-screen bg-gray-50 py-6">
<div class="max-w-4xl mx-auto px-4">

    {{-- HEADER --}}
    <div class="bg-white rounded-xl shadow p-5 mb-4">
        <h2 class="text-xl font-semibold text-gray-800">
            Checkout Pembayaran
        </h2>

        <div class="text-sm text-gray-500 mt-1">
            ID Transaksi: <span class="font-medium">{{ $penjualan->id }}</span>  
            • {{ $penjualan->waktu }}
        </div>
    </div>
    <form action="{{ route('publik.tunai.bayar', $penjualan->id) }}" method="POST" id="formTunai">
    @csrf

    {{-- DAFTAR PRODUK --}}
    <div class="bg-white rounded-xl shadow mb-4 overflow-hidden">

        <div class="px-5 py-3 border-b bg-gray-50">
            <h3 class="font-semibold text-gray-700">
                Daftar Produk
            </h3>
        </div>

        <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="text-sm bg-gray-50 text-gray-600">
                <tr>
                    <th class="py-3 px-4 text-left">Produk</th>
                    <th class="py-3 px-4 text-right">Harga</th>
                    <th class="py-3 px-4 text-center">Jumlah</th>
                    <th class="py-3 px-4 text-right">Subtotal</th>
                </tr>
            </thead>

            <tbody>
            @foreach($details as $d)
            <tr data-row="detail"
                data-harga="{{ (int) $d->harga_satuan }}"
                class="border-t">

                <td class="py-3 px-4 font-medium text-gray-700">
                    {{ $d->nama_produk }}
                </td>

                <td class="py-3 px-4 text-right text-gray-700">
                    Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}
                </td>

                <td class="py-3 px-4 text-center">

                    <div class="inline-flex items-center border rounded-lg overflow-hidden">

                        <button type="button"
                            class="btn-minus px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700">
                            −
                        </button>

                        <input type="number"
                            name="items[{{ $d->id }}][jumlah]"
                            class="qty-input w-12 text-center outline-none"
                            min="1"
                            value="{{ $d->jumlah }}">

                        <button type="button"
                            class="btn-plus px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700">
                            +
                        </button>
                    </div>

                    <input type="hidden" name="items[{{ $d->id }}][id_detail]" value="{{ $d->id }}">
                    <input type="hidden" name="items[{{ $d->id }}][id_produk]" value="{{ $d->id_produk }}">
                    <input type="hidden" name="items[{{ $d->id }}][harga_satuan]" value="{{ $d->harga_satuan }}">
                </td>
                <td class="py-3 px-4 text-right font-semibold subtotal-cell text-gray-800">
                    Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>
    {{-- RINGKASAN PEMBAYARAN --}}
    <div class="bg-white rounded-xl shadow p-5 mb-4">

        <h3 class="font-semibold text-gray-700 mb-3">
            Ringkasan Pembayaran
        </h3>

        <div class="flex justify-between text-sm mb-2">
            <span>Total Belanja</span>
            <span id="totalText" class="font-semibold">
                Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}
            </span>
        </div>

        <input type="hidden" id="total_hidden" value="{{ (int)$penjualan->total_harga }}">

        <div class="mt-4">
            <label class="block text-sm mb-2 font-semibold">Metode Pembayaran</label>

            <label class="flex items-center gap-2">
                <input type="radio" name="metode_pembayaran" value="tunai" checked>
                Tunai
            </label>

            <label class="flex items-center gap-2">
                <input type="radio" name="metode_pembayaran" value="qris">
                QRIS
            </label>
        </div>

        <div id="qrisBox"
            class="hidden mt-3 p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-700">
            Anda akan diarahkan ke halaman pembayaran QRIS.
        </div>

        <div id="tunaiBox" class="mt-4">
            <label class="text-sm block mb-1">Jumlah Bayar</label>
            <input type="number"
                name="jumlah_bayar"
                id="jumlah_bayar"
                class="w-full border p-2 rounded"
                placeholder="Masukkan nominal tunai">
        </div>

        <div class="mt-3 text-sm">
            <strong>Kembali:</strong>
            <span id="kembaliText">Rp 0</span>
        </div>

    </div>

    {{-- TOMBOL AKSI --}}
    <div class="bg-white rounded-xl shadow p-4 flex gap-3">

        <button type="submit"
            name="action"
            value="cart"
            class="px-4 py-2 border border-emerald-500 text-emerald-600 rounded-lg hover:bg-emerald-50">
            🛒 Ke Keranjang
        </button>

        <button type="submit"
            name="action"
            value="pay"
            id="btnBayar"
            class="flex-1 bg-emerald-500 text-white py-2 rounded-lg hover:bg-emerald-600">
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

    inputBayar.addEventListener('input', updateKembali);

    formTunai.addEventListener('submit', function(e) {

        const action = document.activeElement.value;
        const metode = document.querySelector('input[name="metode_pembayaran"]:checked').value;

        
        if (action === 'pay' && metode === 'qris') {
            // Biarkan form submit normal ke controller
            return true;
        }


        if (action === 'pay' && metode === 'tunai') {

            const bayar = parseInt(inputBayar.value || 0);

            if (!bayar || bayar < currentTotal) {
                e.preventDefault();
                alert('Jumlah bayar harus lebih besar atau sama dengan total.');
            }
        }
    });

    toggleMetode();
});
</script>
@endpush
