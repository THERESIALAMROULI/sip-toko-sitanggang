<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('transaction_details');
        Schema::dropIfExists('receivables');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('products');
        Schema::dropIfExists('customers');
    }
    public function down(): void
    {
    }
};
