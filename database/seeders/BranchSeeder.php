<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Main Branch',
                'address' => '123 Buhangin Avenue',
                'city' => 'Davao City',
                'phone' => '+63 2 8123 4567',
            ],
            [
                'name' => 'North Branch',
                'address' => '456 Quezon City',
                'city' => 'Quezon City',
                'phone' => '+63 2 8234 5678',
            ],
            [
                'name' => 'South Branch',
                'address' => '789 Makati City',
                'city' => 'Makati',
                'phone' => '+63 2 8345 6789',
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
        
        $this->command->info('Created ' . count($branches) . ' branches');
    }
}