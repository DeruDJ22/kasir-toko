<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_laporan(): void
    {
        $this->get(route('laporan.index'))->assertRedirect(route('login'));
    }

    public function test_kasir_cannot_access_laporan(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)->get(route('laporan.index'))->assertForbidden();
    }

    public function test_admin_can_access_laporan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('laporan.index'))->assertOk();
    }

    public function test_laporan_shows_totals_and_best_selling(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kasir = User::factory()->create(['role' => 'kasir']);

        $beras = Barang::factory()->create(['nama' => 'Beras Premium']);
        $minyak = Barang::factory()->create(['nama' => 'Minyak Goreng 2L']);

        $this->buatPenjualan($kasir, $beras->id, 2, 25000);
        $this->buatPenjualan($kasir, $beras->id, 1, 25000);
        $this->buatPenjualan($kasir, $minyak->id, 3, 40000);

        $response = $this->actingAs($admin)->get(route('laporan.index'));

        $response->assertOk();
        $response->assertSee('Beras Premium');
        $response->assertSee('Minyak Goreng 2L');
        $response->assertSee('195.000', false);
        $response->assertSee('3');
    }

    public function test_laporan_filters_by_date(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kasir = User::factory()->create(['role' => 'kasir']);
        $barang = Barang::factory()->create(['nama' => 'Beras Premium']);

        $this->buatPenjualanDetail($kasir, $barang->id, 1, 10000, '2026-06-10');
        $this->buatPenjualanDetail($kasir, $barang->id, 5, 10000, '2026-07-01');

        $response = $this->actingAs($admin)
            ->get(route('laporan.index', ['preset' => 'custom', 'start' => '2026-06-01', 'end' => '2026-06-30']));

        $response->assertOk();
        $response->assertSee('Beras Premium');
        $response->assertSee('10.000', false);
        $response->assertDontSee('50.000', false);
    }

    private function buatPenjualan(User $kasir, int $barangId, int $qty, int $harga): Penjualan
    {
        $penjualan = Penjualan::create([
            'kasir_id' => $kasir->id,
            'tanggal' => now()->toDateString(),
            'total' => $qty * $harga,
            'bayar' => $qty * $harga,
            'kembalian' => 0,
        ]);
        PenjualanDetail::create([
            'penjualan_id' => $penjualan->id,
            'barang_id' => $barangId,
            'qty' => $qty,
            'harga' => $harga,
            'subtotal' => $qty * $harga,
        ]);

        return $penjualan;
    }

    private function buatPenjualanDetail(User $kasir, int $barangId, int $qty, int $harga, string $tanggal): void
    {
        $penjualan = Penjualan::create([
            'kasir_id' => $kasir->id,
            'tanggal' => $tanggal,
            'total' => $qty * $harga,
            'bayar' => $qty * $harga,
            'kembalian' => 0,
        ]);
        PenjualanDetail::create([
            'penjualan_id' => $penjualan->id,
            'barang_id' => $barangId,
            'qty' => $qty,
            'harga' => $harga,
            'subtotal' => $qty * $harga,
        ]);
    }
}