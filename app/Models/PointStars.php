<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PointStars extends Model {
    protected $table = 'point_stars';
    protected $primaryKey = 'id_point';
    protected $fillable = ['id_siswa', 'id_pengajar', 'jumlah_point', 'keterangan', 'tanggal'];

    public function siswa() { return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa'); }
    public function pengajar() { return $this->belongsTo(Pengajar::class, 'id_pengajar', 'id_pengajar'); }
}
