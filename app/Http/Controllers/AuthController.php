<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pendaftar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Pastikan DB dipanggil untuk insert tabel pendaftar

class AuthController extends Controller
{
    // Proses Registrasi Pendaftar Baru
    public function register(Request $request)
    {
        // 1. Validasi Input (Primary key disesuaikan ke id_jadwal_daftar)
        $request->validate([
            'nama'            => 'required|string|max:255',
            'email'           => 'required|string|email|max:255|unique:users',
            'password'        => 'required|string|min:8|confirmed',
            'jenis_kelamin'   => 'required|in:L,P',
            'no_hp'           => 'required|string|max:15',
            'asal_sekolah'    => 'required|string|max:255',
            'tingkat_sekolah' => 'required|in:SD,SMP,SMA,Kuliah',
            'alamat'          => 'required|string',
            'jadwal_daftar'   => 'required|exists:jadwal_pendaftaran,id_jadwal_daftar', // Poin koreksi key
        ], [
            'jadwal_daftar.required' => 'Silakan pilih gelombang pendaftaran yang tersedia.',
            'jadwal_daftar.exists'   => 'Gelombang pendaftaran yang dipilih tidak valid atau sudah ditutup.'
        ]);

        return DB::transaction(function () use ($request) {

            // 2. Simpan ke Tabel Users
            $user = User::create([
                'nama'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'pendaftar',
                'status'   => 'aktif',
            ]);

            // 3. Simpan ke Tabel Pendaftar
            Pendaftar::create([
                'id_user'         => $user->id,
                'nama_lengkap'    => $user->nama,
                'jenis_kelamin'   => $request->jenis_kelamin,
                'no_hp'           => $request->no_hp,
                'asal_sekolah'    => $request->asal_sekolah,
                'tingkat_sekolah' => $request->tingkat_sekolah,
                'alamat'          => $request->alamat,
                'id_jadwal_daftar' => $request->jadwal_daftar, // ID gelombang yang dipilih siswa
                'tanggal_daftar'  => now(),
                'status'          => 'Menunggu Jadwal'
            ]);

            Auth::login($user);

            return redirect('/pendaftar/dashboard')->with('success', 'Akun berhasil dibuat! Selamat datang di Elite English.');
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

    // Proses Login
    public function login(Request $request) // Sesuaikan dengan nama fungsi Anda
    {
        // 1. Validasi input form
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 2. Cari apakah email tersebut ada di database
        $user = User::where('email', $request->email)->first();

        // 3. Jika Email TIDAK DITEMUKAN
        if (!$user) {
            return back()->withErrors([
                'email' => 'Alamat email ini belum terdaftar di sistem.',
            ])->withInput($request->only('email', 'remember'));
        }

        // 4. Jika Email ada, cek kecocokan PASSWORD
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password yang Anda masukkan salah.',
            ])->withInput($request->only('email', 'remember'));
        }

        // 5. Jika Email dan Password BENAR, eksekusi login
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {

            $request->session()->regenerate();

            // Cek role user yang login, lalu arahkan ke dashboard masing-masing
            $userRole = Auth::user()->role; // Sesuaikan 'role' dengan nama kolom di database Anda

            if ($userRole == 'admin') {
                return redirect()->intended('/admin/dashboard'); // Ganti dengan URL Admin Anda
            } elseif ($userRole == 'pengajar') {
                return redirect()->intended('/pengajar/dashboard'); // Ganti dengan URL Pengajar Anda
            } elseif ($userRole == 'pendaftar') {
                return redirect()->intended('/pendaftar/dashboard'); // Ganti dengan URL Pengajar Anda
            } elseif ($userRole == 'siswa') {
                return redirect()->intended('/siswa/dashboard'); // Ganti dengan URL Siswa Anda
            }

            // Fallback jika role tidak terdaftar
            return redirect('/');
        }

        // Fallback jika terjadi error sistem yang tidak terduga
        return back()->withErrors([
            'email' => 'Terjadi kesalahan saat mencoba masuk.',
        ]);
    }

    // Proses Keluar (Logout)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
