// database/migrations/2024_01_01_000006_create_insurance_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance', function (Blueprint $table) {
            $table->id('insurance_id');
            $table->string('name');
            $table->text('coverage_details');
            $table->decimal('daily_rate', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance');
    }
};