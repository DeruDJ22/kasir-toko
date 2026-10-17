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

        $kategori = Kategori::factory()->count(5)->create();

        Barang::factory()->count(10)->create([
            'kategori_id' => fn () => $kategori->random()->id,
        ]);
    }
}
