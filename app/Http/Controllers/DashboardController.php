<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $hariIni = today();

        $totalPenjualan = (int) Penjualan::whereDate('tanggal', $hariIni)->sum('total');
        $jumlahTransaksi = (int) Penjualan::whereDate('tanggal', $hariIni)->count();

        $stokRendah = Barang::with('kategori')
            ->where('stok', '<=', 10)
            ->orderBy('stok')
            ->limit(10)
            ->get();

        return view('dashboard', [
            'totalPenjualan' => $totalPenjualan,
            'jumlahTransaksi' => $jumlahTransaksi,
            'stokRendah' => $stokRendah,
        ]);
    }
}
