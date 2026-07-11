<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model {
    protected $table = 'materi';
    protected $primaryKey = 'id_materi';
    protected $fillable = ['id_kelas', 'judul_materi', 'file_materi', 'deskripsi'];

    public function kelas() { return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas'); }
}
