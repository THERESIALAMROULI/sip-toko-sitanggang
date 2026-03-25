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
        $this->seedUser('theresia0424@gmail.com', 'Admin User', 'admin', 'admin456@!!!');
        $this->seedUser('kasir@example.com', 'Bony', 'kasir', 'kasir789@!!!');
        $this->seedUser('owner@example.com', 'Owner User', 'owner', 'owner123@!!!');

        $kategoris = [
            'Sembako',
            'Minuman',
            'Snack',
            'Rokok',
            'Sabun & Detergen',
            'Obat-obatan',
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
        ];

        foreach ($products as $product) {
            $kategoriId = (int) ($kategoriMap[$product['kategori']] ?? 1);

            DB::table('produks')->updateOrInsert(
                ['nama' => $product['nama']],
                [
                    'kategori_id' => $kategoriId,
                    'harga_beli' => $product['harga_jual'],
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
            $adminId = (int) (DB::table('users')->where('email', 'theresia0424@gmail.com')->value('id') ?? 0);
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

        $this->seedSampleTransactions();
    }

    private function seedUser(string $email, string $name, string $role, string $plainPassword): void
    {
        $username = strstr($email, '@', true) ?: $email;

        $payload = [
            'email' => $email,
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

        if (Schema::hasColumn('users', 'username')) {
            $existingByUsername = DB::table('users')
                ->where('username', $username)
                ->first();

            if ($existingByUsername) {
                DB::table('users')
                    ->where('id', $existingByUsername->id)
                    ->update($payload);

                return;
            }
        }

        $existing = DB::table('users')->where('email', $email)->exists();

        if (! $existing) {
            $payload['created_at'] = now();
        }

        DB::table('users')->updateOrInsert(
            ['email' => $email],
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

        $kasirId = (int) (DB::table('users')->where('email', 'kasir@example.com')->value('id') ?? 0);

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
