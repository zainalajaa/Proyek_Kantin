@extends('layouts.publik')

@section('title', 'Transaksi Selesai')

@section('content')

{{-- @php
    dd($penjualan->details);
@endphp --}}

@php
    $isQris = $penjualan->metode_pembayaran === 'qris';

    $colorPrimary = $isQris ? '#10b981' : '#3b82f6';
    $textPrimary  = $isQris ? 'Pembayaran QRIS Berhasil' : 'Pembayaran Tunai Berhasil';

    $message = $isQris
        ? 'Terima kasih sudah melakukan pembayaran dengan QRIS'
        : 'Terima kasih telah melakukan pembayaran secara tunai';
@endphp


<style>
body {
    background: linear-gradient(135deg,
        {{ $isQris ? '#f0fdf4, #ecfeff' : '#eff6ff, #ecfeff' }});
}

@keyframes circleDraw {
    from { stroke-dashoffset: 260; }
    to   { stroke-dashoffset: 0; }
}

@keyframes checkDraw {
    from { stroke-dashoffset: 80; }
    to   { stroke-dashoffset: 0; }
}

@keyframes smoothFade {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.circle-anim {
    stroke-dasharray: 260;
    stroke-dashoffset: 260;
    animation: circleDraw 1.2s ease forwards;
}

.check-anim {
    stroke-dasharray: 80;
    stroke-dashoffset: 80;
    animation: checkDraw 0.8s ease forwards;
    animation-delay: 1.1s;
}

.fade-1 { animation: smoothFade 0.8s ease forwards 1.6s; opacity: 0; }
.fade-2 { animation: smoothFade 0.8s ease forwards 1.9s; opacity: 0; }
.fade-3 { animation: smoothFade 0.8s ease forwards 2.2s; opacity: 0; }

.glass {
    background: rgba(255, 255, 255, 0.65);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}

.glow {
    filter: drop-shadow(0 0 8px rgba(
        {{ $isQris ? '16,185,129' : '59,130,246' }}, 0.4));
}
</style>

<div class="min-h-screen pt-20 pb-12 px-4">

    <div class="max-w-md mx-auto glass border border-white/60 rounded-2xl shadow-xl p-8 text-center">

        {{-- ICON ANIMASI --}}
        <div class="flex justify-center mb-5 glow">
            <svg width="140" height="140" viewBox="0 0 100 100">

                <circle
                    class="circle-anim"
                    cx="50"
                    cy="50"
                    r="42"
                    fill="none"
                    stroke="{{ $colorPrimary }}"
                    stroke-width="5"
                />

                <polyline
                    class="check-anim"
                    points="30,52 45,67 72,38"
                    fill="none"
                    stroke="{{ $colorPrimary }}"
                    stroke-width="5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 fade-1">
            {{ $textPrimary }}
        </h2>

        <p class="text-gray-600 mt-2 fade-2">
            {{ $message }}
        </p>

        <div class="mt-3 fade-2">
            <span class="text-sm text-gray-500">ID Transaksi</span>
            <div class="font-semibold text-lg"
                 style="color: {{ $colorPrimary }}">
                {{ $penjualan->id }}
            </div>
        </div>

        <div class="mt-3 fade-2">
            <span class="text-sm text-gray-500">Total Pembayaran</span>
            <div class="font-semibold text-lg"
                style="color: {{ $colorPrimary }}">
                Rp {{ number_format($penjualan->total_harga,0,',','.') }}
            </div>
        </div>

        <div class="mt-7 fade-3">
            <a href="/"
               class="inline-block text-white px-6 py-2.5 rounded-xl
                      transition-all duration-200
                      transform hover:scale-105 active:scale-95 shadow"
               style="background-color: {{ $colorPrimary }}">
                Kembali ke Beranda
            </a>
        </div>

    </div>
</div>


{{-- SUARA SUKSES --}}
<audio id="successSound">
    <source src="https://www.myinstants.com/media/sounds/notification-sound-7062.mp3" type="audio/mpeg">
</audio>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const sound = document.getElementById('successSound');
    sound.volume = 0.3;

    setTimeout(() => {
        sound.play().catch(() => {});
    }, 900);

});
</script>
@endpush
