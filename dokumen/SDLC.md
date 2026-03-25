# Ringkasan SDLC SIPA Sitanggang

Dokumen ini merangkum implementasi tahapan SDLC pada proyek Sistem Informasi Penjualan Toko Sitanggang.

## 1. Perencanaan dan Analisis Kebutuhan

- Kebutuhan fungsional disusun dalam dokumen use case:
  - `dokumen/Deskripsi_UC_SIPA_Final.docx`
- Aktor utama:
  - Owner
  - Admin
  - Kasir
- Lingkup proses bisnis:
  - Manajemen master data (user, kategori, produk, supplier, pelanggan)
  - Transaksi penjualan tunai/utang
  - Pelunasan piutang
  - Mutasi stok
  - Laporan analitik

## 2. Perancangan Sistem

- Perancangan use case:
  - `dokumen/UCD FINAL.jpg`
- Perancangan struktur data/relasi:
  - `dokumen/erd toko sitanggang.drawio.png`
- Implementasi skema basis data dalam migration Laravel:
  - Folder `database/migrations`

## 3. Implementasi

- Framework backend: Laravel (PHP)
- Frontend: Blade template + CSS custom + JavaScript
- Modul utama:
  - CRUD master data
  - Transaksi penjualan dan piutang
  - Mutasi stok
  - Laporan penjualan, utang, stok (dengan filter dan export PDF/Excel)
  - Laporan pengeluaran untuk owner/admin (total biaya dan laba/rugi)
  - Biaya operasional (kategori biaya + data biaya)

## 4. Pengujian

- Validasi input dilakukan di controller/form request.
- Pengujian fitur dasar framework tersedia di folder:
  - `tests/Feature`
  - `tests/Unit`
- Pengujian manual proses bisnis dilakukan pada:
  - Login per role
  - CRUD master data
  - Proses transaksi tunai/utang
  - Pelunasan utang
  - Mutasi stok
  - Filter dan export laporan

## 5. Deployment dan Pemeliharaan

- Konfigurasi deployment lokal menggunakan `.env` dan MySQL (XAMPP/phpMyAdmin).
- Panduan menjalankan aplikasi tersedia di `README.md`.
- Pemeliharaan dilakukan melalui:
  - Penambahan migration untuk perubahan struktur data
  - Pengelolaan versi melalui Git
  - Penyempurnaan modul sesuai feedback pengguna dan dosen pembimbing
