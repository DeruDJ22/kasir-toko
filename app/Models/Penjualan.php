<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = ['kasir_id', 'tanggal', 'total', 'bayar', 'kembalian'];

    protected $casts = [
        'tanggal' => 'date',
        'total' => 'integer',
        'bayar' => 'integer',
        'kembalian' => 'integer',
    ];

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    public function details()
    {
        return $this->hasMany(PenjualanDetail::class);
    }
}