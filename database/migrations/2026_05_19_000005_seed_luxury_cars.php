<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Get the first branch_id available
        $branchId = DB::table('branches')->value('branch_id');

        if (!$branchId) {
            // Create a default branch if none exists
            $branchId = DB::table('branches')->insertGetId([
                'name'       => 'Main Branch',
                'address'    => '123 Buhangin Avenue',
                'city'       => 'Davao City',
                'phone'      => '+63 82 123 4567',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $cars = [
            [
                'brand'        => 'Mercedes-Benz',
                'model'        => 'S-Class S500',
                'year'         => 2024,
                'license_plate'=> 'LUX-MB-001',
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
                'branch_id'    => $branchId,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'brand'        => 'BMW',
                'model'        => '7 Series 740i',
                'year'         => 2024,
                'license_plate'=> 'LUX-BMW-002',
                'daily_rate'   => 7800.00,
                'status'       => 'available',
                'mileage'      => 4100,
                'category'     => 'luxury',
                'color'        => 'Alpine White',
                'seats'        => 5,
                'transmission' => 'automatic',
                'fuel_type'    => 'gasoline',
                'description'  => 'Executive luxury with BMW latest iDrive 8.5, panoramic sky lounge roof, and executive rear lounge.',
                'image_url'    => 'https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&w=800&q=80',
                'branch_id'    => $branchId,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'brand'        => 'Porsche',
                'model'        => 'Cayenne Turbo GT',
                'year'         => 2024,
                'license_plate'=> 'LUX-POR-003',
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
                'branch_id'    => $branchId,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'brand'        => 'Lamborghini',
                'model'        => 'Urus S',
                'year'         => 2023,
                'license_plate'=> 'LUX-LAM-004',
                'daily_rate'   => 15000.00,
                'status'       => 'available',
                'mileage'      => 1500,
                'category'     => 'luxury',
                'color'        => 'Giallo Orion',
                'seats'        => 5,
                'transmission' => 'automatic',
                'fuel_type'    => 'gasoline',
                'description'  => '666 hp super SUV. The most powerful and fastest Lamborghini SUV ever built.',
                'image_url'    => 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?auto=format&fit=crop&w=800&q=80',
                'branch_id'    => $branchId,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'brand'        => 'Rolls-Royce',
                'model'        => 'Ghost Extended',
                'year'         => 2023,
                'license_plate'=> 'LUX-RR-005',
                'daily_rate'   => 25000.00,
                'status'       => 'available',
                'mileage'      => 900,
                'category'     => 'luxury',
                'color'        => 'Andalusian White',
                'seats'        => 5,
                'transmission' => 'automatic',
                'fuel_type'    => 'gasoline',
                'description'  => 'The most technologically advanced Rolls-Royce ever created. Starlight headliner and whisper-quiet cabin.',
                'image_url'    => 'https://images.unsplash.com/photo-1631295868223-63265b40d9e4?auto=format&fit=crop&w=800&q=80',
                'branch_id'    => $branchId,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'brand'        => 'Range Rover',
                'model'        => 'Sport HSE',
                'year'         => 2024,
                'license_plate'=> 'SUV-RR-001',
                'daily_rate'   => 6500.00,
                'status'       => 'available',
                'mileage'      => 5200,
                'category'     => 'suv',
                'color'        => 'Santorini Black',
                'seats'        => 7,
                'transmission' => 'automatic',
                'fuel_type'    => 'diesel',
                'description'  => 'Unmatched off-road capability with on-road refinement. Terrain Response 2 and air suspension.',
                'image_url'    => 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?auto=format&fit=crop&w=800&q=80',
                'branch_id'    => $branchId,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'brand'        => 'Toyota',
                'model'        => 'Land Cruiser 300',
                'year'         => 2024,
                'license_plate'=> 'SUV-TOY-002',
                'daily_rate'   => 4800.00,
                'status'       => 'available',
                'mileage'      => 7800,
                'category'     => 'suv',
                'color'        => 'Midnight Black',
                'seats'        => 8,
                'transmission' => 'automatic',
                'fuel_type'    => 'diesel',
                'description'  => 'Legendary reliability meets modern luxury. Multi-terrain select and premium JBL audio.',
                'image_url'    => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=800&q=80',
                'branch_id'    => $branchId,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'brand'        => 'Toyota',
                'model'        => 'Camry 2.5V',
                'year'         => 2024,
                'license_plate'=> 'STD-TOY-001',
                'daily_rate'   => 2800.00,
                'status'       => 'available',
                'mileage'      => 12000,
                'category'     => 'standard',
                'color'        => 'Platinum White Pearl',
                'seats'        => 5,
                'transmission' => 'automatic',
                'fuel_type'    => 'gasoline',
                'description'  => 'Refined mid-size sedan with 9-inch touchscreen and Toyota Safety Sense.',
                'image_url'    => 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&w=800&q=80',
                'branch_id'    => $branchId,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'brand'        => 'Honda',
                'model'        => 'Civic RS Turbo',
                'year'         => 2024,
                'license_plate'=> 'STD-HON-002',
                'daily_rate'   => 2200.00,
                'status'       => 'available',
                'mileage'      => 9500,
                'category'     => 'standard',
                'color'        => 'Sonic Gray Pearl',
                'seats'        => 5,
                'transmission' => 'automatic',
                'fuel_type'    => 'gasoline',
                'description'  => 'Sporty and fuel-efficient. 1.5L VTEC Turbo and Honda Sensing suite.',
                'image_url'    => 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?auto=format&fit=crop&w=800&q=80',
                'branch_id'    => $branchId,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'brand'        => 'Toyota',
                'model'        => 'Vios 1.3 XLE',
                'year'         => 2024,
                'license_plate'=> 'ECO-TOY-001',
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
                'branch_id'    => $branchId,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ];

        foreach ($cars as $car) {
            // Only insert if license plate doesn't already exist
            $exists = DB::table('cars')->where('license_plate', $car['license_plate'])->exists();
            if (!$exists) {
                DB::table('cars')->insert($car);
            }
        }
    }

    public function down(): void
    {
        $plates = [
            'LUX-MB-001','LUX-BMW-002','LUX-POR-003','LUX-LAM-004','LUX-RR-005',
            'SUV-RR-001','SUV-TOY-002','STD-TOY-001','STD-HON-002','ECO-TOY-001',
        ];
        DB::table('cars')->whereIn('license_plate', $plates)->delete();
    }
};
