<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Level extends Model {
    protected $table = 'level';
    protected $primaryKey = 'id_level';
    protected $fillable = ['nama_level', 'biaya', 'deskripsi'];

    public function kelas() { return $this->hasMany(Kelas::class, 'id_level', 'id_level'); }
    public function siswa() { return $this->hasMany(Siswa::class, 'id_level', 'id_level'); }
}
