<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            // Widen from decimal(10,2) to decimal(15,2) to handle large amounts
            $table->decimal('total_amount', 15, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->decimal('total_amount', 10, 2)->default(0)->change();
        });
    }
};
