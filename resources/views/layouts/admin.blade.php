<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin | Kantin Kejujuran')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-800">

<div class="flex min-h-screen relative">

    {{-- SIDEBAR --}}
    <aside id="sidebar"
        class="fixed top-0 left-0 h-full w-64 bg-[#009688] text-white p-5 z-50
               transition-transform duration-300 ease-in-out flex flex-col">

        <h2 class="text-xl font-bold mb-8">Admin Panel</h2>

        <nav class="space-y-2 text-sm font-medium flex-1">
            <a href="{{ route('admin.dashboard') }}"
               class="block px-4 py-2 rounded hover:bg-[#00796B]
               {{ request()->routeIs('admin.dashboard') ? 'bg-[#00796B]' : '' }}">
                🏠 Dashboard
            </a>

            <a href="{{ route('admin.produk.lihat') }}"
               class="block px-4 py-2 rounded hover:bg-[#00796B]
               {{ request()->routeIs('admin.produk.*') ? 'bg-[#00796B]' : '' }}">
                📦 Produk
            </a>

            <a href="{{ route('admin.penjualan.index') }}"
               class="block px-4 py-2 rounded hover:bg-[#00796B]
               {{ request()->routeIs('admin.penjualan*') ? 'bg-[#00796B]' : '' }}">
                💰 Penjualan
            </a>

            <a href="{{ route('admin.monitoring_stok') }}"
               class="block px-4 py-2 rounded hover:bg-[#00796B]
               {{ request()->routeIs('admin.monitoring_stok') ? 'bg-[#00796B]' : '' }}">
                📊 Monitoring Stok
            </a>

            <a href="{{ route('admin.monitoring_stok.riwayat') }}"
               class="block px-4 py-2 rounded hover:bg-[#00796B]">
                📅 Riwayat Selisih Stok
            </a>
        </nav>
    </aside>

    {{-- OVERLAY --}}
    <div id="overlay"
         class="fixed inset-0 bg-black bg-opacity-40 hidden z-40"></div>

    @php
        $admin      = auth('admin')->user();
        $adminName  = $admin->name  ?? 'Admin Kantin';
        $adminEmail = $admin->email ?? 'admin@example.com';

        $avatarUrl  = ($admin && isset($admin->photo) && $admin->photo)
            ? asset('storage/' . $admin->photo)
            : 'https://ui-avatars.com/api/?name=' . urlencode($adminName) . '&background=009688&color=fff';
    @endphp

    {{-- MAIN CONTENT --}}
    <div id="mainContent"
         class="flex-1 flex flex-col min-h-screen transition-all duration-300">

        {{-- NAVBAR --}}
        <header class="bg-white shadow-sm border-b px-6 py-3 sticky top-0 z-30 flex justify-between items-center">

            <div class="flex items-center gap-4">
                <button id="toggleSidebar"
                        class="text-2xl text-gray-700">
                    ☰
                </button>

                <h1 class="text-lg md:text-xl font-semibold text-gray-800">
                    @yield('title', 'Dashboard Admin')
                </h1>
            </div>

            {{-- PROFILE --}}
            <div class="relative flex items-center gap-3">

                <div class="hidden md:flex flex-col items-end">
                    <span class="text-sm font-semibold">{{ $adminName }}</span>
                    <span class="text-xs text-gray-500">Administrator</span>
                </div>

                <button id="profileBtn"
                        class="w-10 h-10 rounded-full overflow-hidden border shadow">
                    <img src="{{ $avatarUrl }}" class="w-full h-full object-cover">
                </button>

                <div id="profileDropdown"
                     class="absolute right-0 top-12 w-48 bg-white shadow-lg border rounded-lg py-2 hidden">

                    <div class="px-4 py-3 border-b">
                        <p class="font-semibold text-sm">{{ $adminName }}</p>
                        <p class="text-xs text-gray-500">{{ $adminEmail }}</p>
                    </div>

                    <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100">
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

        <main class="flex-1 p-4 md:p-6">
            @yield('content')
        </main>

    </div>
</div>

{{-- SCRIPT --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const sidebar = document.getElementById("sidebar");
    const mainContent = document.getElementById("mainContent");
    const toggleBtn = document.getElementById("toggleSidebar");
    const overlay = document.getElementById("overlay");
    const profileBtn = document.getElementById("profileBtn");
    const profileDropdown = document.getElementById("profileDropdown");

    let isOpen = false;

    function openSidebar() {
        sidebar.style.transform = "translateX(0)";
        if (window.innerWidth >= 768) {
            mainContent.style.marginLeft = "16rem";
        }
        if (window.innerWidth < 768) {
            overlay.classList.remove("hidden");
        }
        isOpen = true;
    }

    function closeSidebar() {
        sidebar.style.transform = "translateX(-100%)";
        mainContent.style.marginLeft = "0";
        overlay.classList.add("hidden");
        isOpen = false;
    }

    // Default state
    if (window.innerWidth >= 768) {
        openSidebar();
    } else {
        closeSidebar();
    }

    toggleBtn.addEventListener("click", function () {
        if (isOpen) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    overlay.addEventListener("click", function () {
        closeSidebar();
    });

    document.querySelectorAll("#sidebar a").forEach(link => {
        link.addEventListener("click", function () {
            if (window.innerWidth < 768) {
                closeSidebar();
            }
        });
    });

    // Profile dropdown
    profileBtn.addEventListener("click", function (e) {
        e.stopPropagation();
        profileDropdown.classList.toggle("hidden");
    });

    document.addEventListener("click", function (e) {
        if (!profileDropdown.contains(e.target) && e.target !== profileBtn) {
            profileDropdown.classList.add("hidden");
        }
    });

});
</script>

</body>
</html>
