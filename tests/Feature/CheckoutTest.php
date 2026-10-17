<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasir_can_pay_and_stock_is_reduced(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $barang = Barang::factory()->create(['harga' => 20000, 'stok' => 10]);

        $response = $this->actingAs($kasir)->postJson(route('pos.pay'), [
            'items' => [['id' => $barang->id, 'qty' => 3]],
            'bayar' => 70000,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.total', 60000)
            ->assertJsonPath('data.kembalian', 10000);

        $this->assertDatabaseHas('penjualans', [
            'kasir_id' => $kasir->id,
            'total' => 60000,
            'bayar' => 70000,
            'kembalian' => 10000,
        ]);

        $penjualan = Penjualan::first();
        $this->assertDatabaseHas('penjualan_details', [
            'penjualan_id' => $penjualan->id,
            'barang_id' => $barang->id,
            'qty' => 3,
            'harga' => 20000,
            'subtotal' => 60000,
        ]);

        $this->assertSame(7, $barang->fresh()->stok);
    }

    public function test_insufficient_stock_saves_nothing(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $barang = Barang::factory()->create(['stok' => 2]);

        $this->actingAs($kasir)
            ->postJson(route('pos.pay'), [
                'items' => [['id' => $barang->id, 'qty' => 5]],
                'bayar' => 100000,
            ])
            ->assertUnprocessable();

        $this->assertDatabaseCount('penjualans', 0);
        $this->assertDatabaseCount('penjualan_details', 0);
        $this->assertSame(2, $barang->fresh()->stok);
    }

    public function test_underpayment_is_rejected(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $barang = Barang::factory()->create(['harga' => 20000, 'stok' => 5]);

        $this->actingAs($kasir)
            ->postJson(route('pos.pay'), [
                'items' => [['id' => $barang->id, 'qty' => 2]],
                'bayar' => 30000,
            ])
            ->assertUnprocessable();

        $this->assertDatabaseCount('penjualans', 0);
        $this->assertSame(5, $barang->fresh()->stok);
    }

    public function test_unknown_or_zero_qty_items_are_rejected(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $barang = Barang::factory()->create(['stok' => 5]);

        $this->actingAs($kasir)
            ->postJson(route('pos.pay'), [
                'items' => [['id' => $barang->id, 'qty' => 0]],
                'bayar' => 50000,
            ])
            ->assertUnprocessable();

        $this->actingAs($kasir)
            ->postJson(route('pos.pay'), [
                'items' => [['id' => 999999, 'qty' => 1]],
                'bayar' => 50000,
            ])
            ->assertUnprocessable();

        $this->assertDatabaseCount('penjualans', 0);
    }

    public function test_guest_and_admin_cannot_pay(): void
    {
        $this->postJson(route('pos.pay'), [])->assertUnauthorized();

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->postJson(route('pos.pay'), [])
            ->assertForbidden();

        $this->assertDatabaseCount('penjualans', 0);
    }
}
