<?php

namespace Tests\Feature;

use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KaryawanCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_karyawan_pages(): void
    {
        $this->get(route('karyawan.index'))->assertRedirect(route('login'));
        $this->get(route('karyawan.create'))->assertRedirect(route('login'));
    }

    public function test_kasir_cannot_access_karyawan_pages(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($kasir)
            ->get(route('karyawan.index'))
            ->assertForbidden();
    }

    public function test_admin_can_view_karyawan_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('karyawan.index'));
        $response->assertOk();
        $response->assertSee('Admin');
    }

    public function test_admin_can_create_karyawan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $data = [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'nip' => '1000000001',
            'role' => 'kasir',
            'password' => 'password',
        ];

        $response = $this->actingAs($admin)->post(route('karyawan.store'), $data);
        $response->assertRedirect(route('karyawan.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'nip' => '1000000001',
            'role' => 'kasir',
        ]);
    }

    public function test_nama_wajib(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $data = ['email' => 'test@example.com', 'nip' => '1000000002', 'role' => 'kasir', 'password' => 'password'];
        $response = $this->actingAs($admin)->post(route('karyawan.store'), $data);
        $response->assertSessionHasErrors('name');
        $this->assertSame(1, User::count());
    }

    public function test_email_wajib(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $data = ['name' => 'Test', 'nip' => '1000000003', 'role' => 'kasir', 'password' => 'password'];
        $response = $this->actingAs($admin)->post(route('karyawan.store'), $data);
        $response->assertSessionHasErrors('email');
    }

    public function test_email_harus_unik(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['email' => 'exists@example.com', 'name' => 'Existing', 'nip' => '1000000004', 'role' => 'admin']);
        $data = ['name' => 'Dup', 'email' => 'exists@example.com', 'nip' => '1000000005', 'role' => 'kasir', 'password' => 'password'];
        $response = $this->actingAs($admin)->post(route('karyawan.store'), $data);
        $response->assertSessionHasErrors('email');
        $this->assertCount(1, User::where('email', 'exists@example.com')->get());
    }

    public function test_nip_harus_unik(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['nip' => '2000000000', 'name' => 'Existing2', 'email' => 'existing2@example.com', 'role' => 'admin']);
        $data = ['name' => 'Dup2', 'email' => 'dup2@example.com', 'nip' => '2000000000', 'role' => 'kasir', 'password' => 'password'];
        $response = $this->actingAs($admin)->post(route('karyawan.store'), $data);
        $response->assertSessionHasErrors('nip');
        $this->assertCount(1, User::where('nip', '2000000000')->get());
    }

    public function test_role_harus_valid(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $data = ['name' => 'Bad Role', 'email' => 'bad@example.com', 'nip' => '3000000000', 'role' => 'invalid', 'password' => 'password'];
        $response = $this->actingAs($admin)->post(route('karyawan.store'), $data);
        $response->assertSessionHasErrors('role');
    }

    public function test_password_wajib_saat_membuat(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $data = ['name' => 'No Pass', 'email' => 'nopass@example.com', 'nip' => '4000000000', 'role' => 'kasir'];
        $response = $this->actingAs($admin)->post(route('karyawan.store'), $data);
        $response->assertSessionHasErrors('password');
    }

    public function test_admin_dapat_memperbarui_karyawan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $karyawan = User::factory()->create(['name' => 'Old', 'email' => 'old@example.com', 'nip' => '5000000000', 'role' => 'kasir']);
        $data = ['name' => 'New', 'email' => 'new@example.com', 'nip' => '5000000001', 'role' => 'admin', 'password' => 'newpassword'];
        $response = $this->actingAs($admin)->put(route('karyawan.update', $karyawan), $data);
        $response->assertRedirect(route('karyawan.index'));
        $this->assertDatabaseHas('users', ['id' => $karyawan->id, 'name' => 'New', 'role' => 'admin']);
    }

    public function test_admin_tidak_dapat_hapus_diri_sendiri(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
        $response = $this->delete(route('karyawan.destroy', $admin));
        $response->assertRedirect(route('karyawan.index'));
        $this->assertTrue($admin->exists);
    }

    public function test_admin_tidak_dapat_hapus_karyawan_dengan_penjualan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $kasir = User::factory()->create(['role' => 'kasir']);
        Penjualan::create([
            'kasir_id' => $kasir->id,
            'tanggal' => now()->toDateString(),
            'total' => 15000,
            'bayar' => 20000,
            'kembalian' => 5000,
        ]);
        $this->actingAs($admin);
        $response = $this->delete(route('karyawan.destroy', $kasir));
        $response->assertRedirect(route('karyawan.index'));
        $this->assertTrue($kasir->exists);
    }

    public function test_kasir_tidak_dapat_mengakses_halaman_karyawan(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $this->actingAs($kasir)->get(route('karyawan.index'))->assertForbidden();
    }
}
