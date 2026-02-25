@section('content')

<div class="min-h-screen bg-slate-50 py-12">

    <div class="max-w-md mx-auto px-4">

        <div class="bg-white rounded-3xl shadow-sm p-8 text-center">

            <h2 class="text-2xl font-bold text-slate-800 mb-6">
                Pembayaran QRIS
            </h2>

            {{-- TOTAL --}}
            <div class="mb-8">
                <p class="text-sm text-slate-500 mb-2">
                    Total yang harus dibayar
                </p>

                <div class="text-3xl font-bold text-emerald-600">
                    Rp {{ number_format($penjualan->total_harga,0,',','.') }}
                </div>
            </div>

            {{-- QR IMAGE --}}
            <div class="flex justify-center mb-8">
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <img src="{{ asset('storage/images/qris.jpeg') }}"
                         alt="QRIS Pembayaran"
                         class="w-64 rounded-xl">
                </div>
            </div>

            {{-- STATUS --}}
            <div class="mb-8">
                <p class="text-sm font-semibold text-emerald-600">
                    Menunggu pembayaran...
                </p>
                <p class="text-xs text-slate-400 mt-1">
                    Silakan scan QR menggunakan aplikasi pembayaran Anda.
                </p>
            </div>

            {{-- BUTTON --}}
            <form action="{{ route('publik.qris.submit', $penjualan->id) }}" method="POST">
                @csrf

                <button type="submit"
                    class="w-full bg-emerald-500 text-white py-3 rounded-2xl font-semibold hover:bg-emerald-600 transition shadow-sm">
                    Saya Sudah Membayar
                </button>
            </form>

        </div>

    </div>

</div>

@endsection