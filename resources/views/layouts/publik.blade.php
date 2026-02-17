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

                {{-- Kanan: Icon Keranjang untuk checkout --}}
                <div class="flex items-center">
                    <a href="{{ route('cart.index') }}"
                    class="relative inline-flex items-center justify-center h-10 w-10 rounded-full 
                            bg-white/10 hover:bg-white/20 transition">

                        {{-- Icon keranjang (SVG, tanpa Font Awesome) --}}
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" 
                            fill="currentColor" class="h-5 w-5 text-white">
                            <path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.593A2.25 2.25 0 008.75 15h9.5a2.25 2.25 0 002.143-1.57l1.6-5A.75.75 0 0021.25 7.5h-14a.75.75 0 01-.727-.568L5.04 3.028A1.875 1.875 0 003.636 2.25H2.25z" />
                            <path d="M8.25 18a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm8.25 0a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" />
                        </svg>

                        {{-- Badge jumlah item --}}
                        @php
                            $cartCount = session('cart_count', 0);
                        @endphp

                        @if ($cartCount > 0)
                            <span class="absolute -top-1 -right-1 
                                        h-5 min-w-[1.25rem] px-1
                                        flex items-center justify-center
                                        rounded-full bg-[#FDC500]
                                        text-[10px] font-bold text-[#1E293B]">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                </div>

            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 w-full">

        @if(session('success'))
            <div id="flash-success"
                class="flash-message mb-4 flex items-center gap-3 
                    rounded-2xl border border-emerald-500/40 
                    bg-slate-800 text-emerald-300 
                    px-4 py-3 shadow-lg transition-all duration-500">

                <div class="flex items-center justify-center h-8 w-8 rounded-lg bg-emerald-500/20">
                    ✓
                </div>

                <div class="flex-1 text-sm font-medium">
                    {{ session('success') }}
                </div>

                <button onclick="closeFlash('flash-success')"
                    class="text-emerald-300 hover:text-white text-sm">
                    ✕
                </button>
            </div>
        @endif

        @if(session('error'))
            <div id="flash-error"
                class="flash-message mb-4 flex items-center gap-3 
                    rounded-2xl border border-red-500/40 
                    bg-slate-800 text-red-300 
                    px-4 py-3 shadow-lg transition-all duration-500">

                <div class="flex items-center justify-center h-8 w-8 rounded-lg bg-red-500/20">
                    ⚠
                </div>

                <div class="flex-1 text-sm font-medium">
                    {{ session('error') }}
                </div>

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
        function closeFlash(id) {
            const el = document.getElementById(id);
            if (!el) return;

            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }

        setTimeout(() => {
            document.querySelectorAll('.flash-message').forEach(el => {
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 3000);
    </script>

</body>
</html>
