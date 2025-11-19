<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Kantin Kejujuran</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800 flex min-h-screen">

    <!-- Sidebar -->
    <aside id="sidebar"
           class="bg-[#009688] w-64 p-5 space-y-4 text-white fixed h-full z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold">Admin Panel</h2>
            <button id="closeSidebar" class="md:hidden text-white text-2xl">&times;</button>
        </div>

        <nav class="space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded-lg hover:bg-[#00796B]">🏠 Dashboard</a>
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-[#00796B]">📦 Barang Masuk</a>
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-[#00796B]">🚚 Barang Keluar</a>
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-[#00796B]">👥 Pengguna</a>
            <a href="#" class="block px-4 py-2 rounded-lg hover:bg-[#00796B]">⚙️ Pengaturan</a>
        </nav>
    </aside>

    <!-- Overlay (Mobile only) -->
    <div id="overlay"
         class="fixed inset-0 bg-black bg-opacity-50 hidden md:hidden z-40"></div>

    <!-- Main Content -->
    <div class="flex-1 md:ml-64 w-full">
        <!-- Navbar -->
        <header class="bg-[#FFD600] text-[#004D40] flex justify-between items-center p-4 shadow-md">
            <button id="openSidebar" class="md:hidden text-2xl font-bold">&#9776;</button>
            <h1 class="text-xl font-semibold">Dashboard Admin</h1>

            {{-- FORM LOGOUT --}}
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="bg-[#009688] text-white px-4 py-1 rounded-lg hover:bg-[#00796B]">
                    Logout
                </button>
            </form>
        </header>


        <main class="p-6">
            @yield('content')
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');

        openBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        });

        closeBtn.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    </script>
</body>
</html>
