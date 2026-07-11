<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    // Mendefinisikan nama tabel
    protected $table = 'kelas';

    // Mendefinisikan primary key
    protected $primaryKey = 'id_kelas';

    // Kolom-kolom yang diizinkan untuk diisi
    protected $fillable = [
        'nama_kelas',
        'id_level',
        'id_pengajar',
        'deskripsi'
    ];

    /**
     * RELASI TABEL (Menyambungkan tabel)
     */

    // 1 Kelas dikhususkan untuk 1 Level tertentu
    public function level()
    {
        return $this->belongsTo(Level::class, 'id_level', 'id_level');
    }

    // 1 Kelas dipegang oleh 1 Pengajar (Wali Kelas / Instruktur)
    public function pengajar()
    {
        return $this->belongsTo(Pengajar::class, 'id_pengajar', 'id_pengajar');
    }

    // 1 Kelas menampung BANYAK Siswa
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'id_kelas');
    }

    // 1 Kelas memiliki BANYAK file Materi
    public function materi()
    {
        return $this->hasMany(Materi::class, 'id_kelas', 'id_kelas');
    }

    // 1 Kelas memiliki BANYAK Jadwal Belajar
    public function jadwalBelajar()
    {
        return $this->hasMany(JadwalBelajar::class, 'id_kelas', 'id_kelas');
    }
}
