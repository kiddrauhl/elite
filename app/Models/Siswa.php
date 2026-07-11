<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    // Mendefinisikan nama tabel secara eksplisit
    protected $table = 'siswa';

    // Mendefinisikan primary key (karena bukan 'id' bawaan Laravel)
    protected $primaryKey = 'id_siswa';

    // Kolom-kolom yang diizinkan untuk diisi (Mass Assignment)
    protected $fillable = [
        'id_user',
        'id_level',
        'id_kelas',
        'id_level_lanjutan',
        'tanggal_masuk',
        'status',
        'total_point',
        'tingkat_sekolah'
    ];

    /**
     * RELASI TABEL (Menyambungkan tabel)
     */

    // 1 Siswa terhubung dengan 1 Akun User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // 1 Siswa mengambil 1 Level kursus
    public function level()
    {
        return $this->belongsTo(Level::class, 'id_level', 'id_level');
    }

    // 1 Siswa tergabung dalam 1 Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    // 1 Siswa bisa memiliki banyak data Raport
    public function raport()
    {
        return $this->hasMany(Raport::class, 'id_siswa', 'id_siswa');
    }

    // 1 Siswa bisa mendapatkan banyak riwayat Point Stars
    public function pointStars()
    {
        return $this->hasMany(PointStars::class, 'id_siswa', 'id_siswa');
    }

    // 1 Siswa bisa melakukan banyak penukaran Point (Hadiah)
    public function penukaranPoint()
    {
        return $this->hasMany(PenukaranPoint::class, 'id_siswa', 'id_siswa');
    }
}
