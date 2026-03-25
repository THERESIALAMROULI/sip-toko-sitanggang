<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('expense_categories')) {
            Schema::create('expense_categories', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 100)->unique();
                $table->string('deskripsi', 255)->nullable();
                $table->boolean('aktif')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('expense_category_id')->constrained('expense_categories');
                $table->foreignId('user_id')->constrained('users');
                $table->bigInteger('nominal');
                $table->date('tanggal');
                $table->string('catatan', 255)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('expenses')) {
            Schema::drop('expenses');
        }

        if (Schema::hasTable('expense_categories')) {
            Schema::drop('expense_categories');
        }
    }
};
