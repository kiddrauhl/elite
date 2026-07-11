<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;

    // Beritahu Laravel nama tabel yang benar (karena bahasa Indonesia)
    protected $table = 'pendaftar';
    protected $primaryKey = 'id_pendaftar';

    // Kolom yang boleh diisi
    protected $fillable = [
        'id_user',
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_lahir',
        'no_hp',
        'asal_sekolah',
        'tingkat_sekolah',
        'alamat',
        'id_level',
        'tanggal_daftar',
        'id_jadwal_daftar',
        'status'
    ];

    // Relasi ke tabel Users (1 Pendaftar milik 1 User)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
