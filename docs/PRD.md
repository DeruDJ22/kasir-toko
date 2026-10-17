# PRD: Sistem Kasir Toko (Laravel + MySQL + Tailwind)

## Summary
Sistem kasir berbasis web yang menggunakan Laravel, MySQL, dan Tailwind CSS untuk menyediakan antrian login dengan otorisasi peran (admin dan kasir). Admin mengelola kategori, barang, karyawan, serta laporan penjualan yang dapat difilter. Kasir mengakses antrian POS langsung setelah login untuk mencari barang, menambahkan jumlah, memproses pembayaran, mencetak struk, dan mengurangi stok secara otomatis.

## Background
Banyak toko kecil masih menggunakan sistem kasir manual atau aplikasi yang tidak terintegrasi, menyebabkan kesalahan stok dan pelayanan yang lambat. Permukaan akan solusi digital yang terjangkau, mudah digunakan, dan mampu memberikan laporan penjualan real‑time untuk membantu pengambilan keputusan.

## Objective
- Meningkatkan kecepatan transaksi kasir dan mengurangi kesalahan input manual.
- Memberikan visibilitas stok dan penjualan secara real‑time kepada admin.
- Mengurangi waktu pelatihan kasir melalui antrian yang sederhana dan responsif.

## Market Segment
Pemilik toko ritel kecil‑menengah di Indonesia yang membutuhkan sistem kasir terintegrasi dengan manajemen inventaris dan laporan penjualan, dengan dua peran utama: admin (pemilik / manajer) dan kasir (operator harian).

## Value Proposition (6‑part JTBD)
**Who:** Pemilik toko kecil‑menengah dan kasir yang menjual barang dagangan melalui toko fisik.

**Why:** Mereka perlu mencatat penjualan secara akurat, mengontrol stok agar tidak habis atau berlebih, dan mendapatkan laporan harian/bulanan tanpa harus menggabungkan data dari berbagai sumber.

**What before:** Saat ini mereka menggunakan buku kasir manual, spreadsheet, atau aplikasi terpisah yang memerlukan input berulang dan rentan terhadap kesalahan manusia; laporan harus disusun secara manual setelah penutup hari.

**How:** Sistem menyediakan login berbasis peran; setelah login, admin dapat mengelola master data (kategori, barang, karyawan) melalui antrian CRUD, sementara kasir langsung dibawa ke halaman POS untuk mencari barang dengan autocomplete, menentukan jumlah, melihat total, melakukan pembayaran, dan mencetak struk. Setiap penjualan otomatis mengurangi stok barang di database, dan jika transaksi dibatalkan, stok dikembalikan.

**What after:** Transaksi cepat dan akurat, stok selalu sinkron dengan penjualan, laporan penjualan dapat difilter per tanggal, bulan, atau hari dengan satu klik, dan admin dapat memantau kinerja toko secara real‑time.

**Alternatives:** Sistem kasir tradisional (kasir manual), aplikasi spreadsheet, atau solusi berbayar yang kompleks dan memerlukan pelatihan lama. Pilihan kami karena dibangun dengan stack terbuka (Laravel + MySQL + Tailwind), dapat di‑hosting sendiri, dan mudah dikustomisasi.

**Value Proposition Statement:** Dengan Sistem Kasir Toko, pemilik dan kasir bisa melakukan transaksi penjualan dalam hitungan detik, stok diperbarui secara otomatis, dan laporan penjualan tetap akurat dan langsung dapat diakses, tanpa perlu alat terpisah atau pekerjaan manual berulang.

## Solution
### 7.1 UX/Prototypes
- Halaman login (email/password) dengan validasi role.
- Setelah login:
  - Admin: dashboard dengan navbar kiri (links: Dashboard, Kategori, Barang, Karyawan, Penjualan, Laporan).
  - Kasir: langsung diarahkan ke halaman POS (pencarian barang, keranjang, total, tombol Bayar, tombol Cetak Struk).
- CRUD sederhana untuk Kategori (nama, deskripsi), Barang (nama, harga, stok, kategori_id), Karyawan (nama, nip, role).
- Halaman Penjualan menampilkan tabel transaksi dengan filter tanggal (harian, bulanan, custom range) dan kolom total, kasir, serta detail item.
- Nota struk mencetak nama toko, tanggal, daftar item, jumlah, total, dan kasir.

### 7.2 Key Features
- Autentikasi Laravel dengan middleware role (admin vs kasir).
- RBAC sederhana: admin dapat mengakses semua modul; kasir hanya akses POS dan riwayat penjualan terbatas.
- Modul Kategori: create, read, update, delete (soft delete).
- Modul Barang: terkait ke kategori, dengan validasi stok tidak boleh negatif.
- Modul Karyawan: mencatat nama, NIP, dan role (admin/kasir).
- Modul Penjualan: mencatat header (kasir_id, tanggal, total) dan detail (barang_id, qty, harga, subtotal). Setiap penambahan ke keranjang pada POS mengurangi stok barang secara transaksional; jika transaksi dibatalkan atau di‑edit sebelum pembayaran final, stok dikembalikan.
- Filter penjualan: rentang tanggal, preset (hari ini, kemarin, bulan ini, bulan lalu).
- Integrasi pencetakan struk menggunakan window.print() atau printer terhubung via JavaScript.
- Notifikasi toast untuk sukses/gagal transaksi.
- Desain responsif dengan Tailwind CSS.

### 7.3 Technology
- Backend: Laravel 10.x (PHP 8.2+)
- Database: MySQL 5.7+ atau MariaDB
- Frontend: Blade templates + Tailwind CSS 3.x + Alpine.js (optional for interactivity)
- Autentikasi: Laravel Sanctum atau built‑in auth
- Penggilan struktur: Laravel Migrasi dan Seeder untuk data awal.

### 7.4 Assumptions and Open Questions
- Sistem akan di‑jalankan di lingkungannya sendiri (self‑hosted) – tidak memerlukan koneksi internet terus‑menerus kecuali untuk pembaruan.
- Printer struk yang didukung adalah printer thermal yang dapat diakses via browser (window.print) atau melalui esc/pos driver; tidak mencakup integrasi driver khusus.
- Toko hanya memiliki satu lokasi; penetapan multi‑toko akan dianggap sebagai fitur lanjutan.
- Metode pembayaran yang didukung awalnya adalah tunai; kartu/kredit akan ditambahkan di rilis kemudian.
- Apakah diperlukan fitur user management (ganti password, lupa password) – akan disertakan dalam auth Laravel default.

## Release
### Versi 1 (MVP)
- Login dengan role detection.
- Admin: CRUD Kategori, Barang, Karyawan.
- Kasir: Halaman POS dengan pencarian barang, input qty, total, tombol Bayar (tunai), Cetak Struk, otomatis mengurangi stok.
- Penjualan: riwayat transaksi filterable per tanggal/hari/bulan.
- Stok: berkurang saat penjualan selesai, dikembalikan jika transaksi dibatalkan sebelum pembayaran final.
- Dasbor admin menampilkan total penjualan hari ini dan stok rendah.

### Versi 2 (Iterasi selanjutnya)
- Role tambahan: manajer, pengawas.
- Pembayaran non‑tunai (kartu, dompet digital).
- Integrasi barcode scanner.
- Export laporan ke CSV/Excel.
- Multi‑toko / gudang.
- Offline mode dengan penyimpanan lokal dan sinkronisasi ketika kembali online.
- Pengaturan toko (nama, alamat, logo) untuk struk.

---
*Catatan: Bagian analisis kompetitor, rancangan UX detail, dan rencana pelatihan tidak disertakan dalam versi lean ini namun dapat ditambahkan bila diminta.*