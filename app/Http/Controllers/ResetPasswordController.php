<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ]);

        $status = Password::broker('admins')->reset(
            $request->only('email','password','password_confirmation','token'),
            function ($admin, $password) {
                $admin->password = Hash::make($password);
                $admin->save();
            }
        );

        return redirect()->route('admin.login.form')
            ->with('status','Password berhasil diubah. Silakan login.');
    }
}