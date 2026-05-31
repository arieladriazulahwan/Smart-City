<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'umkm_id',
        'category_id',
        'nama_produk',
        'harga',
        'stok_manual',
        'stok_iot',
        'status_stok',
        'gambar',
        'deskripsi',
    ];
}
