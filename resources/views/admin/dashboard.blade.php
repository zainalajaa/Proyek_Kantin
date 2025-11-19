@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <div class="bg-white rounded-lg shadow p-6 border-t-4 border-[#009688]">
    <h3 class="text-gray-500">Total Barang</h3>
    <p class="text-3xl font-bold text-[#009688]">128</p>
  </div>

  <div class="bg-white rounded-lg shadow p-6 border-t-4 border-[#FFD600]">
    <h3 class="text-gray-500">Barang Masuk</h3>
    <p class="text-3xl font-bold text-[#FFD600]">56</p>
  </div>

  <div class="bg-white rounded-lg shadow p-6 border-t-4 border-[#00796B]">
    <h3 class="text-gray-500">Barang Keluar</h3>
    <p class="text-3xl font-bold text-[#00796B]">72</p>
  </div>
</div>

<div class="mt-10 bg-white rounded-lg shadow p-6">
  <h3 class="text-xl font-semibold text-[#009688] mb-4">Aktivitas Terbaru</h3>
  <ul class="space-y-2">
    <li>📦 Barang <b>Kabel Listrik</b> masuk ke gudang utama.</li>
    <li>🚚 Barang <b>Meteran</b> dikirim ke ULP Cempaka.</li>
    <li>⚙️ Barang <b>Panel Distribusi</b> diperiksa oleh teknisi.</li>
  </ul>
</div>
@endsection
