# Ringkasan Detail Proyek SIPA Sitanggang

Dokumen ini adalah ringkasan menyeluruh workspace `sip-toko-sitanggang` pada kondisi saat ini. Fokus utamanya adalah kode aplikasi dan artefak lokal project. Direktori dependency pihak ketiga `vendor/` dan `node_modules/` tidak diinventaris per file karena bukan implementasi bisnis project, tetapi keberadaannya tetap dicatat sebagai dependency terpasang.

## 1. Snapshot Proyek

- Nama aplikasi: SIPA Sitanggang, sistem informasi penjualan toko berbasis Laravel. Referensi: `README.md:1`, `resources/views/layouts/admin.blade.php:7`.
- Stack utama: PHP 8.2, Laravel 12, Breeze, Vite, Tailwind CSS, Alpine.js. Referensi: `composer.json:8`, `composer.json:13`, `package.json:9`.
- Timezone dan locale aplikasi diarahkan ke Indonesia/Jakarta. Referensi: `config/app.php:7`.
- Role utama: `admin`, `kasir`, `owner`. Referensi: `database/migrations/0001_01_01_000000_buat_tabel_pengguna.php:15`, `resources/views/layouts/admin.blade.php:43`, `resources/views/layouts/admin.blade.php:73`, `resources/views/layouts/admin.blade.php:93`.
- Database utama dapat memakai MySQL, tetapi workspace ini juga menyimpan artefak SQLite lokal: `database/database.sqlite` dan file root `sip_toko_sitanggang` (binary SQLite, tidak punya nomor baris teks).

## 2. Gambaran Arsitektur

### 2.1 Alur aplikasi

1. Request masuk lewat `public/index.php:1`.
2. Laravel dibootstrap dari `bootstrap/app.php:5`.
3. Routing web aktif dari `routes/web.php:18` dan `routes/auth.php:5`.
4. Middleware `role` dipetakan di `bootstrap/app.php:11` ke `app/Http/Middleware/PeranMiddleware.php:8`.
5. Controller membaca/menulis model Eloquent pada `app/Models/*`.
6. View Blade pada `resources/views/*` merender halaman admin, kasir, owner, dan laporan.

### 2.2 Pembagian modul berdasarkan role

- Semua user login dapat membuka dashboard dan profil. Referensi: `routes/web.php:22`, `routes/web.php:66`.
- `admin` mengelola master data, biaya, stok, dan user. Referensi: `routes/web.php:26`.
- `kasir` mengelola transaksi, piutang, dan cek stok. Referensi: `routes/web.php:39`.
- `admin` dan `owner` membuka modul laporan. Referensi: `routes/web.php:55`.

## 3. Peta Fitur dan Komponen

### 3.1 Autentikasi dan profil

| Fitur | Route | Back-end | UI | Catatan |
|---|---|---|---|---|
| Form login | `routes/auth.php:5` | `SesiMasukController@create` di `app/Http/Controllers/Autentikasi/SesiMasukController.php:11` | `resources/views/autentikasi/masuk.blade.php:1` | Login memakai `username`, bukan email |
| Proses login | `routes/auth.php:8` | `SesiMasukController@store` di `app/Http/Controllers/Autentikasi/SesiMasukController.php:15` + request `PermintaanMasuk` di `app/Http/Requests/Autentikasi/PermintaanMasuk.php:23` | `resources/views/autentikasi/masuk.blade.php:14` | Ada rate limit dan cek status akun jika kolom `status` tersedia |
| Logout | `routes/auth.php:12` | `SesiMasukController@destroy` di `app/Http/Controllers/Autentikasi/SesiMasukController.php:21` | `resources/views/layouts/admin.blade.php:118` | Menghapus sesi dan regenerate token |
| Edit profil | `routes/web.php:67` | `ProfilController@edit` di `app/Http/Controllers/ProfilController.php:10` | `resources/views/profil/edit.blade.php:1` | Menampilkan nama, username, role |
| Update profil | `routes/web.php:68` | `ProfilController@update` di `app/Http/Controllers/ProfilController.php:16` + `PerbaruiProfilRequest` di `app/Http/Requests/PerbaruiProfilRequest.php:8` | `resources/views/profil/edit.blade.php:11` | Rule `username` aktif hanya jika kolom tersedia |
| Ubah password | `routes/auth.php:11` | `KataSandiController@update` di `app/Http/Controllers/Autentikasi/KataSandiController.php:10` | `resources/views/profil/edit.blade.php:48` | Validasi password lama + confirm password baru |

### 3.2 Dashboard

| Fitur | Route | Back-end | UI | Catatan |
|---|---|---|---|---|
| Dashboard operasional | `routes/web.php:22` | `DasborController@index` di `app/Http/Controllers/DasborController.php:11` | `resources/views/dashboard.blade.php:1` | Menghitung penjualan harian, total produk, total pelanggan, piutang berjalan |
| Tren bulan ini vs lalu | `app/Http/Controllers/DasborController.php:27` | `resources/views/dashboard.blade.php:117` | | Growth % dihitung dari omzet bulan ini vs bulan lalu |
| Ringkasan owner 12 bulan | `app/Http/Controllers/DasborController.php:69` | `resources/views/dashboard.blade.php:222` dan `resources/views/dashboard.blade.php:363` | | Menyusun top produk, utang terlama, line chart, bar chart |
| Piutang jatuh tempo | `app/Http/Controllers/DasborController.php:58` | `resources/views/dashboard.blade.php:322` | | Daftar 6 piutang overdue ditampilkan di dashboard |

### 3.3 Manajemen user

| Fitur | Route | Back-end | UI | Catatan |
|---|---|---|---|---|
| Daftar/filter user | `routes/web.php:27` | `ManajemenPenggunaController@index` di `app/Http/Controllers/ManajemenPenggunaController.php:9` | `resources/views/pengguna/index.blade.php:1` | Filter `q`, `role`, `status` |
| Tambah user | `routes/web.php:27` | `ManajemenPenggunaController@create` di `app/Http/Controllers/ManajemenPenggunaController.php:46`, `store` di `:52` | `resources/views/pengguna/create.blade.php:1` | `username` dan `status` bersifat conditional |
| Edit user | `routes/web.php:27` | `ManajemenPenggunaController@edit` di `:86`, `update` di `:92` | `resources/views/pengguna/edit.blade.php:1` | Password baru bersifat opsional |
| Toggle status | `routes/web.php:28` | `ManajemenPenggunaController@toggleStatus` di `app/Http/Controllers/ManajemenPenggunaController.php:128` | `resources/views/pengguna/index.blade.php:83` | Tidak bisa menonaktifkan akun yang sedang login |
| Hapus user | `routes/web.php:27` | `ManajemenPenggunaController@destroy` di `app/Http/Controllers/ManajemenPenggunaController.php:143` | `resources/views/pengguna/index.blade.php:92` | Tidak bisa menghapus akun aktif sendiri |

### 3.4 Master data produk, kategori, pelanggan, pemasok

| Modul | Route utama | Back-end | UI | Catatan |
|---|---|---|---|---|
| Kategori produk | `routes/web.php:30` | `KategoriController` di `app/Http/Controllers/KategoriController.php:9` | `resources/views/kategoris/index.blade.php:1`, `create.blade.php:1`, `edit.blade.php:1` | Hapus kategori diblokir jika masih dipakai produk |
| Produk | `routes/web.php:32` | `ProdukController` di `app/Http/Controllers/ProdukController.php:8` | `resources/views/produk/index.blade.php:1`, `create.blade.php:1`, `edit.blade.php:1` | Menyimpan harga beli/jual, stok, stok minimum, status aktif |
| Pelanggan | `routes/web.php:33` | `PelangganController` di `app/Http/Controllers/PelangganController.php:7` | `resources/views/pelanggan/index.blade.php:1`, `create.blade.php:1`, `edit.blade.php:1` | Field form memakai `name/phone/address`, model memetakan ke kolom Indonesia |
| Supplier | `routes/web.php:31` | `PemasokController` di `app/Http/Controllers/PemasokController.php:7` | `resources/views/pemasok/index.blade.php:1`, `create.blade.php:1`, `edit.blade.php:1` | Menyimpan status aktif dan jumlah histori stok |

### 3.5 Stok dan mutasi stok

| Fitur | Route | Back-end | UI | Catatan |
|---|---|---|---|---|
| Cek stok | `routes/web.php:40` | `CekStokController@index` di `app/Http/Controllers/CekStokController.php:9` | `resources/views/stok/check.blade.php:1` | Filter kata kunci, kategori, status stok |
| Riwayat mutasi stok | `routes/web.php:36` | `RiwayatStokController@index` di `app/Http/Controllers/RiwayatStokController.php:11` | `resources/views/stok_histories/index.blade.php:1` | Menampilkan mutasi, stok sebelum/sesudah, petugas |
| Tambah mutasi | `routes/web.php:36` | `RiwayatStokController@store` di `app/Http/Controllers/RiwayatStokController.php:63` | `resources/views/stok_histories/create.blade.php:1` | Mendukung `masuk`, `koreksi_tambah`, `koreksi_kurang` |
| Edit mutasi | `routes/web.php:36` | `RiwayatStokController@update` di `app/Http/Controllers/RiwayatStokController.php:120` | `resources/views/stok_histories/edit.blade.php:1` | Mengembalikan stok lama lalu menerapkan stok baru dalam transaksi DB |
| Hapus mutasi | `routes/web.php:36` | `RiwayatStokController@destroy` di `app/Http/Controllers/RiwayatStokController.php:183` | `resources/views/stok_histories/index.blade.php:68` | Menolak hapus jika stok menjadi negatif |

### 3.6 Transaksi penjualan

| Fitur | Route | Back-end | UI | Catatan |
|---|---|---|---|---|
| Daftar transaksi | `routes/web.php:42` | `PenjualanController@index` di `app/Http/Controllers/PenjualanController.php:15` | `resources/views/penjualan/index.blade.php:1` | Filter tanggal, pelanggan, metode bayar |
| Form transaksi | `routes/web.php:42` | `PenjualanController@create` di `app/Http/Controllers/PenjualanController.php:57` | `resources/views/penjualan/create.blade.php:1` | Preview subtotal, total, kembalian, jatuh tempo via JS |
| Simpan transaksi | `routes/web.php:42` | `PenjualanController@store` di `app/Http/Controllers/PenjualanController.php:74` + `SimpanTransaksiRequest` di `app/Http/Requests/SimpanTransaksiRequest.php:12` | `resources/views/penjualan/create.blade.php:5` | DB transaction: kurangi stok, simpan detail, buat piutang bila kredit |
| Detail transaksi | `routes/web.php:42` | `PenjualanController@show` di `app/Http/Controllers/PenjualanController.php:68` | `resources/views/penjualan/show.blade.php:1` | Ada tombol cetak browser dan blok informasi piutang |

### 3.7 Piutang

| Fitur | Route | Back-end | UI | Catatan |
|---|---|---|---|---|
| Daftar piutang | `routes/web.php:48` | `PiutangController@index` di `app/Http/Controllers/PiutangController.php:9` | `resources/views/piutang/index.blade.php:1` | Filter pelanggan, status, overdue only |
| Ubah status piutang | `routes/web.php:48` | `PiutangController@edit` di `app/Http/Controllers/PiutangController.php:80`, `update` di `:86` | `resources/views/piutang/edit.blade.php:1` | Menyimpan `paid_at` dan `paid_by` saat dilunasi |

### 3.8 Biaya operasional

| Modul | Route | Back-end | UI | Catatan |
|---|---|---|---|---|
| Kategori biaya | `routes/web.php:34` | `KategoriPengeluaranController` di `app/Http/Controllers/KategoriPengeluaranController.php:8` | `resources/views/kategori_pengeluaran/index.blade.php:1`, `create.blade.php:1`, `edit.blade.php:1` | Hapus diblokir jika sudah dipakai biaya |
| Pengeluaran operasional | `routes/web.php:35` | `PengeluaranController` di `app/Http/Controllers/PengeluaranController.php:8` | `resources/views/pengeluaran/index.blade.php:1`, `create.blade.php:1`, `edit.blade.php:1` | Menyimpan kategori, nominal, tanggal, catatan, petugas |

### 3.9 Laporan

| Laporan | Route | Back-end | UI | Catatan |
|---|---|---|---|---|
| Penjualan | `routes/web.php:62` | `LaporanController@sales` di `app/Http/Controllers/LaporanController.php:16` | `resources/views/laporan/sales.blade.php:1` | Grafik Chart.js + export Excel/PDF di `resources/views/laporan/sales.blade.php:10` dan `:162` |
| Piutang | `routes/web.php:56` | `LaporanController@receivables` di `app/Http/Controllers/LaporanController.php:93` | `resources/views/laporan/receivables.blade.php:1` | Export Excel/PDF di `resources/views/laporan/receivables.blade.php:10` dan `:119` |
| Stok | `routes/web.php:58` | `LaporanController@stock` di `app/Http/Controllers/LaporanController.php:141` | `resources/views/laporan/stock.blade.php:1` | Nilai persediaan + mutasi terbaru + export Excel/PDF di `resources/views/laporan/stock.blade.php:10` dan `:149` |
| Pengeluaran | `routes/web.php:60` | `LaporanController@expenses` di `app/Http/Controllers/LaporanController.php:204` | `resources/views/laporan/expenses.blade.php:1` | Grafik doughnut komposisi biaya di `resources/views/laporan/expenses.blade.php:152`; tidak ada export file |

## 4. Layer Data dan Skema

### 4.1 Tabel inti aktif

| Tabel | Migrasi | Model | Dipakai oleh |
|---|---|---|---|
| `users` | `database/migrations/0001_01_01_000000_buat_tabel_pengguna.php:8` | `app/Models/Pengguna.php:8` | auth, user management, stok, transaksi, biaya |
| `kategoris` | `database/migrations/2026_03_19_000000_buat_tabel_inti_penjualan.php:10` | `app/Models/Kategori.php:6` | produk, stok, laporan |
| `produks` | `database/migrations/2026_03_19_000000_buat_tabel_inti_penjualan.php:24` | `app/Models/Produk.php:7` | transaksi, stok, dashboard, laporan |
| `pelanggans` | `database/migrations/2026_03_19_000000_buat_tabel_inti_penjualan.php:37` | `app/Models/Pelanggan.php:5` | transaksi, piutang, laporan |
| `penjualans` | `database/migrations/2026_03_19_000000_buat_tabel_inti_penjualan.php:46` | `app/Models/Penjualan.php:5` | dashboard, transaksi, laporan |
| `detail_penjualans` | `database/migrations/2026_03_19_000000_buat_tabel_inti_penjualan.php:59` | `app/Models/DetailPenjualan.php:4` | detail transaksi, top product |
| `piutangs` | `database/migrations/2026_03_19_000000_buat_tabel_inti_penjualan.php:71` | `app/Models/Piutang.php:5` | dashboard, piutang, laporan |
| `suppliers` | `database/migrations/2026_03_19_010000_buat_tabel_pemasok_dan_riwayat_stok.php:9` | `app/Models/Pemasok.php:6` | mutasi stok |
| `stok_histories` | `database/migrations/2026_03_19_010000_buat_tabel_pemasok_dan_riwayat_stok.php:20` | `app/Models/RiwayatStok.php:6` | stok admin, laporan stok |
| `expense_categories` | `database/migrations/2026_03_21_000100_buat_tabel_kategori_pengeluaran_dan_pengeluaran.php:9` | `app/Models/KategoriPengeluaran.php:6` | biaya dan laporan pengeluaran |
| `expenses` | `database/migrations/2026_03_21_000100_buat_tabel_kategori_pengeluaran_dan_pengeluaran.php:18` | `app/Models/Pengeluaran.php:6` | biaya operasional dan laporan |

### 4.2 Kolom tambahan dan migrasi evolusi

- Kolom `uang_diterima` dan `kembalian` pada `penjualans`, serta `jatuh_tempo` pada `piutangs`, ditambahkan di `database/migrations/2026_03_20_000001_tambah_kolom_jatuh_tempo_dan_uang_tunai.php:7`.
- `username` pada `users` ditambahkan dan dibackfill unik di `database/migrations/2026_03_25_000000_tambah_username_pada_tabel_pengguna.php:9`.
- Tabel lama versi Inggris (`products`, `customers`, `transactions`, `transaction_details`, `receivables`) dibuat di migrasi `2026_01_30_*` lalu dihapus di `database/migrations/2026_03_20_000002_hapus_tabel_lama_penjualan.php:6`.
- Tabel sesi, antrian, dan cache dibersihkan lewat `database/migrations/2026_03_25_200000_hapus_tabel_sesi.php:6` dan `database/migrations/2026_03_25_210000_hapus_tabel_antrian_dan_cache.php:6`.

### 4.3 Pola model yang penting

- `Produk`, `Pelanggan`, `Penjualan`, `Piutang`, dan `DetailPenjualan` memakai accessor/mutator untuk menjembatani nama atribut Inggris di kode dengan nama kolom Indonesia di DB. Referensi: `app/Models/Produk.php:28`, `app/Models/Pelanggan.php:14`, `app/Models/Penjualan.php:24`, `app/Models/Piutang.php:24`, `app/Models/DetailPenjualan.php:15`.
- `Penjualan` mengisi otomatis `no_nota`, `user_id`, dan `status` saat create. Referensi: `app/Models/Penjualan.php:74`.
- `Produk` memberi default `kategori_id`, `stok_minimum`, dan `aktif` saat create. Referensi: `app/Models/Produk.php:57`.
- `Pengguna` menyelaraskan `name` dan `nama` jika kolom legacy masih ada. Referensi: `app/Models/Pengguna.php:40`.

## 5. Frontend dan Layout

- Layout utama admin ada di `resources/views/layouts/admin.blade.php:1`, berisi sidebar role-based, topbar, alert global, dan JS toggle sidebar di `:162`.
- Layout guest/login ada di `resources/views/layouts/guest.blade.php:1`.
- Styling utama seluruh aplikasi ada di `resources/css/app.css:1`; file ini tidak sekadar Tailwind utility, tetapi CSS custom lengkap untuk layout, cards, form, table, auth page, dan responsive behavior.
- Bootstrap frontend minimal ada di `resources/js/app.js:1` dan `resources/js/bootstrap.js:1`.
- Report sales, receivables, dan stock memuat CDN `Chart.js`, `jsPDF`, `jspdf-autotable`, dan `xlsx` di file Blade masing-masing.

## 6. Konfigurasi dan Infrastruktur

- Bootstrapping Laravel dan alias middleware: `bootstrap/app.php:5`.
- Provider aplikasi terdaftar di `bootstrap/providers.php:2`.
- `AppServiceProvider` belum punya custom boot/register logic. Referensi: `app/Providers/AppServiceProvider.php:6`.
- Default auth provider memakai model `App\Models\Pengguna`. Referensi: `config/auth.php:13`.
- Database connection bawaan mendukung sqlite/mysql/mariadb/pgsql/sqlsrv. Referensi: `config/database.php:4`.
- Config lain masih standar Laravel tanpa custom domain logic yang menonjol: `config/cache.php:1`, `config/filesystems.php:1`, `config/logging.php:1`, `config/mail.php:1`, `config/queue.php:1`, `config/services.php:1`, `config/session.php:1`.
- Build frontend via Vite: `vite.config.js:1`, `postcss.config.js:1`, `tailwind.config.js:1`.
- Composer scripts penting: `composer.json:35` untuk setup/dev/test.

## 7. Seeder, Sample Data, dan Testing

- Seeder utama ada di `database/seeders/DatabaseSeeder.php:11`.
- Seeder membuat user default, kategori, produk, supplier, pelanggan, kategori biaya, biaya contoh, dan transaksi contoh. Referensi: `database/seeders/DatabaseSeeder.php:13`, `:16`, `:35`, `:61`, `:79`, `:97`, `:115`, `:191`.
- Factory user ada di `database/factories/PenggunaFactory.php:11`.
- PHPUnit sudah dikonfigurasi untuk `tests/Unit` dan `tests/Feature` di `phpunit.xml:7`, tetapi pada workspace saat ini file-file test sudah tidak ada lagi.

## 8. Temuan Penting Saat Menelusuri Kode

1. Fitur status user bersifat opsional di kode, tetapi tidak ada migrasi aktif yang menambahkan kolom `status` ke tabel `users`.
   Referensi: `app/Http/Controllers/ManajemenPenggunaController.php:11`, `app/Http/Controllers/ManajemenPenggunaController.php:63`, `app/Http/Requests/Autentikasi/PermintaanMasuk.php:32`.

2. `README.md` menyebut password default kasir dan owner adalah `password`, tetapi seeder menulis password berbeda.
   Referensi: `README.md:79`, `README.md:80`, `README.md:81`, `database/seeders/DatabaseSeeder.php:13`, `database/seeders/DatabaseSeeder.php:14`, `database/seeders/DatabaseSeeder.php:15`.

3. `README.md` menyebut laporan pengeluaran punya export PDF/Excel, tetapi implementasi export hanya terlihat pada laporan penjualan, piutang, dan stok.
   Referensi: `README.md:21`, `README.md:22`, `README.md:23`, `resources/views/laporan/sales.blade.php:10`, `resources/views/laporan/receivables.blade.php:10`, `resources/views/laporan/stock.blade.php:10`, `resources/views/laporan/expenses.blade.php:1`.

4. Nama kasir `"Bony"` di-hardcode pada beberapa tampilan transaksi.
   Referensi: `resources/views/penjualan/index.blade.php:75`, `resources/views/penjualan/show.blade.php:33`.

5. Workspace ini menyimpan lebih dari satu artefak database SQLite: `database/database.sqlite` dan file root `sip_toko_sitanggang`.

## 9. Inventaris File Proyek

### 9.1 Root dan file infrastruktur

- `README.md` - deskripsi proyek, fitur, langkah setup, akun default. Ref: `README.md:1`.
- `composer.json` - dependency backend dan script setup/dev/test. Ref: `composer.json:1`.
- `composer.lock` - lock dependency Composer. Artefak dependency.
- `package.json` - dependency frontend dan script `vite`. Ref: `package.json:1`.
- `package-lock.json` - lock dependency npm. Artefak dependency.
- `artisan` - entrypoint CLI Laravel. Ref: `artisan:1`.
- `phpunit.xml` - konfigurasi test suite. Ref: `phpunit.xml:1`.
- `vite.config.js` - konfigurasi Vite input CSS/JS. Ref: `vite.config.js:1`.
- `tailwind.config.js` - konfigurasi Tailwind. Ref: `tailwind.config.js:1`.
- `postcss.config.js` - plugin PostCSS. Ref: `postcss.config.js:1`.
- `.env.example` - template environment. Ref: `.env.example:1`.
- `.env` - environment lokal; tidak dirinci karena berpotensi berisi kredensial.
- `.editorconfig` - style editor. Ref: `.editorconfig:1`.
- `.gitattributes` - atribut Git. Ref: `.gitattributes:1`.
- `.gitignore` - file/folder yang di-ignore Git. Ref: `.gitignore:1`.
- `.phpunit.result.cache` - cache hasil test lokal.
- `sip_toko_sitanggang` - binary SQLite lokal, bukan file teks.

### 9.2 `app/Http/Controllers`

- `app/Http/Controllers/Controller.php` - base controller kosong. Ref: `app/Http/Controllers/Controller.php:1`.
- `app/Http/Controllers/DasborController.php` - logika dashboard dan insight owner. Ref: `app/Http/Controllers/DasborController.php:11`.
- `app/Http/Controllers/CekStokController.php` - filter stok cepat untuk kasir. Ref: `app/Http/Controllers/CekStokController.php:9`.
- `app/Http/Controllers/KategoriController.php` - CRUD kategori produk. Ref: `app/Http/Controllers/KategoriController.php:9`.
- `app/Http/Controllers/KategoriPengeluaranController.php` - CRUD kategori biaya. Ref: `app/Http/Controllers/KategoriPengeluaranController.php:8`.
- `app/Http/Controllers/ProdukController.php` - CRUD produk. Ref: `app/Http/Controllers/ProdukController.php:8`.
- `app/Http/Controllers/PelangganController.php` - CRUD pelanggan. Ref: `app/Http/Controllers/PelangganController.php:7`.
- `app/Http/Controllers/PemasokController.php` - CRUD supplier. Ref: `app/Http/Controllers/PemasokController.php:7`.
- `app/Http/Controllers/PengeluaranController.php` - CRUD biaya operasional + filter. Ref: `app/Http/Controllers/PengeluaranController.php:8`.
- `app/Http/Controllers/PenjualanController.php` - daftar transaksi, form transaksi, simpan transaksi, detail transaksi. Ref: `app/Http/Controllers/PenjualanController.php:15`, `:57`, `:68`, `:74`.
- `app/Http/Controllers/PiutangController.php` - daftar piutang, edit status, update pelunasan. Ref: `app/Http/Controllers/PiutangController.php:9`, `:80`, `:86`.
- `app/Http/Controllers/ProfilController.php` - edit/update profil user login. Ref: `app/Http/Controllers/ProfilController.php:10`.
- `app/Http/Controllers/RiwayatStokController.php` - filter, tambah, edit, hapus mutasi stok. Ref: `app/Http/Controllers/RiwayatStokController.php:11`, `:63`, `:120`, `:183`.
- `app/Http/Controllers/ManajemenPenggunaController.php` - manajemen user lengkap. Ref: `app/Http/Controllers/ManajemenPenggunaController.php:9`.
- `app/Http/Controllers/LaporanController.php` - empat laporan utama. Ref: `app/Http/Controllers/LaporanController.php:16`, `:93`, `:141`, `:204`.
- `app/Http/Controllers/Autentikasi/SesiMasukController.php` - login/logout. Ref: `app/Http/Controllers/Autentikasi/SesiMasukController.php:11`.
- `app/Http/Controllers/Autentikasi/KataSandiController.php` - update password. Ref: `app/Http/Controllers/Autentikasi/KataSandiController.php:10`.

### 9.3 `app/Http/Middleware` dan `app/Http/Requests`

- `app/Http/Middleware/PeranMiddleware.php` - pembatas akses berdasarkan role. Ref: `app/Http/Middleware/PeranMiddleware.php:8`.
- `app/Http/Requests/SimpanTransaksiRequest.php` - validasi transaksi penjualan. Ref: `app/Http/Requests/SimpanTransaksiRequest.php:12`.
- `app/Http/Requests/PerbaruiProfilRequest.php` - validasi update profil. Ref: `app/Http/Requests/PerbaruiProfilRequest.php:8`.
- `app/Http/Requests/Autentikasi/PermintaanMasuk.php` - validasi + autentikasi + rate limiting login. Ref: `app/Http/Requests/Autentikasi/PermintaanMasuk.php:23`.

### 9.4 `app/Models`

- `app/Models/Pengguna.php` - model user/auth/role helper. Ref: `app/Models/Pengguna.php:8`.
- `app/Models/Kategori.php` - model kategori produk. Ref: `app/Models/Kategori.php:6`.
- `app/Models/Produk.php` - model produk, accessor price/stock/name, default create, relasi. Ref: `app/Models/Produk.php:7`.
- `app/Models/Pelanggan.php` - model pelanggan dengan mapping `name/phone/address`. Ref: `app/Models/Pelanggan.php:5`.
- `app/Models/Pemasok.php` - model supplier. Ref: `app/Models/Pemasok.php:6`.
- `app/Models/RiwayatStok.php` - model mutasi stok. Ref: `app/Models/RiwayatStok.php:6`.
- `app/Models/Penjualan.php` - model transaksi penjualan. Ref: `app/Models/Penjualan.php:5`.
- `app/Models/DetailPenjualan.php` - model item transaksi. Ref: `app/Models/DetailPenjualan.php:4`.
- `app/Models/Piutang.php` - model piutang dan konversi status `belum/lunas` ke `unpaid/paid`. Ref: `app/Models/Piutang.php:5`.
- `app/Models/KategoriPengeluaran.php` - model kategori biaya. Ref: `app/Models/KategoriPengeluaran.php:6`.
- `app/Models/Pengeluaran.php` - model pengeluaran operasional. Ref: `app/Models/Pengeluaran.php:6`.

### 9.5 `app/Providers` dan `app/View`

- `app/Providers/AppServiceProvider.php` - provider aplikasi default. Ref: `app/Providers/AppServiceProvider.php:1`.
- `app/View/Components/GuestLayout.php` - komponen layout guest/login. Ref: `app/View/Components/GuestLayout.php:5`.

### 9.6 `bootstrap/`

- `bootstrap/app.php` - bootstrap utama Laravel dan alias middleware `role`. Ref: `bootstrap/app.php:5`.
- `bootstrap/providers.php` - daftar service provider. Ref: `bootstrap/providers.php:2`.
- `bootstrap/cache/.gitignore` - placeholder Git.
- `bootstrap/cache/packages.php` - cache package discovery Laravel.
- `bootstrap/cache/services.php` - cache service manifest Laravel.
- `bootstrap/cache/pacA904.tmp` - file temporary cache bootstrap.

### 9.7 `config/`

- `config/app.php` - app name, locale, timezone, maintenance. Ref: `config/app.php:2`.
- `config/auth.php` - guard/provider auth. Ref: `config/auth.php:2`.
- `config/database.php` - koneksi database. Ref: `config/database.php:3`.
- `config/cache.php` - cache store default Laravel. Ref: `config/cache.php:1`.
- `config/filesystems.php` - disk storage Laravel. Ref: `config/filesystems.php:1`.
- `config/logging.php` - channel logging Laravel. Ref: `config/logging.php:1`.
- `config/mail.php` - konfigurasi mailer. Ref: `config/mail.php:1`.
- `config/queue.php` - konfigurasi queue. Ref: `config/queue.php:1`.
- `config/services.php` - layanan pihak ketiga default. Ref: `config/services.php:1`.
- `config/session.php` - driver sesi dan pengaturan session. Ref: `config/session.php:1`.

### 9.8 `database/`

- `database/.gitignore` - placeholder Git.
- `database/database.sqlite` - database SQLite lokal.
- `database/factories/PenggunaFactory.php` - factory user. Ref: `database/factories/PenggunaFactory.php:7`.
- `database/seeders/DatabaseSeeder.php` - seeder utama semua sample data. Ref: `database/seeders/DatabaseSeeder.php:8`.
- `database/migrations/0001_01_01_000000_buat_tabel_pengguna.php` - bootstrap tabel `users`. Ref: `database/migrations/0001_01_01_000000_buat_tabel_pengguna.php:6`.
- `database/migrations/2026_01_30_035001_buat_tabel_produk_lama.php` - migrasi legacy `products`. Ref: `database/migrations/2026_01_30_035001_buat_tabel_produk_lama.php:5`.
- `database/migrations/2026_01_30_035014_buat_tabel_pelanggan_lama.php` - migrasi legacy `customers`. Ref: `database/migrations/2026_01_30_035014_buat_tabel_pelanggan_lama.php:5`.
- `database/migrations/2026_01_30_035024_buat_tabel_transaksi_lama.php` - migrasi legacy `transactions`. Ref: `database/migrations/2026_01_30_035024_buat_tabel_transaksi_lama.php:5`.
- `database/migrations/2026_01_30_035032_buat_tabel_detail_transaksi_lama.php` - migrasi legacy `transaction_details`. Ref: `database/migrations/2026_01_30_035032_buat_tabel_detail_transaksi_lama.php:5`.
- `database/migrations/2026_01_30_035044_buat_tabel_piutang_lama.php` - migrasi legacy `receivables`. Ref: `database/migrations/2026_01_30_035044_buat_tabel_piutang_lama.php:5`.
- `database/migrations/2026_03_19_000000_buat_tabel_inti_penjualan.php` - migrasi inti domain penjualan. Ref: `database/migrations/2026_03_19_000000_buat_tabel_inti_penjualan.php:6`.
- `database/migrations/2026_03_19_010000_buat_tabel_pemasok_dan_riwayat_stok.php` - migrasi supplier dan stok history. Ref: `database/migrations/2026_03_19_010000_buat_tabel_pemasok_dan_riwayat_stok.php:5`.
- `database/migrations/2026_03_20_000001_tambah_kolom_jatuh_tempo_dan_uang_tunai.php` - tambahan kolom pembayaran/piutang. Ref: `database/migrations/2026_03_20_000001_tambah_kolom_jatuh_tempo_dan_uang_tunai.php:5`.
- `database/migrations/2026_03_20_000002_hapus_tabel_lama_penjualan.php` - hapus skema legacy. Ref: `database/migrations/2026_03_20_000002_hapus_tabel_lama_penjualan.php:4`.
- `database/migrations/2026_03_21_000100_buat_tabel_kategori_pengeluaran_dan_pengeluaran.php` - migrasi modul biaya. Ref: `database/migrations/2026_03_21_000100_buat_tabel_kategori_pengeluaran_dan_pengeluaran.php:5`.
- `database/migrations/2026_03_25_000000_tambah_username_pada_tabel_pengguna.php` - tambah/backfill username. Ref: `database/migrations/2026_03_25_000000_tambah_username_pada_tabel_pengguna.php:7`.
- `database/migrations/2026_03_25_200000_hapus_tabel_sesi.php` - hapus tabel session. Ref: `database/migrations/2026_03_25_200000_hapus_tabel_sesi.php:4`.
- `database/migrations/2026_03_25_210000_hapus_tabel_antrian_dan_cache.php` - hapus jobs/cache tables. Ref: `database/migrations/2026_03_25_210000_hapus_tabel_antrian_dan_cache.php:4`.

### 9.9 `public/`

- `public/index.php` - front controller web. Ref: `public/index.php:1`.
- `public/robots.txt` - aturan crawler dasar. Ref: `public/robots.txt:1`.
- `public/favicon.ico` - favicon kosong/placeholder.
- `public/.htaccess` - aturan rewrite Apache.
- `public/build/manifest.json` - manifest hasil build Vite.
- `public/build/assets/app-Cnmcx-Pn.css` - hasil build CSS.
- `public/build/assets/app-4u5Jb2Nr.js` - hasil build JS.

### 9.10 `resources/js` dan `resources/css`

- `resources/js/app.js` - bootstrap Alpine. Ref: `resources/js/app.js:1`.
- `resources/js/bootstrap.js` - bootstrap Axios. Ref: `resources/js/bootstrap.js:1`.
- `resources/css/app.css` - seluruh style custom aplikasi. Ref: `resources/css/app.css:1`.

### 9.11 `resources/views/layouts`, auth, dashboard, profil

- `resources/views/layouts/admin.blade.php` - shell admin/sidebar/topbar. Ref: `resources/views/layouts/admin.blade.php:1`.
- `resources/views/layouts/guest.blade.php` - shell login/guest. Ref: `resources/views/layouts/guest.blade.php:1`.
- `resources/views/autentikasi/masuk.blade.php` - form login. Ref: `resources/views/autentikasi/masuk.blade.php:1`.
- `resources/views/dashboard.blade.php` - dashboard utama semua role. Ref: `resources/views/dashboard.blade.php:1`.
- `resources/views/profil/edit.blade.php` - form profil dan ubah password. Ref: `resources/views/profil/edit.blade.php:1`.

### 9.12 `resources/views` modul admin

- `resources/views/pengguna/index.blade.php` - daftar/filter user. Ref: `resources/views/pengguna/index.blade.php:1`.
- `resources/views/pengguna/create.blade.php` - form tambah user. Ref: `resources/views/pengguna/create.blade.php:1`.
- `resources/views/pengguna/edit.blade.php` - form edit user. Ref: `resources/views/pengguna/edit.blade.php:1`.
- `resources/views/kategoris/index.blade.php` - daftar kategori. Ref: `resources/views/kategoris/index.blade.php:1`.
- `resources/views/kategoris/create.blade.php` - form kategori baru. Ref: `resources/views/kategoris/create.blade.php:1`.
- `resources/views/kategoris/edit.blade.php` - form edit kategori. Ref: `resources/views/kategoris/edit.blade.php:1`.
- `resources/views/produk/index.blade.php` - daftar produk. Ref: `resources/views/produk/index.blade.php:1`.
- `resources/views/produk/create.blade.php` - form tambah produk. Ref: `resources/views/produk/create.blade.php:1`.
- `resources/views/produk/edit.blade.php` - form edit produk. Ref: `resources/views/produk/edit.blade.php:1`.
- `resources/views/pelanggan/index.blade.php` - daftar pelanggan. Ref: `resources/views/pelanggan/index.blade.php:1`.
- `resources/views/pelanggan/create.blade.php` - form tambah pelanggan. Ref: `resources/views/pelanggan/create.blade.php:1`.
- `resources/views/pelanggan/edit.blade.php` - form edit pelanggan. Ref: `resources/views/pelanggan/edit.blade.php:1`.
- `resources/views/pemasok/index.blade.php` - daftar supplier. Ref: `resources/views/pemasok/index.blade.php:1`.
- `resources/views/pemasok/create.blade.php` - form tambah supplier. Ref: `resources/views/pemasok/create.blade.php:1`.
- `resources/views/pemasok/edit.blade.php` - form edit supplier. Ref: `resources/views/pemasok/edit.blade.php:1`.
- `resources/views/kategori_pengeluaran/index.blade.php` - daftar kategori biaya. Ref: `resources/views/kategori_pengeluaran/index.blade.php:1`.
- `resources/views/kategori_pengeluaran/create.blade.php` - form tambah kategori biaya. Ref: `resources/views/kategori_pengeluaran/create.blade.php:1`.
- `resources/views/kategori_pengeluaran/edit.blade.php` - form edit kategori biaya. Ref: `resources/views/kategori_pengeluaran/edit.blade.php:1`.
- `resources/views/pengeluaran/index.blade.php` - daftar/filter biaya. Ref: `resources/views/pengeluaran/index.blade.php:1`.
- `resources/views/pengeluaran/create.blade.php` - form tambah biaya. Ref: `resources/views/pengeluaran/create.blade.php:1`.
- `resources/views/pengeluaran/edit.blade.php` - form edit biaya. Ref: `resources/views/pengeluaran/edit.blade.php:1`.
- `resources/views/stok_histories/index.blade.php` - daftar/filter mutasi stok. Ref: `resources/views/stok_histories/index.blade.php:1`.
- `resources/views/stok_histories/create.blade.php` - form mutasi stok baru. Ref: `resources/views/stok_histories/create.blade.php:1`.
- `resources/views/stok_histories/edit.blade.php` - form edit mutasi stok. Ref: `resources/views/stok_histories/edit.blade.php:1`.

### 9.13 `resources/views` modul kasir

- `resources/views/stok/check.blade.php` - halaman cek stok. Ref: `resources/views/stok/check.blade.php:1`.
- `resources/views/penjualan/index.blade.php` - daftar/filter transaksi. Ref: `resources/views/penjualan/index.blade.php:1`.
- `resources/views/penjualan/create.blade.php` - form transaksi dengan JS hitung total/kembalian. Ref: `resources/views/penjualan/create.blade.php:1`, `:130`.
- `resources/views/penjualan/show.blade.php` - detail transaksi dan cetak. Ref: `resources/views/penjualan/show.blade.php:1`.
- `resources/views/piutang/index.blade.php` - daftar/filter piutang. Ref: `resources/views/piutang/index.blade.php:1`.
- `resources/views/piutang/edit.blade.php` - update status piutang. Ref: `resources/views/piutang/edit.blade.php:1`.

### 9.14 `resources/views/laporan`

- `resources/views/laporan/sales.blade.php` - laporan penjualan + chart + export Excel/PDF. Ref: `resources/views/laporan/sales.blade.php:1`, `:162`.
- `resources/views/laporan/receivables.blade.php` - laporan piutang + export Excel/PDF. Ref: `resources/views/laporan/receivables.blade.php:1`, `:119`.
- `resources/views/laporan/stock.blade.php` - laporan stok + mutasi terbaru + export Excel/PDF. Ref: `resources/views/laporan/stock.blade.php:1`, `:149`.
- `resources/views/laporan/expenses.blade.php` - laporan pengeluaran + doughnut chart komposisi. Ref: `resources/views/laporan/expenses.blade.php:1`, `:152`.

### 9.15 `routes/`

- `routes/web.php` - seluruh route aplikasi utama. Ref: `routes/web.php:18`.
- `routes/auth.php` - route login/logout/password. Ref: `routes/auth.php:5`.
- `routes/console.php` - command `inspire` default Laravel. Ref: `routes/console.php:4`.

### 9.16 `storage/`

- `storage/app/.gitignore` - placeholder Git.
- `storage/app/private/.gitignore` - placeholder Git.
- `storage/app/public/.gitignore` - placeholder Git.
- `storage/framework/.gitignore` - placeholder Git.
- `storage/framework/cache/.gitignore` - placeholder Git.
- `storage/framework/cache/data/.gitignore` - placeholder Git.
- `storage/framework/sessions/.gitignore` - placeholder Git.
- `storage/framework/views/.gitignore` - placeholder Git.
- `storage/framework/sessions/KndkED22JOrSwJpWixH99zLReTf8eUud1uikJtSr` - file sesi runtime.
- `storage/framework/sessions/lQ8uvHLfl5wuQlNcAGZxEwAx1nc9pvaqXp3d1nzU` - file sesi runtime.
- `storage/framework/testing/.gitignore` - placeholder Git.
- `storage/logs/.gitignore` - placeholder Git.
- `storage/logs/laravel.log` - log runtime lokal.

## 10. Kesimpulan Singkat

Secara fungsional, project ini sudah membentuk sistem penjualan toko yang cukup lengkap: autentikasi multi-role, master data, transaksi tunai/utang, piutang, stok, pengeluaran, dashboard, dan laporan. Pola kode yang paling menonjol adalah adaptasi antara nama field Inggris di controller/view dengan nama tabel/kolom Indonesia di database melalui accessor/mutator model.

Kalau kamu mau, langkah berikutnya aku bisa lanjutkan dengan salah satu dari tiga opsi ini tanpa mulai dari nol lagi:

1. Memecah dokumen ini menjadi beberapa file per modul agar lebih mudah dibaca.
2. Menambahkan diagram relasi data dan diagram alur request dari route ke view.
3. Meninjau inkonsistensi yang aku temukan lalu membuat daftar perbaikan teknis yang aman dikerjakan.


