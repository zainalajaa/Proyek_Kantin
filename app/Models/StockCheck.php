<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockCheck extends Model
{
    protected $fillable = [
        'id_produk',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'tanggal'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}

