<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KategoriCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_kategori_pages(): void
    {
        $this->get(route('kategori.index'))->assertRedirect(route('login'));
        $this->get(route('kategori.create'))->assertRedirect(route('login'));
    }

    public function test_kasir_cannot_access_kategori_pages(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)
            ->get(route('kategori.index'))
            ->assertForbidden();
    }

    public function test_admin_can_view_kategori_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Kategori::factory()->create(['nama' => 'Sembako']);
        Kategori::factory()->create(['nama' => 'Minuman']);

        $response = $this->actingAs($admin)->get(route('kategori.index'));

        $response->assertOk();
        $response->assertSee('Sembako');
        $response->assertSee('Minuman');
    }

    public function test_admin_can_create_kategori(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('kategori.store'), [
            'nama' => 'Bumbu Dapur',
            'deskripsi' => 'Bumbu masak instan',
        ]);

        $response->assertRedirect(route('kategori.index'));
        $this->assertDatabaseHas('kategoris', ['nama' => 'Bumbu Dapur']);
    }

    public function test_kategori_nama_must_be_unique_among_active(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Kategori::factory()->create(['nama' => 'Sembako']);

        $response = $this->actingAs($admin)->post(route('kategori.store'), [
            'nama' => 'Sembako',
        ]);

        $response->assertSessionHasErrors('nama');
        $this->assertSame(1, Kategori::count());
    }

    public function test_admin_can_update_kategori(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kategori = Kategori::factory()->create(['nama' => 'Lama']);

        $response = $this->actingAs($admin)->put(route('kategori.update', $kategori), [
            'nama' => 'Baru',
            'deskripsi' => 'Update',
        ]);

        $response->assertRedirect(route('kategori.index'));
        $this->assertDatabaseHas('kategoris', ['id' => $kategori->id, 'nama' => 'Baru']);
    }

    public function test_admin_cannot_delete_kategori_with_active_barang(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kategori = Kategori::factory()->create();
        Barang::factory()->create(['kategori_id' => $kategori->id]);

        $response = $this->actingAs($admin)->delete(route('kategori.destroy', $kategori));

        $response->assertRedirect(route('kategori.index'));
        $response->assertSessionHas('error');
        $this->assertNotNull(Kategori::find($kategori->id));
    }

    public function test_admin_can_delete_empty_kategori(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kategori = Kategori::factory()->create();

        $response = $this->actingAs($admin)->delete(route('kategori.destroy', $kategori));

        $response->assertRedirect(route('kategori.index'));
        $this->assertSoftDeleted('kategoris', ['id' => $kategori->id]);
    }
}
