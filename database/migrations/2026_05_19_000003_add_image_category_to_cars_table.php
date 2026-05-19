<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            if (!Schema::hasColumn('cars', 'image_url')) {
                $table->string('image_url')->nullable()->after('branch_id');
            }
            if (!Schema::hasColumn('cars', 'category')) {
                $table->enum('category', ['economy', 'standard', 'luxury', 'suv', 'van'])->default('standard')->after('image_url');
            }
            if (!Schema::hasColumn('cars', 'color')) {
                $table->string('color', 50)->nullable()->after('category');
            }
            if (!Schema::hasColumn('cars', 'seats')) {
                $table->tinyInteger('seats')->default(5)->after('color');
            }
            if (!Schema::hasColumn('cars', 'transmission')) {
                $table->enum('transmission', ['automatic', 'manual'])->default('automatic')->after('seats');
            }
            if (!Schema::hasColumn('cars', 'fuel_type')) {
                $table->enum('fuel_type', ['gasoline', 'diesel', 'electric', 'hybrid'])->default('gasoline')->after('transmission');
            }
            if (!Schema::hasColumn('cars', 'description')) {
                $table->text('description')->nullable()->after('fuel_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn(['image_url', 'category', 'color', 'seats', 'transmission', 'fuel_type', 'description']);
        });
    }
};
