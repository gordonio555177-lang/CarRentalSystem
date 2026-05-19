<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Car extends Model
{
    use HasFactory;

    protected $primaryKey = 'car_id';
    
    protected $fillable = [
        'model',
        'brand',
        'year',
        'license_plate',
        'daily_rate',
        'status',
        'mileage',
        'branch_id',
        'image_url',
        'category',
        'color',
        'seats',
        'transmission',
        'fuel_type',
        'description',
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
        'year' => 'integer',
        'mileage' => 'integer',
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'car_id', 'car_id');
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'car_id', 'car_id');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'car_id', 'car_id');
    }

    // Check if car is available for given dates
    public function isAvailable($startDate, $endDate)
    {
        // Check if car is in maintenance
        if ($this->status === 'maintenance') {
            return false;
        }

        // Check if car is unavailable
        if ($this->status === 'unavailable') {
            return false;
        }

        // Parse dates
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Check for overlapping active or pending rentals
        $overlappingRental = $this->rentals()
            ->where(function($query) {
                $query->where('status', 'active')
                    ->orWhere('status', 'pending');
            })
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function($q) use ($start, $end) {
                        $q->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                    });
            })
            ->exists();

        return !$overlappingRental;
    }

    // Check if car needs maintenance
    public function needsMaintenance()
    {
        $lastMaintenance = $this->maintenances()
            ->where('status', 'completed')
            ->latest('maintenance_date')
            ->first();
        
        if (!$lastMaintenance) {
            return $this->mileage > 5000; // First maintenance at 5000km
        }
        
        $kmSinceLastMaintenance = $this->mileage - $lastMaintenance->odometer_at_maintenance;
        return $kmSinceLastMaintenance > 5000; // Every 5000km
    }

    // Calculate total earnings from this car
    public function totalEarnings()
    {
        return $this->rentals()
            ->where('status', 'returned')
            ->sum('total_amount');
    }

    // Get average rating
    public function averageRating()
    {
        return $this->feedback()->avg('rating') ?? 0;
    }

    // Get current rental if any
    public function currentRental()
    {
        return $this->rentals()
            ->where('status', 'active')
            ->with('customer')
            ->first();
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->brand} {$this->model} ({$this->year})";
    }

    public function getFormattedDailyRateAttribute()
    {
        return '₱' . number_format($this->daily_rate, 2);
    }

    public function getFormattedMileageAttribute()
    {
        return number_format($this->mileage) . ' km';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'available' => 'success',
            'rented' => 'warning',
            'maintenance' => 'danger',
            'unavailable' => 'secondary'
        ];
        
        $color = $badges[$this->status] ?? 'secondary';
        return '<span class="badge bg-' . $color . '">' . ucfirst($this->status) . '</span>';
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeRented($query)
    {
        return $query->where('status', 'rented');
    }

    public function scopeMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand', 'like', "%{$brand}%");
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeByPriceRange($query, $min, $max)
    {
        return $query->whereBetween('daily_rate', [$min, $max]);
    }

    // Get available cars for date range
    public static function getAvailableCars($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        return self::where('status', 'available')
            ->whereDoesntHave('rentals', function($query) use ($start, $end) {
                $query->where('status', 'active')
                    ->where(function($q) use ($start, $end) {
                        $q->whereBetween('start_date', [$start, $end])
                            ->orWhereBetween('end_date', [$start, $end])
                            ->orWhere(function($inner) use ($start, $end) {
                                $inner->where('start_date', '<=', $start)
                                    ->where('end_date', '>=', $end);
                            });
                    });
            })
            ->get();
    }

    // Get popular cars based on rental count
    public static function getPopularCars($limit = 5)
    {
        return self::withCount('rentals')
            ->orderBy('rentals_count', 'desc')
            ->limit($limit)
            ->get();
    }

    // Update car status based on rentals
    public function updateStatusFromRentals()
    {
        $hasActiveRental = $this->rentals()
            ->where('status', 'active')
            ->exists();
        
        $hasPendingMaintenance = $this->maintenances()
            ->where('status', 'scheduled')
            ->where('maintenance_date', '<=', now())
            ->exists();
        
        if ($hasActiveRental) {
            $this->status = 'rented';
        } elseif ($hasPendingMaintenance) {
            $this->status = 'maintenance';
        } elseif ($this->status !== 'unavailable') {
            $this->status = 'available';
        }
        
        $this->saveQuietly();
        
        return $this->status;
    }
}