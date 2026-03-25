<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('kategoris')) {
            Schema::create('kategoris', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 100)->unique();
                $table->timestamps();
            });
        }

        if (DB::table('kategoris')->count() === 0) {
            DB::table('kategoris')->insert([
                'nama' => 'Umum',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (! Schema::hasTable('produks')) {
            Schema::create('produks', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 150);
                $table->foreignId('kategori_id')->constrained('kategoris');
                $table->bigInteger('harga_beli')->default(0);
                $table->bigInteger('harga_jual')->default(0);
                $table->integer('stok')->default(0);
                $table->integer('stok_minimum')->default(10);
                $table->boolean('aktif')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('pelanggans')) {
            Schema::create('pelanggans', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 150);
                $table->string('telp', 20)->nullable();
                $table->string('alamat', 255)->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('penjualans')) {
            Schema::create('penjualans', function (Blueprint $table) {
                $table->id();
                $table->string('no_nota', 32)->unique();
                $table->foreignId('user_id')->constrained('users');
                $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggans')->nullOnDelete();
                $table->bigInteger('total');
                $table->enum('metode', ['tunai', 'utang'])->default('tunai');
                $table->enum('status', ['lunas', 'utang'])->default('lunas');
                $table->dateTime('tanggal')->useCurrent();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('detail_penjualans')) {
            Schema::create('detail_penjualans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('penjualan_id')->constrained('penjualans')->cascadeOnDelete();
                $table->foreignId('produk_id')->constrained('produks');
                $table->string('nama_produk', 150);
                $table->bigInteger('harga_jual');
                $table->integer('qty');
                $table->bigInteger('subtotal');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('piutangs')) {
            Schema::create('piutangs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('penjualan_id')->constrained('penjualans')->cascadeOnDelete();
                $table->foreignId('pelanggan_id')->constrained('pelanggans');
                $table->bigInteger('jumlah');
                $table->enum('status', ['belum', 'lunas'])->default('belum');
                $table->foreignId('dilunasi_oleh')->nullable()->constrained('users')->nullOnDelete();
                $table->dateTime('tgl_lunas')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('piutangs');
        Schema::dropIfExists('detail_penjualans');
        Schema::dropIfExists('penjualans');
        Schema::dropIfExists('pelanggans');
        Schema::dropIfExists('produks');
        Schema::dropIfExists('kategoris');
    }
};
