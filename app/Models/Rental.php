<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $primaryKey = 'rental_id';
    
    protected $fillable = [
        'customer_id', 
        'car_id', 
        'start_date', 
        'end_date', 
        'actual_return_date',
        'total_amount', 
        'status', 
        'staff_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'actual_return_date' => 'date',
    ];

    // Relationship with Customer (User)
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    // Relationship with Car
    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id', 'car_id');
    }

    // Relationship with Staff
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'staff_id');
    }

    // Relationship with Insurance
    public function insurances()
    {
        return $this->belongsToMany(Insurance::class, 'rental_insurance', 'rental_id', 'insurance_id')
                    ->withTimestamps();
    }

    // Relationship with Invoice
    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'rental_id', 'rental_id');
    }

    // Relationship with Payment
    public function payment()
    {
        return $this->hasOne(Payment::class, 'rental_id', 'rental_id');
    }

    // Relationship with Feedback
    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'rental_id', 'rental_id');
    }

    // Relationship with FuelRecord
    public function fuelRecord()
    {
        return $this->hasOne(FuelRecord::class, 'rental_id', 'rental_id');
    }

    public function calculateLateFee($dailyLateFee = 25)
    {
        if ($this->actual_return_date && $this->actual_return_date > $this->end_date) {
            $lateDays = $this->end_date->diffInDays($this->actual_return_date);
            return $lateDays * $dailyLateFee;
        }
        return 0;
    }

    public function calculateInsuranceTotal()
    {
        $totalInsurance = 0;
        foreach ($this->insurances as $insurance) {
            $days = $this->start_date->diffInDays($this->end_date);
            $totalInsurance += $insurance->daily_rate * $days;
        }
        return $totalInsurance;
    }
}