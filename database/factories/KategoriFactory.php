<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriFactory extends Factory
{
    protected $model = Kategori::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->unique()->randomElement([
                'Sembako',
                'Minuman',
                'Bumbu Dapur',
                'Makanan Ringan',
                'Kebutuhan Rumah',
                'Perawatan Tubuh',
                'Produk Susu',
                'Bahan Kue',
            ]),
            'deskripsi' => $this->faker->sentence(5),
        ];
    }
}
