<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmptyStateTest extends TestCase
{
    use RefreshDatabase;

    public function test_kategori_index_shows_empty_state_when_none(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('kategori.index'))
            ->assertOk()
            ->assertSee('Belum ada kategori. Tambahkan kategori pertama untuk mulai mengelola barang.');
    }

    public function test_kategori_index_shows_no_match_for_search(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('kategori.index', ['q' => 'zzzznomatch']))
            ->assertOk()
            ->assertSee('Tidak ada kategori yang cocok dengan "zzzznomatch".', false);
    }

    public function test_barang_index_shows_empty_state_when_none(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('barang.index'))
            ->assertOk()
            ->assertSee('Belum ada barang. Tambahkan barang pertama untuk mulai berjualan.');
    }

    public function test_karyawan_index_shows_no_match_for_search(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'Admin Utama']);

        $this->actingAs($admin)
            ->get(route('karyawan.index', ['q' => 'zzzznomatch']))
            ->assertOk()
            ->assertSee('Tidak ada karyawan yang cocok dengan "zzzznomatch".', false);
    }

    public function test_penjualan_index_shows_empty_state_when_no_transactions(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('penjualan.index'))
            ->assertOk()
            ->assertSee('Belum ada transaksi pada rentang ini.');
    }

    public function test_laporan_shows_empty_state_when_no_sales_in_range(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('laporan.index'))
            ->assertOk()
            ->assertSee('Tidak ada data penjualan pada rentang ini.')
            ->assertSee('Rp 0');
    }

    public function test_dashboard_shows_no_low_stock_message_when_stock_healthy(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Tidak ada barang stok rendah.');
    }

    public function test_pos_page_shows_empty_cart_message_initially(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($kasir)
            ->get(route('pos.index'))
            ->assertOk()
            ->assertSee('Keranjang kosong. Cari barang untuk mulai berjualan.');
    }
}
