<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change method enum to include gcash and add notes column
        DB::statement("ALTER TABLE payments MODIFY COLUMN method ENUM('cash','credit_card','debit_card','bank_transfer','gcash','maya') NOT NULL");

        if (!Schema::hasColumn('payments', 'notes')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->text('notes')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE payments MODIFY COLUMN method ENUM('cash','credit_card','debit_card','bank_transfer') NOT NULL");
    }
};
