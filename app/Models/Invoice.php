<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $primaryKey = 'invoice_id';
    
    protected $fillable = [
        'rental_id', 
        'invoice_date', 
        'subtotal', 
        'insurance_fee', 
        'late_fee', 
        'tax', 
        'total_due'
    ];

    protected $casts = [
        'invoice_date' => 'date',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class, 'rental_id', 'rental_id');
    }

    public function calculateTotal()
    {
        $this->total_due = $this->subtotal + $this->insurance_fee + $this->late_fee + $this->tax;
        return $this->total_due;
    }
}