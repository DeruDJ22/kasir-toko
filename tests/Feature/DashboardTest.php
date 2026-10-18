<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_dashboard(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_kasir_cannot_access_dashboard(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)->get(route('dashboard'))->assertForbidden();
    }

    public function test_admin_sees_today_sales_total_and_count(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kasir = User::factory()->create(['role' => 'kasir']);

        Penjualan::create([
            'kasir_id' => $kasir->id,
            'tanggal' => now()->toDateString(),
            'total' => 25000,
            'bayar' => 25000,
            'kembalian' => 0,
        ]);
        Penjualan::create([
            'kasir_id' => $kasir->id,
            'tanggal' => now()->toDateString(),
            'total' => 35000,
            'bayar' => 35000,
            'kembalian' => 0,
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('60.000', false);
        $response->assertSee('Transaksi hari ini');
    }

    public function test_dashboard_excludes_other_day_sales(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Penjualan::create([
            'kasir_id' => $admin->id,
            'tanggal' => now()->subDay()->toDateString(),
            'total' => 99999,
            'bayar' => 99999,
            'kembalian' => 0,
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertDontSee('99.999', false);
    }

    public function test_dashboard_lists_low_stock_barang_only(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kategori = Kategori::factory()->create(['nama' => 'Sembako']);

        Barang::factory()->create(['nama' => 'Beras Premium', 'stok' => 5, 'kategori_id' => $kategori->id]);
        Barang::factory()->create(['nama' => 'Gula Tebu', 'stok' => 40, 'kategori_id' => $kategori->id]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Beras Premium');
        $response->assertSee('Stok rendah');
        $response->assertDontSee('Gula Tebu');
    }
}
