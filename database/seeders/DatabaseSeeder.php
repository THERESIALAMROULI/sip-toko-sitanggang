<?php
namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    public function run(): void
    {
        $this->seedUser('admin', 'Admin User', 'admin', 'admin456@!!!');
        $this->seedUser('kasir', 'Kasir User', 'kasir', 'kasir789@!!!');
        $this->seedUser('owner', 'Owner User', 'owner', 'owner123@!!!');
        $kategoris = [
            'Sembako',
            'Minuman',
            'Snack',
            'Rokok',
            'Sabun & Detergen',
            'Obat-obatan',
            'Bumbu Dapur',
            'Produk Susu',
            'Perawatan Bayi',
        ];
        foreach ($kategoris as $kategoriNama) {
            DB::table('kategoris')->updateOrInsert(
                ['nama' => $kategoriNama],
                [
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
        $kategoriMap = DB::table('kategoris')
            ->pluck('id', 'nama');
        $products = [
            ['nama' => 'Beras 5kg', 'kategori' => 'Sembako', 'harga_jual' => 78000, 'stok' => 20],
            ['nama' => 'Gula Pasir 1kg', 'kategori' => 'Sembako', 'harga_jual' => 16000, 'stok' => 25],
            ['nama' => 'Minyak Goreng 1L', 'kategori' => 'Sembako', 'harga_jual' => 18000, 'stok' => 14],
            ['nama' => 'Mie Instan', 'kategori' => 'Sembako', 'harga_jual' => 3500, 'stok' => 80],
            ['nama' => 'Susu UHT 1L', 'kategori' => 'Minuman', 'harga_jual' => 21000, 'stok' => 12],
            ['nama' => 'Teh Celup', 'kategori' => 'Minuman', 'harga_jual' => 9000, 'stok' => 30],
            ['nama' => 'Kopi Sachet', 'kategori' => 'Minuman', 'harga_jual' => 2500, 'stok' => 65],
            ['nama' => 'Air Mineral 600ml', 'kategori' => 'Minuman', 'harga_jual' => 4000, 'stok' => 90],
            ['nama' => 'Biskuit Cokelat', 'kategori' => 'Snack', 'harga_jual' => 8500, 'stok' => 40],
            ['nama' => 'Keripik Singkong', 'kategori' => 'Snack', 'harga_jual' => 7000, 'stok' => 35],
            ['nama' => 'Wafer Vanila', 'kategori' => 'Snack', 'harga_jual' => 9500, 'stok' => 28],
            ['nama' => 'Rokok Filter', 'kategori' => 'Rokok', 'harga_jual' => 28000, 'stok' => 18],
            ['nama' => 'Rokok Kretek', 'kategori' => 'Rokok', 'harga_jual' => 24000, 'stok' => 22],
            ['nama' => 'Sabun Mandi Batang', 'kategori' => 'Sabun & Detergen', 'harga_jual' => 4500, 'stok' => 50],
            ['nama' => 'Detergen Bubuk 800gr', 'kategori' => 'Sabun & Detergen', 'harga_jual' => 18000, 'stok' => 19],
            ['nama' => 'Pembersih Lantai', 'kategori' => 'Sabun & Detergen', 'harga_jual' => 16000, 'stok' => 16],
            ['nama' => 'Paracetamol', 'kategori' => 'Obat-obatan', 'harga_jual' => 12000, 'stok' => 24],
            ['nama' => 'Vitamin C', 'kategori' => 'Obat-obatan', 'harga_jual' => 15000, 'stok' => 26],
            ['nama' => 'Minyak Kayu Putih', 'kategori' => 'Obat-obatan', 'harga_jual' => 23000, 'stok' => 12],
            ['nama' => 'Garam Halus', 'kategori' => 'Bumbu Dapur', 'harga_jual' => 5000, 'stok' => 33],
            ['nama' => 'Kecap Manis', 'kategori' => 'Bumbu Dapur', 'harga_jual' => 12000, 'stok' => 21],
            ['nama' => 'Saus Sambal', 'kategori' => 'Bumbu Dapur', 'harga_jual' => 11000, 'stok' => 18],
            ['nama' => 'Susu Bubuk Anak', 'kategori' => 'Produk Susu', 'harga_jual' => 42000, 'stok' => 14],
            ['nama' => 'Yogurt Botol', 'kategori' => 'Produk Susu', 'harga_jual' => 9000, 'stok' => 20],
            ['nama' => 'Keju Slice', 'kategori' => 'Produk Susu', 'harga_jual' => 18000, 'stok' => 17],
            ['nama' => 'Popok Bayi', 'kategori' => 'Perawatan Bayi', 'harga_jual' => 52000, 'stok' => 13],
            ['nama' => 'Tisu Basah Bayi', 'kategori' => 'Perawatan Bayi', 'harga_jual' => 17000, 'stok' => 27],
            ['nama' => 'Bedak Bayi', 'kategori' => 'Perawatan Bayi', 'harga_jual' => 14000, 'stok' => 15],
            ['nama' => 'Sapu Lantai', 'kategori' => 'Umum', 'harga_jual' => 28000, 'stok' => 11],
            ['nama' => 'Pel Serbaguna', 'kategori' => 'Umum', 'harga_jual' => 35000, 'stok' => 12],
        ];
        foreach ($products as $product) {
            $kategoriId = (int) ($kategoriMap[$product['kategori']] ?? 1);
            $purchasePrice = (int) ($product['harga_beli'] ?? floor(($product['harga_jual'] * 0.8) / 100) * 100);
            DB::table('produks')->updateOrInsert(
                ['nama' => $product['nama']],
                [
                    'kategori_id' => $kategoriId,
                    'harga_beli' => max(100, $purchasePrice),
                    'harga_jual' => $product['harga_jual'],
                    'stok' => $product['stok'],
                    'stok_minimum' => 10,
                    'aktif' => 1,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
        $suppliers = [
            ['nama' => 'PT Indofood Sukses', 'telp' => '021-7981590', 'alamat' => 'Jakarta Selatan', 'keterangan' => 'Mie instan, bumbu', 'aktif' => 1],
            ['nama' => 'CV Maju Jaya', 'telp' => '0274-445566', 'alamat' => 'Yogyakarta', 'keterangan' => 'Rokok, minuman', 'aktif' => 1],
            ['nama' => 'UD Sumber Rejeki', 'telp' => '0274-889900', 'alamat' => 'Sleman', 'keterangan' => 'Sembako, minyak goreng', 'aktif' => 1],
            ['nama' => 'PT Sentosa Niaga', 'telp' => '022-667788', 'alamat' => 'Bandung', 'keterangan' => 'Produk susu dan snack', 'aktif' => 1],
            ['nama' => 'CV Prima Grosir', 'telp' => '031-445577', 'alamat' => 'Surabaya', 'keterangan' => 'Perawatan bayi dan alat rumah tangga', 'aktif' => 1],
        ];
        foreach ($suppliers as $supplier) {
            DB::table('suppliers')->updateOrInsert(
                ['nama' => $supplier['nama']],
                [
                    'telp' => $supplier['telp'],
                    'alamat' => $supplier['alamat'],
                    'keterangan' => $supplier['keterangan'],
                    'aktif' => $supplier['aktif'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
        $customers = [
            ['nama' => 'Budi Santoso', 'telp' => '081234567890', 'alamat' => 'Jl. Merdeka No. 10'],
            ['nama' => 'Siti Aisyah', 'telp' => '081298765432', 'alamat' => 'Jl. Kenanga No. 22'],
            ['nama' => 'Andi Pratama', 'telp' => '081355551111', 'alamat' => 'Jl. Dahlia No. 8'],
            ['nama' => 'Rina Marlina', 'telp' => '081366669999', 'alamat' => 'Jl. Mawar No. 17'],
            ['nama' => 'Yusuf Rahman', 'telp' => '081377778888', 'alamat' => 'Jl. Melati No. 5'],
        ];
        foreach ($customers as $customer) {
            DB::table('pelanggans')->updateOrInsert(
                ['telp' => $customer['telp']],
                [
                    'nama' => $customer['nama'],
                    'alamat' => $customer['alamat'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
        if (Schema::hasTable('expense_categories')) {
            $expenseCategories = [
                ['nama' => 'Transportasi', 'deskripsi' => 'Biaya bensin, parkir, dan pengiriman', 'aktif' => 1],
                ['nama' => 'Utilitas', 'deskripsi' => 'Listrik, air, internet, dan telepon', 'aktif' => 1],
                ['nama' => 'Perlengkapan Toko', 'deskripsi' => 'ATK, kantong plastik, alat kebersihan', 'aktif' => 1],
                ['nama' => 'Perawatan Peralatan', 'deskripsi' => 'Servis freezer, timbangan, dan etalase', 'aktif' => 1],
                ['nama' => 'Keamanan', 'deskripsi' => 'CCTV, gembok, dan kebutuhan keamanan toko', 'aktif' => 1],
            ];
            foreach ($expenseCategories as $expenseCategory) {
                DB::table('expense_categories')->updateOrInsert(
                    ['nama' => $expenseCategory['nama']],
                    [
                        'deskripsi' => $expenseCategory['deskripsi'],
                        'aktif' => $expenseCategory['aktif'],
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
        if (Schema::hasTable('expenses') && Schema::hasTable('expense_categories')) {
            $adminId = (int) (DB::table('users')->where('username', 'admin')->value('id') ?? 0);
            $utilitasId = (int) (DB::table('expense_categories')->where('nama', 'Utilitas')->value('id') ?? 0);
            $perlengkapanId = (int) (DB::table('expense_categories')->where('nama', 'Perlengkapan Toko')->value('id') ?? 0);
            if ($adminId > 0 && $utilitasId > 0) {
                DB::table('expenses')->updateOrInsert(
                    [
                        'expense_category_id' => $utilitasId,
                        'user_id' => $adminId,
                        'tanggal' => now()->subDays(3)->toDateString(),
                        'catatan' => 'Pembayaran listrik bulanan',
                    ],
                    [
                        'nominal' => 350000,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
            if ($adminId > 0 && $perlengkapanId > 0) {
                DB::table('expenses')->updateOrInsert(
                    [
                        'expense_category_id' => $perlengkapanId,
                        'user_id' => $adminId,
                        'tanggal' => now()->subDays(1)->toDateString(),
                        'catatan' => 'Beli plastik dan alat tulis',
                    ],
                    [
                        'nominal' => 125000,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
        $this->seedSampleStockHistories();
        $this->seedSampleTransactions();
    }
    private function seedUser(string $username, string $name, string $role, string $plainPassword): void
    {
        $payload = [
            'name' => $name,
            'role' => $role,
            'password' => Hash::make($plainPassword),
            'updated_at' => now(),
        ];
        if (Schema::hasColumn('users', 'nama')) {
            $payload['nama'] = $name;
        }
        if (Schema::hasColumn('users', 'username')) {
            $payload['username'] = $username;
        }
        if (Schema::hasColumn('users', 'status')) {
            $payload['status'] = 'aktif';
        }
        $existingByUsername = null;
        if (Schema::hasColumn('users', 'username')) {
            $existingByUsername = DB::table('users')
                ->where('username', $username)
                ->first();

            if (! $existingByUsername && $username === 'kasir') {
                $existingByUsername = DB::table('users')
                    ->where('username', 'bony')
                    ->first();
            }
        }

        if ($existingByUsername) {
            DB::table('users')
                ->where('id', $existingByUsername->id)
                ->update($payload);
            return;
        }
        $payload['created_at'] = now();
        DB::table('users')->updateOrInsert(
            ['username' => $username],
            $payload
        );
    }
    private function seedSampleTransactions(): void
    {
        if (! Schema::hasTable('penjualans')
            || ! Schema::hasTable('detail_penjualans')
            || ! Schema::hasTable('piutangs')) {
            return;
        }
        $kasirId = (int) (DB::table('users')->where('username', 'kasir')->value('id') ?? 0);
        $customers = DB::table('pelanggans')->orderBy('id')->pluck('id')->values();
        $products = DB::table('produks')
            ->where('aktif', 1)
            ->orderBy('id')
            ->get(['id', 'nama', 'harga_jual', 'stok']);
        if ($products->count() < 3 || $customers->isEmpty()) {
            return;
        }
        $this->createSampleTransaction(
            'TRX-EX-ADM-001',
            $kasirId,
            (int) $customers[0],
            'tunai',
            now()->subDays(5),
            [
                ['product_id' => (int) $products[0]->id, 'qty' => 2],
                ['product_id' => (int) $products[1]->id, 'qty' => 1],
            ],
            null
        );
        $this->createSampleTransaction(
            'TRX-EX-ADM-002',
            $kasirId,
            (int) ($customers[4] ?? $customers[0]),
            'utang',
            now()->subDays(18),
            [
                ['product_id' => (int) $products[1]->id, 'qty' => 2],
            ],
            now()->addDays(10)->toDateString()
        );
        $this->createSampleTransaction(
            'TRX-EX-KAS-001',
            $kasirId,
            (int) $customers[1],
            'tunai',
            now()->subDays(2),
            [
                ['product_id' => (int) $products[2]->id, 'qty' => 3],
            ],
            null
        );
        $this->createSampleTransaction(
            'TRX-EX-KAS-002',
            $kasirId,
            (int) $customers[2],
            'utang',
            now()->subDays(10),
            [
                ['product_id' => (int) $products[0]->id, 'qty' => 1],
                ['product_id' => (int) $products[2]->id, 'qty' => 2],
            ],
            now()->addDays(7)->toDateString()
        );
        $this->createSampleTransaction(
            'TRX-EX-OWN-001',
            $kasirId,
            (int) $customers[3],
            'utang',
            now()->subDays(35),
            [
                ['product_id' => (int) $products[1]->id, 'qty' => 2],
            ],
            now()->subDays(5)->toDateString()
        );
        $this->createSampleTransaction(
            'TRX-EX-OWN-002',
            $kasirId,
            (int) $customers[0],
            'tunai',
            now()->subDays(1),
            [
                ['product_id' => (int) $products[2]->id, 'qty' => 1],
                ['product_id' => (int) $products[3]->id, 'qty' => 4],
            ],
            null
        );
    }

    private function seedSampleStockHistories(): void
    {
        if (! Schema::hasTable('stok_histories')
            || ! Schema::hasTable('suppliers')
            || ! Schema::hasTable('produks')) {
            return;
        }

        $adminId = (int) (DB::table('users')->where('username', 'admin')->value('id') ?? 0);
        if ($adminId <= 0) {
            return;
        }

        $supplierMap = DB::table('suppliers')->pluck('id', 'nama');
        $productMap = DB::table('produks')->pluck('id', 'nama');
        $entries = [
            [
                'produk' => 'Beras 5kg',
                'supplier' => 'UD Sumber Rejeki',
                'jumlah' => 10,
                'tanggal' => now()->subDays(14)->setTime(8, 30),
                'keterangan' => 'Restok mingguan beras',
            ],
            [
                'produk' => 'Susu UHT 1L',
                'supplier' => 'PT Sentosa Niaga',
                'jumlah' => 8,
                'tanggal' => now()->subDays(10)->setTime(9, 15),
                'keterangan' => 'Barang datang dari pemasok susu',
            ],
            [
                'produk' => 'Popok Bayi',
                'supplier' => 'CV Prima Grosir',
                'jumlah' => 6,
                'tanggal' => now()->subDays(7)->setTime(10, 0),
                'keterangan' => 'Tambahan stok popok bayi',
            ],
            [
                'produk' => 'Detergen Bubuk 800gr',
                'supplier' => 'CV Prima Grosir',
                'jumlah' => -3,
                'tanggal' => now()->subDays(4)->setTime(15, 20),
                'keterangan' => 'Koreksi stok setelah audit rak',
            ],
            [
                'produk' => 'Kopi Sachet',
                'supplier' => 'CV Maju Jaya',
                'jumlah' => 12,
                'tanggal' => now()->subDays(2)->setTime(11, 45),
                'keterangan' => 'Penambahan stok kopi sachet',
            ],
        ];

        foreach ($entries as $entry) {
            $productId = (int) ($productMap[$entry['produk']] ?? 0);
            $supplierId = (int) ($supplierMap[$entry['supplier']] ?? 0);

            if ($productId <= 0) {
                continue;
            }

            $existing = DB::table('stok_histories')
                ->where('produk_id', $productId)
                ->where('tanggal', $entry['tanggal'])
                ->where('keterangan', $entry['keterangan'])
                ->first();

            if ($existing) {
                continue;
            }

            $product = DB::table('produks')
                ->where('id', $productId)
                ->lockForUpdate()
                ->first();

            if (! $product) {
                continue;
            }

            $stokSebelum = (int) $product->stok;
            $stokSesudah = max($stokSebelum + (int) $entry['jumlah'], 0);
            $appliedQty = $stokSesudah - $stokSebelum;

            DB::table('produks')
                ->where('id', $productId)
                ->update([
                    'stok' => $stokSesudah,
                    'updated_at' => now(),
                ]);

            DB::table('stok_histories')->insert([
                'produk_id' => $productId,
                'supplier_id' => $supplierId > 0 ? $supplierId : null,
                'user_id' => $adminId,
                'jumlah' => $appliedQty,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $entry['keterangan'],
                'tanggal' => $entry['tanggal'],
                'created_at' => $entry['tanggal'],
                'updated_at' => now(),
            ]);
        }
    }
    private function createSampleTransaction(
        string $noNota,
        int $userId,
        int $customerId,
        string $paymentType,
        $transactionDate,
        array $items,
        ?string $dueDate
    ): void {
        if ($userId <= 0) {
            return;
        }
        $existing = DB::table('penjualans')
            ->where('no_nota', $noNota)
            ->first();
        if ($existing) {
            return;
        }
        $productIds = collect($items)->pluck('product_id')->unique()->values()->all();
        $productMap = DB::table('produks')
            ->whereIn('id', $productIds)
            ->get(['id', 'nama', 'harga_jual', 'stok'])
            ->keyBy('id');
        $detailRows = [];
        $total = 0;
        foreach ($items as $item) {
            $productId = (int) ($item['product_id'] ?? 0);
            $requestedQty = (int) ($item['qty'] ?? 0);
            if ($productId <= 0 || $requestedQty <= 0 || ! isset($productMap[$productId])) {
                continue;
            }
            $product = $productMap[$productId];
            $availableStock = (int) ($product->stok ?? 0);
            $qty = min($requestedQty, $availableStock);
            if ($qty <= 0) {
                continue;
            }
            $price = (int) ($product->harga_jual ?? 0);
            $subtotal = $qty * $price;
            $total += $subtotal;
            $detailRows[] = [
                'produk_id' => $productId,
                'nama_produk' => $product->nama,
                'harga_jual' => $price,
                'qty' => $qty,
                'subtotal' => $subtotal,
            ];
        }
        if ($total <= 0 || empty($detailRows)) {
            return;
        }
        $isCash = $paymentType === 'tunai';
        $cashReceived = $isCash ? ($total + 10000) : null;
        $changeAmount = $isCash ? 10000 : null;
        $now = now();
        $payload = [
            'no_nota' => $noNota,
            'user_id' => $userId,
            'pelanggan_id' => $customerId,
            'total' => $total,
            'metode' => $paymentType,
            'status' => $isCash ? 'lunas' : 'utang',
            'tanggal' => $transactionDate,
            'created_at' => $transactionDate,
            'updated_at' => $now,
        ];
        if (Schema::hasColumn('penjualans', 'uang_diterima')) {
            $payload['uang_diterima'] = $cashReceived;
        }
        if (Schema::hasColumn('penjualans', 'kembalian')) {
            $payload['kembalian'] = $changeAmount;
        }
        $penjualanId = DB::table('penjualans')->insertGetId($payload);
        foreach ($detailRows as $detail) {
            DB::table('detail_penjualans')->insert([
                'penjualan_id' => $penjualanId,
                'produk_id' => $detail['produk_id'],
                'nama_produk' => $detail['nama_produk'],
                'harga_jual' => $detail['harga_jual'],
                'qty' => $detail['qty'],
                'subtotal' => $detail['subtotal'],
                'created_at' => $transactionDate,
                'updated_at' => $now,
            ]);
            DB::table('produks')
                ->where('id', $detail['produk_id'])
                ->decrement('stok', $detail['qty']);
        }
        if (! $isCash) {
            $receivablePayload = [
                'penjualan_id' => $penjualanId,
                'pelanggan_id' => $customerId,
                'jumlah' => $total,
                'status' => 'belum',
                'created_at' => $transactionDate,
                'updated_at' => $now,
            ];
            if (Schema::hasColumn('piutangs', 'jatuh_tempo')) {
                $receivablePayload['jatuh_tempo'] = $dueDate;
            }
            DB::table('piutangs')->insert($receivablePayload);
        }
    }
}
