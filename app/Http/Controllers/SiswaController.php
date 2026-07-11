<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    // =======================================================
    // 1. DASHBOARD UTAMA SISWA
    // =======================================================
    public function dashboard()
    {
        // 1. Ambil ID User yang sedang login
        $userId = Auth::id();

        // 2. Ambil data utama Siswa berdasarkan ID User
        $siswa = DB::table('siswa')->where('id_user', $userId)->first();

        $navbarProfil = DB::table('pendaftar')
            ->leftJoin('siswa', 'pendaftar.id_user', '=', 'siswa.id_user')
            ->where('pendaftar.id_user', Auth::id())
            ->select('pendaftar.nama_lengkap', 'siswa.foto_profil')
            ->first();

        // Jika data siswa ditemukan, kita rangkai relasi manualnya agar bisa dibaca oleh View
        if ($siswa) {
            // A. Ambil data Level Siswa
            $siswa->level = DB::table('level')
                ->where('id_level', $siswa->id_level)
                ->first();

            // B. Ambil data Kelas & Pengajar (Jika siswa sudah punya kelas)
            $siswa->kelas = DB::table('kelas')
                ->where('id_kelas', $siswa->id_kelas)
                ->first();

            if ($siswa->kelas) {
                $siswa->kelas->pengajar = DB::table('pengajar')
                    ->where('id_pengajar', $siswa->kelas->id_pengajar)
                    ->first();
            }

        }

        // 3. Ambil riwayat penukaran hadiah terakhir milik siswa ini (Maksimal 5 history terbaru)
        $penukaran = collect(); // Default koleksi kosong jika siswa tidak ditemukan

        if ($siswa) {
            $penukaran = DB::table('penukaran_point')
                ->join('gift', 'penukaran_point.id_gift', '=', 'gift.id_gift')
                ->where('penukaran_point.id_siswa', $siswa->id_siswa)
                ->select('penukaran_point.*', 'gift.nama_gift')
                ->orderBy('penukaran_point.tanggal_penukaran', 'desc')
                ->limit(5)
                ->get();
        }

        $jadwalTerdekat = null;

        if ($siswa && $siswa->id_kelas) {
            // 🌟 AMBIL JADWAL TERDEKAT UNTUK KELAS SISWA INI
            $jadwalTerdekat = DB::table('jadwal_belajar')
                ->where('id_kelas', $siswa->id_kelas)
                ->where('tanggal', '>=', now()->toDateString()) // Ambil jadwal hari ini atau ke depan
                ->orderBy('tanggal', 'asc') // Urutkan dari tanggal paling dekat
                ->orderBy('jam_mulai', 'asc')
                ->first();
        }
        // 4. Lemparkan semua data ke view Dashboard Siswa
        return view('siswa.dashboard', compact('siswa', 'penukaran', 'jadwalTerdekat', 'navbarProfil'));
    }

    // =======================================================
    // 2. HALAMAN RUANG KELAS SISWA
    // =======================================================
    public function kelas()
    {
        $userId = Auth::id();
        $siswa = DB::table('siswa')->where('id_user', $userId)->first();

        $kelas = null;
        $pengajar = null;
        $temanKelas = collect(); // Koleksi kosong secara default

        // 1. INISIALISASI DEFAULT NILAI ABSENSI
        $hadir = 0;
        $izin  = 0;
        $sakit = 0;
        $alfa  = 0;
        $riwayatAbsensi = collect();
        $jadwalTerdekat = null; // Inisialisasi jadwal

        $navbarProfil = DB::table('pendaftar')
            ->leftJoin('siswa', 'pendaftar.id_user', '=', 'siswa.id_user')
            ->where('pendaftar.id_user', Auth::id())
            ->select('pendaftar.nama_lengkap', 'siswa.foto_profil')
            ->first();

        // Pastikan data siswa ada dan sudah memiliki id_kelas
        if ($siswa && $siswa->id_kelas) {

            // 🌟 PERBAIKAN 1: Ambil data kelas beserta nama level HANYA berdasarkan level siswa saat ini
            $kelas = DB::table('kelas')
                ->where('id_kelas', $siswa->id_kelas)
                ->first();

            // Ambil nama level secara terpisah dari tabel level agar lebih akurat
            $levelSiswa = DB::table('level')->where('id_level', $siswa->id_level)->first();
            if ($kelas && $levelSiswa) {
                $kelas->nama_level = $levelSiswa->nama_level;
            }

            // Jika kelas ditemukan, ambil data pengajar dan teman sekelas
            if ($kelas) {
                $pengajar = DB::table('pengajar')->where('id_pengajar', $kelas->id_pengajar)->first();

                // 🌟 PERBAIKAN 2: Ambil daftar teman sekelas yang memiliki id_kelas DAN id_level yang sama
                $temanKelas = DB::table('siswa')
                    ->join('users', 'siswa.id_user', '=', 'users.id')
                    ->join('pendaftar', 'pendaftar.id_user', '=', 'users.id')
                    ->where('siswa.id_kelas', $siswa->id_kelas)
                    ->where('siswa.id_level', $siswa->id_level) // Kunci berdasarkan level!
                    ->where('siswa.id_siswa', '!=', $siswa->id_siswa) // Kecualikan diri sendiri
                    ->select('pendaftar.nama_lengkap', 'siswa.total_point')
                    ->orderBy('siswa.total_point', 'desc')
                    ->get();
            }

            // 2. AMBIL REKAP KEHADIRAN SISWA
            $rekapAbsensi = DB::table('absensi')
                ->select('status', DB::raw('count(*) as total'))
                ->where('id_siswa', $siswa->id_siswa)
                ->where('id_kelas', $siswa->id_kelas)
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $hadir = $rekapAbsensi['hadir'] ?? 0;
            $izin  = $rekapAbsensi['izin'] ?? 0;
            $sakit = $rekapAbsensi['sakit'] ?? 0;
            $alfa  = $rekapAbsensi['alfa'] ?? 0;

            // 3. AMBIL RIWAYAT ABSENSI
            $riwayatAbsensi = DB::table('absensi')
                ->where('id_siswa', $siswa->id_siswa)
                ->where('id_kelas', $siswa->id_kelas)
                ->orderBy('tanggal', 'desc')
                ->limit(10)
                ->get();

            // 🌟 PERBAIKAN 3: Ambil jadwal terdekat berdasarkan id_kelas DAN id_level siswa
            $jadwalTerdekat = DB::table('jadwal_belajar')
                ->where('id_kelas', $siswa->id_kelas)
                ->where('id_level', $siswa->id_level) // Kunci jadwal sesuai level!
                ->where('tanggal', '>=', now()->toDateString())
                ->orderBy('tanggal', 'asc')
                ->orderBy('jam_mulai', 'asc')
                ->first();
        }

        // 4. LEMPARKAN SEMUA VARIABEL BARU KE BLADE
        return view('siswa.kelas', compact(
            'siswa',
            'kelas',
            'pengajar',
            'temanKelas',
            'hadir',
            'izin',
            'sakit',
            'alfa',
            'jadwalTerdekat',
            'riwayatAbsensi',
            'navbarProfil'
        ));
    }

    // =======================================================
    // 3. HALAMAN MATERI BELAJAR SISWA
    // =======================================================
    public function materiIndex()
    {
        $userId = Auth::id();
        $siswa = DB::table('siswa')->where('id_user', $userId)->first();

        $materi = collect(); // Default koleksi kosong
        $kelas = null;

        $navbarProfil = DB::table('pendaftar')
            ->leftJoin('siswa', 'pendaftar.id_user', '=', 'siswa.id_user')
            ->where('pendaftar.id_user', Auth::id())
            ->select('pendaftar.nama_lengkap', 'siswa.foto_profil')
            ->first();

        // Pastikan siswa ada dan sudah masuk kelas
        if ($siswa && $siswa->id_kelas) {
            $kelas = DB::table('kelas')->where('id_kelas', $siswa->id_kelas)->first();

            // Ambil data materi berdasarkan kelas DAN level siswa tersebut
            $materi = DB::table('materi')
                        ->where('id_kelas', $siswa->id_kelas)
                        ->where('id_level', $siswa->id_level) // 🌟 PERBAIKAN: Kunci akses materi sesuai level siswa
                        ->orderBy('created_at', 'desc')
                        ->get();
        }

        return view('siswa.materi', compact('siswa', 'kelas', 'materi', 'navbarProfil'));
    }

    public function downloadMateri($id_materi)
    {
        $materi = DB::table('materi')->where('id_materi', $id_materi)->first();

        // 🌟 UBAH DI SINI: Cek dan panggil file_materi
        if ($materi && $materi->file_materi) {
            $path = 'public/materi/' . $materi->file_materi;

            if (Storage::exists($path)) {
                return Storage::download($path);
            }
        }

        return redirect()->back()->with('error', 'Mohon maaf, file fisik tidak ditemukan di server.');
    }

    // =======================================================
    // 4. HALAMAN PENDAFTARAN ULANG / PEMBAYARAN
    // =======================================================
    public function pembayaran()
    {
        $userId = Auth::id();

        // 1. Mengambil data siswa beserta data pendaftar dan detail level lanjutan
        $siswa = DB::table('siswa')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            ->join('pendaftar', 'pendaftar.id_user', '=', 'users.id')
            ->leftJoin('level as level_kini', 'siswa.id_level', '=', 'level_kini.id_level')
            ->leftJoin('level as level_lanjutan', 'siswa.id_level_lanjutan', '=', 'level_lanjutan.id_level')
            ->where('siswa.id_user', $userId)
            ->select(
                'siswa.*',
                'pendaftar.id_pendaftar',
                'pendaftar.nama_lengkap',
                'users.email',
                'pendaftar.no_hp',
                'level_kini.nama_level as level_sekarang',
                'level_lanjutan.nama_level as level_baru',
                'level_lanjutan.biaya as biaya_baru'
            )
            ->first();

        $navbarProfil = DB::table('pendaftar')
            ->leftJoin('siswa', 'pendaftar.id_user', '=', 'siswa.id_user')
            ->where('pendaftar.id_user', Auth::id())
            ->select('pendaftar.nama_lengkap', 'siswa.foto_profil')
            ->first();

        $snapToken = null;
        $statusPembayaran = null;
        $pembayaran = null;

        // 3. LOGIKA MIDTRANS: Hanya berjalan jika pengajar sudah mengisi id_level_lanjutan
        if ($siswa && $siswa->id_level_lanjutan) {

            // 🌟 PERBAIKAN: Ubah nama variabel dari $tagihan menjadi $pembayaran agar sinkron dengan Blade
            $pembayaran = DB::table('pembayaran')
                ->where('id_pendaftar', $siswa->id_pendaftar)
                ->where('order_id', 'like', 'RE-%')
                ->orderBy('created_at', 'desc')
                ->first();

            if ($pembayaran) {
                $statusPembayaran = $pembayaran->status_verifikasi;

                if ($statusPembayaran == 'pending') {
                    $snapToken = $pembayaran->snap_token;
                }
            } else {
                // Jika belum ada tagihan sama sekali, request token baru ke Midtrans
                \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
                \Midtrans\Config::$isSanitized = true;
                \Midtrans\Config::$is3ds = true;

                $orderId = 'RE-' . time() . '-' . $siswa->id_siswa;
                $jumlahBayar = (int) $siswa->biaya_baru;

                $params = [
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => $jumlahBayar,
                    ],
                    'customer_details' => [
                        'first_name' => $siswa->nama_lengkap,
                        'email' => $siswa->email,
                        'phone' => $siswa->no_hp,
                    ]
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $statusPembayaran = 'pending';

                // Siapkan data untuk diinsert
                $dataBaru = [
                    'id_pendaftar'      => $siswa->id_pendaftar,
                    'order_id'          => $orderId,
                    'jumlah_bayar'      => $jumlahBayar,
                    'snap_token'        => $snapToken,
                    'status_verifikasi' => 'pending',
                    'tanggal_bayar'     => now(),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];

                DB::table('pembayaran')->insert($dataBaru);

                // 🌟 PERBAIKAN: Bungkus array ke object agar Blade bisa membacanya dengan panah ($pembayaran->order_id)
                $pembayaran = (object) $dataBaru;
            }
        }
        return view('siswa.pembayaran', compact('siswa', 'navbarProfil', 'snapToken', 'statusPembayaran', 'pembayaran'));
    }

    public function cetakInvoiceRegistrasi($orderId)
    {
        // 1. Ambil data siswa yang sedang login dan JOIN dengan pendaftar & level
        $userId = Auth::id();
        $siswa = DB::table('siswa')
            ->join('pendaftar', 'siswa.id_user', '=', 'pendaftar.id_user')
            ->leftJoin('level', 'siswa.id_level', '=', 'level.id_level')
            ->where('siswa.id_user', $userId)
            ->select(
                'siswa.*',
                'pendaftar.id_pendaftar',
                'pendaftar.nama_lengkap',
                'level.nama_level'
            )
            ->first();

        if (!$siswa) {
            return redirect()->back()->with('error', 'Akses ditolak. Data siswa tidak ditemukan.');
        }

        // 2. Ambil data pembayaran registrasi ulang berdasarkan Order ID
        $tagihan = DB::table('pembayaran')
            ->where('order_id', $orderId)
            ->where('id_pendaftar', $siswa->id_pendaftar) // 🌟 Properti ini sekarang sudah tersedia
            ->where('status_verifikasi', 'settlement')
            ->first();

        if (!$tagihan) {
            return redirect()->back()->with('error', 'Invoice tidak valid atau pembayaran belum lunas.');
        }

        // 3. Siapkan array data untuk dikirim ke view PDF
        $data = [
            'pembayaran' => $tagihan,
            'siswa' => $siswa,
        ];

        // 4. Render tampilan HTML ke dalam format PDF
        $pdf = Pdf::loadView('siswa.invoice', $data);

        return $pdf->stream('Invoice-Registrasi-Ulang-' . $orderId . '.pdf');
    }

    // =======================================================
    // 5. HALAMAN KATALOG & PENUKARAN GIFT (POIN STARS)
    // =======================================================
    public function katalogGift()
    {
        $userId = Auth::id();
        $siswa = DB::table('siswa')->where('id_user', $userId)->first();
        $navbarProfil = DB::table('pendaftar')
            ->leftJoin('siswa', 'pendaftar.id_user', '=', 'siswa.id_user')
            ->where('pendaftar.id_user', Auth::id())
            ->select('pendaftar.nama_lengkap', 'siswa.foto_profil')
            ->first();

        // Ambil daftar hadiah dari database, urutkan dari poin termurah
        $gifts = DB::table('gift')->orderBy('poin_dibutuhkan', 'asc')->get();

        // Ambil riwayat penukaran khusus milik siswa ini
        $riwayat = DB::table('penukaran_point')
            ->join('gift', 'penukaran_point.id_gift', '=', 'gift.id_gift')
            ->where('penukaran_point.id_siswa', $siswa->id_siswa)
            ->select('penukaran_point.*', 'gift.nama_gift', 'gift.poin_dibutuhkan')
            ->orderBy('penukaran_point.tanggal_penukaran', 'desc')
            ->get();

        return view('siswa.gift', compact('siswa', 'gifts', 'riwayat', 'navbarProfil'));
    }

    public function prosesTukarPoint($id_gift)
    {
        $userId = Auth::id();
        $siswa = DB::table('siswa')->where('id_user', $userId)->first();
        $gift = DB::table('gift')->where('id_gift', $id_gift)->first();

        // Keamanan 1: Pastikan poin mencukupi
        if ($siswa->total_point < $gift->poin_dibutuhkan) {
            return redirect()->back()->with('error', 'Poin kamu tidak mencukupi untuk hadiah ini.');
        }

        // Keamanan 2: Pastikan stok masih ada
        if ($gift->stok < 1) {
            return redirect()->back()->with('error', 'Maaf, stok merchandise ini sedang habis.');
        }

        // Jika aman, masukkan data ke antrean penukaran (Status: Menunggu)
        DB::table('penukaran_point')->insert([
            'id_siswa'          => $siswa->id_siswa,
            'id_gift'           => $gift->id_gift,
            'tanggal_penukaran' => date('Y-m-d'), // Menyimpan tanggal hari ini
            'status'            => 'proses',
            'created_at'        => now(),
            'updated_at'        => now()
        ]);

        return redirect()->back()->with('success', 'Pengajuan penukaran berhasil dikirim! Silakan tunggu konfirmasi Admin.');
    }

    // =======================================================
    // 6. HALAMAN PROFIL SISWA
    // =======================================================
    public function profil()
    {
        $userId = Auth::id();

        $profil = DB::table('users')
            ->join('pendaftar', 'users.id', '=', 'pendaftar.id_user')
            ->leftJoin('siswa', 'users.id', '=', 'siswa.id_user')
            ->where('users.id', $userId)
            ->select('users.nama', 'users.email', 'pendaftar.*', 'siswa.total_point', 'siswa.foto_profil')
            ->first();

        return view('siswa.profil', compact('profil'));
    }

    public function update(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'no_hp'         => 'nullable|string|max:20',
            'asal_sekolah'  => 'nullable|string|max:255',
            'alamat'        => 'nullable|string',
            'foto_profil'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $userId = Auth::id();

        // 2. Siapkan & Update data TEKS ke tabel 'pendaftar'
        $dataPendaftar = [
            'nama_lengkap'  => $request->nama_lengkap,
            'tanggal_lahir' => $request->tanggal_lahir,
            'no_hp'         => $request->no_hp,
            'asal_sekolah'  => $request->asal_sekolah,
            'alamat'        => $request->alamat,
            'updated_at'    => now()
        ];

        DB::table('pendaftar')->where('id_user', $userId)->update($dataPendaftar);

        // 🌟 BARU: Ikut update kolom 'nama' di tabel 'users' agar data akun & profil tetap sinkron
        DB::table('users')->where('id', $userId)->update([
            'nama'       => $request->nama_lengkap,
            'updated_at' => now()
        ]);

        // 🌟 BARU: Logika enkripsi dan simpan password baru jika kolom diisi oleh siswa
        if ($request->filled('password')) {
            DB::table('users')->where('id', $userId)->update([
                'password'   => \Illuminate\Support\Facades\Hash::make($request->password), // Di-hash demi keamanan
                'updated_at' => now()
            ]);
        }

        // 3. Logika Upload & Update FOTO ke tabel 'siswa'
        if ($request->hasFile('foto_profil')) {

            // Cek foto lama di tabel SISWA
            $siswaLama = DB::table('siswa')->where('id_user', $userId)->first();

            if ($siswaLama && isset($siswaLama->foto_profil)) {
                Storage::disk('public')->delete($siswaLama->foto_profil);
            }

            // Simpan foto baru ke folder storage
            $pathFoto = $request->file('foto_profil')->store('profil_siswa', 'public');

            // 🌟 PERBAIKAN: Gunakan updateOrInsert agar anti-gagal
            DB::table('siswa')->updateOrInsert(
                ['id_user' => $userId], // Kunci pencarian
                ['foto_profil' => $pathFoto] // Data yang diisi/diubah
            );
        }



        // 4. Kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Profil anda berhasil diperbarui!');
    }

    // =======================================================
    // 7. HALAMAN E-RAPORT SISWA
    // =======================================================
    public function raport()
    {
        $userId = Auth::id();
        $siswa = DB::table('siswa')->where('id_user', $userId)->first();

        $raportList = collect();

        $navbarProfil = DB::table('pendaftar')
            ->leftJoin('siswa', 'pendaftar.id_user', '=', 'siswa.id_user')
            ->where('pendaftar.id_user', Auth::id())
            ->select('pendaftar.nama_lengkap', 'siswa.foto_profil')
            ->first();

        if ($siswa) {
            // Ambil semua raport milik siswa ini, digabungkan dengan info kelas dan pengajar
            $raportList = DB::table('raport')
                ->join('kelas', 'raport.id_kelas', '=', 'kelas.id_kelas')
                ->join('pengajar', 'raport.id_pengajar', '=', 'pengajar.id_pengajar')
                ->join('users', 'pengajar.id_user', '=', 'users.id') // Ambil nama instruktur
                ->leftJoin('level', 'kelas.id_level', '=', 'level.id_level')
                ->where('raport.id_siswa', $siswa->id_siswa)
                ->select('raport.*', 'kelas.nama_kelas', 'level.nama_level', 'users.nama as nama_pengajar')
                ->orderBy('raport.tanggal_dibagikan', 'desc')
                ->get();
        }

        return view('siswa.raport', compact('raportList', 'navbarProfil'));
    }

    public function cetakRaport($id_kelas)
    {
        $userId = Auth::id();
        $siswa = DB::table('siswa')->where('id_user', $userId)->first();

        // Ambil data raport spesifik beserta biodata lengkap siswa
        $raport = DB::table('raport')
                ->join('kelas', 'raport.id_kelas', '=', 'kelas.id_kelas')
                ->join('pengajar', 'raport.id_pengajar', '=', 'pengajar.id_pengajar')
                ->join('users', 'pengajar.id_user', '=', 'users.id')
                // 🌟 TAMBAHAN KUNCI: Panggil tabel siswa dulu sebelum join ke pendaftar
                ->join('siswa', 'raport.id_siswa', '=', 'siswa.id_siswa')
                // Sekarang pemanggilan 'siswa.id_user' di bawah ini menjadi legal dan terbaca sistem
                ->join('pendaftar', 'siswa.id_user', '=', 'pendaftar.id_user')
                ->leftJoin('level', 'kelas.id_level', '=', 'level.id_level')
                ->where('raport.id_kelas', $id_kelas)
                ->where('raport.id_siswa', $siswa->id_siswa)
                ->select(
                    'raport.*',
                    'kelas.nama_kelas',
                    'level.nama_level',
                    'users.nama as nama_pengajar',
                    'pendaftar.nama_lengkap'
                )
                ->first();

        $navbarProfil = DB::table('pendaftar')
            ->leftJoin('siswa', 'pendaftar.id_user', '=', 'siswa.id_user')
            ->where('pendaftar.id_user', Auth::id())
            ->select('pendaftar.nama_lengkap', 'siswa.foto_profil')
            ->first();

        if(!$raport) {
            return redirect()->back()->with('error', 'Dokumen Raport tidak ditemukan.');
        }

        return view('siswa.raport_cetak', compact('raport', 'siswa', 'navbarProfil'));
    }

    public function sertifikat()
    {
        $userId = Auth::id();

        $siswa = DB::table('siswa')
            ->join('pendaftar', 'siswa.id_user', '=', 'pendaftar.id_user')
            ->where('siswa.id_user', $userId)
            ->first();

        return view('siswa.sertifikat', compact('siswa'));
    }
}
