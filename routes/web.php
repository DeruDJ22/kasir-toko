<?php

use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'role:admin'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('kategori', KategoriController::class)->except(['show']);
    Route::resource('barang', BarangController::class)->except(['show']);
    Route::resource('karyawan', KaryawanController::class)->except(['show']);
});

Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/cari', [PosController::class, 'search'])->name('pos.search');
    Route::post('/pos/bayar', [PosController::class, 'pay'])->name('pos.pay');
});

Route::middleware('auth')->group(function () {
    Route::get('/pos/struk/{penjualan}', [PosController::class, 'receipt'])->name('pos.receipt');
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/{penjualan}', [PenjualanController::class, 'show'])->name('penjualan.show');
});

require __DIR__.'/auth.php';
