<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Raport extends Model {
    protected $table = 'raport';
    protected $primaryKey = 'id_raport';
    protected $fillable = ['id_siswa', 'id_kelas', 'id_pengajar', 'nilai_speaking', 'nilai_listening', 'nilai_reading', 'nilai_writing', 'rata_rata', 'catatan_pengajar', 'tanggal_dibagikan'];

    public function siswa() { return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa'); }
    public function kelas() { return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas'); }
    public function pengajar() { return $this->belongsTo(Pengajar::class, 'id_pengajar', 'id_pengajar'); }
}
