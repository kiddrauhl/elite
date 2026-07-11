<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    // Menampilkan halaman form untuk mengetik password baru
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    // Memproses perubahan password di database
    public function reset(Request $request)
    {
        // 1. Validasi input (Pastikan field konfirmasi password bernama 'password_confirmation')
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // 2. Proses pencocokan token dan reset password oleh Laravel
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Jika token valid, ganti password lama dengan yang baru
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        // 3. Cek hasil eksekusi
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Kata sandi Anda berhasil direset! Silakan login dengan kata sandi baru.');
        }

        // Jika token kedaluwarsa atau tidak valid
        return back()->withErrors(['email' => ['Token pengaturan ulang kata sandi ini tidak valid atau sudah kedaluwarsa.']]);
    }
}
