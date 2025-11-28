<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Kantin Kejujuran ULP PLN Banjarbaru')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/a2d9d5e9b2.js" crossorigin="anonymous"></script>
</head>
<body class="bg-[#F9FAFB] text-[#1E293B]">

    {{-- Navbar --}}
    <nav class="bg-[#009DAE] text-white shadow-lg sticky top-0 z-30">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <h1 class="text-2xl font-bold tracking-wide">
                Kantin Kejujuran
            </h1>
            <ul class="flex space-x-6 text-sm md:text-base">
                <li><a href="{{ url('/publik') }}" class="hover:text-[#FDC500] transition">Home</a></li>
                <li><a href="#menu" class="hover:text-[#FDC500] transition">Menu</a></li>
                <li><a href="#tentang" class="hover:text-[#FDC500] transition">Tentang</a></li>
                <li><a href="#kontak" class="hover:text-[#FDC500] transition">Kontak</a></li>
            </ul>
        </div>
    </nav>

    {{-- Konten Utama --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-[#009DAE] text-white text-center py-4 mt-10">
        <p>&copy; {{ date('Y') }} Kantin Kejujuran ULP PLN Banjarbaru. Semua Hak Dilindungi.</p>
    </footer>

    {{-- Smooth Scroll untuk anchor navbar --}}
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(link => {
            link.addEventListener('click', e => {
                const target = document.querySelector(link.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>

</body>
</html>
