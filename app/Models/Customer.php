<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'customer_id';
    
    protected $fillable = [
        'user_id',  // Add this
        'first_name', 
        'last_name', 
        'email', 
        'phone', 
        'license_no', 
        'address', 
        'registered_date'
    ];

    protected $casts = [
        'registered_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'customer_id', 'customer_id');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'customer_id', 'customer_id');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}