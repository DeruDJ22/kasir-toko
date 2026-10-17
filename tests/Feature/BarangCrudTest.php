<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BarangCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_barang_pages(): void
    {
        $this->get(route('barang.index'))->assertRedirect(route('login'));
        $this->get(route('barang.create'))->assertRedirect(route('login'));
    }

    public function test_kasir_cannot_access_barang_pages(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)
            ->get(route('barang.index'))
            ->assertForbidden();
    }

    public function test_admin_can_view_barang_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kategori = Kategori::factory()->create(['nama' => 'Sembako']);
        Barang::factory()->create(['nama' => 'Beras', 'kategori_id' => $kategori->id]);
        Barang::factory()->create(['nama' => 'Gula Pasir', 'kategori_id' => $kategori->id]);

        $response = $this->actingAs($admin)->get(route('barang.index'));

        $response->assertOk();
        $response->assertSee('Beras');
        $response->assertSee('Gula Pasir');
    }

    public function test_admin_can_create_barang(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kategori = Kategori::factory()->create();

        $response = $this->actingAs($admin)->post(route('barang.store'), [
            'nama' => 'Minyak Goreng',
            'harga' => 18000,
            'stok' => 20,
            'kategori_id' => $kategori->id,
        ]);

        $response->assertRedirect(route('barang.index'));
        $this->assertDatabaseHas('barangs', ['nama' => 'Minyak Goreng', 'harga' => 18000]);
    }

    public function test_stok_cannot_be_negative(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kategori = Kategori::factory()->create();

        $response = $this->actingAs($admin)->post(route('barang.store'), [
            'nama' => 'Mi Instan',
            'harga' => 3000,
            'stok' => -5,
            'kategori_id' => $kategori->id,
        ]);

        $response->assertSessionHasErrors('stok');
        $this->assertSame(0, Barang::count());
    }

    public function test_harga_must_be_positive(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kategori = Kategori::factory()->create();

        $response = $this->actingAs($admin)->post(route('barang.store'), [
            'nama' => 'Kopi Sachet',
            'harga' => 0,
            'stok' => 10,
            'kategori_id' => $kategori->id,
        ]);

        $response->assertSessionHasErrors('harga');
    }

    public function test_kategori_id_must_be_active(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kategori = Kategori::factory()->create();
        $kategori->delete();

        $response = $this->actingAs($admin)->post(route('barang.store'), [
            'nama' => 'Teh Sachet',
            'harga' => 1500,
            'stok' => 15,
            'kategori_id' => $kategori->id,
        ]);

        $response->assertSessionHasErrors('kategori_id');
    }

    public function test_admin_can_update_barang(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $barang = Barang::factory()->create(['nama' => 'Lama', 'harga' => 10000]);

        $response = $this->actingAs($admin)->put(route('barang.update', $barang), [
            'nama' => 'Baru',
            'harga' => 12000,
            'stok' => $barang->stok,
            'kategori_id' => $barang->kategori_id,
        ]);

        $response->assertRedirect(route('barang.index'));
        $this->assertDatabaseHas('barangs', ['id' => $barang->id, 'nama' => 'Baru', 'harga' => 12000]);
    }

    public function test_admin_can_delete_barang(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $barang = Barang::factory()->create();

        $response = $this->actingAs($admin)->delete(route('barang.destroy', $barang));

        $response->assertRedirect(route('barang.index'));
        $this->assertSoftDeleted('barangs', ['id' => $barang->id]);
    }
}
