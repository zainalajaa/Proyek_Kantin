<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin | Kantin Kejujuran')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-emerald-50 text-gray-800 overflow-x-hidden">

<div class="relative min-h-screen">

    {{-- SIDEBAR --}}
    <aside id="sidebar"
        class="fixed top-0 left-0 h-full w-64
               bg-gradient-to-b from-emerald-900 via-emerald-800 to-emerald-700
               text-white flex flex-col z-40
               overflow-y-auto
               transform -translate-x-full md:translate-x-0
               transition-transform duration-300 ease-in-out">

        {{-- Logo --}}
        <div class="px-5">
            <img src="{{ asset('storage/images/logo-kj.png') }}"
                 alt="Logo KJ"
                 class="w-24 h-auto object-contain">
        </div>

        <nav class="flex-1 overflow-y-auto space-y-2 text-sm font-medium px-4 pb-6">

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg
               transition-all duration-200 hover:bg-emerald-700
               {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-700 shadow-inner' : '' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span>Dashboard</span>
            </a>

            {{-- Produk --}}
            <a href="{{ route('admin.produk.lihat') }}"
               class="relative flex items-center justify-between px-4 py-3 rounded-xl
               transition-all duration-200 hover:bg-emerald-700
               {{ request()->routeIs('admin.produk.*') ? 'bg-emerald-700 shadow-inner' : '' }}">

                <div class="flex items-center gap-3">
                    <i data-lucide="package" class="w-5 h-5"></i>
                    <span>Produk</span>
                </div>

                @if(isset($totalLowStock) && $totalLowStock > 0)
                    @if($criticalStock > 0)
                        <span class="min-w-[22px] h-6 px-2 text-xs font-bold
                                     bg-red-500 text-white animate-pulse
                                     rounded-full flex items-center justify-center">
                            {{ $totalLowStock }}
                        </span>
                    @else
                        <span class="min-w-[22px] h-6 px-2 text-xs font-semibold
                                     bg-amber-400 text-gray-900
                                     rounded-full flex items-center justify-center">
                            {{ $totalLowStock }}
                        </span>
                    @endif
                @endif
            </a>

            {{-- Penjualan --}}
            <a href="{{ route('admin.penjualan.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg
               transition-all duration-200 hover:bg-emerald-700
               {{ request()->routeIs('admin.penjualan*') ? 'bg-emerald-700 shadow-inner' : '' }}">
                <i data-lucide="wallet" class="w-5 h-5"></i>
                <span>Penjualan</span>
            </a>

            {{-- Monitoring Stok --}}
            <a href="{{ route('admin.monitoring_stok') }}"
               class="relative flex items-center justify-between px-4 py-3 rounded-xl
               transition-all duration-200 hover:bg-emerald-700
               {{ request()->routeIs('admin.monitoring_stok*') ? 'bg-emerald-700 shadow-inner' : '' }}">

                <div class="flex items-center gap-3">
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    <span>Monitoring Stok</span>
                </div>

                @if($monitoringStatus === 'belum_input')
                    <span class="min-w-[22px] h-6 px-2 text-xs font-bold
                                 bg-red-500 text-white animate-pulse
                                 rounded-full flex items-center justify-center">
                        !
                    </span>
                @elseif($monitoringStatus === 'belum_selesai')
                    <span class="min-w-[22px] h-6 px-2 text-xs font-semibold
                                 bg-amber-400 text-gray-900
                                 rounded-full flex items-center justify-center">
                        {{ $sisaMonitoring }}
                    </span>
                @endif
            </a>

            {{-- Riwayat --}}
            <a href="{{ route('admin.monitoring_stok.riwayat') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg
               transition-all duration-200 hover:bg-emerald-700
               {{ request()->routeIs('admin.monitoring_stok.riwayat') ? 'bg-emerald-700 shadow-inner' : '' }}">
                <i data-lucide="history" class="w-5 h-5"></i>
                <span>Riwayat</span>
            </a>

        </nav>
    </aside>

   {{-- Overlay --}}
    <div id="overlay"
         class="fixed inset-0 bg-black/40 hidden z-30 md:hidden"></div>

    {{-- MAIN CONTENT --}}
    <div id="mainContent"
         class="min-h-screen transition-all duration-300">

        @php
            $admin = auth('admin')->user();
            $adminName = $admin->name ?? 'Admin Kantin';
            $avatarUrl = ($admin && $admin->photo)
                ? asset('storage/' . $admin->photo)
                : 'https://ui-avatars.com/api/?name=' . urlencode($adminName);
        @endphp

        {{-- NAVBAR --}}
        <header class="bg-white md:bg-emerald-50 border-b border-emerald-100
                       px-4 md:px-6 py-4
                       flex justify-between items-center sticky top-0 z-20">

            <div class="flex items-center gap-4">

                {{-- TOMBOL TETAP ADA --}}
                <button id="toggleSidebar"
                        class="text-2xl text-emerald-800">
                    ☰
                </button>

                <h1 class="text-base md:text-xl font-semibold text-emerald-900 truncate">
                    @yield('title', 'Dashboard Admin')
                </h1>

            </div>

            {{-- PROFILE --}}
            <div class="flex items-center gap-3 relative">

                <div class="hidden sm:flex flex-col items-end">
                    <span class="text-sm font-semibold text-emerald-900">
                        {{ $adminName }}
                    </span>
                    <span class="text-xs text-emerald-600">
                        Administrator
                    </span>
                </div>

                <button id="profileBtn"
                        class="w-9 h-9 md:w-10 md:h-10 rounded-full overflow-hidden border border-emerald-200 shadow">
                    <img src="{{ $avatarUrl }}" class="w-full h-full object-cover">
                </button>

                <div id="profileDropdown"
                     class="absolute right-0 top-12 w-44 bg-white shadow-xl border rounded-lg py-2 hidden z-50">

                    <a href="{{ route('admin.profile') }}"
                       class="block px-4 py-2 text-sm hover:bg-emerald-50">
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

        {{-- CONTENT --}}
        <main class="p-4 md:p-6">
            <div class="w-full overflow-x-auto">
                @yield('content')
            </div>
        </main>

    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    lucide.createIcons();

    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("overlay");
    const toggleBtn = document.getElementById("toggleSidebar");
    const mainContent = document.getElementById("mainContent");
    const profileBtn = document.getElementById("profileBtn");
    const profileDropdown = document.getElementById("profileDropdown");

    function openSidebar() {
        sidebar.style.transform = "translateX(0)";
        if (window.innerWidth < 768) {
            overlay.classList.remove("hidden");
        }
        mainContent.style.paddingLeft = "16rem";
    }

    function closeSidebar() {
        sidebar.style.transform = "translateX(-100%)";
        overlay.classList.add("hidden");
        mainContent.style.paddingLeft = "0";
    }

    // DEFAULT STATE
    if (window.innerWidth >= 768) {
        openSidebar(); // Desktop sidebar tampil default
    } else {
        closeSidebar(); // Mobile sidebar tersembunyi default
    }

    toggleBtn.addEventListener("click", function () {
        if (sidebar.style.transform === "translateX(-100%)") {
            openSidebar();
        } else {
            closeSidebar();
        }
    });

    overlay.addEventListener("click", closeSidebar);

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