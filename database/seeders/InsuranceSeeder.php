<?php

namespace Database\Seeders;

use App\Models\Insurance;
use Illuminate\Database\Seeder;

class InsuranceSeeder extends Seeder
{
    public function run(): void
    {
        $insurances = [
            [
                'name' => 'Basic Coverage',
                'coverage_details' => 'Covers third party liability and basic damage protection.',
                'daily_rate' => 300.00,
            ],
            [
                'name' => 'Premium Coverage',
                'coverage_details' => 'Full coverage including theft, accident, and personal accident insurance.',
                'daily_rate' => 500.00,
            ],
            [
                'name' => 'Comprehensive Plus',
                'coverage_details' => 'Premium coverage with zero excess and 24/7 roadside assistance.',
                'daily_rate' => 800.00,
            ],
        ];

        foreach ($insurances as $insurance) {
            Insurance::create($insurance);
        }
        
        $this->command->info('Created ' . count($insurances) . ' insurance plans');
    }
}