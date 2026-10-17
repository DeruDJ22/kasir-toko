<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;

    protected $fillable = ['penjualan_id', 'barang_id', 'qty', 'harga', 'subtotal'];

    protected $casts = [
        'qty' => 'integer',
        'harga' => 'integer',
        'subtotal' => 'integer',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}