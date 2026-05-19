<?php
// database/migrations/2026_05_06_200000_create_staff_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('staff')) {
            Schema::create('staff', function (Blueprint $table) {
                $table->id('staff_id');
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->unique();
                $table->string('phone');
                $table->enum('role', ['manager', 'agent', 'admin'])->default('agent');
                $table->date('hire_date');
                $table->string('password');
                $table->rememberToken();
                $table->unsignedBigInteger('branch_id');
                $table->timestamps();
                
                $table->foreign('branch_id')->references('branch_id')->on('branches')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};