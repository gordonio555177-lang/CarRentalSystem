<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'staff_id';
    protected $table = 'staff';
    
    protected $fillable = [
        'first_name', 
        'last_name', 
        'email', 
        'phone', 
        'role', 
        'hire_date', 
        'branch_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'staff_id', 'staff_id');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}