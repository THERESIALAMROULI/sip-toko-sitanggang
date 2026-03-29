<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('receivables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->unique()->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->date('due_date')->nullable(); // tambahkan kolom tanggal jatuh tempo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receivables');
    }
};
