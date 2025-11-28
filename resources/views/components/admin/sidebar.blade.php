<nav class="space-y-2 text-sm font-medium flex-1">

    <a href="{{ route('admin.dashboard') }}" 
       class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#00796B]">
        🏠 Dashboard
    </a>

    <a href="{{ route('admin.produk') }}"
       class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#00796B]">
        🍜 Produk (Admin)
    </a>

    <a href="{{ route('produk.public.index') }}"
       class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#00796B]">
        🛒 Lihat Produk
    </a>

</nav>
