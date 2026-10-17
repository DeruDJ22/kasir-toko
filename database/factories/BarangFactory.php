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
            'nama' => $this->faker->randomElement([
                'Beras',
                'Minyak Goreng',
                'Gula Pasir',
                'Mi Instan',
                'Kopi Sachet',
            ]),
            'harga' => $this->faker->numberBetween(10000, 50000),
            'stok' => $this->faker->numberBetween(1, 50),
            'kategori_id' => Kategori::factory(),
        ];
    }
}
