<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JadwalPendaftaran extends Model {
    protected $table = 'jadwal_pendaftaran';
    protected $primaryKey = 'id_jadwal_daftar';
    protected $fillable = ['nama_gelombang', 'tanggal_mulai', 'tanggal_selesai', 'status'];

    public function pendaftaran() { return $this->hasMany(Pendaftaran::class, 'id_jadwal_daftar', 'id_jadwal_daftar'); }
}
