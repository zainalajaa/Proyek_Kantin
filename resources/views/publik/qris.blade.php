@extends('layouts.publik')

@section('title', 'Qris')

@section('content')

<style>
@keyframes spinSlow {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.animate-spin-slow {
    animation: spinSlow 2s linear infinite;
}
</style>

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
            <div class="mb-6">
                <p class="text-sm font-semibold text-emerald-600">
                    Menunggu pembayaran...
                </p>
                <p class="text-xs text-slate-400 mt-1">
                    Silakan scan QR menggunakan aplikasi pembayaran Anda.
                </p>
            </div>

            {{-- INFO --}}
            <div class="mb-6 p-3 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-700">
                ⚠️ Setelah melakukan pembayaran, wajib upload bukti dan klik <b>“Saya Sudah Membayar”</b>.
            </div>

            {{-- FORM --}}
            <form id="formQris" 
                  action="{{ route('publik.qris.submit', $penjualan->id) }}" 
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                {{-- UPLOAD BUKTI --}}
                <div class="mb-5 text-left">
                    <label class="text-sm font-medium text-slate-600">
                        Upload Bukti Pembayaran
                    </label>

                    <input type="file" 
                           name="payment_proof" 
                           id="payment_proof"
                           accept="image/*"
                           class="mt-2 block w-full text-sm border border-slate-200 rounded-lg p-2">

                    {{-- PESAN ERROR --}}
                    <p id="errorMessage" class="text-xs text-red-500 mt-1 hidden">
                        ⚠️ Bukti pembayaran wajib diupload sebelum melanjutkan.
                    </p>

                    <p class="text-xs text-slate-400 mt-1">
                        Format JPG/PNG, maksimal 2MB
                    </p>
                </div>

                {{-- PREVIEW --}}
                <div class="mb-5 hidden" id="previewContainer">
                    <p class="text-xs text-slate-500 mb-2">Preview Bukti:</p>
                    <img id="previewImage" class="w-40 mx-auto rounded-lg shadow">
                </div>

                {{-- BUTTON --}}
                <button type="submit"
                    id="btnQris"
                    class="w-full bg-emerald-500 text-white py-3 rounded-2xl font-semibold hover:bg-emerald-600 active:scale-95 transition shadow-sm">
                    Saya Sudah Membayar
                </button>

            </form>

        </div>

    </div>

</div>

{{-- LOADING --}}
<div id="loadingOverlay"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden">

    <div class="bg-white rounded-2xl p-6 flex flex-col items-center gap-4 shadow-xl">

        <svg class="w-10 h-10 animate-spin-slow text-emerald-500"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10"
                stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8v8z"></path>
        </svg>

        <p class="text-sm font-semibold text-emerald-600">
            Memverifikasi pembayaran...
        </p>

    </div>
</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const formQris = document.getElementById('formQris');
    const overlay  = document.getElementById('loadingOverlay');
    const btnQris  = document.getElementById('btnQris');
    const input    = document.getElementById('payment_proof');
    const preview  = document.getElementById('previewImage');
    const container= document.getElementById('previewContainer');
    const errorMsg = document.getElementById('errorMessage');

    // PREVIEW GAMBAR
    input.addEventListener('change', function(e) {
        const file = e.target.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);
            container.classList.remove('hidden');
            errorMsg.classList.add('hidden');
        }
    });

    // VALIDASI TANPA ALERT BROWSER
    formQris.addEventListener('submit', function(e) {

        if (!input.files.length) {
            e.preventDefault();
            errorMsg.classList.remove('hidden');
            return;
        }

        overlay.classList.remove('hidden');

        btnQris.disabled = true;
        btnQris.classList.add('opacity-70', 'cursor-not-allowed');
    });

});
</script>
@endpush