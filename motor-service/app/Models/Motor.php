<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    protected $table = 'motors';

    protected $fillable = [
        'nama',
        'merk',
        'tipe',
        'tahun',
        'harga',
        'stok',
        'warna',
        'deskripsi',
        'gambar',
        'is_terlaris',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_terlaris' => 'boolean',
    ];
}
