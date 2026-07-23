<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pendaftar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        // 1. Validasi HANYA untuk pembuatan Akun (User)
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        return DB::transaction(function () use ($request) {
            
            // 2. Generate OTP
            $otpCode = rand(100000, 999999);

            // 3. HANYA Simpan ke Tabel Users
            $user = User::create([
                'nama'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'pendaftar', // Role tetap pendaftar untuk akses dashboard
                'status'   => 'aktif',
                'otp'      => $otpCode,
            ]);

            // ❌ Pendaftar::create(...) DIHAPUS TOTAL DARI SINI ❌

            // 4. Kirim Email OTP
            \Illuminate\Support\Facades\Mail::send('emails.otp', ['otpCode' => $otpCode], function($message) use($request){
                $message->to($request->email);
                $message->subject('Verifikasi Akun - Kode OTP');
            });

            session(['verifikasi_email' => $request->email]);
            return redirect('/verifikasi-otp')->with('success', 'Akun berhasil dibuat! Silakan cek email Anda untuk verifikasi.');
        });
    }

    // Menampilkan Halaman Register
    public function showRegistrationForm()
    {
        $hariIni = now()->format('Y-m-d');

        $gelombangForm = DB::table('jadwal_pendaftaran')
            ->where('status', 'buka')
            ->where('tanggal_mulai', '<=', $hariIni)
            ->where('tanggal_selesai', '>=', $hariIni)
            ->get();

        return view('auth.register', compact('gelombangForm'));
    }

    public function halamanOtp()
    {
        
        if (!session('verifikasi_email')) {
            return redirect('/register');
        }
        return view('auth.otp');
    }

    public function prosesOtp(Request $request)
    {
        $email = session('verifikasi_email');
        $user = User::where('email', $email)->first();

        // Validasi apakah OTP cocok
        if ($user && $user->otp == $request->otp) {
            // Hapus OTP agar tidak bisa dipakai ulang
            $user->update(['otp' => null]);

            // Hapus session sementara
            session()->forget('verifikasi_email');

            // LAKUKAN LOGIN DI SINI
            Auth::login($user);

            return redirect('/pendaftar/dashboard')->with('success', 'Email berhasil diverifikasi! Selamat datang di Elite English.');
        }

        // Jika OTP salah
        return back()->with('error', 'Kode OTP salah. Silakan coba lagi.');
    }

    // Proses Login
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);


        $user = User::where('email', $request->email)->first();


        if (!$user) {
            return back()->withErrors([
                'email' => 'Alamat email ini belum terdaftar di sistem.',
            ])->withInput($request->only('email', 'remember'));
        }


        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password yang Anda masukkan salah.',
            ])->withInput($request->only('email', 'remember'));
        }


        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {

            $request->session()->regenerate();

            $userRole = Auth::user()->role;

            if ($userRole == 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($userRole == 'pengajar') {
                return redirect()->intended('/pengajar/dashboard');
            } elseif ($userRole == 'pendaftar') {
                return redirect()->intended('/pendaftar/dashboard');
            } elseif ($userRole == 'siswa') {
                return redirect()->intended('/siswa/dashboard');
            }


            return redirect('/');
        }


        return back()->withErrors([
            'email' => 'Terjadi kesalahan saat mencoba masuk.',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
