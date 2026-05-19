// database/migrations/2024_01_01_000008_create_rental_insurance_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_insurance', function (Blueprint $table) {
            $table->id('rental_insurance_id');
            $table->unsignedBigInteger('rental_id');
            $table->unsignedBigInteger('insurance_id');
            $table->foreign('rental_id')->references('rental_id')->on('rentals')->onDelete('cascade');
            $table->foreign('insurance_id')->references('insurance_id')->on('insurance')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_insurance');
    }
};