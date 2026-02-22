<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profil', compact('admin'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'password' => 'nullable|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        // Update password jika diisi
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        // =============================
        // UPDATE FOTO PROFIL
        // =============================
        if ($request->hasFile('photo')) {

            // Hapus foto lama jika ada
            if ($admin->photo && Storage::disk('public')->exists($admin->photo)) {
                Storage::disk('public')->delete($admin->photo);
            }

            // Simpan foto baru
            $path = $request->file('photo')->store('admin', 'public');

            $admin->photo = $path;
        }

        $admin->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function deletePhoto()
    {
        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('admin')->user();

        if ($admin && $admin->photo) {

            if (Storage::disk('public')->exists($admin->photo)) {
                Storage::disk('public')->delete($admin->photo);
            }

            $admin->photo = null;
            $admin->save();
        }

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }
}