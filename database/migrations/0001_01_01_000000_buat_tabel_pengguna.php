<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->enum('role', ['admin', 'kasir', 'owner'])->default('kasir');
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        } else {
            if (! Schema::hasColumn('users', 'name')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('name')->nullable()->after('id');
                });
            }
            if (! Schema::hasColumn('users', 'role')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->enum('role', ['admin', 'kasir', 'owner'])->default('kasir');
                });
            }
            if (Schema::hasColumn('users', 'nama')) {
                DB::statement("UPDATE users SET name = COALESCE(NULLIF(name, ''), nama)");
            } elseif (Schema::hasColumn('users', 'username')) {
                DB::statement("UPDATE users SET name = COALESCE(NULLIF(name, ''), username)");
            }
        }
    }
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
