<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kantin Kejujuran ULP PLN Banjarbaru</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://kit.fontawesome.com/a2d9d5e9b2.js" crossorigin="anonymous"></script>
</head>
<body class="bg-[#F9FAFB] text-[#1E293B]">

  <!-- Navbar -->
  <nav class="bg-[#009DAE] text-white shadow-lg">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
      <h1 class="text-2xl font-bold tracking-wide">Kantin Kejujuran</h1>
      <ul class="flex space-x-6">
        <li><a href="#" class="hover:text-[#FDC500] transition">Home</a></li>
        <li><a href="#menu" class="hover:text-[#FDC500] transition">Menu</a></li>
        <li><a href="#tentang" class="hover:text-[#FDC500] transition">Tentang</a></li>
        <li><a href="#kontak" class="hover:text-[#FDC500] transition">Kontak</a></li>
      </ul>
    </div>
  </nav>

  <!-- Hero -->
  <section class="relative bg-gradient-to-br from-[#009DAE] to-[#FDC500] py-20 text-white">
    <div class="container mx-auto text-center px-6">
      <h2 class="text-4xl font-bold mb-4 drop-shadow-lg">Selamat Datang di Kantin Kejujuran</h2>
      <p class="text-lg mb-6 max-w-2xl mx-auto drop-shadow-md">
        Nikmati berbagai makanan dan minuman lezat sambil menjaga nilai kejujuran bersama ULP PLN Banjarbaru.
      </p>
      <button class="bg-white text-[#009DAE] px-6 py-3 rounded-full font-semibold hover:bg-[#FDC500] hover:text-[#1E293B] transition">
        Lihat Menu
      </button>
    </div>
  </section>

  <!-- Menu -->
  <section id="menu" class="py-16 bg-white">
    <div class="container mx-auto px-6">
      <h3 class="text-3xl font-semibold text-center text-[#009DAE] mb-10">Menu Hari Ini</h3>

      <div class="grid md:grid-cols-3 gap-8">
        <!-- Card 1 -->
        <div class="bg-[#E0F7FA] rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
          <img src="https://source.unsplash.com/400x250/?nasi-goreng" alt="Nasi Goreng" class="rounded-t-2xl">
          <div class="p-4 text-center">
            <h4 class="font-bold text-lg mb-2 text-[#009DAE]">Nasi Goreng Spesial</h4>
            <p class="text-gray-600">Rp12.000</p>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-[#E0F7FA] rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
          <img src="https://source.unsplash.com/400x250/?mie-goreng" alt="Mie Goreng" class="rounded-t-2xl">
          <div class="p-4 text-center">
            <h4 class="font-bold text-lg mb-2 text-[#009DAE]">Mie Goreng</h4>
            <p class="text-gray-600">Rp10.000</p>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-[#E0F7FA] rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
          <img src="https://source.unsplash.com/400x250/?es-teh" alt="Es Teh" class="rounded-t-2xl">
          <div class="p-4 text-center">
            <h4 class="font-bold text-lg mb-2 text-[#009DAE]">Es Teh Manis</h4>
            <p class="text-gray-600">Rp5.000</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Tentang -->
  <section id="tentang" class="bg-[#E0F2F1] py-16">
    <div class="container mx-auto px-6 text-center">
      <h3 class="text-3xl font-semibold text-[#009DAE] mb-4">Tentang Kantin Kejujuran</h3>
      <p class="max-w-2xl mx-auto text-gray-700 leading-relaxed">
        Kantin Kejujuran ULP PLN Banjarbaru adalah inisiatif yang mengajarkan pentingnya kejujuran dalam kehidupan sehari-hari.
        Pengunjung dapat mengambil makanan dan minuman serta membayar sesuai harga yang tertera tanpa pengawasan kasir.
      </p>
    </div>
  </section>

  <!-- Kontak -->
  <section id="kontak" class="py-16 bg-white">
    <div class="container mx-auto px-6 text-center">
      <h3 class="text-3xl font-semibold text-[#009DAE] mb-6">Hubungi Kami</h3>
      <p class="text-gray-700 mb-4">Jl. A. Yani KM 30, Banjarbaru, Kalimantan Selatan</p>
      <p class="text-gray-700 mb-6">
        Email: <a href="mailto:info@ulpplnbanjarbaru.co.id" class="text-[#009DAE] font-semibold hover:text-[#FDC500] transition">
          info@ulpplnbanjarbaru.co.id
        </a>
      </p>
      <button class="bg-[#009DAE] text-white px-6 py-3 rounded-full font-semibold hover:bg-[#FDC500] hover:text-[#1E293B] transition">
        Kirim Pesan
      </button>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-[#009DAE] text-white text-center py-4">
    <p>&copy; 2025 Kantin Kejujuran ULP PLN Banjarbaru. Semua Hak Dilindungi.</p>
  </footer>

  <script>
    // Efek transisi halus saat klik menu navbar
    document.querySelectorAll('a[href^="#"]').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        document.querySelector(link.getAttribute('href')).scrollIntoView({
          behavior: 'smooth'
        });
      });
    });
  </script>

</body>
</html>
