<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PenukaranPoint extends Model {
    protected $table = 'penukaran_point';
    protected $primaryKey = 'id_penukaran';
    protected $fillable = ['id_siswa', 'id_gift', 'tanggal_penukaran', 'status'];

    public function siswa() { return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa'); }
    public function gift() { return $this->belongsTo(Gift::class, 'id_gift', 'id_gift'); }
}
