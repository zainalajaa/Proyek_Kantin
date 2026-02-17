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

<div class="min-h-screen bg-gradient-to-b from-slate-100 to-slate-200 py-6">
<div class="max-w-4xl mx-auto px-4">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-5">
        <h2 class="text-xl sm:text-2xl font-bold text-slate-800">
            Checkout Pembayaran
        </h2>

        <div class="text-xs sm:text-sm text-slate-500 mt-1">
            ID Transaksi: 
            <span class="font-semibold">{{ $penjualan->id }}</span>
            • {{ $penjualan->waktu }}
        </div>
    </div>

    <form action="{{ route('publik.tunai.bayar', $penjualan->id) }}" method="POST" id="formTunai">
    @csrf


    {{-- DAFTAR PRODUK --}}
    <div>
        <table class="w-full text-xs sm:text-sm table-fixed">
            <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="py-2 px-2 text-left w-[30%]">Produk</th>
                    <th class="py-2 px-2 text-right w-[18%] whitespace-nowrap">Harga</th>
                    <th class="py-2 px-2 text-center w-[28%]">Jumlah</th>
                    <th class="py-2 px-2 text-right w-[24%] whitespace-nowrap">Subtotal</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
            @foreach($details as $d)
            <tr data-row="detail"
                data-harga="{{ (int) $d->harga_satuan }}">

                {{-- PRODUK --}}
                <td class="py-2 px-2 font-medium text-slate-700 truncate">
                    {{ $d->nama_produk }}
                </td>

                {{-- HARGA --}}
                <td class="py-2 px-2 text-right text-slate-600 whitespace-nowrap">
                    Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}
                </td>

                {{-- JUMLAH --}}
                <td class="py-2 px-2 text-center">

                    <div class="flex items-center justify-center border border-slate-300 rounded-lg overflow-hidden">

                        <button type="button"
                            class="btn-minus px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs">
                            −
                        </button>

                        <input type="number"
                            name="items[{{ $d->id }}][jumlah]"
                            class="qty-input w-8 text-center text-xs outline-none"
                            min="1"
                            value="{{ $d->jumlah }}">

                        <button type="button"
                            class="btn-plus px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs">
                            +
                        </button>
                    </div>

                    <input type="hidden" name="items[{{ $d->id }}][id_detail]" value="{{ $d->id }}">
                    <input type="hidden" name="items[{{ $d->id }}][id_produk]" value="{{ $d->id_produk }}">
                    <input type="hidden" name="items[{{ $d->id }}][harga_satuan]" value="{{ $d->harga_satuan }}">
                </td>

                {{-- SUBTOTAL --}}
                <td class="py-2 px-2 text-right font-semibold text-emerald-600 whitespace-nowrap subtotal-cell">
                    Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                </td>

            </tr>
            @endforeach
            </tbody>
        </table>
    </div>


    {{-- RINGKASAN --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-5">

        <h3 class="font-semibold text-slate-700 mb-4">
            Ringkasan Pembayaran
        </h3>

        <div class="flex justify-between items-center text-sm mb-3">
            <span class="text-slate-600">Total Belanja</span>
            <span id="totalText" class="text-lg font-bold text-emerald-600">
                Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}
            </span>
        </div>

        <input type="hidden" id="total_hidden" value="{{ (int)$penjualan->total_harga }}">

        {{-- METODE --}}
        <div class="mt-4">
            <label class="block text-sm font-semibold mb-2">
                Metode Pembayaran
            </label>

            <div class="space-y-2 text-sm">
                <label class="flex items-center gap-2">
                    <input type="radio" name="metode_pembayaran" value="tunai" checked>
                    Tunai
                </label>

                <label class="flex items-center gap-2">
                    <input type="radio" name="metode_pembayaran" value="qris">
                    QRIS
                </label>
            </div>
        </div>

        {{-- QRIS INFO --}}
        <div id="qrisBox"
            class="hidden mt-4 p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-700">
            Anda akan diarahkan ke halaman pembayaran QRIS.
        </div>

        {{-- TUNAI --}}
        <div id="tunaiBox" class="mt-4">
            <label class="text-sm block mb-1">Jumlah Bayar</label>
            <input type="number"
                name="jumlah_bayar"
                id="jumlah_bayar"
                class="w-full border border-slate-300 p-2.5 rounded-lg focus:ring-2 focus:ring-emerald-400 outline-none"
                placeholder="Masukkan nominal tunai">
        </div>

        <div class="mt-4 text-sm">
            <span class="font-semibold">Kembali:</span>
            <span id="kembaliText" class="text-emerald-600 font-semibold">
                Rp 0
            </span>
        </div>

    </div>

    {{-- ACTION BUTTON --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 flex flex-col sm:flex-row gap-3">

        <button type="submit"
            name="action"
            value="cart"
            class="border border-emerald-500 text-emerald-600 py-2.5 rounded-xl hover:bg-emerald-50 transition">
            🛒 Ke Keranjang
        </button>

        <button type="submit"
            name="action"
            value="pay"
            id="btnBayar"
            class="flex-1 bg-emerald-500 text-white py-2.5 rounded-xl font-semibold hover:bg-emerald-600 transition">
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
