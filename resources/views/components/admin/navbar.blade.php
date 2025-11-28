{{-- resources/views/components/admin/navbar.blade.php --}}
@props(['adminName', 'adminEmail', 'avatarUrl'])

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

        <button id="profileBtn"
                class="w-10 h-10 rounded-full overflow-hidden border border-gray-300 shadow">
            <img src="{{ $avatarUrl }}" alt="Foto Profil" class="w-full h-full object-cover">
        </button>

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
