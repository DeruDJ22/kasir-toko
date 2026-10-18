<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => 'password',
                'nip' => '1000000001',
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'kasir@example.com'],
            [
                'name' => 'Kasir',
                'password' => 'password',
                'nip' => '1000000002',
                'role' => 'kasir',
            ]
        );

        $kategori = collect([
            'Sembako' => 'Bahan pokok rumah tangga: beras, minyak, gula, garam.',
            'Minuman' => 'Kopi sachet, teh, dan minuman siap saji.',
            'Bumbu Dapur' => 'Bumbu masak kering dan basah.',
            'Makanan Ringan' => 'Snack, biskuit, dan mi instan.',
            'Kebutuhan Rumah' => 'Deterjen, sabun, dan pembersih.',
        ])->map(fn (string $deskripsi, string $nama) => Kategori::firstOrCreate(
            ['nama' => $nama],
            ['deskripsi' => $deskripsi]
        ));

        $barang = [
            ['nama' => 'Beras Pandan Wangi 5kg', 'harga' => 72000, 'stok' => 18, 'kategori' => 'Sembako'],
            ['nama' => 'Minyak Goreng 2L', 'harga' => 36000, 'stok' => 24, 'kategori' => 'Sembako'],
            ['nama' => 'Gula Pasir 1kg', 'harga' => 18000, 'stok' => 30, 'kategori' => 'Sembako'],
            ['nama' => 'Garam Dapur 500g', 'harga' => 6000, 'stok' => 40, 'kategori' => 'Bumbu Dapur'],
            ['nama' => 'Mi Instan Goreng', 'harga' => 3500, 'stok' => 60, 'kategori' => 'Makanan Ringan'],
            ['nama' => 'Mi Instan Kuah', 'harga' => 3500, 'stok' => 50, 'kategori' => 'Makanan Ringan'],
            ['nama' => 'Kopi Sachet 3in1', 'harga' => 2000, 'stok' => 80, 'kategori' => 'Minuman'],
            ['nama' => 'Teh Celup 25', 'harga' => 9500, 'stok' => 35, 'kategori' => 'Minuman'],
            ['nama' => 'Deterjen Bubuk 1kg', 'harga' => 25000, 'stok' => 22, 'kategori' => 'Kebutuhan Rumah'],
            ['nama' => 'Sabun Mandi Cair 400ml', 'harga' => 22000, 'stok' => 15, 'kategori' => 'Kebutuhan Rumah'],
        ];

        foreach ($barang as $item) {
            $kat = $kategori->get($item['kategori']);
            if (! $kat) {
                continue;
            }
            Barang::firstOrCreate(
                ['nama' => $item['nama']],
                [
                    'harga' => $item['harga'],
                    'stok' => $item['stok'],
                    'kategori_id' => $kat->id,
                ]
            );
        }
    }
}
