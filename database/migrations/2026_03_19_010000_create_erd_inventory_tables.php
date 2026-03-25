<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 150);
                $table->string('telp', 20)->nullable();
                $table->string('alamat', 255)->nullable();
                $table->string('keterangan', 255)->nullable();
                $table->boolean('aktif')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('stok_histories')) {
            Schema::create('stok_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('produk_id')->constrained('produks');
                $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
                $table->foreignId('user_id')->constrained('users');
                $table->integer('jumlah');
                $table->integer('stok_sebelum');
                $table->integer('stok_sesudah');
                $table->string('keterangan', 255)->nullable();
                $table->dateTime('tanggal')->useCurrent();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('stok_histories')) {
            Schema::drop('stok_histories');
        }

        if (Schema::hasTable('suppliers')) {
            Schema::drop('suppliers');
        }
    }
};
