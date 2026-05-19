<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks to avoid constraint violations
        Schema::disableForeignKeyConstraints();
        
        // Call seeders in proper order
        $seeders = [
            BranchSeeder::class,        // First: Branches
            StaffSeeder::class,         // Second: Staff (depends on branches)
            AdminStaffSeeder::class,    // Third: Admin staff
            CarSeeder::class,           // Fourth: Cars (depends on branches)
            InsuranceSeeder::class,     // Fifth: Insurance
            CustomerSeeder::class,      // Sixth: Customers (if you have)
            RentalSeeder::class,        // Seventh: Rentals (depends on cars & customers)
        ];
        
        foreach ($seeders as $seeder) {
            if (class_exists($seeder)) {
                $this->call($seeder);
                $this->command->info("✓ Seeded: " . class_basename($seeder));
            } else {
                $this->command->warn("⚠ Seeder not found: " . class_basename($seeder));
            }
        }
        
        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
        
        $this->command->info('');
        $this->command->info('Database seeding completed successfully!');
    }
}