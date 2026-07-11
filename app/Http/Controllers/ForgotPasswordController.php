<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
//use Illuminate\Support\Facades\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Menampilkan formulir lupa sandi
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        // 1. Validasi input
        $request->validate(['email' => 'required|email']);

        // 2. Laravel otomatis ngecek email, bikin token, dan kirim email!
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // 3. Cek apakah pengiriman berhasil atau gagal
        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', 'Tautan pemulihan kata sandi telah dikirim ke email Anda!');
        }

        // Jika email tidak ditemukan di database
        return back()->withErrors(['email' => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.']);
    }
}
