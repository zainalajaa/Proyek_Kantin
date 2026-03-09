<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\Admin;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return back()->withErrors([
                'email' => 'Email yang dimasukkan salah.'
            ]);
        }

        // buat token reset
        $token = Str::random(64);

        // simpan ke tabel password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => bcrypt($token),
                'created_at' => now()
            ]
        );

        // buat link reset
        $resetLink = url('/reset-password/'.$token.'?email='.$request->email);

        return back()->with([
            'status' => 'Link reset password berhasil dibuat.',
            'reset_link' => $resetLink
        ]);
    }
}