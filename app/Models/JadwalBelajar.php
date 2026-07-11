<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JadwalBelajar extends Model {
    protected $table = 'jadwal_belajar';
    protected $primaryKey = 'id_jadwal';
    protected $fillable = ['id_kelas', 'id_pengajar', 'tanggal', 'hari', 'jam_mulai', 'jam_selesai', 'keterangan'];

    public function kelas() { return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas'); }
    public function pengajar() { return $this->belongsTo(Pengajar::class, 'id_pengajar', 'id_pengajar'); }
}
