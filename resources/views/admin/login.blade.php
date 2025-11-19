<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - Kantin Kejujuran PLN</title>
  
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js" defer></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#00BFA5] via-[#00ACC1] to-[#FFD600]">

  <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-8 w-[90%] max-w-md">
    
    <!-- Logo PLN -->
    <div class="flex justify-center mb-6">
      <div class="bg-[#00BFA5] text-white p-4 rounded-full shadow-lg text-2xl">
        ⚡
      </div>
    </div>

    <h1 class="text-2xl font-bold text-gray-800 text-center mb-2">
      Kantin Kejujuran PLN
    </h1>
    <p class="text-center text-gray-600 mb-6 text-sm">
      Silakan masuk untuk mengelola sistem
    </p>

    <!-- Error Message -->
    @if (session('error'))
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 text-center">
        {{ session('error') }}
      </div>
    @endif`

    <!-- FORM LOGIN -->
    <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
      @csrf

      <!-- Input Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" id="email" required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00BFA5]"
          placeholder="contoh@pln.co.id">
      </div>

      <!-- Input Password + Toggle -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        
        <div class="relative">
          <input type="password" name="password" id="password" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FFD600]"
            placeholder="Masukkan password">

          <!-- Icon Mata -->
          <button type="button" onclick="togglePassword()" 
            class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">
            <i id="eyeIcon" class="fas fa-eye"></i>
          </button>
        </div>
      </div>

      <!-- Remember -->
      <div class="flex items-center justify-between text-sm">
        <a href="#" class="text-[#00BFA5] hover:text-[#009E8E] hover:underline">
          Lupa password?
        </a>
      </div>

      <!-- Submit -->
      <button type="submit"
        class="w-full bg-gradient-to-r from-[#00BFA5] to-[#FFD600] hover:from-[#009E8E] hover:to-[#FFC107]
               text-gray-800 font-semibold py-2.5 rounded-lg shadow-md transition duration-300">
        Masuk
      </button>
    </form>

    <!-- Footer -->
    <p class="mt-8 text-center text-gray-600 text-sm">
      © {{ date('Y') }} Kantin Kejujuran PLN Banjarbaru
    </p>
  </div>


  <!-- Script Toggle Password -->
  <script>
    function togglePassword() {
      const passwordField = document.getElementById('password');
      const eyeIcon = document.getElementById('eyeIcon');

      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
      } else {
        passwordField.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
      }
    }
  </script>

</body>
</html>
