<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = [
        'waktu',
        'total_harga',
        'metode_pembayaran',
        'status',
        'paid_amount',
        'paid_at',
    ];

    public function details()
    {
        return $this->hasMany(\App\Models\PenjualanDetail::class, 'id_penjualan');
    }
}
