// database/migrations/2024_01_01_000013_create_fuel_records_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_records', function (Blueprint $table) {
            $table->id('fuel_record_id');
            $table->unsignedBigInteger('rental_id');
            $table->enum('fuel_level_out', ['full', 'three_quarters', 'half', 'quarter', 'empty']);
            $table->enum('fuel_level_in', ['full', 'three_quarters', 'half', 'quarter', 'empty'])->nullable();
            $table->decimal('refuel_charge', 10, 2)->default(0);
            $table->foreign('rental_id')->references('rental_id')->on('rentals')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_records');
    }
};