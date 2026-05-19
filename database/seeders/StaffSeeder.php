<?php
// database/seeders/StaffSeeder.php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        
        if ($branches->isEmpty()) {
            $this->command->error('No branches found. Run BranchSeeder first.');
            return;
        }

        $staffMembers = [
            [
                'first_name' => 'rude',
                'last_name' => 'ordo',
                'email' => 'rude@carrental.com',
                'phone' => '+63 912 3456 789',
                'role' => 'agent',
                'hire_date' => now(),
                'password' => Hash::make('password123'), // ADD PASSWORD
            ],
            [
                'first_name' => 'rud',
                'last_name' => 'Santos',
                'email' => 'rud@carrental.com',
                'phone' => '+63 923 4567 890',
                'role' => 'agent',
                'hire_date' => now(),
                'password' => Hash::make('password123'), // ADD PASSWORD
            ],
        ];

        foreach ($staffMembers as $index => $staff) {
            $staff['branch_id'] = $branches[$index % $branches->count()]->branch_id;
            Staff::create($staff);
        }
        
        $this->command->info('Created ' . count($staffMembers) . ' staff members');
    }
}