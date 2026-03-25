<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Legacy tables from initial scaffold, replaced by ERD tables:
        // pelanggans, produks, penjualans, detail_penjualans, piutangs.
        Schema::dropIfExists('transaction_details');
        Schema::dropIfExists('receivables');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('products');
        Schema::dropIfExists('customers');
    }

    public function down(): void
    {
        // Intentionally left blank.
        // Legacy tables are deprecated and should not be restored.
    }
};

