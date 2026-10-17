<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_pos_page(): void
    {
        $this->get(route('pos.index'))->assertRedirect(route('login'));
    }

    public function test_admin_cannot_access_pos_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('pos.index'))
            ->assertForbidden();
    }

    public function test_kasir_can_view_pos_page(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $response = $this->actingAs($kasir)->get(route('pos.index'));

        $response->assertOk();
        $response->assertSee('Cari barang');
    }

    public function test_kasir_can_search_barang(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        Barang::factory()->create(['nama' => 'Beras Pandan Wangi', 'harga' => 68000, 'stok' => 12]);

        $response = $this->actingAs($kasir)->getJson(route('pos.search', ['q' => 'beras']));

        $response->assertOk()
            ->assertJsonStructure(['data' => [['id', 'nama', 'harga', 'stok', 'kategori']]])
            ->assertJsonPath('data.0.nama', 'Beras Pandan Wangi')
            ->assertJsonPath('data.0.harga', 68000);
    }

    public function test_kasir_search_returns_empty_without_match(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $response = $this->actingAs($kasir)->getJson(route('pos.search', ['q' => 'xyznonexistent']));

        $response->assertOk()
            ->assertJson(['data' => []]);
    }

    public function test_admin_cannot_search_barang(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->getJson(route('pos.search', ['q' => 'a']))
            ->assertForbidden();
    }

    public function test_search_excludes_out_of_stock(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        Barang::factory()->create(['nama' => 'Kopi Sachet', 'stok' => 4]);
        Barang::factory()->create(['nama' => 'Kopi Hitam', 'stok' => 0]);

        $response = $this->actingAs($kasir)->getJson(route('pos.search', ['q' => 'kopi']));

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nama', 'Kopi Sachet');
    }
}