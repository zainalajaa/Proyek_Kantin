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

  <!-- LEFT SIDE -->
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
          Masukkan password baru untuk akun admin
        </p>
      </div>

      {{-- STATUS --}}
      @if(session('status'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 text-sm text-center">
        {{ session('status') }}
      </div>
      @endif

      {{-- ERROR --}}
      @if($errors->any())
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 text-sm">
        <ul class="list-disc ml-4">
          @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ request()->email }}">

        <!-- PASSWORD BARU -->
        <div class="relative">
          <input type="password"
                 name="password"
                 id="password"
                 required
                 placeholder="Password Baru"
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg 
                        text-sm focus:outline-none focus:ring-2 focus:ring-[#00BFA5]">

          <button type="button"
                  id="togglePassword"
                  class="absolute inset-y-0 right-3 flex items-center text-gray-500">
            <i id="eyeIcon" class="fa-solid fa-eye text-sm"></i>
          </button>
        </div>

        <!-- KONFIRMASI PASSWORD -->
        <div class="relative">
          <input type="password"
                 name="password_confirmation"
                 id="password_confirmation"
                 required
                 placeholder="Konfirmasi Password"
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg 
                        text-sm focus:outline-none focus:ring-2 focus:ring-[#FFD600]">
        </div>

        <!-- BUTTON -->
        <button type="submit"
        class="w-full py-2 rounded-lg text-sm font-semibold text-gray-800
               bg-gradient-to-r from-[#00BFA5] to-[#FFD600]
               hover:from-[#009E8E] hover:to-[#FFC107]
               transition duration-300 shadow">
          Reset Password
        </button>

      </form>

      <p class="mt-6 text-xs text-gray-500 text-center">
        © {{ date('Y') }} Kantin Kejujuran PLN Banjarbaru
      </p>

  </div>

  <!-- RIGHT SIDE -->
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

<script>

document.getElementById("togglePassword").addEventListener("click", function () {

    const passwordInput = document.getElementById("password");
    const eyeIcon = document.getElementById("eyeIcon");

    if(passwordInput.type === "password"){

        passwordInput.type = "text";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");

    }else{

        passwordInput.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");

    }

});

</script>

</body>
</html>