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
                $table->string('email')->unique();
                $table->enum('role', ['admin', 'kasir', 'owner'])->default('kasir');
                $table->timestamp('email_verified_at')->nullable();
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
            if (! Schema::hasColumn('users', 'email')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('email')->nullable()->after('name');
                });
            }
            if (! Schema::hasColumn('users', 'email_verified_at')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->timestamp('email_verified_at')->nullable()->after('email');
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
            if (Schema::hasColumn('users', 'username')) {
                DB::statement("UPDATE users SET email = CONCAT(username, '@local.test') WHERE (email IS NULL OR email = '') AND username IS NOT NULL AND username <> ''");
            }
            DB::statement("UPDATE users SET email = CONCAT('user', id, '@local.test') WHERE email IS NULL OR email = ''");
            $emailUniqueIndexExists = DB::table('information_schema.statistics')
                ->where('table_schema', DB::raw('DATABASE()'))
                ->where('table_name', 'users')
                ->where('index_name', 'users_email_unique')
                ->exists();
            if (! $emailUniqueIndexExists) {
                Schema::table('users', function (Blueprint $table) {
                    $table->unique('email');
                });
            }
        }
    }
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
