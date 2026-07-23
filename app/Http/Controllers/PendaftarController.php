<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Midtrans\Config;
use Midtrans\Snap;

class PendaftarController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $pendaftar = DB::table('pendaftar')->where('id_user', $userId)->first();

        // 1. PASTIKAN BARIS INI ADA UNTUK MENGAMBIL DATA
        $gelombang = DB::table('jadwal_pendaftaran')->get(); 

        $pendaftaran = null;
        $siswaData = null; 

        if ($pendaftar) {
            $pendaftaran = DB::table('pendaftaran')
                ->leftJoin('pengajar', 'pendaftaran.id_pengajar', '=', 'pengajar.id_pengajar')
                ->leftJoin('users', 'pengajar.id_user', '=', 'users.id')
                ->leftJoin('level', 'pendaftaran.id_level_rekomendasi', '=', 'level.id_level')
                ->where('pendaftaran.id_pendaftar', $pendaftar->id_pendaftar)
                ->select('pendaftaran.*', 'users.nama as nama_pengajar', 'level.nama_level')
                ->first();

            if ($pendaftar->status == 'diterima') {
                $siswaData = DB::table('siswa')
                    ->join('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
                    ->join('level', 'siswa.id_level', '=', 'level.id_level')
                    ->where('siswa.id_user', $userId)
                    ->select('siswa.*', 'kelas.nama_kelas', 'level.nama_level')
                    ->first();
            }
        }

        // 2. PASTIKAN KATA 'gelombang' TERTULIS DI DALAM COMPACT
        return view('pendaftar.dashboard', compact('pendaftar', 'pendaftaran', 'siswaData', 'gelombang'));
    }

    public function halamanBiodata($id_jadwal)
    {
        // 1. Ambil data dari tabel berdasarkan $id_jadwal
        $gelombang = DB::table('jadwal_pendaftaran')->where('id_jadwal_daftar', $id_jadwal)->first();
        
        // 2. Jika tidak ketemu, kembalikan ke dashboard
        if (!$gelombang) {
            return redirect('/pendaftar/dashboard')->with('error', 'Gelombang tidak ditemukan.');
        }

        // 3. PASTIKAN BARIS INI BENAR (Mengirim $gelombang ke view)
        return view('pendaftar.biodata', compact('gelombang'));
    }

    public function simpanBiodata(Request $request)
    {
        // Validasi form biodata
        $request->validate([
            'id_jadwal_daftar' => 'required',
            'jenis_kelamin'    => 'required|in:L,P',
            'no_hp'            => 'required|string|max:15',
            'asal_sekolah'     => 'required|string|max:255',
            'tingkat_sekolah'  => 'required|in:SD,SMP,SMA,Kuliah',
            'alamat'           => 'required|string',
        ]);

        $user = Auth::user();

        // 🌟 DI SINILAH PENDAFTARAN SEBENARNYA TERJADI 🌟
        // Data dimasukkan ke tabel pendaftar dengan status 'pending'
        DB::table('pendaftar')->insert([
            'id_user'          => $user->id,
            'nama_lengkap'     => $user->nama ?? $user->name, 
            'jenis_kelamin'    => $request->jenis_kelamin,
            'no_hp'            => $request->no_hp,
            'asal_sekolah'     => $request->asal_sekolah,
            'tingkat_sekolah'  => $request->tingkat_sekolah,
            'alamat'           => $request->alamat,
            'id_jadwal_daftar' => $request->id_jadwal_daftar,
            'tanggal_daftar'   => now(),
            'status'           => 'Menunggu Jadwal' // Ini akan otomatis mengubah tampilan dashboard menjadi "Menunggu Verifikasi"
        ]);

        return redirect('/pendaftar/dashboard')->with('success', 'Pendaftaran berhasil! Silakan tunggu Admin mengatur jadwal Level Test Anda.');
    }

    public function prosesPelunasan()
    {
        $userId = Auth::id();

        // 1. Ubah status di tabel siswa menjadi 'Aktif'
        DB::table('siswa')->where('id_user', $userId)->update([
            'status' => 'aktif'
        ]);

        // 2. UBAH ROLE USER MENJADI SISWA
        DB::table('users')->where('id', $userId)->update([
            'role' => 'siswa'
        ]);

        return redirect('/siswa/dashboard')->with('success', 'Pembayaran Berhasil! Selamat datang di kelas bimbingan SIBIJAR.');
    }

    // Menampilkan halaman form biodata
    public function biodata()
    {
        $userId = Auth::id();
        $pendaftar = DB::table('pendaftar')->where('id_user', $userId)->first();
        $user = DB::table('users')->where('id', $userId)->first();

        return view('pendaftar.biodata', compact('pendaftar', 'user'));
    }
    public function editBiodata()
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        return view('pendaftar.biodata', compact('user', 'pendaftar'));
    }

    // Memproses penyimpanan data biodata
    public function updateBiodata(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'no_hp'           => 'required|numeric',
            'asal_sekolah'    => 'required|string|max:255',
            'tingkat_sekolah' => 'required|in:SD,SMP,SMA,Kuliah',
            'alamat'          => 'required|string',
        ]);

        // Update Tabel Pendaftar
        DB::table('pendaftar')->where('id_user', $userId)->update([
            'nama_lengkap'    => $request->nama_lengkap,
            'no_hp'           => $request->no_hp,
            'asal_sekolah'    => $request->asal_sekolah,
            'tingkat_sekolah' => $request->tingkat_sekolah,
            'alamat'          => $request->alamat,
        ]);

        // Update Tabel Users (agar nama di navbar sinkron)
        DB::table('users')->where('id', $userId)->update([
            'nama' => $request->nama_lengkap
        ]);

        return redirect()->back()->with('success', 'Biodata profil Anda berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Cek jika password lama cocok dengan database
        if (!Hash::check($request->password_lama, $user->password)) {
            return redirect()->back()->with('error', 'Password lama yang Anda masukkan salah.');
        }

        // Update password baru
        DB::table('users')->where('id', $user->id)->update([
            'password' => Hash::make($request->password_baru)
        ]);

        return redirect()->back()->with('success', 'Password akun Anda berhasil diganti!');
    }


    public function pembayaran()
    {
        $userId = Auth::id();

        // 1. Ambil data pendaftar dan LANGSUNG Join ke tabel level
        $pendaftar = DB::table('pendaftar')
            ->leftJoin('level', 'pendaftar.id_level', '=', 'level.id_level')
            ->select('pendaftar.*', 'level.nama_level', 'level.biaya')
            ->where('pendaftar.id_user', $userId)
            ->first();


        if (!$pendaftar) {
            return redirect()->back()->with('error', 'Profil pendaftar tidak ditemukan.');
        }

        $snapToken = null;
        $pembayaran = null;

        // 2. JIKA LEVEL SUDAH DITENTUKAN PENGAJAR -> Cek Tagihan
        if ($pendaftar->id_level) {

            $pembayaran = DB::table('pembayaran')
                ->where('id_pendaftar', $pendaftar->id_pendaftar)
                ->orderBy('created_at', 'desc')
                ->first();

            // Skenario A: Jika belum ada tagihan sama sekali
            if (!$pembayaran) {
                \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
                \Midtrans\Config::$isSanitized = true;
                \Midtrans\Config::$is3ds = true;

                $orderId = 'TRX-' . time() . '-' . $pendaftar->id_pendaftar;
                $jumlahBayar = (int) $pendaftar->biaya;

                $params = [
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => $jumlahBayar,
                    ],
                    'customer_details' => [
                        'first_name' => $pendaftar->nama_lengkap,
                        'phone' => $pendaftar->no_hp,
                    ]
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($params);

                DB::table('pembayaran')->insert([
                    'id_pendaftar'      => $pendaftar->id_pendaftar,
                    'order_id'          => $orderId,
                    'jumlah_bayar'      => $jumlahBayar,
                    'snap_token'        => $snapToken,
                    'status_verifikasi' => 'pending',
                    'tanggal_bayar'     => now(),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);


                $pembayaran = DB::table('pembayaran')->where('order_id', $orderId)->first();
            }

            elseif ($pembayaran->status_verifikasi == 'pending') {
                $snapToken = $pembayaran->snap_token;
            }

        }


        return view('pendaftar.pembayaran', compact('pendaftar', 'snapToken', 'pembayaran'));
    }

    public function cetakInvoice($orderId)
    {
        // 1. Ambil data pembayaran berdasarkan Order ID
        $pembayaran = DB::table('pembayaran')
            ->where('order_id', $orderId)
            ->where('status_verifikasi', 'settlement')
            ->first();

        if (!$pembayaran) {
            return redirect()->back()->with('error', 'Invoice tidak ditemukan atau belum lunas.');
        }

        // 2. Ambil data pendaftar yang melakukan pembayaran
        $pendaftar = DB::table('pendaftar')->where('id_pendaftar', $pembayaran->id_pendaftar)->first();

        // 3. Siapkan data untuk dikirim ke view PDF
        $data = [
            'title' => 'Invoice Pembayaran Elite Academy',
            'pembayaran' => $pembayaran,
            'pendaftar' => $pendaftar,
        ];

        // 4. Render tampilan HTML ke dalam format PDF

        $pdf = Pdf::loadView('pendaftar.invoice', $data);

        return $pdf->stream('Invoice-' . $orderId . '.pdf');
    }
}
