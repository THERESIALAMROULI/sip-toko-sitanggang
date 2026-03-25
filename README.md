# SIPA Toko Sitanggang

Sistem Informasi Penjualan berbasis Laravel untuk UMKM Toko Sitanggang.

## Palet warna UI

- `#0F2854`
- `#1C4D8D`
- `#4988C4`
- `#BDE8F5`

## Fitur utama

- Login multi-role: `admin`, `kasir`, `owner`
- Manajemen produk
- Manajemen pelanggan
- Manajemen kategori biaya operasional
- Pencatatan biaya operasional
- Input transaksi (tunai/utang)
- Piutang otomatis dari transaksi kredit
- Laporan penjualan + grafik + export PDF/Excel
- Laporan utang + export PDF/Excel
- Laporan stok + export PDF/Excel
- Laporan pengeluaran (total biaya dan laba/rugi)
- Dashboard ringkasan data toko

## Struktur tabel (sesuai ERD)

- `users`
- `kategoris`
- `suppliers`
- `produks`
- `pelanggans`
- `penjualans`
- `detail_penjualans`
- `piutangs`
- `stok_histories`
- `expense_categories`
- `expenses`

## Cara menjalankan (XAMPP/phpMyAdmin)

1. Install dependency backend:
   ```bash
   composer install
   ```
2. Install dependency frontend:
   ```bash
   npm install
   ```
3. Salin file environment lalu generate app key:
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```
4. Buat database baru di phpMyAdmin, misalnya: `sip_toko_sitanggang`.
5. Atur koneksi database di file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sip_toko_sitanggang
   DB_USERNAME=root
   DB_PASSWORD=
   ```
6. Jalankan migrasi + seeder:
   ```bash
   php artisan migrate --seed
   ```
7. Jalankan aplikasi:
   ```bash
   npm run dev
   php artisan serve
   ```
8. Buka: `http://127.0.0.1:8000`

## Akun default (hasil seeder)

- Admin: `theresia0424@gmail.com` / `admin456@!!!`
- Kasir: `kasir@example.com` / `password`
- Owner: `owner@example.com` / `password`

## Catatan

- Folder referensi desain ada di `dokumen/index.html`.
- Ringkasan tahapan SDLC ada di `dokumen/SDLC.md`.
- Semua data utama tersimpan di MySQL (phpMyAdmin).
