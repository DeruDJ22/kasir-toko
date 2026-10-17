<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_barang_belongs_to_kategori(): void
    {
        $barang = Barang::factory()->create();

        $this->assertInstanceOf(Kategori::class, $barang->kategori);
    }

    public function test_kategori_soft_delete_keeps_barang_rows(): void
    {
        $kategori = Kategori::factory()->create();
        $barang = Barang::factory()->create(['kategori_id' => $kategori->id]);

        $kategori->delete();

        $this->assertSoftDeleted('kategoris', ['id' => $kategori->id]);
        $this->assertNull(Kategori::find($kategori->id));
        $this->assertNotNull(Barang::find($barang->id));
    }

    public function test_penjualan_has_details_and_kasir(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $penjualan = Penjualan::create([
            'kasir_id' => $kasir->id,
            'tanggal' => now()->toDateString(),
            'total' => 30000,
            'bayar' => 50000,
            'kembalian' => 20000,
        ]);

        $barang = Barang::factory()->create(['harga' => 15000]);
        PenjualanDetail::create([
            'penjualan_id' => $penjualan->id,
            'barang_id' => $barang->id,
            'qty' => 2,
            'harga' => 15000,
            'subtotal' => 30000,
        ]);

        $this->assertCount(1, $penjualan->fresh()->details);
        $this->assertSame($kasir->id, $penjualan->kasir->id);
        $this->assertSame(30000, $penjualan->details->first()->subtotal);
    }

    public function test_user_seeder_creates_admin_and_kasir(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', ['role' => 'admin', 'nip' => '1000000001']);
        $this->assertDatabaseHas('users', ['role' => 'kasir', 'nip' => '1000000002']);
        $this->assertSame(5, Kategori::count());
        $this->assertSame(10, Barang::count());
        $this->assertSame(2, User::whereIn('role', ['admin', 'kasir'])->count());
    }
}