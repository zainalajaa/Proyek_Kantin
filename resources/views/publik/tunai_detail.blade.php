@extends('layouts.publik') {{-- atau layout publik Anda --}}

@section('title', 'Pembayaran Tunai')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-lg font-semibold mb-4">Detail Pembayaran (Tunai)</h2>

    <div class="mb-4">
        <p><strong>ID Transaksi:</strong> {{ $penjualan->id }}</p>
        <p><strong>Waktu:</strong> {{ $penjualan->waktu }}</p>
        <p><strong>Metode:</strong> Tunai</p>
    </div>

    <table class="w-full mb-4">
        <thead>
            <tr class="text-left">
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $d)
            <tr>
                <td>{{ $d->nama_produk }}</td>
                <td>Rp {{ number_format($d->harga_satuan,0,',','.') }}</td>
                <td>{{ $d->jumlah }}</td>
                <td>Rp {{ number_format($d->subtotal,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mb-4">
        <p class="text-right text-lg font-semibold">Total: Rp {{ number_format($penjualan->total_harga,0,',','.') }}</p>
    </div>

    <form action="{{ route('publik.tunai.bayar', $penjualan->id) }}" method="POST" id="formTunai">
        @csrf
        <div class="mb-4">
            <label class="block text-sm">Jumlah Bayar (Rp)</label>
            <input type="number" name="jumlah_bayar" id="jumlah_bayar" min="0" required
                   class="w-full p-2 border rounded" value="{{ old('jumlah_bayar') }}">
        </div>

        <div class="mb-4">
            <p><strong>Kembali:</strong> <span id="kembaliText">Rp 0</span></p>
        </div>

        @if(session('error'))
            <div class="text-red-600 mb-3">{{ session('error') }}</div>
        @endif

        <button type="submit" class="w-full bg-emerald-500 text-white py-2 rounded">Bayar</button>
    </form>
</div>

<script>
    const inputBayar = document.getElementById('jumlah_bayar');
    const kembaliText = document.getElementById('kembaliText');
    const total = {{ (int) $penjualan->total_harga }};

    function formatRupiah(n) {
        return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    if (inputBayar) {
        inputBayar.addEventListener('input', () => {
            const bayar = parseInt(inputBayar.value || 0, 10);
            const kembali = bayar - total;
            kembaliText.textContent = kembali >= 0 ? formatRupiah(kembali) : 'Rp 0';
        });

        document.getElementById('formTunai').addEventListener('submit', (e) => {
            const bayar = parseInt(inputBayar.value || 0, 10);
            if (bayar < total) {
                e.preventDefault();
                alert('Jumlah bayar harus >= total.');
            }
        });
    }
</script>
@endsection
