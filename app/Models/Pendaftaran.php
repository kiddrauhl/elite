<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model {
    protected $table = 'pendaftaran';
    protected $primaryKey = 'id_pendaftaran';
    protected $fillable = ['id_pendaftar', 'id_siswa', 'id_jadwal_daftar', 'nilai_test', 'tanggal', 'status'];

    public function pendaftar() { return $this->belongsTo(Pendaftar::class, 'id_pendaftar', 'id_pendaftar'); }
    public function siswa() { return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa'); } // Bisa Null jika baru
    public function jadwal() { return $this->belongsTo(JadwalPendaftaran::class, 'id_jadwal_daftar', 'id_jadwal_daftar'); }
}
