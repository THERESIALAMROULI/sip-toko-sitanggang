<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndex('produks', ['aktif', 'stok'], 'produks_aktif_stok_index');
        $this->addIndex('produks', ['kategori_id', 'aktif'], 'produks_kategori_aktif_index');
        $this->addIndex('produks', ['nama'], 'produks_nama_index');

        $this->addIndex('penjualans', ['tanggal'], 'penjualans_tanggal_index');
        $this->addIndex('penjualans', ['status', 'tanggal'], 'penjualans_status_tanggal_index');
        $this->addIndex('penjualans', ['metode', 'tanggal'], 'penjualans_metode_tanggal_index');
        $this->addIndex('penjualans', ['pelanggan_id', 'tanggal'], 'penjualans_pelanggan_tanggal_index');

        $this->addIndex('detail_penjualans', ['produk_id', 'penjualan_id'], 'detail_penjualan_produk_transaksi_index');
        $this->addIndex('detail_penjualans', ['penjualan_id', 'produk_id'], 'detail_penjualan_transaksi_produk_index');

        $this->addIndex('piutangs', ['status', 'jatuh_tempo'], 'piutangs_status_jatuh_tempo_index');
        $this->addIndex('piutangs', ['pelanggan_id', 'status'], 'piutangs_pelanggan_status_index');
        $this->addIndex('piutangs', ['created_at'], 'piutangs_created_at_index');

        $this->addIndex('stok_histories', ['tanggal'], 'stok_histories_tanggal_index');
        $this->addIndex('stok_histories', ['produk_id', 'tanggal'], 'stok_histories_produk_tanggal_index');
        $this->addIndex('stok_histories', ['supplier_id', 'tanggal'], 'stok_histories_supplier_tanggal_index');
        $this->addIndex('stok_histories', ['jumlah', 'tanggal'], 'stok_histories_jumlah_tanggal_index');

        $this->addIndex('expenses', ['tanggal'], 'expenses_tanggal_index');
        $this->addIndex('expenses', ['expense_category_id', 'tanggal'], 'expenses_kategori_tanggal_index');
        $this->addIndex('expense_categories', ['aktif', 'nama'], 'expense_categories_aktif_nama_index');
        $this->addIndex('suppliers', ['aktif', 'nama'], 'suppliers_aktif_nama_index');
        $this->addIndex('pelanggans', ['nama'], 'pelanggans_nama_index');
    }

    public function down(): void
    {
        $this->dropIndex('pelanggans', 'pelanggans_nama_index');
        $this->dropIndex('suppliers', 'suppliers_aktif_nama_index');
        $this->dropIndex('expense_categories', 'expense_categories_aktif_nama_index');
        $this->dropIndex('expenses', 'expenses_kategori_tanggal_index');
        $this->dropIndex('expenses', 'expenses_tanggal_index');

        $this->dropIndex('stok_histories', 'stok_histories_jumlah_tanggal_index');
        $this->dropIndex('stok_histories', 'stok_histories_supplier_tanggal_index');
        $this->dropIndex('stok_histories', 'stok_histories_produk_tanggal_index');
        $this->dropIndex('stok_histories', 'stok_histories_tanggal_index');

        $this->dropIndex('piutangs', 'piutangs_created_at_index');
        $this->dropIndex('piutangs', 'piutangs_pelanggan_status_index');
        $this->dropIndex('piutangs', 'piutangs_status_jatuh_tempo_index');

        $this->dropIndex('detail_penjualans', 'detail_penjualan_transaksi_produk_index');
        $this->dropIndex('detail_penjualans', 'detail_penjualan_produk_transaksi_index');

        $this->dropIndex('penjualans', 'penjualans_pelanggan_tanggal_index');
        $this->dropIndex('penjualans', 'penjualans_metode_tanggal_index');
        $this->dropIndex('penjualans', 'penjualans_status_tanggal_index');
        $this->dropIndex('penjualans', 'penjualans_tanggal_index');

        $this->dropIndex('produks', 'produks_nama_index');
        $this->dropIndex('produks', 'produks_kategori_aktif_index');
        $this->dropIndex('produks', 'produks_aktif_stok_index');
    }

    private function addIndex(string $table, array $columns, string $name): void
    {
        if (! Schema::hasTable($table) || Schema::hasIndex($table, $name)) {
            return;
        }

        foreach ($columns as $column) {
            if (! Schema::hasColumn($table, $column)) {
                return;
            }
        }

        Schema::table($table, function (Blueprint $table) use ($columns, $name) {
            $table->index($columns, $name);
        });
    }

    private function dropIndex(string $table, string $name): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasIndex($table, $name)) {
            return;
        }

        Schema::table($table, function (Blueprint $table) use ($name) {
            $table->dropIndex($name);
        });
    }
};
