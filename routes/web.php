<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\PengajarController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\HomeController;






// Halaman Beranda (Landing Page)
Route::get('/', [HomeController::class, 'index']);

// Halaman Daftar Program/Level
Route::get('/program', function () {
    return view('program');
});

// Route Tampilan (GET)
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::get('/register', function () { return view('auth.register'); });

// Route Proses Data (POST)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
// Rute untuk menampilkan halaman lupa sandi (GET)
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Rute untuk memproses pengiriman email/token pemulihan (POST)
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

// 4. Memproses penyimpanan password baru ke database
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


// Rute untuk halaman verifikasi pendaftar baru
Route::get('/admin/verifikasi', function () {
        return view('admin.verifikasi');
    });

    // Rute untuk menampilkan halaman List Jadwal yang baru
    Route::get('/admin/jadwal', [AdminController::class, 'jadwalIndex'])->name('admin.jadwal.index');

    // Rute proses verifikasi (pastikan diarahkan ke fungsi yang benar)
    Route::post('/admin/verifikasi/jadwal/{id}', [AdminController::class, 'verifikasiJadwal'])->name('admin.verifikasi.jadwal');



    // ROUTE KHUSUS ADMIN (Dilindungi Middleware)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    // Halaman Dashboard Admin: /admin/dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard']);

    // Rute Detail & Update Status
    Route::get('/pendaftar/{id}', [AdminController::class, 'showPendaftar']);
    Route::post('/pendaftar/{id}/status', [AdminController::class, 'updateStatus']);
    Route::get('/verifikasi', [AdminController::class, 'verifikasi']);
    Route::get('/pantau-pembayaran', [AdminController::class, 'pantauPembayaran'])->name('admin.pantau_pembayaran');

    Route::get('/siswa', [AdminController::class, 'siswaIndex'])->name('admin.siswa.index');
    // 1. Proses Update Data Siswa (Sesuai action Javascript modal Anda)
    Route::put('/siswa/update/{id}', [AdminController::class, 'siswaUpdate']);
    // 2. Proses Hapus Data Siswa (Sesuai action Javascript modal Anda)
    Route::delete('/siswa/delete/{id}', [AdminController::class, 'siswaDelete']);
    Route::get('siswa', [AdminController::class, 'siswaIndex']);
    Route::get('/siswa/export-excel', [AdminController::class, 'exportExcel']);
    Route::get('/siswa/export-pdf', [AdminController::class, 'exportPdf']);

    Route::get('/siswa/penempatan-lanjutan', [AdminController::class, 'penempatanLanjutan']);
    Route::get('/siswa/penempatan-lanjutan/pilih-kelas/{id_siswa}', [AdminController::class, 'pilihKelasLanjutan']);
    Route::post('/siswa/penempatan-lanjutan/simpan/{id_siswa}', [AdminController::class, 'simpanPenempatanLanjutan']);
    Route::get('/siswa/penempatan-lanjutan/export-excel', [AdminController::class, 'exportExcelPenempatan']);
    Route::get('/siswa/penempatan-lanjutan/export-pdf', [AdminController::class, 'exportPdfPenempatan']);

    // ========================================================
    // MANAJEMEN KELAS (CRUD LENGKAP ADMIN)
    // ========================================================
    // 1. Tampilkan Halaman Utama Kelas
    Route::get('/kelas', [AdminController::class, 'kelasIndex'])->name('admin.kelas.index');
    // 2. Proses Tambah Kelas Baru (Form Modal Create)
    Route::post('/kelas', [AdminController::class, 'kelasStore'])->name('admin.kelas.store');
    // 3. Proses Perbarui Data Kelas (Form Modal Update)
    Route::put('/kelas/update/{id}', [AdminController::class, 'kelasUpdate'])->name('admin.kelas.update');
    // 4. Proses Hapus Data Kelas (Form Modal Delete)
    Route::delete('/kelas/delete/{id}', [AdminController::class, 'kelasDelete'])->name('admin.kelas.delete');

    // ========================================================
    // MANAJEMEN DATA PENGAJAR (CRUD ADMIN)
    // ========================================================
    Route::get('/pengajar', [AdminController::class, 'pengajarIndex'])->name('admin.pengajar.index');
    Route::post('/pengajar', [AdminController::class, 'pengajarStore'])->name('admin.pengajar.store');
    Route::put('/pengajar/update/{id}', [AdminController::class, 'pengajarUpdate'])->name('admin.pengajar.update');
    Route::delete('/pengajar/delete/{id}', [AdminController::class, 'pengajarDelete'])->name('admin.pengajar.delete');

    // ========================================================
    // REWARD SYSTEM: KELOLA HADIAH & VERIFIKASI PENUKARAN POIN
    // ========================================================
    // 1. Jalur Sub-Menu Master Data Hadiah (CRUD)
    Route::get('/gift', [AdminController::class, 'giftIndex'])->name('admin.gift.index');
    Route::post('/gift', [AdminController::class, 'giftStore'])->name('admin.gift.store');
    Route::put('/gift/update/{id}', [AdminController::class, 'giftUpdate'])->name('admin.gift.update');
    Route::delete('/gift/delete/{id}', [AdminController::class, 'giftDelete'])->name('admin.gift.delete');

    // 2. Jalur Sub-Menu Verifikasi Pengajuan Siswa (ACC/TOLAK)
    Route::get('/pengajuan-gift', [AdminController::class, 'pengajuanGiftIndex'])->name('admin.pengajuan_gift.index');
    Route::post('/pengajuan-gift/setujui/{id}', [AdminController::class, 'pengajuanGiftSetujui'])->name('admin.pengajuan_gift.setujui');
    Route::post('/pengajuan-gift/tolak/{id}', [AdminController::class, 'pengajuanGiftTolak'])->name('admin.pengajuan_gift.tolak');
    Route::get('/admin/riwayat-gift', [AdminController::class, 'riwayatGiftIndex'])->name('admin.gift.riwayat');

    Route::get('/gelombang', [AdminController::class, 'gelombangIndex'])->name('admin.gelombang.index');
    Route::post('/gelombang', [AdminController::class, 'jadwalStore'])->name('admin.gelombang.store');
    Route::put('/gelombang/update/{id}', [AdminController::class, 'jadwalUpdate'])->name('admin.gelombang.update');
    Route::delete('/gelombang/delete/{id}', [AdminController::class, 'jadwalDelete'])->name('admin.gelombang.delete');

    // Rute Penempatan Kelas & Level Pendaftar (Admin)
    Route::get('/penempatan', [AdminController::class, 'penempatanIndex'])->name('admin.penempatan.index');
    Route::post('/penempatan/proses/{id}', [AdminController::class, 'prosesPenempatan'])->name('admin.penempatan.proses');

    Route::get('/jadwalbelajar', [AdminController::class, 'jadwalBelajarIndex'])->name('admin.jadwalbelajar.index');
    Route::post('/jadwalbelajar/simpan', [AdminController::class, 'jadwalSimpan'])->name('admin.jadwalbelajar.simpan');
    Route::post('/jadwalbelajar/hapus/{id}', [AdminController::class, 'jadwalHapus'])->name('admin.jadwalbelajar.hapus');
    Route::get('/jadwalbelajar/edit/{id}', [AdminController::class, 'jadwalEdit'])->name('admin.jadwalbelajar.edit');
    Route::post('/jadwalbelajar/update/{id}', [AdminController::class, 'jadwalBelajarUpdate'])->name('admin.jadwalbelajar.update');

    Route::get('/laporan-perkembangan', [AdminController::class, 'laporanPerkembangan'])->name('laporan.perkembangan');

    Route::get('/profil', [AdminController::class, 'profil'])->name('admin.profil.index');
    Route::post('/profil/update', [AdminController::class, 'updateProfil'])->name('admin.profil.update');

    Route::get('/alumni', [App\Http\Controllers\AdminController::class, 'dataAlumni']);
    Route::get('/alumni/terbitkan/{id_siswa}', [AdminController::class, 'terbitkanSertifikat']);

    });

// ROUTE KHUSUS PENDAFTAR (Dilindungi Middleware)
Route::middleware(['auth', 'role:pendaftar'])->prefix('pendaftar')->group(function () {

    // Halaman Dashboard Pendaftar: /pendaftar/dashboard
    Route::get('/dashboard', [PendaftarController::class, 'dashboard']);

    // Rute Tambahan Pendaftar
    Route::get('/biodata', [PendaftarController::class, 'biodata'])->name('pendaftar.biodata');
    Route::post('/biodata/update', [PendaftarController::class, 'updateBiodata'])->name('pendaftar.biodata.update');
    Route::post('/password/update', [PendaftarController::class, 'updatePassword'])->name('pendaftar.password.update');

    Route::get('/pendaftar/pembayaran', [PendaftarController::class, 'pembayaran'])->name('pendaftar.pembayaran');
    Route::post('/pendaftar/pelunasan', [PendaftarController::class, 'prosesPelunasan'])->name('pendaftar.pelunasan');

    Route::get('/invoice/{order_id}', [PendaftarController::class, 'cetakInvoice'])->name('pendaftar.invoice.cetak');
});

    Route::get('/pendaftar/pembayaran', [PendaftarController::class, 'pembayaran'])->name('pendaftar.pembayaran');
    // Jalur Tampilan & Proses Pembayaran Pendaftar
//Route::get('/pendaftar/pembayaran', function () {
//    return view('pendaftar.pembayaran');
//})->middleware('auth');

Route::post('/pendaftar/pembayaran/proses', function (Illuminate\Http\Request $request) {
    // Simulasi validasi kiriman form
    if ($request->metode_pembayaran == 'manual') {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        // Logika simpan file foto bukti transfer Anda nanti di sini
    }

    return redirect('/pendaftar/dashboard')->with('success', 'Pembayaran Anda berhasil dikirim! Silakan tunggu verifikasi admin.');
})->middleware('auth')->name('pendaftar.pembayaran.proses');

// ========================================================
// ROUTE GROUP KHUSUS ROLE: SISWA
// ========================================================
Route::prefix('siswa')->middleware(['auth'])->group(function () {

    // Halaman Beranda / Dashboard Siswa
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');

    // (Opsional) Rute placeholder untuk menu lain di sidebar agar tidak error 404 saat diklik
    Route::get('/kelas', [SiswaController::class, 'kelas'])->name('siswa.kelas');
    Route::get('/materi', [SiswaController::class, 'materiIndex'])->name('siswa.materi');
    // Route::get('/level-test', [SiswaController::class, 'levelTest'])->name('siswa.level_test');
    Route::get('/pembayaran', [SiswaController::class, 'pembayaran'])->name('siswa.pembayaran');
    Route::get('/invoice/{order_id}', [App\Http\Controllers\SiswaController::class, 'cetakInvoiceRegistrasi'])->name('siswa.invoice.cetak');
    Route::get('/gift', [SiswaController::class, 'katalogGift'])->name('siswa.gift');
    Route::post('/gift/tukar/{id}', [SiswaController::class, 'prosesTukarPoint'])->name('siswa.gift.tukar');
    Route::get('/profil', [SiswaController::class, 'profil'])->name('siswa.profil');
    Route::post('/profil/update', [SiswaController::class, 'update'])->name('siswa.profil.update');
    Route::get('/materi/download/{id_materi}', [SiswaController::class, 'downloadMateri'])->name('siswa.materi.download');

    Route::get('/sertifikat', [App\Http\Controllers\SiswaController::class, 'sertifikat']);
    // Rute E-Raport Siswa
    Route::get('/raport', [SiswaController::class, 'raport'])->name('siswa.raport');
    Route::get('/raport/cetak/{id_kelas}', [SiswaController::class, 'cetakRaport'])->name('siswa.raport.cetak');
});


// ========================================================
// ROUTE GROUP KHUSUS ROLE: PENGAJAR
// ========================================================
Route::prefix('pengajar')->middleware(['auth'])->group(function () {

    // Halaman Beranda / Dashboard Pengajar
    Route::get('/dashboard', [PengajarController::class, 'dashboard'])->name('pengajar.dashboard');
    // Placeholder rute agar tombol dropdown tidak error saat diklik
    Route::get('/jadwal', [PengajarController::class, 'jadwalIndex'])->name('pengajar.jadwal');

    Route::get('/kelas', [PengajarController::class, 'kelasIndex'])->name('pengajar.kelas');

    Route::get('/kelas/{id_kelas}/{id_level}', [PengajarController::class, 'kelasDetail'])->name('pengajar.kelas.detail');

    Route::get('/level-test', [PengajarController::class, 'levelTestIndex'])->name('pengajar.level_test');
    Route::post('/level-test/nilai/{id_pendaftar}', [PengajarController::class, 'submitNilaiTest'])->name('pengajar.level_test.submit');

    // Rute Raport Siswa
    Route::get('/raport', [PengajarController::class, 'raportIndex'])->name('pengajar.raport');
    Route::get('/raport/kelas/{id_kelas}/{id_level}', [PengajarController::class, 'raportSiswa'])->name('pengajar.raport.siswa');
    Route::post('/raport/submit/{id_kelas}/{id_siswa}', [PengajarController::class, 'submitRaport'])->name('pengajar.raport.submit');

    Route::post('/pengajar/kelas/absensi', [PengajarController::class, 'simpanAbsensi'])->name('pengajar.absensi.simpan');

    // Rute Materi Pengajar
    Route::get('/materi', [PengajarController::class, 'materiIndex'])->name('pengajar.materi');
    Route::post('/materi/upload', [PengajarController::class, 'materiUpload'])->name('pengajar.materi.upload');
    // Route untuk cascading dropdown materi
    Route::get('/materi/get-level/{id_kelas}', [PengajarController::class, 'getLevelByKelas']);
    Route::get('/materi/hapus/{id}', [PengajarController::class, 'materiHapus'])->name('pengajar.materi.hapus');

    // Rute Kelola Point Stars
    Route::get('/poin', [PengajarController::class, 'poinIndex'])->name('pengajar.poin.index');
    Route::post('/poin/tambah', [PengajarController::class, 'poinStore'])->name('pengajar.poin.store');

    Route::get('/profil', [PengajarController::class, 'profil'])->name('pengajar.profil');
    Route::post('/profil/update', [PengajarController::class, 'updateProfil'])->name('pengajar.profil.update');
    });

