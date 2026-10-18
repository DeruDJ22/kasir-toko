<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    protected $model = Barang::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->unique()->randomElement([
                'Beras Pandan Wangi 5kg',
                'Beras Premium 1kg',
                'Minyak Goreng 1L',
                'Minyak Goreng 2L',
                'Gula Pasir 1kg',
                'Gula Tebu 500g',
                'Mi Instan Goreng',
                'Mi Instan Kuah',
                'Kopi Sachet 3in1',
                'Kopi Hitam 200ml',
                'Teh Celup 25',
                'Garam Dapur 500g',
                'Tepung Terigu 1kg',
                'Telur Ayam 1kg',
                'Sabun Mandi Cair',
                'Pasta Gigi 120g',
                'Deterjen Bubuk 1kg',
                'Minyak Kayu Putih 60ml',
                'Biskuit Kaleng',
                'Kecap Manis 275ml',
            ]),
            'harga' => $this->faker->numberBetween(4000, 85000),
            'stok' => $this->faker->numberBetween(1, 50),
            'kategori_id' => Kategori::factory(),
        ];
    }
}
