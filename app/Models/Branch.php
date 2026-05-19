<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $primaryKey = 'branch_id';
    
    protected $fillable = [
        'name', 
        'address', 
        'city', 
        'phone', 
        'manager_staff_id'
    ];

    public function manager()
    {
        return $this->belongsTo(Staff::class, 'manager_staff_id', 'staff_id');
    }

    public function staff()
    {
        return $this->hasMany(Staff::class, 'branch_id', 'branch_id');
    }

    public function cars()
    {
        return $this->hasMany(Car::class, 'branch_id', 'branch_id');
    }
}