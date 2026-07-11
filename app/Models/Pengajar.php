<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pengajar extends Model {
    protected $table = 'pengajar';
    protected $primaryKey = 'id_pengajar';
    protected $fillable = ['id_user', 'nama_pengajar', 'no_hp', 'alamat'];

    public function user() { return $this->belongsTo(User::class, 'id_user', 'id'); }
    public function kelas() { return $this->hasMany(Kelas::class, 'id_pengajar', 'id_pengajar'); }
    public function jadwalBelajar() { return $this->hasMany(JadwalBelajar::class, 'id_pengajar', 'id_pengajar'); }
    public function pointStars() { return $this->hasMany(PointStars::class, 'id_pengajar', 'id_pengajar'); }
    public function raport() { return $this->hasMany(Raport::class, 'id_pengajar', 'id_pengajar'); }
}
