<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Gift extends Model {
    protected $table = 'gift';
    protected $primaryKey = 'id_gift';
    protected $fillable = ['nama_gift', 'deskripsi', 'poin_dibutuhkan', 'stok', 'foto_gift'];

    public function penukaranPoint() { return $this->hasMany(PenukaranPoint::class, 'id_gift', 'id_gift'); }
}
