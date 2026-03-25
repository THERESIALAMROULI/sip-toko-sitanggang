<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('penjualans')) {
            Schema::table('penjualans', function (Blueprint $table) {
                if (! Schema::hasColumn('penjualans', 'uang_diterima')) {
                    $table->bigInteger('uang_diterima')->nullable()->after('total');
                }

                if (! Schema::hasColumn('penjualans', 'kembalian')) {
                    $table->bigInteger('kembalian')->nullable()->after('uang_diterima');
                }
            });
        }

        if (Schema::hasTable('piutangs')) {
            Schema::table('piutangs', function (Blueprint $table) {
                if (! Schema::hasColumn('piutangs', 'jatuh_tempo')) {
                    $table->date('jatuh_tempo')->nullable()->after('jumlah');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('penjualans')) {
            Schema::table('penjualans', function (Blueprint $table) {
                if (Schema::hasColumn('penjualans', 'kembalian')) {
                    $table->dropColumn('kembalian');
                }

                if (Schema::hasColumn('penjualans', 'uang_diterima')) {
                    $table->dropColumn('uang_diterima');
                }
            });
        }

        if (Schema::hasTable('piutangs')) {
            Schema::table('piutangs', function (Blueprint $table) {
                if (Schema::hasColumn('piutangs', 'jatuh_tempo')) {
                    $table->dropColumn('jatuh_tempo');
                }
            });
        }
    }
};
