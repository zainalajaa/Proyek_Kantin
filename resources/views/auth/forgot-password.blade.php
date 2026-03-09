<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Reset Password - Kantin Kejujuran PLN</title>

@vite(['resources/css/app.css','resources/js/app.js'])

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="min-h-screen flex items-center justify-center bg-gray-100 p-4">

<div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

<!-- LEFT -->
<div class="relative z-10 p-8 flex flex-col justify-center bg-white">

<div class="flex justify-center mb-4">
<img src="{{ asset('storage/images/logo-kj.png') }}"
class="w-28 h-auto object-contain">
</div>

<div class="text-center mb-6">
<h1 class="text-2xl font-bold text-gray-800 mb-1">
Reset Password
</h1>
<p class="text-gray-500 text-sm">
Masukkan email admin untuk membuat link reset password
</p>
</div>

{{-- STATUS --}}
@if (session('status'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 text-sm text-center">
{{ session('status') }}
</div>
@endif

{{-- RESET LINK --}}
@if(session('reset_link'))
<div class="bg-blue-50 border border-blue-300 text-blue-800 px-4 py-3 rounded-lg mb-4 text-sm">

    <p class="font-semibold mb-2">
        Link Reset Password
    </p>

    <div class="bg-white border rounded-md p-3 text-xs font-mono text-blue-700 w-full overflow-x-auto">

        <a href="{{ session('reset_link') }}"
           class="block w-full break-words underline">

            {{ session('reset_link') }}

        </a>

    </div>

</div>
@endif

{{-- ERROR --}}
@error('email')
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 text-sm">
{{ $message }}
</div>
@enderror

<form method="POST" action="{{ route('password.email') }}" class="space-y-4">
@csrf

<input type="email"
name="email"
required
class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
focus:outline-none focus:ring-2 focus:ring-[#00BFA5]"
placeholder="Masukkan Email">

<button type="submit"
class="w-full py-2 rounded-lg text-sm font-semibold text-gray-800
bg-gradient-to-r from-[#00BFA5] to-[#FFD600]
hover:from-[#009E8E] hover:to-[#FFC107]
transition duration-300 shadow">
Kirim Link Reset
</button>

</form>

<div class="text-center mt-4">
<a href="{{ route('admin.login') }}"
class="text-xs text-[#00ACC1] hover:underline">
Kembali ke Login
</a>
</div>

<p class="mt-6 text-xs text-gray-500 text-center">
© {{ date('Y') }} Kantin Kejujuran PLN Banjarbaru
</p>

</div>

<!-- RIGHT -->
<div class="hidden md:flex relative pointer-events-none
bg-gradient-to-br from-[#00ACC1] to-[#00BFA5]">

<div class="absolute w-48 h-48 bg-[#FFD600] opacity-20
rounded-full -top-8 -right-8 blur-2xl"></div>

<div class="absolute w-64 h-64 bg-white opacity-10
rounded-full bottom-0 -left-10 blur-2xl"></div>

<div class="flex items-center justify-center w-full text-white p-6 text-center">
<div>
<h2 class="text-xl font-bold mb-3">
Sistem Informasi Kantin
</h2>

<p class="text-white/80 text-sm">
Kelola produk dan transaksi secara modern dan transparan.
</p>
</div>
</div>

</div>

</div>

</body>
</html>