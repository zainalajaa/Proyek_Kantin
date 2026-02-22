@extends('layouts.admin')

@section('title', 'Profil Admin')

@section('content')

<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

    <!-- Header -->
    <div class="mb-8 text-center">
        <h2 class="text-xl font-semibold text-gray-800">
            Profil Admin
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            Kelola informasi akun Anda
        </p>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm text-center">
            {{ session('success') }}
        </div>
    @endif

    <!-- FOTO PROFIL -->
    <div class="flex flex-col items-center mb-10">

        <div class="relative">
            @if($admin->photo)
                <img src="{{ asset('storage/'.$admin->photo) }}" 
                     class="w-32 h-32 object-cover rounded-full border-4 border-emerald-500 shadow-sm">
            @else
                <img src="https://ui-avatars.com/api/?name={{ $admin->name }}" 
                     class="w-32 h-32 object-cover rounded-full border-4 border-gray-300 shadow-sm">
            @endif
        </div>

        <!-- Tombol Foto -->
        <div class="flex gap-3 mt-5">

            <!-- Ganti Foto -->
            <label class="cursor-pointer inline-flex items-center px-4 py-2 text-sm font-medium 
                        bg-blue-600 text-white rounded-lg shadow-sm
                        hover:bg-blue-700 transition">
                Ganti Foto
                <input type="file" 
                    name="photo" 
                    form="updateProfileForm"
                    class="hidden"
                    onchange="previewImage(event)">
            </label>
            <!-- Hapus Foto -->
            @if($admin->photo)
                <form action="{{ route('admin.profile.photo.delete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium 
                               border border-red-500 text-red-600 rounded-lg
                               hover:bg-red-50 transition">
                        Hapus Foto
                    </button>
                </form>
            @endif

        </div>

    </div>

    <!-- FORM UPDATE -->
    <form id="updateProfileForm"
          action="{{ route('admin.profile.update') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Nama
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ $admin->name }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Email
                </label>
                <input type="email" 
                       name="email" 
                       value="{{ $admin->email }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Password Baru
                </label>
                <input type="password" 
                       name="password"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Konfirmasi Password
                </label>
                <input type="password" 
                       name="password_confirmation"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-emerald-400">
            </div>

        </div>

        <div class="mt-8 text-center">
            <button type="submit"
                class="px-8 py-2.5 bg-emerald-600 text-white text-sm font-semibold
                       rounded-lg shadow-sm hover:bg-emerald-700 transition">
                Simpan Perubahan
            </button>
        </div>

    </form>

</div>

<!-- SCRIPT PREVIEW FOTO -->
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const img = document.querySelector('img.rounded-full');
        img.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

@endsection