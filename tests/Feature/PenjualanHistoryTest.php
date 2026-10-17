<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenjualanHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_penjualan_history(): void
    {
        $this->get(route('penjualan.index'))->assertRedirect(route('login'));
        $this->get(route('penjualan.show', 1))->assertRedirect(route('login'));
    }

    public function test_admin_sees_all_penjualan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kasirA = User::factory()->create(['role' => 'kasir', 'name' => 'Andi']);
        $kasirB = User::factory()->create(['role' => 'kasir', 'name' => 'Budi']);

        $this->buatPenjualan($kasirA, 50000);
        $this->buatPenjualan($kasirB, 75000);

        $response = $this->actingAs($admin)->get(route('penjualan.index'));

        $response->assertOk();
        $response->assertSee('Andi');
        $response->assertSee('Budi');
    }

    public function test_kasir_only_sees_own_penjualan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kasirA = User::factory()->create(['role' => 'kasir', 'name' => 'Andi']);
        $kasirB = User::factory()->create(['role' => 'kasir', 'name' => 'Budi']);

        $this->buatPenjualan($kasirA, 50000);
        $this->buatPenjualan($kasirB, 75000);

        $response = $this->actingAs($kasirA)->get(route('penjualan.index'));

        $response->assertOk();
        $response->assertSee('Andi');
        $response->assertDontSee('Budi');
    }

    public function test_preset_filters_work(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $today = Penjualan::create([
            'kasir_id' => $admin->id,
            'tanggal' => now()->toDateString(),
            'total' => 10000,
            'bayar' => 10000,
            'kembalian' => 0,
        ]);
        $yesterday = Penjualan::create([
            'kasir_id' => $admin->id,
            'tanggal' => now()->subDay()->toDateString(),
            'total' => 20000,
            'bayar' => 20000,
            'kembalian' => 0,
        ]);
        $lastMonth = Penjualan::create([
            'kasir_id' => $admin->id,
            'tanggal' => now()->subMonthNoOverflow()->startOfMonth()->addDays(2)->toDateString(),
            'total' => 30000,
            'bayar' => 30000,
            'kembalian' => 0,
        ]);

        $this->actingAs($admin)
            ->get(route('penjualan.index', ['preset' => 'hari_ini']))
            ->assertOk()
            ->assertSee('#' . str_pad((string) $today->id, 6, '0', STR_PAD_LEFT))
            ->assertDontSee('#' . str_pad((string) $yesterday->id, 6, '0', STR_PAD_LEFT))
            ->assertDontSee('#' . str_pad((string) $lastMonth->id, 6, '0', STR_PAD_LEFT));

        $this->actingAs($admin)
            ->get(route('penjualan.index', ['preset' => 'kemarin']))
            ->assertOk()
            ->assertSee('#' . str_pad((string) $yesterday->id, 6, '0', STR_PAD_LEFT))
            ->assertDontSee('#' . str_pad((string) $today->id, 6, '0', STR_PAD_LEFT));
    }

    public function test_custom_range_filter(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $inside = Penjualan::create([
            'kasir_id' => $admin->id,
            'tanggal' => '2026-06-10',
            'total' => 10000,
            'bayar' => 10000,
            'kembalian' => 0,
        ]);
        $outside = Penjualan::create([
            'kasir_id' => $admin->id,
            'tanggal' => '2026-07-01',
            'total' => 10000,
            'bayar' => 10000,
            'kembalian' => 0,
        ]);

        $this->actingAs($admin)
            ->get(route('penjualan.index', ['preset' => 'custom', 'start' => '2026-06-01', 'end' => '2026-06-30']))
            ->assertOk()
            ->assertSee('#' . str_pad((string) $inside->id, 6, '0', STR_PAD_LEFT))
            ->assertDontSee('#' . str_pad((string) $outside->id, 6, '0', STR_PAD_LEFT));
    }

    public function test_index_shows_totals_and_count(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->buatPenjualan($admin, 25000);
        $this->buatPenjualan($admin, 35000);

        $response = $this->actingAs($admin)->get(route('penjualan.index'));

        $response->assertOk();
        $response->assertSee('60.000', false);
        $response->assertSee('2');
    }

    public function test_show_displays_item_details(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $barang = Barang::factory()->create(['nama' => 'Beras Premium']);

        $penjualan = Penjualan::create([
            'kasir_id' => $kasir->id,
            'tanggal' => now()->toDateString(),
            'total' => 60000,
            'bayar' => 70000,
            'kembalian' => 10000,
        ]);
        PenjualanDetail::create([
            'penjualan_id' => $penjualan->id,
            'barang_id' => $barang->id,
            'qty' => 2,
            'harga' => 30000,
            'subtotal' => 60000,
        ]);

        $response = $this->actingAs($kasir)->get(route('penjualan.show', $penjualan));

        $response->assertOk()
            ->assertSee('Beras Premium')
            ->assertSee('60.000')
            ->assertSee('70.000')
            ->assertSee('10.000');
    }

    public function test_kasir_cannot_view_other_kasir_penjualan(): void
    {
        $kasirA = User::factory()->create(['role' => 'kasir']);
        $kasirB = User::factory()->create(['role' => 'kasir']);

        $penjualan = Penjualan::create([
            'kasir_id' => $kasirA->id,
            'tanggal' => now()->toDateString(),
            'total' => 10000,
            'bayar' => 10000,
            'kembalian' => 0,
        ]);

        $this->actingAs($kasirB)
            ->get(route('penjualan.show', $penjualan))
            ->assertForbidden();
    }

    public function test_admin_can_view_any_penjualan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kasir = User::factory()->create(['role' => 'kasir']);

        $penjualan = Penjualan::create([
            'kasir_id' => $kasir->id,
            'tanggal' => now()->toDateString(),
            'total' => 10000,
            'bayar' => 10000,
            'kembalian' => 0,
        ]);

        $this->actingAs($admin)
            ->get(route('penjualan.show', $penjualan))
            ->assertOk();
    }

    private function buatPenjualan(User $kasir, int $total): Penjualan
    {
        $barang = Barang::factory()->create();
        $penjualan = Penjualan::create([
            'kasir_id' => $kasir->id,
            'tanggal' => now()->toDateString(),
            'total' => $total,
            'bayar' => $total,
            'kembalian' => 0,
        ]);
        PenjualanDetail::create([
            'penjualan_id' => $penjualan->id,
            'barang_id' => $barang->id,
            'qty' => 1,
            'harga' => $total,
            'subtotal' => $total,
        ]);

        return $penjualan;
    }
}