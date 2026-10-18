<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Sistem Kasir Toko">
  </a>
</p>

## Tentang Sistem Ini

Sistem Kasir Toko adalah aplikasi point-of-sale (POS) berbasis web untuk toko ritel kecil. Ini menyediakan manajemen peran (`admin` dan `kasir`), manajemen produk, antrian POS, riwayat penjualan, dan laporan.

## Persyaratan

- PHP 8.4+
- MySQL 5.7+ (atau MariaDB)
- Node.js & npm
- Composer

## Instalasi

1. Clone repositori.
2. `composer install`
3. `npm install`
4. `cp .env.example .env`
5. `php artisan key:generate`
6. Edit `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` di `.env` kalau perlu.
7. `php artisan migrate --seed`

## Menjalankan Aplikasi

- **Pengembangan**:
  `npm run dev` (Vite dev server) lalu `php artisan serve` (aplikasi di http://localhost:8000)
- **Produksi**:
  `npm run build` lalu `php artisan serve --env=prod`

## Akun Seed

Setelah menjalankan migrasi + seed, dua akun dibuat:

- **Admin**: `admin@example.com` / `password` (NIP: `1000000001`, peran: `admin`)
- **Kasir**: `kasir@example.com` / `password` (NIP: `1000000002`, peran: `kasir`)

Gunakan kredensial ini untuk login. Admin diarahkan ke dashboard; kasir langsung ke halaman POS.

## Ringkasan Antrian

### Admin

- Dashboard
- Kategori
- Barang
- Karyawan (pengguna)
- Penjualan (riwayat transaksi)
- Laporan (ringkasan & barang terlaris)

### Kasir

- POS: pencarian barang JSON, keranjang Alpine, pembayaran tunai, cetak struk, riwayat penjualan pribadi.

## Catatan

- Semua teks UI dalam Bahasa Indonesia.
- Stok berkurang saat pembayaran checkout, bukan saat menambah ke keranjang.
- Jalankan `php artisan test` untuk suite pengujian; `npm run build` untuk aset produksi.
- File ini merupakan bagian dari proyek `kasir-toko` di branch `agent/build`.