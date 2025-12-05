<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin | Kantin Kejujuran')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">

<div class="flex min-h-screen">

    {{-- SIDEBAR KIRI FULL --}}
    <aside id="sidebar"
           class="bg-[#009688] w-64 p-5 text-white h-screen fixed left-0 top-0 z-50
                  flex flex-col transition-all duration-300">

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl font-bold tracking-wide">Admin Panel</h2>
        </div>

        <nav class="space-y-2 text-sm font-medium flex-1">
            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#00796B] transition
                    {{ request()->routeIs('admin.dashboard') ? 'bg-[#00796B]' : '' }}">
                🏠 Dashboard
            </a>

            {{-- Produk Masuk --}}
            <a href="{{ route('admin.produk.lihat') }}"
            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#00796B] transition
                    {{ request()->routeIs('admin.produk.*') ? 'bg-[#00796B]' : '' }}">
                📦 Produk
            </a>


            {{-- Penjualan --}}
            <a href="{{ route('admin.penjualan.index') }}"
            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#00796B] transition
                    {{ request()->routeIs('admin.penjualan') ? 'bg-[#00796B]' : '' }}">
                💰 Penjualan
            </a>

            {{-- Pengguna --}}
            <a href="{{ route('admin.pengguna') }}"
            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#00796B] transition
                    {{ request()->routeIs('admin.pengguna') ? 'bg-[#00796B]' : '' }}">
                👥 Pengguna
            </a>

            {{-- Pengaturan --}}
            <a href="{{ route('admin.pengaturan') }}"
            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#00796B] transition
                    {{ request()->routeIs('admin.pengaturan') ? 'bg-[#00796B]' : '' }}">
                ⚙️ Pengaturan
            </a>
        </nav>
    </aside>

    {{-- OVERLAY (untuk mobile ketika sidebar dibuka) --}}
    <div id="overlay"
         class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden"></div>

    @php
        $admin      = auth('admin')->user();
        $adminName  = $admin->name  ?? 'Admin Kantin';
        $adminEmail = $admin->email ?? 'admin@example.com';

        // Kalau punya kolom foto di tabel admin, ganti $admin->photo
        $avatarUrl  = ($admin && isset($admin->photo) && $admin->photo)
            ? asset('storage/' . $admin->photo)
            : 'https://ui-avatars.com/api/?name=' . urlencode($adminName) . '&background=009688&color=fff';
    @endphp

    {{-- BAGIAN KANAN: NAVBAR + KONTEN --}}
    <div id="mainContent"
         class="flex-1 flex flex-col min-h-screen ml-64 transition-all duration-300">

        {{-- NAVBAR PUTIH --}}
        <header class="bg-white shadow-sm border-b px-6 py-3 sticky top-0 z-30 flex items-center justify-between">

            {{-- Kiri: tombol sidebar + judul --}}
            <div class="flex items-center gap-4">
                <button id="toggleSidebar" class="text-2xl text-gray-700">
                    ☰
                </button>
                <h1 class="text-lg md:text-xl font-semibold text-gray-800">
                    @yield('title', 'Dashboard Admin')
                </h1>
            </div>

            {{-- Kanan: info admin + avatar + dropdown --}}
            <div class="relative flex items-center gap-3">
                <div class="hidden md:flex flex-col items-end leading-tight">
                    <span class="text-sm font-semibold text-gray-800">
                        {{ $adminName }}
                    </span>
                    <span class="text-xs text-gray-500">Administrator</span>
                </div>

                {{-- Foto profil --}}
                <button id="profileBtn"
                        class="w-10 h-10 rounded-full overflow-hidden border border-gray-300 shadow">
                    <img src="{{ $avatarUrl }}" alt="Foto Profil" class="w-full h-full object-cover">
                </button>

                {{-- DROPDOWN PROFIL + LOGOUT --}}
                <div id="profileDropdown"
                     class="absolute right-0 top-12 w-48 bg-white shadow-lg border rounded-lg py-2 hidden">

                    <div class="px-4 py-3 border-b">
                        <p class="font-semibold text-gray-800 text-sm">
                            {{ $adminName }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $adminEmail }}
                        </p>
                    </div>

                    <a href="#"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Profil
                    </a>

                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- KONTEN --}}
        <main class="flex-1 p-4 md:p-6">
            @yield('content')
        </main>
    </div>
</div>

<script>
    const sidebar         = document.getElementById('sidebar');
    const mainContent     = document.getElementById('mainContent');
    const toggleBtn       = document.getElementById('toggleSidebar');
    const overlay         = document.getElementById('overlay');
    const profileBtn      = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');

    // === TOGGLE SIDEBAR (ikon ☰) ===
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            const isHidden = sidebar.classList.toggle('hidden');
            mainContent.classList.toggle('ml-64');

            // overlay hanya dipakai di layar kecil
            if (window.innerWidth < 768) {
                if (!isHidden) {
                    overlay.classList.remove('hidden');
                } else {
                    overlay.classList.add('hidden');
                }
            }
        });
    }

    // Klik overlay menutup sidebar di mobile
    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.add('hidden');
            mainContent.classList.remove('ml-64');
            overlay.classList.add('hidden');
        });
    }

    // Pastikan di desktop awalnya sidebar terlihat & konten bergeser
    window.addEventListener('load', () => {
        if (window.innerWidth >= 768) {
            sidebar.classList.remove('hidden');
            mainContent.classList.add('ml-64');
        }
    });

    // === DROPDOWN PROFIL (Profil + Logout) ===
    if (profileBtn && profileDropdown) {
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            profileDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!profileDropdown.classList.contains('hidden') &&
                !profileDropdown.contains(e.target)) {
                profileDropdown.classList.add('hidden');
            }
        });
    }
</script>

</body>
</html>
