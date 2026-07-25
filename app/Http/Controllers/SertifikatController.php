<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SertifikatController extends Controller
{
    public function verifikasiPublic($id_siswa, $id_level)
    {
        // 1. Ambil data siswa
        $siswa = DB::table('siswa')
            ->join('pendaftar', 'siswa.id_user', '=', 'pendaftar.id_user')
            ->where('siswa.id_siswa', $id_siswa)
            ->first();

        // 2. Ambil data level yang sedang diverifikasi
        $level = DB::table('level')
            ->where('id_level', $id_level)
            ->first();

        if (!$siswa || !$level) {
            abort(404, 'Data sertifikat tidak ditemukan atau tidak valid.');
        }

        // 3. Cek apakah di database kolom sertifikat_level_X untuk siswa ini ada isinya
        $kolom_sertifikat = 'sertifikat_level_' . $id_level;
        $status_valid = !is_null($siswa->$kolom_sertifikat);

        // 4. UBAH BARIS INI: Lemparkan ke halaman validasi_qr
        return view('sertifikat.validasi_qr', compact('siswa', 'level', 'status_valid'));
    }
}
