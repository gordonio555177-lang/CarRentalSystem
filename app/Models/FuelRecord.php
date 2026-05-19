<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelRecord extends Model
{
    use HasFactory;

    protected $primaryKey = 'fuel_record_id';
    
    protected $fillable = [
        'rental_id', 
        'fuel_level_out', 
        'fuel_level_in', 
        'refuel_charge'
    ];

    protected $casts = [
        'refuel_charge' => 'decimal:2',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class, 'rental_id', 'rental_id');
    }

    public function calculateRefuelCharge($fuelPricePerLiter = 2.5)
    {
        $fuelLevels = [
            'full' => 1.0,
            'three_quarters' => 0.75,
            'half' => 0.5,
            'quarter' => 0.25,
            'empty' => 0.0
        ];
        
        $tankCapacity = 50; // liters
        $outLevel = $fuelLevels[$this->fuel_level_out] ?? 0;
        $inLevel = $fuelLevels[$this->fuel_level_in] ?? 0;
        
        $litersNeeded = ($inLevel - $outLevel) * $tankCapacity;
        
        if ($litersNeeded < 0) {
            $this->refuel_charge = abs($litersNeeded) * $fuelPricePerLiter;
        }
        
        return $this->refuel_charge;
    }
}