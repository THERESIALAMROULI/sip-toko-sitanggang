<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('detail_penjualans') && ! Schema::hasColumn('detail_penjualans', 'harga_beli')) {
            Schema::table('detail_penjualans', function (Blueprint $table) {
                $table->bigInteger('harga_beli')->nullable()->after('nama_produk');
            });

            DB::table('detail_penjualans')
                ->join('produks', 'produks.id', '=', 'detail_penjualans.produk_id')
                ->update([
                    'detail_penjualans.harga_beli' => DB::raw('produks.harga_beli'),
                ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('detail_penjualans') && Schema::hasColumn('detail_penjualans', 'harga_beli')) {
            Schema::table('detail_penjualans', function (Blueprint $table) {
                $table->dropColumn('harga_beli');
            });
        }
    }
};
