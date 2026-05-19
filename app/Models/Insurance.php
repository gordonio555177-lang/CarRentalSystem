<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    use HasFactory;

    protected $primaryKey = 'insurance_id';
    protected $table = 'insurance';
    
    protected $fillable = [
        'name', 
        'coverage_details', 
        'daily_rate'
    ];

    public function rentals()
    {
        return $this->belongsToMany(Rental::class, 'rental_insurance', 'insurance_id', 'rental_id')
                    ->withTimestamps();
    }
}