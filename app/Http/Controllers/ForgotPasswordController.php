<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email'
        ], [
            'email.exists' => 'Email tidak terdaftar sebagai admin.'
        ]);

        // kirim link reset ke email via Gmail SMTP
        $status = Password::broker('admins')->sendResetLink(
            $request->only('email')
        );

        // cek hasil
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Link reset password sudah dikirim ke email.');
        }

        return back()->withErrors([
            'email' => 'Gagal mengirim email reset password.'
        ]);
    }
}