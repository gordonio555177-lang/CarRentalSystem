<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $primaryKey = 'maintenance_id';
    
    protected $fillable = [
        'car_id', 
        'maintenance_date', 
        'description', 
        'cost', 
        'next_due_date', 
        'status'
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'next_due_date' => 'date',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id', 'car_id');
    }
}