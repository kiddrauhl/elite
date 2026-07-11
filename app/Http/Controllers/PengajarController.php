<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PengajarController extends Controller
{
    // =======================================================
    // 1. DASHBOARD UTAMA PENGAJAR
    // =======================================================
    public function dashboard(\Illuminate\Http\Request $request)
    {
        $userId = Auth::id();
        $pengajar = DB::table('pengajar')->where('id_user', $userId)->first();

        $kelasAjar = collect();
        $totalSiswa = 0;
        $jadwalMengajar = collect();
        $tanggalHariIni = date('Y-m-d');

        // 1. Tangkap Filter Bulan & Tahun (Default: Bulan Ini)
        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));

        // 2. Metadata Kalender menggunakan Carbon
        $firstDayOfMonth = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1);
        $daysInMonth = $firstDayOfMonth->daysInMonth; // Total hari bulan ini (28, 30, 31)
        $startDayOfWeek = $firstDayOfMonth->dayOfWeek; // Hari pertama jatuh di hari apa? (0 = Minggu, 1 = Senin, dst)

        if ($pengajar) {
            // Hitung Kelas & Siswa
            $kelasAjar = DB::table('kelas')->where('id_pengajar', $pengajar->id_pengajar)->get();
            $idKelasArray = $kelasAjar->pluck('id_kelas')->toArray();

            if (!empty($idKelasArray)) {
                $totalSiswa = DB::table('siswa')->whereIn('id_kelas', $idKelasArray)->count();
            }

            // Ambil jadwal HANYA untuk bulan dan tahun yang difilter
            $jadwalMengajar = DB::table('jadwal_belajar')
                ->join('kelas', 'jadwal_belajar.id_kelas', '=', 'kelas.id_kelas')
                ->leftJoin('level', 'kelas.id_level', '=', 'level.id_level')
                ->where('jadwal_belajar.id_pengajar', $pengajar->id_pengajar)
                ->whereMonth('jadwal_belajar.tanggal', $selectedMonth)
                ->whereYear('jadwal_belajar.tanggal', $selectedYear)
                ->select('jadwal_belajar.*', 'kelas.nama_kelas', 'level.nama_level')
                ->get();
        }

            $jadwalHariIni = DB::table('jadwal_belajar')
                ->join('kelas', 'jadwal_belajar.id_kelas', '=', 'kelas.id_kelas')
                ->leftJoin('level', 'kelas.id_level', '=', 'level.id_level')
                ->where('jadwal_belajar.id_pengajar', $pengajar->id_pengajar)
                ->where('jadwal_belajar.tanggal', $tanggalHariIni) // Hanya ambil tanggal hari ini
                ->select('jadwal_belajar.*', 'kelas.nama_kelas', 'level.nama_level')
                ->orderBy('jadwal_belajar.jam_mulai', 'asc') // Urutkan dari jam paling pagi
                ->get();

        // Lempar semua variabel ke View
        return view('pengajar.dashboard', compact(
            'pengajar', 'kelasAjar', 'totalSiswa', 'jadwalMengajar', 'jadwalHariIni',
            'selectedMonth', 'selectedYear', 'daysInMonth', 'startDayOfWeek'
        ));
    }

    // =======================================================
    // 2. HALAMAN DAFTAR JADWAL MENGAJAR
    // =======================================================
    public function jadwalIndex(\Illuminate\Http\Request $request)
    {
        $userId = Auth::id();
        $pengajar = DB::table('pengajar')->where('id_user', $userId)->first();

        // =========================================================
        // 1. LOGIKA KALENDER (Disalin dari Dashboard)
        // =========================================================
        // Tangkap Filter Bulan & Tahun (Default: Bulan Ini)
        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));

        // Metadata Kalender menggunakan Carbon
        $firstDayOfMonth = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1);
        $daysInMonth = $firstDayOfMonth->daysInMonth; // Total hari bulan ini (28, 30, 31)
        $startDayOfWeek = $firstDayOfMonth->dayOfWeek; // Hari pertama jatuh di hari apa? (0 = Minggu, 1 = Senin, dst)

        // Variabel penampung
        $jadwalHariIni = collect();
        $semuaJadwal = collect();
        $events = []; // Tetap disiapkan jika FullCalendar masih dipakai bersamaan

        if ($pengajar) {
            $tanggalHariIni = date('Y-m-d');

            // 2. Ambil Jadwal Khusus Hari Ini Saja
            $jadwalHariIni = DB::table('jadwal_belajar')
                ->join('kelas', 'jadwal_belajar.id_kelas', '=', 'kelas.id_kelas')
                ->leftJoin('level', 'kelas.id_level', '=', 'level.id_level')
                ->where('jadwal_belajar.id_pengajar', $pengajar->id_pengajar)
                ->where('jadwal_belajar.tanggal', $tanggalHariIni)
                ->select('jadwal_belajar.*', 'kelas.nama_kelas', 'level.nama_level')
                ->orderBy('jadwal_belajar.jam_mulai', 'asc')
                ->get();

            // 3. Ambil Jadwal Mengajar BERDASARKAN FILTER BULAN & TAHUN
            $semuaJadwal = DB::table('jadwal_belajar')
                ->join('kelas', 'jadwal_belajar.id_kelas', '=', 'kelas.id_kelas')
                ->leftJoin('level', 'kelas.id_level', '=', 'level.id_level')
                ->where('jadwal_belajar.id_pengajar', $pengajar->id_pengajar)
                ->whereMonth('jadwal_belajar.tanggal', $selectedMonth) // 🌟 Filter berdasarkan bulan
                ->whereYear('jadwal_belajar.tanggal', $selectedYear)   // 🌟 Filter berdasarkan tahun
                ->select('jadwal_belajar.*', 'kelas.nama_kelas', 'level.nama_level')
                ->orderBy('jadwal_belajar.tanggal', 'asc')
                ->orderBy('jadwal_belajar.jam_mulai', 'asc')
                ->get();

            // 4. (Opsional) Format untuk FullCalendar jika masih dibutuhkan di view
            foreach ($semuaJadwal as $j) {
                $events[] = [
                    'title' => $j->nama_kelas,
                    'start' => $j->tanggal . 'T' . $j->jam_mulai,
                    'end'   => $j->tanggal . 'T' . $j->jam_selesai,
                    'color' => '#1e3a8a',
                    'textColor' => '#fbbf24',
                    'extendedProps' => [
                        'keterangan' => $j->keterangan
                    ]
                ];
            }
        }


        // Lempar semua variabel ke View pengajar.jadwal
        return view('pengajar.jadwal', compact(
            'jadwalHariIni',
            'semuaJadwal',
            'jadwalHariIni',
            'events',
            'selectedMonth',
            'selectedYear',
            'daysInMonth',
            'startDayOfWeek'
        ));
    }

    // =======================================================
    // 3. HALAMAN MANAJEMEN KELAS & DAFTAR SISWA
    // =======================================================
    public function kelasIndex()
    {
        $userId = Auth::id();
        $pengajar = DB::table('pengajar')->where('id_user', $userId)->first();

        $kelasList = collect();
        if ($pengajar) {
            $kelasList = DB::table('kelas')
                ->leftJoin('level', 'kelas.id_level', '=', 'level.id_level')
                ->where('kelas.id_pengajar', $pengajar->id_pengajar)
                ->select('kelas.id_kelas', 'kelas.nama_kelas', 'level.id_level', 'level.nama_level')
                ->get();

            foreach ($kelasList as $k) {

                $jadwalTerdekat = DB::table('jadwal_belajar')
                    ->where('id_kelas', $k->id_kelas)
                    ->where('id_level', $k->id_level)
                    ->where('tanggal', '>=', now()->toDateString())
                    ->orderBy('tanggal', 'asc')
                    ->orderBy('jam_mulai', 'asc')
                    ->first();


                $k->jadwal_terdekat = $jadwalTerdekat;
            }
        }

        return view('pengajar.kelas', compact('kelasList'));
    }

    public function kelasDetail($id_kelas, $id_level)
    {
        $userId = Auth::id();
        $pengajar = DB::table('pengajar')->where('id_user', $userId)->first();

        // 1. Ambil data kelas
        $kelas = DB::table('kelas')
            ->where('id_kelas', $id_kelas)
            ->where('id_pengajar', $pengajar->id_pengajar)
            ->first();

        // 2. Ambil data level spesifik dari kartu yang diklik
        $level = DB::table('level')->where('id_level', $id_level)->first();

        // Jika kelas atau level tidak valid
        if (!$kelas || !$level) {
            return redirect('/pengajar/kelas')->with('error', 'Akses ditolak. Kelas atau Level tidak ditemukan.');
        }

        // Tempelkan properti nama_level ke objek kelas agar pemanggilan di Blade tidak error
        $kelas->nama_level = $level->nama_level;

        $tanggalHariIni = date('Y-m-d');

        // 3. Ambil data jadwal sesi yang aktif/terdekat
        $jadwalAktif = DB::table('jadwal_belajar')
            ->where('id_kelas', $id_kelas)
            ->where('id_level', $id_level)
            ->where('tanggal', '>=', $tanggalHariIni)
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->first();

        if ($jadwalAktif) {
            $jadwalAktif->nama_level = $level->nama_level;
        }

        // 4. Query data siswa secara mutlak berdasarkan id_kelas DAN siswa.id_level
        $siswa = DB::table('siswa')
            ->join('pendaftar', 'siswa.id_user', '=', 'pendaftar.id_user')
            ->leftJoin('absensi', function($join) use ($tanggalHariIni) {
                $join->on('siswa.id_siswa', '=', 'absensi.id_siswa')
                     ->where('absensi.tanggal', '=', $tanggalHariIni);
            })
            ->where('siswa.id_kelas', $id_kelas)
            ->where('siswa.id_level', $id_level) // 🌟 MENGGUNAKAN TABEL SISWA
            ->select(
                'siswa.*',
                'pendaftar.nama_lengkap',
                'pendaftar.no_hp',
                'absensi.status as status_absensi'
            )->get();

        return view('pengajar.kelas_detail', compact('kelas', 'siswa', 'jadwalAktif'));
    }

    // =======================================================
    // 4. HALAMAN LEVEL TEST (TABEL PENDAFTARAN)
    // =======================================================
    public function levelTestIndex()
    {
        $userId = Auth::id();
        $pengajar = DB::table('pengajar')->where('id_user', $userId)->first();

        $levels = DB::table('level')->get();
        $jadwalTest = collect();

        if ($pengajar) {
            $jadwalTest = DB::table('pendaftaran')
                ->join('pendaftar', 'pendaftaran.id_pendaftar', '=', 'pendaftar.id_pendaftar')
                ->leftJoin('level', 'pendaftaran.id_level_rekomendasi', '=', 'level.id_level')
                ->where('pendaftaran.id_pengajar', $pengajar->id_pengajar)
                ->select('pendaftaran.*', 'pendaftar.nama_lengkap', 'pendaftar.no_hp', 'level.nama_level')
                ->orderBy('pendaftaran.tanggal', 'desc')
                ->get();
        }

        return view('pengajar.level_test', compact('jadwalTest', 'levels'));
    }

    public function submitNilaiTest(\Illuminate\Http\Request $request, $id_pendaftar)
    {
        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'id_level' => 'required'
        ]);

        DB::beginTransaction();
        try {
            // 1. Update data nilai dan histori rekomendasi di tabel 'pendaftaran'
            DB::table('pendaftaran')
                ->where('id_pendaftar', $id_pendaftar)
                ->update([
                    'nilai_test'           => $request->nilai,
                    'id_level_rekomendasi' => $request->id_level,
                    'status'               => 'proses'
                ]);

            // 2. 🌟 KUNCI PEMBUKA PEMBAYARAN: Update id_level di tabel 'pendaftar'
            DB::table('pendaftar')
                ->where('id_pendaftar', $id_pendaftar)
                ->update([
                    'id_level' => $request->id_level
                ]);

            DB::commit();
            return redirect()->back()->with('success', 'Nilai tersimpan! Tagihan pembayaran otomatis aktif di akun pendaftar.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // =======================================================
    // 5. HALAMAN PENILAIAN & RAPORT SISWA
    // =======================================================
    public function raportIndex()
    {
        $userId = Auth::id();
        $pengajar = DB::table('pengajar')->where('id_user', $userId)->first();

        $kelasAjar = collect();
        if ($pengajar) {
            $kelasAjar = DB::table('kelas')
                ->leftJoin('level', 'kelas.id_level', '=', 'level.id_level')
                ->where('kelas.id_pengajar', $pengajar->id_pengajar)
                ->select('kelas.id_kelas', 'kelas.nama_kelas', 'level.id_level', 'level.nama_level')
                ->get();
        }

        return view('pengajar.raport', compact('kelasAjar'));
    }

    public function raportSiswa($id_kelas, $id_level)
    {
        $userId = Auth::id();
        $pengajar = DB::table('pengajar')->where('id_user', $userId)->first();

        $kelas = DB::table('kelas')
            ->where('id_kelas', $id_kelas)
            ->where('id_pengajar', $pengajar->id_pengajar)
            ->first();

        $level = DB::table('level')->where('id_level', $id_level)->first();

        if (!$kelas || !$level) {
            return redirect('/pengajar/raport')->with('error', 'Akses ditolak. Kelas atau Level tidak ditemukan.');
        }

        $kelas->nama_level = $level->nama_level;
        $semuaLevel = DB::table('level')->get();

        $siswa = DB::table('siswa')
            ->join('pendaftar', 'siswa.id_user', '=', 'pendaftar.id_user')
            ->leftJoin('raport', function($join) {
                $join->on('siswa.id_siswa', '=', 'raport.id_siswa')
                     ->on('siswa.id_kelas', '=', 'raport.id_kelas');
            })
            ->select(
                'siswa.id_siswa as id_siswa',
                'siswa.id_level', // 🌟 TAMBAHAN: Agar terbaca di logika @if pada Blade
                'siswa.id_level_lanjutan',
                'pendaftar.nama_lengkap',
                'raport.nilai_speaking',
                'raport.nilai_listening',
                'raport.nilai_reading',
                'raport.nilai_writing',
                'raport.rata_rata',
                'raport.catatan_pengajar'
            )
            ->where('siswa.id_kelas', $id_kelas)
            ->where('siswa.id_level', $id_level)
            ->get();

        return view('pengajar.raport_siswa', compact('kelas', 'siswa', 'semuaLevel', 'level'));
    }

    public function submitRaport(\Illuminate\Http\Request $request, $id_kelas, $id_siswa)
    {
        $userId = Auth::id();
        $pengajar = DB::table('pengajar')->where('id_user', $userId)->first();

        if (!$pengajar) {
            return redirect()->back()->with('error', 'Akses ditolak: Data pengajar tidak valid.');
        }

        // 🌟 PERBAIKAN: Ubah id_level_lanjutan menjadi nullable
        $request->validate([
            'nilai_speaking'    => 'required|numeric|min:0|max:100',
            'nilai_listening'   => 'required|numeric|min:0|max:100',
            'nilai_reading'     => 'required|numeric|min:0|max:100',
            'nilai_writing'     => 'required|numeric|min:0|max:100',
            'id_level_lanjutan' => 'nullable',
            'catatan_pengajar'  => 'nullable|string'
        ]);

        $rata_rata = ($request->nilai_speaking + $request->nilai_listening + $request->nilai_reading + $request->nilai_writing) / 4;

        DB::beginTransaction();
        try {
            // 1. Simpan atau perbarui nilai ke tabel Raport
            DB::table('raport')->updateOrInsert(
                ['id_siswa' => $id_siswa, 'id_kelas' => $id_kelas],
                [
                    'id_pengajar'       => $pengajar->id_pengajar,
                    'nilai_speaking'    => $request->nilai_speaking,
                    'nilai_listening'   => $request->nilai_listening,
                    'nilai_reading'     => $request->nilai_reading,
                    'nilai_writing'     => $request->nilai_writing,
                    'rata_rata'         => $rata_rata,
                    'catatan_pengajar'  => $request->catatan_pengajar,
                    'tanggal_dibagikan' => date('Y-m-d')
                ]
            );

            // 2. Simpan Rekomendasi Level Lanjutan ke tabel Siswa
            DB::table('siswa')
                ->where('id_siswa', $id_siswa)
                ->update([
                    // Jika form mengirim nilai kosong (dari siswa Expert), ini akan menyimpan NULL dengan rapi di database
                    'id_level_lanjutan' => $request->id_level_lanjutan
                ]);

            DB::commit();
            return redirect()->back()->with('success', 'Raport tersimpan! Tagihan Registrasi Ulang otomatis aktif di akun siswa.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    // 1. Tampilan Halaman Materi beserta Daftar Kelas
    public function materiIndex()
    {
        $userId = Auth::id();
        $pengajar = DB::table('pengajar')->where('id_user', $userId)->first();

        $materiList = collect();
        $kelasList = collect();

        $levelList = DB::table('level')->get();

        if ($pengajar) {
            // 1. Ambil daftar materi yang pernah diupload oleh pengajar ini
            $materiList = DB::table('materi')
                ->join('kelas', 'materi.id_kelas', '=', 'kelas.id_kelas')
                ->where('materi.id_pengajar', $pengajar->id_pengajar)
                ->select('materi.*', 'kelas.nama_kelas')
                ->orderBy('materi.id_materi', 'desc')
                ->get();

            // 2. 🌟 KUNCI PERBAIKAN: Saring dropdown kelas agar HANYA menampilkan kelas milik pengajar ini
            $kelasList = DB::table('kelas')
                ->where('id_pengajar', $pengajar->id_pengajar) // Filter berdasarkan id_pengajar yang login
                ->get();
        }

        return view('pengajar.materi', compact('materiList', 'kelasList', 'levelList'));
    }

    // 2. Proses Upload Materi dengan Pilihan Kelas
    public function materiUpload(\Illuminate\Http\Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'id_kelas'     => 'required',
            'id_level'     => 'required',
            'judul_materi' => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'file_materi'  => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:10240'
        ]);

        // 2. Ambil Data Pengajar yang sedang login
        $userId = Auth::id();
        $pengajar = DB::table('pengajar')->where('id_user', $userId)->first();

        if (!$pengajar) {
            return redirect()->back()->with('error', 'Sesi pengajar tidak valid.');
        }

        // 3. 🌟 PROTEKSI KEAMANAN EKSTRA (Backend Validation)
        // Pastikan id_kelas yang dikirim dari form benar-benar diajar oleh pengajar ini
        $validasiKelas = DB::table('kelas')
            ->where('id_kelas', $request->id_kelas)
            ->where('id_pengajar', $pengajar->id_pengajar)
            ->first();

        if (!$validasiKelas) {
            // Jika ada yang mencoba bypass HTML via Inspect Element, sistem akan menolak
            return redirect()->back()->with('error', 'Akses ditolak! Anda tidak diizinkan mengunggah materi ke kelas ini.');
        }

        // 4. Proses Upload File jika semuanya aman
        if ($request->hasFile('file_materi')) {
            $file = $request->file('file_materi');

            // Membersihkan nama file dari spasi agar URL aman
            $namaFileAsli = str_replace(' ', '_', $file->getClientOriginalName());
            $namaFileBaru = time() . '_' . $namaFileAsli;

            // Simpan file ke storage/app/public/materi
            $file->storeAs('public/materi', $namaFileBaru);

            // 5. Simpan jejak ke database
            DB::table('materi')->insert([
                'id_pengajar'   => $pengajar->id_pengajar,
                'id_kelas'      => $request->id_kelas,
                'id_level'      => $request->id_level,
                'judul_materi'  => $request->judul_materi,
                'deskripsi'     => $request->deskripsi,
                'file_materi'   => $namaFileBaru,
                'tanggal_upload'=> date('Y-m-d')
            ]);

            return redirect()->back()->with('success', 'File materi berhasil diunggah ke kelas ' . $validasiKelas->nama_kelas . '!');
        }

        return redirect()->back()->with('error', 'Gagal memproses file materi.');
    }

    // Fungsi baru untuk dipanggil oleh JavaScript

    public function getLevelByKelas($id_kelas)
    {
        $userId = Auth::id();
        $pengajar = DB::table('pengajar')->where('id_user', $userId)->first();

        if (!$pengajar) {
            return response()->json([]);
        }

        $levelKelas = DB::table('kelas')
            ->join('level', 'kelas.id_level', '=', 'level.id_level')
            ->where('kelas.id_kelas', $id_kelas)
            ->where('kelas.id_pengajar', $pengajar->id_pengajar)
            ->select('level.id_level', 'level.nama_level')
            ->get();

        // Kembalikan datanya ke JavaScript
        return response()->json($levelKelas);
    }

    // 3. Hapus Materi
    public function materiHapus($id_materi)
    {
        $materi = DB::table('materi')->where('id_materi', $id_materi)->first();

        if ($materi) {
            // 🌟 UBAH DI SINI: Hapus fisik menggunakan file_materi
            Storage::delete('public/materi/' . $materi->file_materi);
            DB::table('materi')->where('id_materi', $id_materi)->delete();

            return redirect()->back()->with('success', 'Materi berhasil dihapus!');
        }
        return redirect()->back()->with('error', 'Materi tidak ditemukan.');
    }

    public function simpanAbsensi(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'id_siswa' => 'required',
            'id_kelas' => 'required',
            'status'   => 'required|in:hadir,izin,sakit,alfa'
        ]);

        $tanggalHariIni = date('Y-m-d');

        // Gunakan updateOrInsert agar jika hari ini sudah absen, statusnya langsung berubah tanpa membuat baris baru
        DB::table('absensi')->updateOrInsert(
            [
                'id_siswa' => $request->id_siswa,
                'id_kelas' => $request->id_kelas,
                'tanggal'  => $tanggalHariIni
            ],
            [
                'status'     => $request->status,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        return redirect()->back()->with('success', 'Absensi berhasil diperbarui!');
    }

    public function poinIndex(\Illuminate\Http\Request $request)
    {
        $userId = Auth::id();
        $pengajar = DB::table('pengajar')->where('id_user', $userId)->first();

        // Ambil daftar kelas yang diajar oleh instruktur ini untuk dropdown filter
        $kelasList = DB::table('kelas')->where('id_pengajar', $pengajar->id_pengajar)->get();

        // 🌟 BARU: Ambil daftar level untuk dropdown filter
        $levelList = DB::table('level')->get();

        $selectedKelas = $request->input('filter_kelas');
        $selectedLevel = $request->input('filter_level'); // 🌟 BARU: Tangkap input level

        $siswaList = collect();

        // Jika ada kelas yang dipilih, tampilkan murid-murid di kelas tersebut
        if ($selectedKelas) {
            $query = DB::table('siswa')
                ->join('pendaftar', 'siswa.id_user', '=', 'pendaftar.id_user')
                ->where('siswa.id_kelas', $selectedKelas);

            // 🌟 BARU: Terapkan filter level jika pengajar memilih level tertentu
            if ($selectedLevel) {
                $query->where('siswa.id_level', $selectedLevel);
            }

            $siswaList = $query->select('siswa.*', 'pendaftar.nama_lengkap')
                ->orderBy('pendaftar.nama_lengkap', 'asc')
                ->get();
        }


        return view('pengajar.poin', compact('kelasList', 'levelList', 'siswaList', 'selectedKelas', 'selectedLevel'));
    }

    public function poinStore(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'id_siswa' => 'required',
            'nominal'  => 'required|integer|min:1',
        ]);

        // Tambahkan poin ke tabel siswa (Sesuaikan nama kolom 'point_stars' dengan database Anda)
        DB::table('siswa')
            ->where('id_siswa', $request->id_siswa)
            ->increment('total_point', $request->nominal);

        return redirect()->back()->with('success', 'Berhasil menambahkan ' . $request->nominal . ' Point Stars kepada siswa!');
    }

    public function profil()
    {
        // Mengambil data langsung dari tabel users
        $profil = DB::table('users')->where('id', Auth::id())->first();

        // Ganti menjadi 'admin.profil' jika ini di dalam AdminController
        return view('pengajar.profil', compact('profil'));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $userId = Auth::id();

        $dataUpdate = [
            'nama'       => $request->nama,
            'email'      => $request->email,
            'updated_at' => now()
        ];

        // Logika Upload Foto
        if ($request->hasFile('foto_profil')) {
            $userLama = DB::table('users')->where('id', $userId)->first();

            if ($userLama && isset($userLama->foto_profil)) {
                Storage::disk('public')->delete($userLama->foto_profil);
            }

            $pathFoto = $request->file('foto_profil')->store('profil_users', 'public');
            $dataUpdate['foto_profil'] = $pathFoto;
        }

        // Jika kolom password diisi, maka update passwordnya
        if ($request->filled('password')) {
            DB::table('users')->where('id', Auth::id())->update([
                'password' => Hash::make($request->password)
            ]);
        }

        // Eksekusi update ke tabel users
        DB::table('users')->where('id', $userId)->update($dataUpdate);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
