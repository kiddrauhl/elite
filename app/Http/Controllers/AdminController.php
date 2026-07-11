<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Models\User;
use App\Models\Pendaftar;
//use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Level;
use App\Models\Pengajar;
//use App\Models\Gift;
//use App\Models\JadwalPendaftaran;
//use App\Models\JadwalBelajar;
//use App\Models\Pembayaran;
//use App\Models\Pendaftaran;
//use App\Models\PenukaranPoint;
//use App\Models\PointStars;
//use App\Models\Raport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPendaftar = DB::table('pendaftar')->count();
        $totalSiswa     = DB::table('siswa')->count();
        $totalPengajar  = DB::table('pengajar')->count();

        $topSiswa = DB::table('siswa')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            ->join('pendaftar', 'siswa.id_user', '=', 'pendaftar.id_user')
            ->select('pendaftar.nama_lengkap', 'siswa.total_point', 'siswa.id_siswa')
            ->orderBy('siswa.total_point', 'desc')
            ->limit(5)
            ->get();

        $grafikPrestasi = DB::table('raport')
            ->join('siswa', 'raport.id_siswa', '=', 'siswa.id_siswa')
            ->join('pendaftar', 'siswa.id_user', '=', 'pendaftar.id_user')
            ->select('pendaftar.nama_lengkap', 'raport.rata_rata')
            ->orderBy('raport.rata_rata', 'desc')
            ->limit(7)
            ->get();

        $labelGrafik = $grafikPrestasi->pluck('nama_lengkap');
        $dataGrafik  = $grafikPrestasi->pluck('rata_rata');

        return view('admin.dashboard', compact(
            'totalPendaftar',
            'totalSiswa',
            'totalPengajar',
            'topSiswa',
            'labelGrafik',
            'dataGrafik'
        ));
    }

    public function showPendaftar($id)
    {
        $pendaftar = Pendaftar::with('user')->findOrFail($id);

        return view('admin.verifikasi', compact('pendaftar'));
    }

    // Fungsi untuk memperbarui status pendaftar
    public function updateStatus(Request $request, $id)
    {
        $pendaftar = Pendaftar::findOrFail($id);
        $pendaftar->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status pendaftar berhasil diperbarui.');
    }

    // =======================================================
    // 1. TAMPILKAN HALAMAN VERIFIKASI (Tempat Modal Berada)
    // =======================================================
    public function verifikasi(Request $request)
    {
        $query = DB::table('pendaftar')->where('status', 'Menunggu Jadwal');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('asal_sekolah', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan Jenjang / Tingkat Sekolah
        if ($request->filled('jenjang')) {
            $query->where('tingkat_sekolah', $request->jenjang);
        }

        if ($request->filled('gelombang')) {
            $query->where('id_jadwal_daftar', $request->gelombang);
        }

        // Eksekusi query pendaftar yang sudah difilter
        $pendaftar = $query->get();

        // 2. Ambil daftar gelombang untuk opsi dropdown di Blade
        $listGelombang = DB::table('jadwal_pendaftaran')->orderBy('id_jadwal_daftar', 'desc')->get();

        // 3. WAJIB ADA DI SINI: Ambil daftar pengajar untuk dropdown di Modal Verifikasi
        $pengajarList = DB::table('pengajar')
            ->join('users', 'pengajar.id_user', '=', 'users.id')
            ->select('pengajar.id_pengajar', 'users.nama as nama_pengajar')
            ->get();

        // Kembalikan ke view beserta semua variabel yang dibutuhkan
        return view('admin.verifikasi', compact('pendaftar', 'pengajarList', 'listGelombang'));
    }

    // =======================================================
    // 2. PROSES SIMPAN JADWAL & UBAH STATUS (Pendaftaran)
    // =======================================================
    public function verifikasiJadwal(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'tanggal'     => 'required|date',
            'jam_tes'     => 'required',
            'catatan_tes' => 'required|string|max:255',
            'id_pengajar' => 'required',
        ]);

        // 2. Simpan atau Update data jadwal ke tabel pendaftaran
        DB::table('pendaftaran')->updateOrInsert(
            ['id_pendaftar' => $id],
            [
                'tanggal'     => $request->tanggal,
                'waktu'       => $request->jam_tes,      // Menyimpan jam tes ke kolom waktu
                'ruangan'     => $request->catatan_tes,  // Menyimpan lokasi/ruangan
                'id_pengajar' => $request->id_pengajar,
                // Metode tes dipaksa menjadi 'offline' sesuai permintaan Anda
                'status'      => 'proses',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        );

        // 3. Update status di tabel pendaftar
        DB::table('pendaftar')
            ->where('id_pendaftar', $id)
            ->update([
                'status' => 'proses'
            ]);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Pendaftar berhasil disetujui! Jadwal dan ruang tes telah diatur.');
    }

    // =======================================================
    // 3. TAMPILKAN HALAMAN LIST JADWAL
    // =======================================================
    public function jadwalIndex()
    {
        // 🌟 PERBAIKAN: Ubah nama variabel menjadi $jadwal dan gabungkan dengan tabel lain
        // agar view bisa membaca nama pendaftar dan nama pengajar
        $jadwal = DB::table('pendaftaran')
            ->join('pendaftar', 'pendaftaran.id_pendaftar', '=', 'pendaftar.id_pendaftar')
            ->leftJoin('pengajar', 'pendaftaran.id_pengajar', '=', 'pengajar.id_pengajar')
            ->leftJoin('users', 'pengajar.id_user', '=', 'users.id')
            ->select(
                'pendaftaran.*',
                'pendaftar.nama_lengkap',
                'pendaftar.no_hp',
                'users.nama as nama_pengajar'
            )
            ->get();

        // Data pengajar untuk dropdown (jika ada fitur edit jadwal di halaman ini)
        $pengajarList = DB::table('pengajar')
            ->join('users', 'pengajar.id_user', '=', 'users.id')
            ->select('pengajar.id_pengajar', 'users.nama as nama_pengajar')
            ->get();

        // 🌟 PERBAIKAN: Pastikan 'jadwal' ikut dikirimkan di dalam compact()
        return view('admin.jadwal.index', compact('jadwal', 'pengajarList'));
    }

    // ==========================================
    // BAGIAN KHUSUS DATA SISWA (CRUD RIIL)
    // ==========================================

    // 1. TAMPILKAN DATA (READ)

    public function siswaIndex(Request $request)
    {
        // 1. Ambil data master untuk pilihan opsi Dropdown di Blade
        $dataKelas = DB::table('kelas')->get();
        $dataLevel = DB::table('level')->get();

        $query = DB::table('siswa')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            // PERBAIKAN 1: Relasikan pendaftar langsung ke siswa.id_user agar lebih spesifik
            ->join('pendaftar', 'siswa.id_user', '=', 'pendaftar.id_user')
            ->leftJoin('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->leftJoin('level', 'siswa.id_level', '=', 'level.id_level')
            ->select(
                'siswa.*',
                'users.email',
                'pendaftar.nama_lengkap',
                'pendaftar.no_hp',
                'pendaftar.alamat',
                'pendaftar.asal_sekolah',
                'kelas.nama_kelas',
                'level.nama_level'
            )
            // PERBAIKAN 2 (SOLUSI UTAMA): Mencegah duplikasi data (Cartesian Product)
            ->groupBy('siswa.id_siswa');

        if ($request->filled('filter_kelas')) {
            $query->where('siswa.id_kelas', $request->filter_kelas);
        }

        if ($request->filled('filter_level')) {
            // PERBAIKAN 3: Filter level sebaiknya menembak langsung ke id_level milik siswa
            $query->where('siswa.id_level', $request->filter_level);
        }

        $query->orderBy('siswa.id_siswa', 'desc');

        $siswa = $query->paginate(10);

        return view('admin.siswa.index', compact('siswa', 'dataKelas', 'dataLevel'));
    }

    /**
     * 1. PROSES UPDATE DATA SISWA (Multi-Tabel)
     */
    public function siswaUpdate(Request $request, $id)
    {
        // 1. Validasi disesuaikan dengan atribut 'name' di form HTML yang baru
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_hp'        => 'required|string|max:15',
            'id_level'     => 'required|integer',
            'id_kelas'     => 'nullable|integer', // Boleh kosong jika siswa dicabut dari kelas

        ]);

        // 2. Gunakan Transaction untuk mencegah data corrupt
        DB::transaction(function () use ($request, $id) {

            // A. Cari data master siswa berdasarkan id_siswa
            $siswa = DB::table('siswa')->where('id_siswa', $id)->first();

            if ($siswa) {
                // B. Update Level Kelas dan Kelas Penempatan di tabel 'siswa'
                DB::table('siswa')->where('id_siswa', $id)->update([
                    'id_level'   => $request->id_level,
                    'id_kelas'   => $request->id_kelas, // 🌟 Menyimpan perpindahan kelas
                    // 'total_point' dihapus dari update agar tidak tertimpa/dimanipulasi
                    'updated_at' => now(),
                ]);

                // C. Update Nama Akun di tabel 'users'
                DB::table('users')->where('id', $siswa->id_user)->update([
                    'nama'       => $request->nama_lengkap,
                    'updated_at' => now(),
                ]);

                // D. Update Nama Lengkap dan Nomor WhatsApp di tabel 'pendaftar'
                DB::table('pendaftar')->where('id_user', $siswa->id_user)->update([
                    'nama_lengkap' => $request->nama_lengkap,
                    'no_hp'        => $request->no_hp,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Data profil dan penempatan kelas siswa berhasil diperbarui!');
    }

    /**
     * 2. PROSES HAPUS DATA SISWA
     */
    public function siswaDelete($id)
    {
        DB::table('siswa')->where('id_siswa', $id)->delete();

        return redirect()->back()->with('success', 'Data siswa aktif berhasil dihapus dari sistem bimbingan.');
    }

    public function dataAlumni()
    {
        $alumni = DB::table('siswa')
            ->join('pendaftar', 'siswa.id_user', '=', 'pendaftar.id_user')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            ->leftJoin('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->leftJoin('level', 'siswa.id_level', '=', 'level.id_level')
            ->select(
                'siswa.*',
                'pendaftar.nama_lengkap',
                'users.email',
                'pendaftar.no_hp',
                'kelas.nama_kelas'
            )
            // 🌟 Filter langsung menggunakan nama level, bukan ID angka lagi
            ->where('level.nama_level', 'Expert')
            ->orderBy('pendaftar.nama_lengkap', 'asc')
            ->paginate(15);

        return view('admin.siswa.alumni', compact('alumni'));
    }

    public function terbitkanSertifikat($id_siswa)
    {
        // 1. Ambil data siswa dan pendaftar
        $siswa = DB::table('siswa')
            ->join('pendaftar', 'siswa.id_user', '=', 'pendaftar.id_user')
            ->where('siswa.id_siswa', $id_siswa)
            ->first();

        if (!$siswa) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        // 2. Tentukan nama file secara otomatis (misal: sertifikat_ahmad_rida.pdf)
        $nama_file = 'sertifikat_' . strtolower(str_replace(' ', '_', $siswa->nama_lengkap)) . '.pdf';

        // 3. Pastikan folder penyimpanannya ada
        $path = public_path('sertifikat');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        // 4. Generate PDF dari tampilan Blade (kita akan buat tampilan ini setelah ini)
        $pdf = Pdf::loadView('admin.sertifikat.template', ['siswa' => $siswa]);

        // 5. Atur ukuran kertas menjadi A4 dengan posisi Landscape (mendatar)
        $pdf->setPaper('A4', 'landscape');

        // 6. Simpan file PDF ke dalam folder public/sertifikat/
        $pdf->save($path . '/' . $nama_file);

        // 7. Update kolom file_sertifikat di database agar sistem tahu sertifikat sudah jadi
        DB::table('siswa')
            ->where('id_siswa', $id_siswa)
            ->update(['file_sertifikat' => $nama_file]);

        return back()->with('success', 'Sertifikat berhasil diterbitkan!');
    }

    public function penempatanLanjutan()
    {
        // Ambil data pendaftaran ulang yang sudah lunas (settlement)
        $dataPenempatan = DB::table('pembayaran')
            ->where('pembayaran.order_id', 'like', 'RE-%')
            ->where('pembayaran.status_verifikasi', 'settlement')
            ->join('pendaftar', 'pembayaran.id_pendaftar', '=', 'pendaftar.id_pendaftar')
            ->join('siswa', 'pendaftar.id_user', '=', 'siswa.id_user')
            ->leftJoin('level as level_lama', 'siswa.id_level', '=', 'level_lama.id_level')
            ->leftJoin('level as level_baru', 'siswa.id_level_lanjutan', '=', 'level_baru.id_level')
            ->leftJoin('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->select(
                'pembayaran.*',
                'pendaftar.nama_lengkap',
                'siswa.id_siswa',
                'siswa.id_level_lanjutan',
                'level_lama.nama_level as level_sekarang',
                'level_baru.nama_level as level_tujuan',
                'kelas.nama_kelas'
            )

            ->orderBy('pembayaran.updated_at', 'desc')
            ->get();

        return view('admin.siswa.penempatan_lanjutan', compact('dataPenempatan'));
    }

    public function pilihKelasLanjutan($id_siswa)
    {
        // 1. Ambil detail siswa beserta info transisi levelnya
        $siswa = DB::table('siswa')
            ->join('pendaftar', 'siswa.id_user', '=', 'pendaftar.id_user')
            ->leftJoin('level as level_lama', 'siswa.id_level', '=', 'level_lama.id_level')
            ->leftJoin('level as level_baru', 'siswa.id_level_lanjutan', '=', 'level_baru.id_level')
            ->select(
                'siswa.*',
                'pendaftar.nama_lengkap',
                'level_lama.nama_level as level_sekarang',
                'level_baru.nama_level as level_tujuan'
            )
            ->where('siswa.id_siswa', $id_siswa)
            ->first();

        if (!$siswa || !$siswa->id_level_lanjutan) {
            return redirect('/admin/penempatan-lanjutan')->with('error', 'Siswa tidak ditemukan atau belum siap ditempatkan.');
        }

        $daftarKelas = DB::table('kelas')
            ->where('id_level', $siswa->id_level_lanjutan)
            ->get();

        return view('admin.siswa.pilih_kelas_lanjutan', compact('siswa', 'daftarKelas'));
    }

    public function simpanPenempatanLanjutan(Request $request, $id_siswa)
    {
        $request->validate([
            'id_kelas' => 'required'
        ]);

        $siswa = DB::table('siswa')->where('id_siswa', $id_siswa)->first();

        if (!$siswa || !$siswa->id_level_lanjutan) {
            return redirect('/admin/siswa/penempatan-lanjutan')->with('error', 'Data siswa tidak valid.');
        }

        DB::beginTransaction();
        try {
            // 3. PROSES SAKTI: Naikkan level, kunci kelas baru, dan bersihkan antrean (set NULL)
            DB::table('siswa')
                ->where('id_siswa', $id_siswa)
                ->update([
                    'id_level'          => $siswa->id_level_lanjutan,
                    'id_kelas'          => $request->id_kelas,
                    'id_level_lanjutan' => null,
                    'status'            => 'aktif',
                    'updated_at'        => now()
                ]);

            DB::commit();
            return redirect('/admin/siswa/penempatan-lanjutan')->with('success', 'Siswa resmi naik level dan berhasil dialokasikan ke kelas baru!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        $query = DB::table('siswa')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            ->join('pendaftar', 'pendaftar.id_user', '=', 'users.id')
            ->leftJoin('level', 'siswa.id_level', '=', 'level.id_level')
            ->select(
                'siswa.*',
                'pendaftar.nama_lengkap',
                'users.email',
                'pendaftar.no_hp',
                'pendaftar.asal_sekolah',
                'level.nama_level'
            );


        if ($request->has('id_kelas') && $request->id_kelas != '') {
            $query->where('siswa.id_kelas', $request->id_kelas);
        }
        if ($request->has('id_level') && $request->id_level != '') {
            $query->where('siswa.id_level', $request->id_level);
        }

        $data = $query->get();

        $filename = "Data_Siswa_Elite_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');


        fputcsv($handle, ['No', 'Nama Siswa', 'Email', 'No HP', 'Asal Sekolah', 'Tingkat Level', 'Total Point']);

        $no = 1;
        foreach ($data as $row) {
            fputcsv($handle, [
                $no++,
                $row->nama_lengkap,
                $row->email,
                $row->no_hp,
                $row->asal_sekolah,
                $row->nama_level ?? 'Belum Ada',
                $row->total_point ?? 0
            ]);
        }

        fclose($handle);
        exit;
    }

    public function exportPdf(Request $request)
    {

        $query = DB::table('siswa')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            ->join('pendaftar', 'pendaftar.id_user', '=', 'users.id')
            ->leftJoin('level', 'siswa.id_level', '=', 'level.id_level')
            ->select(
                'siswa.*',
                'pendaftar.nama_lengkap',
                'users.email',
                'pendaftar.no_hp',
                'pendaftar.asal_sekolah',
                'level.nama_level'
            );


        if ($request->has('id_kelas') && $request->id_kelas != '') {
            $query->where('siswa.id_kelas', $request->id_kelas);
        }
        if ($request->has('id_level') && $request->id_level != '') {
            $query->where('siswa.id_level', $request->id_level);
        }

        $data = $query->get();

        return view('admin.siswa.cetak_pdf', compact('data'));
    }

    public function exportExcelPenempatan(Request $request)
    {
        $data = DB::table('pembayaran')
            ->where('pembayaran.order_id', 'like', 'RE-%')
            ->where('pembayaran.status_verifikasi', 'settlement')
            ->join('pendaftar', 'pembayaran.id_pendaftar', '=', 'pendaftar.id_pendaftar')
            ->join('siswa', 'pendaftar.id_user', '=', 'siswa.id_user')
            ->leftJoin('level as level_lama', 'siswa.id_level', '=', 'level_lama.id_level')
            ->leftJoin('level as level_baru', 'siswa.id_level_lanjutan', '=', 'level_baru.id_level')
            ->leftJoin('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->select(
                'pembayaran.*',
                'pendaftar.nama_lengkap',
                'siswa.id_level_lanjutan',
                'level_lama.nama_level as level_sekarang',
                'level_baru.nama_level as level_tujuan',
                'kelas.nama_kelas'
            )
            ->orderBy('pembayaran.updated_at', 'desc')
            ->get();

        $filename = "Laporan_Penempatan_Lanjutan_Elite_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');


        fputcsv($handle, ['No', 'Nama Siswa', 'Nomor Transaksi', 'Status Transisi', 'Keterangan / Kelas Aktif', 'Tanggal Pembayaran']);

        $no = 1;
        foreach ($data as $row) {

            $statusTransisi = $row->id_level_lanjutan ? 'Menunggu Penempatan' : 'Selesai Ditempatkan';

            $keterangan = $row->id_level_lanjutan
                ? ($row->level_sekarang . ' -> ' . $row->level_tujuan)
                : ('Aktif di ' . $row->level_sekarang . ' (' . ($row->nama_kelas ?? 'Tanpa Kelas') . ')');

            fputcsv($handle, [
                $no++,
                $row->nama_lengkap,
                $row->order_id,
                $statusTransisi,
                $keterangan,
                $row->tanggal_bayar ?? $row->updated_at
            ]);
        }

        fclose($handle);
        exit;
    }

    public function exportPdfPenempatan(Request $request)
    {

        $dataPenempatan = DB::table('pembayaran')
            ->where('pembayaran.order_id', 'like', 'RE-%')
            ->where('pembayaran.status_verifikasi', 'settlement')
            ->join('pendaftar', 'pembayaran.id_pendaftar', '=', 'pendaftar.id_pendaftar')
            ->join('siswa', 'pendaftar.id_user', '=', 'siswa.id_user')
            ->leftJoin('level as level_lama', 'siswa.id_level', '=', 'level_lama.id_level')
            ->leftJoin('level as level_baru', 'siswa.id_level_lanjutan', '=', 'level_baru.id_level')
            ->leftJoin('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->select(
                'pembayaran.*',
                'pendaftar.nama_lengkap',
                'siswa.id_level_lanjutan',
                'level_lama.nama_level as level_sekarang',
                'level_baru.nama_level as level_tujuan',
                'kelas.nama_kelas'
            )
            ->orderBy('pembayaran.updated_at', 'desc')
            ->get();

        return view('admin.siswa.cetak_pdf_penempatan', compact('dataPenempatan'));
    }

    // 1. READ: Tampilkan Halaman Utama Kelas
    public function kelasIndex(Request $request)
    {
        // 1. Ambil data master untuk pilihan dropdown di Blade
        $levels = Level::all();
        $pengajar = Pengajar::all();
        $dataKelasUtama = Kelas::all(); // Diperlukan untuk mengisi opsi dropdown kelas

        // 2. Siapkan kueri utama dengan relasi dan hitung jumlah siswa aktif
        $queryKelas = Kelas::with(['level', 'pengajar'])
            ->withCount(['siswa' => function($query) {
                $query->where('status', 'Aktif');
            }]);

        // 3. Logika Filter Level
        if ($request->filled('filter_level')) {
            $queryKelas->where('id_level', $request->filter_level);
        }

        // 4. Logika Filter Kelas (Baru)
        if ($request->filled('filter_kelas')) {
            $queryKelas->where('id_kelas', $request->filter_kelas);
        }

        // 5. Eksekusi Kueri, urutkan dari yang terbaru
        $kelas = $queryKelas->orderBy('created_at', 'desc')->get();

        return view('admin.kelas.index', compact('kelas', 'levels', 'pengajar', 'dataKelasUtama'));
    }

    // 2. CREATE: Proses Tambah Kelas Baru
    public function kelasStore(Request $request)
    {
        $request->validate([
            'nama_kelas'  => 'required|string|max:255',
            'id_level'    => 'required|exists:level,id_level',       // sesuaikan nama tabel level Anda
            'id_pengajar' => 'required|exists:pengajar,id_pengajar', // sesuaikan nama tabel pengajar Anda
        ]);

        Kelas::create([
            'nama_kelas'  => $request->nama_kelas,
            'id_level'    => $request->id_level,
            'id_pengajar' => $request->id_pengajar,
        ]);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas baru berhasil ditambahkan!');
    }

    // 3. UPDATE: Proses Perbarui Data Kelas
    public function kelasUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_kelas'  => 'required|string|max:255',
            'id_level'    => 'required|exists:level,id_level',
            'id_pengajar' => 'required|exists:pengajar,id_pengajar',
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update([
            'nama_kelas'  => $request->nama_kelas,
            'id_level'    => $request->id_level,
            'id_pengajar' => $request->id_pengajar,
        ]);

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil diperbarui!');
    }

    // 4. DELETE: Proses Hapus Data Kelas
    public function kelasDelete($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus dari sistem!');
    }

    public function pengajarIndex()
    {
        $pengajar = DB::table('pengajar')
                        ->join('users', 'pengajar.id_user', '=', 'users.id')
                        ->select('pengajar.*', 'users.email')
                        ->get();

        return view('admin.pengajar.index', compact('pengajar'));
    }

    // 2. CREATE: Simpan Akun Login di Users dan Biodata di Pengajar (Transaction)
    public function pengajarStore(Request $request)
    {
        $request->validate([
            'nama_pengajar' => 'required|string|max:255',
            'no_hp'         => 'required|string|max:15',
            'email'         => 'required|string|email|max:255|unique:users',
            'password'      => 'required|string|min:8',
            'alamat'        => 'required|string',
        ]);

        DB::transaction(function () use ($request) {
            // A. Buat data login di tabel users terlebih dahulu
            $userId = DB::table('users')->insertGetId([
                'nama'       => $request->nama_pengajar,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                'role'       => 'pengajar',
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // B. Buat data biodata pengajar menggunakan id_user yang baru lahir
            DB::table('pengajar')->insert([
                'id_user'       => $userId,
                'nama_pengajar' => $request->nama_pengajar,
                'no_hp'         => $request->no_hp,
                'alamat'        => $request->alamat,
            ]);
        });

        return redirect()->route('admin.pengajar.index')->with('success', 'Data instruktur dan akun login baru berhasil dibuat!');
    }

    // 3. UPDATE: Perbarui Data Pengajar dan Sinkronkan Nama ke Tabel Users
    public function pengajarUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_pengajar' => 'required|string|max:255',
            'no_hp'         => 'required|string|max:15',
            'alamat'        => 'required|string',
        ]);

        DB::transaction(function () use ($request, $id) {
            // Ambil data pengajar lama untuk mendapatkan id_user
            $pengajar = DB::table('pengajar')->where('id_pengajar', $id)->first();

            if ($pengajar) {
                // A. Update tabel pengajar
                DB::table('pengajar')->where('id_pengajar', $id)->update([
                    'nama_pengajar' => $request->nama_pengajar,
                    'no_hp'         => $request->no_hp,
                    'alamat'        => $request->alamat,
                ]);

                // B. Update nama akun di tabel users agar sinkron
                DB::table('users')->where('id', $pengajar->id_user)->update([
                    'nama'       => $request->nama_pengajar,
                    'updated_at' => now(),
                ]);
            }
        });

        return redirect()->route('admin.pengajar.index')->with('success', 'Biodata pengajar berhasil diperbarui!');
    }

    // 4. DELETE: Hapus Data Pengajar Sekaligus Akun Loginnya di Users
    public function pengajarDelete($id)
    {
        DB::transaction(function () use ($id) {
            $pengajar = DB::table('pengajar')->where('id_pengajar', $id)->first();

            if ($pengajar) {
                // Hapus di kedua tabel sekaligus agar tidak ada data sampah menggantung
                DB::table('pengajar')->where('id_pengajar', $id)->delete();
                DB::table('users')->where('id', $pengajar->id_user)->delete();
            }
        });

        return redirect()->route('admin.pengajar.index')->with('success', 'Data pengajar dan akun login resmi dihapus dari sistem.');
    }

    // --- Bagian Gift (Hadiah) ---
    // -----------------------------------------------------------
    // A. SUB-MENU 1: LOGIKA CRUD DATA HADIAH (GIFT)
    // -----------------------------------------------------------
    // 1. CREATE: Simpan Item Hadiah & Unggah File Foto

    public function giftIndex()
    {
        $gifts = DB::table('gift')->get();
        return view('admin.gift.index', compact('gifts'));
    }
    public function giftStore(Request $request)
    {
        $request->validate([
            'nama_gift'       => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'poin_dibutuhkan' => 'required|integer|min:1',
            'stok'            => 'required|integer|min:0',
            'foto_gift'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // Batas maksimal gambar 2MB
        ]);

        $namaFileFoto = null;

        // Proses pengemasan file gambar ke folder public
        if ($request->hasFile('foto_gift')) {
            $file = $request->file('foto_gift');
            $namaFileFoto = 'gift_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/gifts'), $namaFileFoto);
        }

        DB::table('gift')->insert([
            'nama_gift'       => $request->nama_gift,
            'deskripsi'       => $request->deskripsi,
            'poin_dibutuhkan' => $request->poin_dibutuhkan,
            'stok'            => $request->stok,
            'foto_gift'       => $namaFileFoto,
            'created_at'      => now(),
            'updated_at'      => now()
        ]);

        return redirect()->route('admin.gift.index')->with('success', 'Katalog hadiah baru berhasil ditambahkan beserta foto!');
    }

    // 2. UPDATE: Perbarui Data & Ganti Foto (Hapus Berkas Foto Lama)
    public function giftUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_gift'       => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'poin_dibutuhkan' => 'required|integer|min:1',
            'stok'            => 'required|integer|min:0',
            'foto_gift'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $giftLama = DB::table('gift')->where('id_gift', $id)->first();
        $namaFileFoto = $giftLama->foto_gift; // Default gunakan file lama jika tidak ganti foto

        if ($request->hasFile('foto_gift')) {
            // A. Jika ada foto baru, hapus berkas fisik foto lama di folder proyek (jika ada)
            if ($giftLama->foto_gift && file_exists(public_path('uploads/gifts/' . $giftLama->foto_gift))) {
                unlink(public_path('uploads/gifts/' . $giftLama->foto_gift));
            }

            // B. Simpan berkas foto baru
            $file = $request->file('foto_gift');
            $namaFileFoto = 'gift_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/gifts'), $namaFileFoto);
        }

        DB::table('gift')->where('id_gift', $id)->update([
            'nama_gift'       => $request->nama_gift,
            'deskripsi'       => $request->deskripsi,
            'poin_dibutuhkan' => $request->poin_dibutuhkan,
            'stok'            => $request->stok,
            'foto_gift'       => $namaFileFoto,
            'updated_at'      => now()
        ]);

        return redirect()->route('admin.gift.index')->with('success', 'Data katalog hadiah berhasil diperbarui!');
    }

    // 3. DELETE: Hapus Data Kelas Sekaligus Hapus Berkas Gambar dari Penyimpanan
    public function giftDelete($id)
    {
        $gift = DB::table('gift')->where('id_gift', $id)->first();

        if ($gift) {
            // Hapus file gambar di harddisk agar tidak memenuhi storage folder
            if ($gift->foto_gift && file_exists(public_path('uploads/gifts/' . $gift->foto_gift))) {
                unlink(public_path('uploads/gifts/' . $gift->foto_gift));
            }

            DB::table('gift')->where('id_gift', $id)->delete();
        }

        return redirect()->route('admin.gift.index')->with('success', 'Hadiah beserta dokumen foto berhasil dihapus dari sistem.');
    }

    // -----------------------------------------------------------
    // B. SUB-MENU 2: LOGIKA VERIFIKASI PENGAJUKAN TUKAR POIN
    // -----------------------------------------------------------
        // TAMPILKAN DAFTAR ANTRIAN PENGAJUAN
    public function pengajuanGiftIndex()
    {
        // 🌟 PERBAIKAN 1: Gunakan leftJoin agar data tidak gaib jika ada relasi yang putus
        $pengajuan = DB::table('penukaran_point')
                        ->leftJoin('siswa', 'penukaran_point.id_siswa', '=', 'siswa.id_siswa')
                        ->leftJoin('users', 'siswa.id_user', '=', 'users.id')
                        ->leftJoin('pendaftar', 'pendaftar.id_user', '=', 'users.id')
                        ->leftJoin('gift', 'penukaran_point.id_gift', '=', 'gift.id_gift')
                        ->where('penukaran_point.status', 'proses')
                        ->select(
                            'penukaran_point.id_penukaran',
                            'penukaran_point.tanggal_penukaran',
                            'penukaran_point.status',
                            'pendaftar.nama_lengkap',
                            'siswa.total_point',
                            'gift.nama_gift',
                            // 🌟 PERHATIAN: Pastikan ini sama dengan kolom di database Anda!
                            'gift.poin_dibutuhkan',
                            'gift.stok'
                        )
                        ->get();
        return view('admin.gift.pengajuan', compact('pengajuan'));

    }

    // PROSES SETUJUI (ACC) PENGAJUAN
    public function pengajuanGiftSetujui($id)
    {
        $data = DB::table('penukaran_point')->where('id_penukaran', $id)->first();
        $gift = DB::table('gift')->where('id_gift', $data->id_gift)->first();
        $siswa = DB::table('siswa')->where('id_siswa', $data->id_siswa)->first();

        // Tetap lakukan validasi: Pastikan poin siswa masih cukup sebelum disetujui
        if ($siswa->total_point >= $gift->poin_dibutuhkan) {

            // 🌟 Cukup jalankan UPDATE status.
            // Setelah kode ini dieksekusi, MySQL Trigger otomatis memotong Poin & Stok!
            DB::table('penukaran_point')->where('id_penukaran', $id)->update([
                'status'     => 'selesai',
                'updated_at' => now()
            ]);

            return redirect()->route('admin.pengajuan_gift.index')->with('success', 'Penukaran poin berhasil disetujui! Poin dan stok otomatis terpotong oleh sistem.');
        }

        // Jika poin ternyata kurang (misal siswa melakukan double-click/spam request)
        return redirect()->route('admin.pengajuan_gift.index')->with('error', 'Gagal disetujui! Poin siswa tidak mencukupi.');
    }

    // PROSES TOLAK PENGAJUAN
    public function pengajuanGiftTolak($id)
    {
        DB::table('penukaran_point')->where('id_penukaran', $id)->update([
            'status'     => 'dibatalkan',
            'updated_at' => now()
        ]);

        return redirect()->route('admin.pengajuan_gift.index')->with('success', 'Permohonan penukaran poin berhasil ditolak.');
    }

    public function riwayatGiftIndex()
    {
        // Ambil data penukaran yang statusnya sudah diproses (Disetujui / Ditolak)
        $riwayat = DB::table('penukaran_point')
                        ->leftJoin('siswa', 'penukaran_point.id_siswa', '=', 'siswa.id_siswa')
                        ->leftJoin('users', 'siswa.id_user', '=', 'users.id')
                        ->leftJoin('pendaftar', 'pendaftar.id_user', '=', 'users.id')
                        ->leftJoin('gift', 'penukaran_point.id_gift', '=', 'gift.id_gift')
                        ->where('penukaran_point.status', '!=', 'proses')
                        ->select(
                            'penukaran_point.id_penukaran',
                            'penukaran_point.tanggal_penukaran',
                            'penukaran_point.updated_at',
                            'penukaran_point.status',
                            'pendaftar.nama_lengkap',
                            'gift.nama_gift',
                            'gift.poin_dibutuhkan'
                        )
                        ->orderBy('penukaran_point.updated_at', 'desc') // Urutkan yang terbaru diproses
                        ->get();

        return view('admin.gift.riwayat', compact('riwayat'));
    }

    // ===========================================================
    // SUB-MENU: CRUD GELOMBANG PENDAFTARAN
    // ===========================================================

    public function gelombangIndex()
    {
        $jadwal = DB::table('jadwal_pendaftaran')
            // 🌟 1. Prioritaskan status 'Buka' ke paling atas
            ->orderByRaw("CASE WHEN status = 'Buka' THEN 1 ELSE 2 END")

            // 🌟 2. Jika statusnya sama, urutkan dari ID terbaru ke terlama
            ->orderBy('id_jadwal_daftar', 'desc')

            ->get();

        return view('admin.gelombang.index', compact('jadwal'));
    }

    public function jadwalStore(Request $request)
    {
        $request->validate([
            'nama_gelombang'  => 'required|string|max:255',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status'          => 'required|in:buka,tutup',
        ]);

        DB::table('jadwal_pendaftaran')->insert([
            'nama_gelombang'  => $request->nama_gelombang,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status'          => $request->status,
            'created_at'      => now(),
            'updated_at'      => now()
        ]);

        return redirect()->back()->with('success', 'Gelombang pendaftaran berhasil ditambahkan!');
    }

    public function jadwalUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_gelombang'  => 'required|string|max:255',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status'          => 'required|in:buka,tutup',
        ]);

        // Jika status diubah ke Aktif, matikan status gelombang yang lain
        if ($request->status == 'Aktif') {
            DB::table('jadwal_pendaftaran')->where('id_jadwal_daftar', '!=', $id)->update(['status' => 'Nonaktif']);
        }

        DB::table('jadwal_pendaftaran')->where('id_jadwal_daftar', $id)->update([
            'nama_gelombang'  => $request->nama_gelombang,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status'          => $request->status,
            'updated_at'      => now()
        ]);

        return redirect()->back()->with('success', 'Data gelombang pendaftaran berhasil diperbarui!');
    }

    public function jadwalDelete($id)
    {
        DB::table('jadwal_pendaftaran')->where('id_jadwal_daftar', $id)->delete();
        return redirect()->back()->with('success', 'Gelombang pendaftaran berhasil dihapus.');
    }

    // =======================================================
    // HALAMAN PENEMPATAN KELAS & LEVEL SISWA BARU
    // =======================================================

    // 1. Tampilkan List Pendaftar yang Siap Ditempatkan Kelas (Termasuk History)
    public function penempatanIndex()
    {
        // Ambil pendaftar yang SUDAH mengisi Level Rekomendasi/Nilai dari Pengajar
        $siapTempatkan = DB::table('pendaftaran')
            ->join('pendaftar', 'pendaftaran.id_pendaftar', '=', 'pendaftar.id_pendaftar')
            ->leftJoin('pengajar', 'pendaftaran.id_pengajar', '=', 'pengajar.id_pengajar')
            ->leftJoin('users', 'pengajar.id_user', '=', 'users.id')
            ->leftJoin('level', 'pendaftaran.id_level_rekomendasi', '=', 'level.id_level') // Rekomendasi dari guru

            // 🌟 PERBAIKAN 1: Hubungkan ke tabel siswa dan kelas untuk mendeteksi status penempatan final
            ->leftJoin('siswa', 'pendaftar.id_user', '=', 'siswa.id_user')
            ->leftJoin('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')

            // ->where('pendaftar.status', 'proses') 🌟 BARIS INI DIHAPUS AGAR HISTORY TETAP MUNCUL
            ->whereNotNull('pendaftaran.id_level_rekomendasi') // Hanya yang sudah diperiksa pengajar

            ->select(
                'pendaftaran.*',
                'pendaftar.nama_lengkap',
                'pendaftar.id_user as id_user_pendaftar',
                'pendaftar.tingkat_sekolah',
                'pendaftar.status as status_pendaftar',
                'users.nama as nama_pengajar',
                'level.nama_level as rekomendasi_level',
                'siswa.id_siswa',
                'kelas.nama_kelas'
            )
            // 🌟 PERBAIKAN 2: Urutkan agar yang statusnya masih 'proses' (belum dapat kelas) berada di paling atas
            ->orderByRaw("FIELD(pendaftar.status, 'proses') DESC")
            ->orderBy('pendaftaran.updated_at', 'desc')
            ->paginate(10);

        // Ambil data Master Kelas dan Master Level untuk Dropdown Pilihan Admin
        $kelasList = DB::table('kelas')->get();
        $levelList = DB::table('level')->get();

        return view('admin.penempatan.index', compact('siapTempatkan', 'kelasList', 'levelList'));
    }

    // 2. Proses Pemindahan Pendaftar Menjadi Siswa Resmi (Eksekusi Setelah Pembayaran Lunas)
    public function prosesPenempatan(Request $request, $id_pendaftar)
    {
        $request->validate([
            'id_level' => 'required',
            'id_kelas' => 'required',
        ]);

        // Ambil data pendaftar untuk mendapatkan 'id_user'-nya
        $pendaftar = DB::table('pendaftar')->where('id_pendaftar', $id_pendaftar)->first();

        if (!$pendaftar) {
            return redirect()->back()->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // 🌟 KUNCI PENGAMAN: Pastikan status pembayaran sudah 'settlement' (lunas) di tabel pembayaran
        $pembayaran = DB::table('pembayaran')
            ->where('id_pendaftar', $id_pendaftar)
            ->where('status_verifikasi', 'settlement')
            ->first();

        if (!$pembayaran) {
            return redirect()->back()->with('error', 'Gagal memproses penempatan. Pendaftar ini belum melunasi tagihan pendaftaran.');
        }

        DB::beginTransaction();
        try {
            // A. UPDATE status pendaftar menjadi 'diterima' sesuai ENUM database
            DB::table('pendaftar')->where('id_pendaftar', $id_pendaftar)->update([
                'status'   => 'diterima',
                'id_level' => $request->id_level
            ]);

            // B. INSERT data baru ke tabel 'siswa'
            $id_siswa = DB::table('siswa')->insertGetId([
                'id_user'         => $pendaftar->id_user,
                'id_level'        => $request->id_level,
                'id_kelas'        => $request->id_kelas,
                'tingkat_sekolah' => $pendaftar->tingkat_sekolah,
                'tanggal_masuk'   => date('Y-m-d'),
                'status'          => 'aktif',
                'total_point'     => 0
            ]);

            // Bagian C dan D telah dihapus karena sudah diotomatisasi oleh Trigger di phpMyAdmin

            DB::commit();

            return redirect()->back()->with('success', 'Pendaftar berhasil ditempatkan ke kelas dan resmi menjadi Siswa aktif!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function pantauPembayaran()
    {
        $dataPembayaran = DB::table('pembayaran')
            ->join('pendaftar', 'pembayaran.id_pendaftar', '=', 'pendaftar.id_pendaftar')
            ->leftJoin('level', 'pendaftar.id_level', '=', 'level.id_level')
            ->select(
                'pembayaran.*',
                'pendaftar.nama_lengkap',
                'pendaftar.status as status_pendaftar',
                'level.nama_level'
            )

            ->where('pembayaran.order_id', 'like', 'TRX-%')

            ->orderByRaw("FIELD(pembayaran.status_verifikasi, 'settlement', 'pending', 'expire', 'cancel')")
            ->orderBy('pembayaran.updated_at', 'desc')
            ->get();

        return view('admin.pantau_pembayaran', compact('dataPembayaran'));
    }

    // CRUD JADWAL BELAJAR KELAS

    public function jadwalBelajarIndex(\Illuminate\Http\Request $request)
    {
        $query = DB::table('jadwal_belajar')
            ->join('kelas', 'jadwal_belajar.id_kelas', '=', 'kelas.id_kelas')
            ->join('pengajar', 'jadwal_belajar.id_pengajar', '=', 'pengajar.id_pengajar')
            ->join('users', 'pengajar.id_user', '=', 'users.id')
            ->leftJoin('level', 'jadwal_belajar.id_level', '=', 'level.id_level') // 🌟 BARU: Join ke tabel level
            ->select('jadwal_belajar.*', 'kelas.nama_kelas', 'users.nama as nama_pengajar', 'level.nama_level'); // 🌟 BARU: Ambil nama_level


        if ($request->has('filter_tanggal') && $request->filter_tanggal != '') {
            $query->where('jadwal_belajar.tanggal', $request->filter_tanggal);
        }


        if ($request->has('filter_kelas') && $request->filter_kelas != '') {
            $query->where('jadwal_belajar.id_kelas', $request->filter_kelas);
        }

        $jadwalList = $query->orderBy('jadwal_belajar.tanggal', 'desc')
                            ->orderBy('jadwal_belajar.jam_mulai', 'asc')
                            ->get();

        $kelasList = DB::table('kelas')->get();
        $levelList = DB::table('level')->get();
        $pengajarList = DB::table('pengajar')
            ->join('users', 'pengajar.id_user', '=', 'users.id')
            ->select('pengajar.id_pengajar', 'users.nama as nama_lengkap')
            ->get();


        return view('admin.jadwalbelajar.index', compact('jadwalList', 'kelasList', 'pengajarList', 'levelList'));
    }

    // 🌟 2. TAMBAHKAN FUNGSI TAMPILKAN HALAMAN EDIT
    public function jadwalEdit($id)
    {
        $jadwal = DB::table('jadwal_belajar')->where('id_jadwal', $id)->first();
        $kelasList = DB::table('kelas')->get();
        $levelList = DB::table('level')->get();
        $pengajarList = DB::table('pengajar')
            ->join('users', 'pengajar.id_user', '=', 'users.id')
            ->select('pengajar.id_pengajar', 'users.nama as nama_lengkap')
            ->get();

        return view('admin.jadwalbelajar.edit', compact('jadwal', 'kelasList', 'pengajarList', 'levelList'));
    }

    // 🌟 3. TAMBAHKAN FUNGSI PROSES UPDATE DATA
    public function jadwalBelajarUpdate(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'id_kelas'    => 'required',
            'id_level'    => 'required',
            'id_pengajar' => 'required',
            'tanggal'     => 'required|date',
            'hari'        => 'required',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
        ]);

        DB::table('jadwal_belajar')->where('id_jadwal', $id)->update([
            'id_kelas'    => $request->id_kelas,
            'id_level'    => $request->id_level,
            'id_pengajar' => $request->id_pengajar,
            'tanggal'     => $request->tanggal,
            'hari'        => $request->hari,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'keterangan'  => $request->keterangan,
            'updated_at'  => now()
        ]);

        return redirect()->route('admin.jadwalbelajar.index')->with('success', 'Jadwal berhasil diperbarui!');
    }

    // SIMPAN DATA JADWAL
    public function jadwalSimpan(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'id_kelas'    => 'required',
            'id_level'    => 'required',
            'id_pengajar' => 'required',
            'tanggal'     => 'required|date',
            'hari'        => 'required',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'keterangan'  => 'nullable|string'
        ]);

        DB::table('jadwal_belajar')->insert([
            'id_kelas'    => $request->id_kelas,
            'id_level'    => $request->id_level,
            'id_pengajar' => $request->id_pengajar,
            'tanggal'     => $request->tanggal,
            'hari'        => $request->hari,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'keterangan'  => $request->keterangan,
            'created_at'  => now(),
            'updated_at'  => now()
        ]);

        return redirect()->back()->with('success', 'Jadwal pembelajaran berhasil ditambahkan!');
    }

    // HAPUS JADWAL
    public function jadwalHapus($id)
    {
        DB::table('jadwal_belajar')->where('id_jadwal', $id)->delete();
        return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    }

    public function laporanPerkembangan(\Illuminate\Http\Request $request)
    {
        // 1. Ambil Input Filter (Mendukung 'id_*' atau 'filter_*' dari Blade)
        $selectedLevel = $request->input('id_level') ?? $request->input('filter_level');
        $selectedKelas = $request->input('id_kelas') ?? $request->input('filter_kelas');

        // 2. Metadata untuk Dropdown Filter
        $levels = DB::table('level')->get();

        $kelasQuery = DB::table('kelas');
        if ($selectedLevel) {
            $kelasQuery->where('id_level', $selectedLevel);
        }
        $kelasList = $kelasQuery->get();

        // 3. Query Top 3 Point Stars dengan Filter
        $topStarsQuery = DB::table('siswa')
            ->join('pendaftar', 'siswa.id_user', '=', 'pendaftar.id_user')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            ->leftJoin('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->select('siswa.*', 'pendaftar.nama_lengkap', 'siswa.foto_profil', 'kelas.nama_kelas')
            ->where('siswa.total_point', '>', 0);

        if ($selectedLevel) {
            $topStarsQuery->where('kelas.id_level', $selectedLevel);
        }
        if ($selectedKelas) {
            $topStarsQuery->where('siswa.id_kelas', $selectedKelas);
        }
        $topStars = $topStarsQuery->orderBy('siswa.total_point', 'desc')->limit(5)->get();

        // 4. Query Kehadiran Siswa dengan Filter
        $kehadiranSiswaQuery = DB::table('absensi')
            ->join('siswa', 'absensi.id_siswa', '=', 'siswa.id_siswa')
            ->join('pendaftar', 'siswa.id_user', '=', 'pendaftar.id_user')
            ->join('kelas', 'absensi.id_kelas', '=', 'kelas.id_kelas')
            ->select(
                'pendaftar.nama_lengkap',
                'kelas.nama_kelas',
                DB::raw('COUNT(absensi.id_absensi) as total_sesi'),
                DB::raw("SUM(CASE WHEN absensi.status = 'hadir' THEN 1 ELSE 0 END) as total_hadir")
            );

        if ($selectedLevel) {
            $kehadiranSiswaQuery->where('kelas.id_level', $selectedLevel);
        }
        if ($selectedKelas) {
            $kehadiranSiswaQuery->where('absensi.id_kelas', $selectedKelas);
        }
        $kehadiranSiswa = $kehadiranSiswaQuery->groupBy('absensi.id_siswa', 'pendaftar.nama_lengkap', 'kelas.nama_kelas')->get();

        // 5. Query Kehadiran Pengajar dengan Filter
        $kehadiranPengajarQuery = DB::table('absensi')
            ->join('kelas', 'absensi.id_kelas', '=', 'kelas.id_kelas')
            ->join('pengajar', 'kelas.id_pengajar', '=', 'pengajar.id_pengajar')
            ->select(
                'pengajar.nama_pengajar as nama',
                'kelas.nama_kelas',
                DB::raw('COUNT(DISTINCT absensi.tanggal) as total_mengajar')
            );

        if ($selectedLevel) {
            $kehadiranPengajarQuery->where('kelas.id_level', $selectedLevel);
        }
        if ($selectedKelas) {
            $kehadiranPengajarQuery->where('kelas.id_kelas', $selectedKelas);
        }
        $kehadiranPengajar = $kehadiranPengajarQuery->groupBy('pengajar.id_pengajar', 'pengajar.nama_pengajar', 'kelas.id_kelas', 'kelas.nama_kelas')->get();

        // 6. Query Top 3 Nilai Akhir dari Tabel Raport dengan Filter
        $topNilaiQuery = DB::table('raport')
            ->join('siswa', 'raport.id_siswa', '=', 'siswa.id_siswa')
            ->join('pendaftar', 'siswa.id_user', '=', 'pendaftar.id_user')
            ->join('users', 'siswa.id_user', '=', 'users.id')
            ->leftJoin('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
            ->select('siswa.*', 'pendaftar.nama_lengkap', 'siswa.foto_profil', 'kelas.nama_kelas', 'raport.rata_rata');

        if ($selectedLevel) {
            $topNilaiQuery->where('kelas.id_level', $selectedLevel);
        }
        if ($selectedKelas) {
            $topNilaiQuery->where('siswa.id_kelas', $selectedKelas);
        }
        $topNilai = $topNilaiQuery->orderBy('raport.rata_rata', 'desc')->limit(5)->get();

        // 7. 🌟 PERBAIKAN UTAMA: Query Data Grafik Dinamis Mengikuti Filter
        $chartQuery = DB::table('kelas')
            ->leftJoin('siswa', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            ->leftJoin('raport', 'siswa.id_siswa', '=', 'raport.id_siswa')
            ->select(
                'kelas.nama_kelas',
                DB::raw('ROUND(AVG(raport.rata_rata), 1) as avg_nilai'),
                DB::raw('ROUND(AVG(siswa.total_point), 1) as avg_stars')
            );

        if ($selectedLevel) {
            $chartQuery->where('kelas.id_level', $selectedLevel);
        }
        if ($selectedKelas) {
            $chartQuery->where('kelas.id_kelas', $selectedKelas);
        }

        $chartData = $chartQuery->groupBy('kelas.id_kelas', 'kelas.nama_kelas')->get();

        return view('admin.laporan.perkembangan', compact(
            'topStars', 'topNilai', 'levels', 'kelasList',
            'selectedLevel', 'selectedKelas', 'chartData',
            'kehadiranSiswa', 'kehadiranPengajar'
        ));
    }

    public function profil()
    {
        // Mengambil data langsung dari tabel users
        $profil = DB::table('users')->where('id', Auth::id())->first();

        // Ganti menjadi 'admin.profil' jika ini di dalam AdminController
        return view('admin.profil.index', compact('profil'));
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

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        DB::table('users')->where('id', $userId)->update($dataUpdate);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }


}
