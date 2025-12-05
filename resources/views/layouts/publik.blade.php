<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kantin Kejujuran ULP PLN Banjarbaru')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <script src="https://kit.fontawesome.com/a2d9d5e9b2.js" crossorigin="anonymous" defer></script>
</head>
<body class="bg-[#F9FAFB] text-[#1E293B] min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="bg-[#009DAE] text-white shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                {{-- Kiri: logo + judul --}}
                <div class="flex items-center gap-3">
                    @php
                        $logoPath = 'storage/images/logo-kantin.png';
                    @endphp

                    @if (file_exists(public_path($logoPath)))
                        <img src="{{ asset($logoPath) }}"
                             alt="Logo Kantin"
                             class="h-10 w-10 object-contain bg-white p-1 rounded-full shadow-md">
                    @else
                        {{-- Fallback avatar --}}
                        <div class="h-10 w-10 rounded-full bg-white flex items-center justify-center shadow-md text-[#009DAE] font-bold">
                            KK
                        </div>
                    @endif

                    <div class="leading-tight">
                        <a href="{{ route('publik.index') }}" class="block">
                            <h1 class="text-lg md:text-xl font-bold tracking-wide">Kantin Kejujuran</h1>
                            <p class="text-xs md:text-sm opacity-90">ULP PLN Banjarbaru</p>
                        </a>
                    </div>
                </div>

                {{-- Kanan: menu dan toggle mobile --}}
                <div class="flex items-center gap-4">
                    <ul id="navDesktop" class="hidden md:flex space-x-6 text-sm md:text-base">
                        <li><a href="{{ route('publik.index') }}" class="hover:text-[#FDC500] transition">Home</a></li>
                        <li><a href="#menu" class="hover:text-[#FDC500] transition">Menu</a></li>
                        <li><a href="#tentang" class="hover:text-[#FDC500] transition">Tentang</a></li>
                        <li><a href="#kontak" class="hover:text-[#FDC500] transition">Kontak</a></li>
                    </ul>

                    {{-- Mobile menu button --}}
                    <button id="mobileMenuBtn" class="md:hidden p-2 rounded-md hover:bg-black/10" aria-label="Toggle menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile menu (hidden by default) --}}
            <div id="mobileMenu" class="md:hidden hidden pb-4">
                <ul class="flex flex-col space-y-2 text-sm">
                    <li><a href="{{ route('publik.index') }}" class="block px-3 py-2 hover:bg-white/10 rounded">Home</a></li>
                    <li><a href="#menu" class="block px-3 py-2 hover:bg-white/10 rounded">Menu</a></li>
                    <li><a href="#tentang" class="block px-3 py-2 hover:bg-white/10 rounded">Tentang</a></li>
                    <li><a href="#kontak" class="block px-3 py-2 hover:bg-white/10 rounded">Kontak</a></li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 w-full">
        @if(session('success'))
            <div class="mb-4 text-green-800 bg-green-100 border border-green-200 p-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 text-red-800 bg-red-100 border border-red-200 p-3 rounded">
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Konten utama --}}
    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-[#009DAE] text-white text-center py-6 mt-6">
        <p class="text-sm">&copy; {{ date('Y') }} Kantin Kejujuran ULP PLN Banjarbaru. Semua Hak Dilindungi.</p>
    </footer>

    @stack('scripts')
    <script>
        // Mobile menu toggle
        const menuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        if (menuBtn && mobileMenu) {
            menuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Smooth scroll for anchors on the same page
        document.querySelectorAll('a[href^="#"]').forEach(link => {
            link.addEventListener('click', e => {
                const target = document.querySelector(link.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                    // close mobile menu after click
                    if (!mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                    }
                }
            });
        });
    </script>
</body>
</html>
