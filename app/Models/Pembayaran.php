<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model {
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    protected $fillable = ['id_pendaftar',
        'tanggal_bayar',
        'order_id',
        'jumlah_bayar',
        'snap_token',
        'tipe_pembayaran',
        'status_verifikasi'];

    public function pendaftar() { return $this->belongsTo(Pendaftar::class, 'id_pendaftar', 'id_pendaftar'); }
}
