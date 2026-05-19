<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::first();
        if (!$branch) {
            $this->command->warn('No branch found. Run BranchSeeder first.');
            return;
        }

        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Car::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $cars = [
            // ── LUXURY ──────────────────────────────────────────────────────
            [
                'brand'        => 'Mercedes-Benz',
                'model'        => 'S-Class S500',
                'year'         => 2024,
                'license_plate'=> 'LUX-001',
                'daily_rate'   => 8500.00,
                'status'       => 'available',
                'mileage'      => 3200,
                'category'     => 'luxury',
                'color'        => 'Obsidian Black',
                'seats'        => 5,
                'transmission' => 'automatic',
                'fuel_type'    => 'gasoline',
                'description'  => 'The pinnacle of luxury sedans. Features Burmester 4D surround sound, massaging seats, and MBUX Hyperscreen.',
                'image_url'    => 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=800&q=80',
                'branch_id'    => $branch->branch_id,
            ],
            [
                'brand'        => 'BMW',
                'model'        => '7 Series 740i',
                'year'         => 2024,
                'license_plate'=> 'LUX-002',
                'daily_rate'   => 7800.00,
                'status'       => 'available',
                'mileage'      => 4100,
                'category'     => 'luxury',
                'color'        => 'Alpine White',
                'seats'        => 5,
                'transmission' => 'automatic',
                'fuel_type'    => 'gasoline',
                'description'  => 'Executive luxury with BMW\'s latest iDrive 8.5, panoramic sky lounge roof, and executive rear lounge.',
                'image_url'    => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&w=800&q=80',
                'branch_id'    => $branch->branch_id,
            ],
            [
                'brand'        => 'Porsche',
                'model'        => 'Cayenne Turbo GT',
                'year'         => 2024,
                'license_plate'=> 'LUX-003',
                'daily_rate'   => 9200.00,
                'status'       => 'available',
                'mileage'      => 2800,
                'category'     => 'luxury',
                'color'        => 'Carmine Red',
                'seats'        => 5,
                'transmission' => 'automatic',
                'fuel_type'    => 'gasoline',
                'description'  => '640 hp twin-turbo V8. 0-100 km/h in 3.3 seconds. The ultimate performance SUV.',
                'image_url'    => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80',
                'branch_id'    => $branch->branch_id,
            ],
            [
                'brand'        => 'Lamborghini',
                'model'        => 'Urus S',
                'year'         => 2023,
                'license_plate'=> 'LUX-004',
                'daily_rate'   => 15000.00,
                'status'       => 'available',
                'mileage'      => 1500,
                'category'     => 'luxury',
                'color'        => 'Giallo Orion',
                'seats'        => 5,
                'transmission' => 'automatic',
                'fuel_type'    => 'gasoline',
                'description'  => '666 hp super SUV. The most powerful, fastest, and most driver-focused Lamborghini SUV ever.',
                'image_url'    => 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?auto=format&fit=crop&w=800&q=80',
                'branch_id'    => $branch->branch_id,
            ],
            [
                'brand'        => 'Rolls-Royce',
                'model'        => 'Ghost Extended',
                'year'         => 2023,
                'license_plate'=> 'LUX-005',
                'daily_rate'   => 25000.00,
                'status'       => 'available',
                'mileage'      => 900,
                'category'     => 'luxury',
                'color'        => 'Andalusian White',
                'seats'        => 5,
                'transmission' => 'automatic',
                'fuel_type'    => 'gasoline',
                'description'  => 'The most technologically advanced Rolls-Royce ever created. Starlight headliner, bespoke audio, and whisper-quiet cabin.',
                'image_url'    => 'https://images.unsplash.com/photo-1631295868223-63265b40d9e4?auto=format&fit=crop&w=800&q=80',
                'branch_id'    => $branch->branch_id,
            ],
            // ── SUV ─────────────────────────────────────────────────────────
            [
                'brand'        => 'Range Rover',
                'model'        => 'Sport HSE',
                'year'         => 2024,
                'license_plate'=> 'SUV-001',
                'daily_rate'   => 6500.00,
                'status'       => 'available',
                'mileage'      => 5200,
                'category'     => 'suv',
                'color'        => 'Santorini Black',
                'seats'        => 7,
                'transmission' => 'automatic',
                'fuel_type'    => 'diesel',
                'description'  => 'Unmatched off-road capability with on-road refinement. Terrain Response 2, air suspension, and panoramic roof.',
                'image_url'    => 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?auto=format&fit=crop&w=800&q=80',
                'branch_id'    => $branch->branch_id,
            ],
            [
                'brand'        => 'Toyota',
                'model'        => 'Land Cruiser 300',
                'year'         => 2024,
                'license_plate'=> 'SUV-002',
                'daily_rate'   => 4800.00,
                'status'       => 'available',
                'mileage'      => 7800,
                'category'     => 'suv',
                'color'        => 'Midnight Black',
                'seats'        => 8,
                'transmission' => 'automatic',
                'fuel_type'    => 'diesel',
                'description'  => 'Legendary reliability meets modern luxury. Multi-terrain select, crawl control, and premium JBL audio.',
                'image_url'    => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=800&q=80',
                'branch_id'    => $branch->branch_id,
            ],
            // ── STANDARD ────────────────────────────────────────────────────
            [
                'brand'        => 'Toyota',
                'model'        => 'Camry 2.5V',
                'year'         => 2024,
                'license_plate'=> 'STD-001',
                'daily_rate'   => 2800.00,
                'status'       => 'available',
                'mileage'      => 12000,
                'category'     => 'standard',
                'color'        => 'Platinum White Pearl',
                'seats'        => 5,
                'transmission' => 'automatic',
                'fuel_type'    => 'gasoline',
                'description'  => 'Refined mid-size sedan with 9-inch touchscreen, wireless Apple CarPlay, and Toyota Safety Sense.',
                'image_url'    => 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&w=800&q=80',
                'branch_id'    => $branch->branch_id,
            ],
            [
                'brand'        => 'Honda',
                'model'        => 'Civic RS Turbo',
                'year'         => 2024,
                'license_plate'=> 'STD-002',
                'daily_rate'   => 2200.00,
                'status'       => 'available',
                'mileage'      => 9500,
                'category'     => 'standard',
                'color'        => 'Sonic Gray Pearl',
                'seats'        => 5,
                'transmission' => 'automatic',
                'fuel_type'    => 'gasoline',
                'description'  => 'Sporty and fuel-efficient. 1.5L VTEC Turbo, Honda Sensing suite, and 9-inch infotainment.',
                'image_url'    => 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?auto=format&fit=crop&w=800&q=80',
                'branch_id'    => $branch->branch_id,
            ],
            // ── ECONOMY ─────────────────────────────────────────────────────
            [
                'brand'        => 'Toyota',
                'model'        => 'Vios 1.3 XLE',
                'year'         => 2024,
                'license_plate'=> 'ECO-001',
                'daily_rate'   => 1500.00,
                'status'       => 'available',
                'mileage'      => 18000,
                'category'     => 'economy',
                'color'        => 'Super White',
                'seats'        => 5,
                'transmission' => 'automatic',
                'fuel_type'    => 'gasoline',
                'description'  => 'Reliable and fuel-efficient city car. Perfect for daily commutes and short trips.',
                'image_url'    => 'https://images.unsplash.com/photo-1590362891991-f776e747a588?auto=format&fit=crop&w=800&q=80',
                'branch_id'    => $branch->branch_id,
            ],
        ];

        foreach ($cars as $car) {
            Car::create($car);
        }

        $this->command->info('Created ' . count($cars) . ' cars (5 luxury, 2 SUV, 2 standard, 1 economy)');
    }
}
