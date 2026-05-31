<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    protected $fillable = [
        'user_id',
        'nama_umkm',
        'alamat',
        'kategori_usaha',
        'latitude',
        'longitude',
        'status_verifikasi',
    ];
}
