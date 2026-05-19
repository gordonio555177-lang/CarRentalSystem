<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Assign images and categories to any existing cars that have none.
     */
    public function up(): void
    {
        $imageMap = [
            // keyword => [image_url, category]
            'gtr'        => ['https://images.unsplash.com/photo-1544636331-e26879cd4d9b?auto=format&fit=crop&w=800&q=80', 'luxury'],
            'nissan'     => ['https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?auto=format&fit=crop&w=800&q=80', 'standard'],
            'terra'      => ['https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=800&q=80', 'suv'],
            'toyota'     => ['https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?auto=format&fit=crop&w=800&q=80', 'standard'],
            'honda'      => ['https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?auto=format&fit=crop&w=800&q=80', 'standard'],
            'bmw'        => ['https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&w=800&q=80', 'luxury'],
            'mercedes'   => ['https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=800&q=80', 'luxury'],
            'porsche'    => ['https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=800&q=80', 'luxury'],
            'lamborghini'=> ['https://images.unsplash.com/photo-1544636331-e26879cd4d9b?auto=format&fit=crop&w=800&q=80', 'luxury'],
            'rolls'      => ['https://images.unsplash.com/photo-1631295868223-63265b40d9e4?auto=format&fit=crop&w=800&q=80', 'luxury'],
            'range'      => ['https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?auto=format&fit=crop&w=800&q=80', 'suv'],
            'land'       => ['https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=800&q=80', 'suv'],
            'ford'       => ['https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=800&q=80', 'suv'],
            'mitsubishi' => ['https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=800&q=80', 'suv'],
            'hyundai'    => ['https://images.unsplash.com/photo-1590362891991-f776e747a588?auto=format&fit=crop&w=800&q=80', 'economy'],
            'kia'        => ['https://images.unsplash.com/photo-1590362891991-f776e747a588?auto=format&fit=crop&w=800&q=80', 'economy'],
        ];

        // Default fallback image
        $defaultImage    = 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=800&q=80';
        $defaultCategory = 'standard';

        $cars = DB::table('cars')->whereNull('image_url')->orWhere('image_url', '')->get();

        foreach ($cars as $car) {
            $brandLower = strtolower($car->brand . ' ' . $car->model);
            $image    = $defaultImage;
            $category = $defaultCategory;

            foreach ($imageMap as $keyword => [$img, $cat]) {
                if (str_contains($brandLower, $keyword)) {
                    $image    = $img;
                    $category = $cat;
                    break;
                }
            }

            DB::table('cars')->where('car_id', $car->car_id)->update([
                'image_url'  => $image,
                'category'   => $category,
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Non-destructive — no rollback needed
    }
};
