<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin | Kantin Kejujuran')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-emerald-50 text-gray-800">

<div class="relative min-h-screen">

    {{-- SIDEBAR --}}
    <aside id="sidebar"
        class="fixed top-0 left-0 h-full w-64 
               bg-gradient-to-b from-emerald-900 via-emerald-800 to-emerald-700 
               text-white flex flex-col z-40
               transition-transform duration-300 ease-in-out">

        {{-- Logo --}}
        <div class="px-5">
            <img src="{{ asset('storage/images/logo-kj.png') }}" 
                 alt="Logo KJ"
                 class="w-24 h-auto object-contain">
        </div>

        <nav class="flex-1 space-y-2 text-sm font-medium px-4">

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg 
               transition-all duration-200
               hover:bg-emerald-700
               {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-700 shadow-inner' : '' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5 text-white"></i>
                <span>Dashboard</span>
            </a>

            {{-- Produk --}}
            <a href="{{ route('admin.produk.lihat') }}"
               class="relative group flex items-center justify-between px-4 py-3 rounded-xl
               transition-all duration-200 hover:bg-emerald-700
               {{ request()->routeIs('admin.produk.*') ? 'bg-emerald-700 shadow-inner' : '' }}">

                <div class="flex items-center gap-3">
                    <i data-lucide="package"
                       class="w-5 h-5 text-white"></i>
                    <span class="font-medium">Produk</span>
                </div>

                @if(isset($totalLowStock) && $totalLowStock > 0)
                    @if($criticalStock > 0)
                        <span class="min-w-[22px] h-6 px-2 text-xs font-bold 
                                     bg-red-500 text-white rounded-full 
                                     flex items-center justify-center animate-pulse">
                            {{ $totalLowStock }}
                        </span>
                    @else
                        <span class="min-w-[22px] h-6 px-2 text-xs font-semibold 
                                     bg-amber-400 text-gray-900 rounded-full 
                                     flex items-center justify-center">
                            {{ $totalLowStock }}
                        </span>
                    @endif
                @endif

            </a>

            {{-- Penjualan --}}
            <a href="{{ route('admin.penjualan.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg 
               transition-all duration-200
               hover:bg-emerald-700
               {{ request()->routeIs('admin.penjualan*') ? 'bg-emerald-700 shadow-inner' : '' }}">
                <i data-lucide="wallet" class="w-5 h-5 text-white"></i>
                <span>Penjualan</span>
            </a>

            {{-- Monitoring Stok --}}
            <a href="{{ route('admin.monitoring_stok') }}"
               class="relative group flex items-center justify-between px-4 py-3 rounded-xl
               transition-all duration-200 hover:bg-emerald-700
               {{ request()->routeIs('admin.monitoring_stok*') ? 'bg-emerald-700 shadow-inner' : '' }}">

                <div class="flex items-center gap-3">
                    <i data-lucide="bar-chart-3"
                       class="w-5 h-5 text-white"></i>
                    <span>Monitoring Stok</span>
                </div>

                @if($monitoringStatus === 'belum_input')
                    <span class="min-w-[22px] h-6 px-2 text-xs font-bold 
                                 bg-red-500 text-white rounded-full 
                                 flex items-center justify-center animate-pulse">
                        !
                    </span>
                @elseif($monitoringStatus === 'belum_selesai')
                    <span class="min-w-[22px] h-6 px-2 text-xs font-semibold 
                                 bg-amber-400 text-gray-900 rounded-full 
                                 flex items-center justify-center">
                        {{ $sisaMonitoring }}
                    </span>
                @endif

            </a>

            {{-- Riwayat --}}
            <a href="{{ route('admin.monitoring_stok.riwayat') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg 
               transition-all duration-200
               hover:bg-emerald-700
               {{ request()->routeIs('admin.monitoring_stok.riwayat') ? 'bg-emerald-700 shadow-inner' : '' }}">
                <i data-lucide="history" class="w-5 h-5 text-white"></i>
                <span>Riwayat</span>
            </a>

        </nav>
    </aside>


    {{-- MAIN CONTENT --}}
    <div id="mainContent" class="ml-64 flex flex-col min-h-screen">

        @php
            $admin      = auth('admin')->user();
            $adminName  = $admin->name ?? 'Admin Kantin';
            $adminEmail = $admin->email ?? 'admin@example.com';
            $avatarUrl  = ($admin && $admin->photo)
                ? asset('storage/' . $admin->photo)
                : 'https://ui-avatars.com/api/?name=' . urlencode($adminName) . '&background=065f46&color=ffffff';
        @endphp

        {{-- NAVBAR --}}
        <header class="bg-emerald-50 border-b border-emerald-100 
                       px-6 py-4 flex justify-between items-center relative z-30">

            <div class="flex items-center gap-4">
                <button id="toggleSidebar"
                        class="text-2xl text-emerald-800">
                    ☰
                </button>

                <h1 class="text-lg md:text-xl font-semibold text-emerald-900">
                    @yield('title', 'Dashboard Admin')
                </h1>
            </div>

            {{-- PROFILE --}}
            <div class="flex items-center gap-3 relative">

                <div class="hidden md:flex flex-col items-end leading-tight">
                    <span class="text-sm font-semibold text-emerald-900">
                        {{ $adminName }}
                    </span>
                    <span class="text-xs text-emerald-600">
                        Administrator
                    </span>
                </div>

                <button id="profileBtn"
                        class="w-10 h-10 rounded-full overflow-hidden border border-emerald-200 shadow">
                    <img src="{{ $avatarUrl }}" class="w-full h-full object-cover">
                </button>

                <div id="profileDropdown"
                     class="absolute right-0 top-14 w-44 bg-white shadow-xl border rounded-lg py-2 hidden z-50">

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
        <main class="flex-1 p-6">
            @yield('content')
        </main>

    </div>

</div>


<script>
document.addEventListener("DOMContentLoaded", function () {

    lucide.createIcons();

    const sidebar = document.getElementById("sidebar");
    const mainContent = document.getElementById("mainContent");
    const toggleBtn = document.getElementById("toggleSidebar");
    const profileBtn = document.getElementById("profileBtn");
    const profileDropdown = document.getElementById("profileDropdown");

    let sidebarVisible = true;

    toggleBtn.addEventListener("click", function () {
        if (sidebarVisible) {
            sidebar.style.transform = "translateX(-100%)";
            mainContent.style.marginLeft = "0";
            sidebarVisible = false;
        } else {
            sidebar.style.transform = "translateX(0)";
            mainContent.style.marginLeft = "16rem";
            sidebarVisible = true;
        }
    });

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