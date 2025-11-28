<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';

    // SESUAIKAN DENGAN NAMA KOLOM DI DATABASE
    protected $fillable = [
        'nama_produk',
        'stok',
        'harga',
        'gambar_produk', // <-- ini yang dipakai controller & migration
    ];

    // kolom di migration integer, jadi cast decimal:2 nggak perlu
    // boleh dihapus atau ganti ke integer
    // protected $casts = [
    //     'harga' => 'integer',
    // ];
}
