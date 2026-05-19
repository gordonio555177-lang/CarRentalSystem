<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminStaffSeeder extends Seeder
{
    public function run(): void
    {
        // Create branch
        $branch = Branch::first();
        if (!$branch) {
            $branch = Branch::create([
                'name' => 'Main Branch',
                'address' => '123 Buhangin Street',
                'city' => 'Davao City',
                'phone' => '1234567890',
            ]);
        }

        // Create admin
        Staff::updateOrCreate(
            ['email' => 'admin@carrental.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'phone' => '09123456789',
                'role' => 'admin',
                'hire_date' => now(),
                'branch_id' => $branch->branch_id,
                'password' => Hash::make('password123'),
            ]
        );
    }
}