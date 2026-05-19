<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Rental;
use App\Models\Insurance;
use App\Models\FuelRecord;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::where('status', 'available');
        
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            
            $query->whereDoesntHave('rentals', function($q) use ($startDate, $endDate) {
                $q->where('status', 'active')
                  ->where(function($subq) use ($startDate, $endDate) {
                      $subq->whereBetween('start_date', [$startDate, $endDate])
                           ->orWhereBetween('end_date', [$startDate, $endDate])
                           ->orWhere(function($inner) use ($startDate, $endDate) {
                               $inner->where('start_date', '<=', $startDate)
                                     ->where('end_date', '>=', $endDate);
                           });
                  });
            });
        }
        
        $cars = $query->paginate(12);
        return view('user.cars.index', compact('cars'));
    }

    public function show(Car $car)
    {
        $insurances = Insurance::all();
        $car->load(['feedback' => function($q) {
            $q->with('customer')->latest();
        }]);

        return view('user.cars.show', compact('car', 'insurances'));
    }

    public function book(Request $request, Car $car)
    {
        $request->validate([
            'start_date'     => 'required|date|after_or_equal:today',
            'end_date'       => 'required|date|after:start_date',
            'insurance_ids'  => 'nullable|array|exists:insurance,insurance_id',
        ]);

        if (!$car->isAvailable($request->start_date, $request->end_date)) {
            return back()->with('error', 'Car is not available for selected dates!');
        }

        $user      = auth()->user();
        $startDate = Carbon::parse($request->start_date);
        $endDate   = Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate);
        $subtotal  = $car->daily_rate * $totalDays;

        // Calculate insurance total
        $insuranceTotal = 0;
        $insuranceIds   = $request->input('insurance_ids', []);
        if (!empty($insuranceIds)) {
            $selectedInsurances = Insurance::whereIn('insurance_id', $insuranceIds)->get();
            foreach ($selectedInsurances as $ins) {
                $insuranceTotal += $ins->daily_rate * $totalDays;
            }
        }

        $tax         = ($subtotal + $insuranceTotal) * 0.12;
        $totalAmount = $subtotal + $insuranceTotal + $tax;

        // Ensure a Customer profile exists for this user (for admin display)
        $customer = Customer::firstOrCreate(
            ['user_id' => $user->id],
            [
                'first_name'      => explode(' ', $user->name)[0] ?? $user->name,
                'last_name'       => explode(' ', $user->name)[1] ?? '',
                'email'           => $user->email,
                'phone'           => $user->phone ?? '',
                'license_no'      => 'LIC-' . strtoupper(uniqid()),
                'address'         => $user->address ?? '',
                'registered_date' => now(),
            ]
        );

        DB::transaction(function () use ($request, $car, $totalDays, $subtotal, $totalAmount, $insuranceIds, $user) {
            $rental = Rental::create([
                'customer_id'  => $user->id,   // maps to users.id
                'car_id'       => $car->car_id,
                'start_date'   => $request->start_date,
                'end_date'     => $request->end_date,
                'total_amount' => $totalAmount,
                'status'       => 'pending',
            ]);

            // Attach selected insurances
            if (!empty($insuranceIds)) {
                $rental->insurances()->attach($insuranceIds);
            }

            // Create fuel record
            FuelRecord::create([
                'rental_id'      => $rental->rental_id,
                'fuel_level_out' => 'full',
            ]);
        });

        return redirect()->route('user.rentals.index')
            ->with('success', 'Booking request submitted! Please wait for admin confirmation.');
    }
    
    public function checkAvailability(Request $request, Car $car)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);
        
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $isAvailable = $car->isAvailable($startDate, $endDate);
        
        return response()->json([
            'available' => $isAvailable,
            'car' => $car->brand . ' ' . $car->model,
            'message' => $isAvailable ? 'Car is available for selected dates' : 'Car is not available for selected dates'
        ]);
    }
    
    public function calculateCost(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,car_id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'insurance_ids' => 'nullable|array|exists:insurance,insurance_id',
        ]);
        
        $car = Car::find($request->car_id);
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate);
        
        $subtotal = $car->daily_rate * $totalDays;
        
        $insuranceTotal = 0;
        if ($request->has('insurance_ids') && !empty($request->insurance_ids)) {
            $insurances = Insurance::whereIn('insurance_id', $request->insurance_ids)->get();
            foreach ($insurances as $insurance) {
                $insuranceTotal += $insurance->daily_rate * $totalDays;
            }
        }
        
        $tax = ($subtotal + $insuranceTotal) * 0.12;
        $total = $subtotal + $insuranceTotal + $tax;
        
        return response()->json([
            'success' => true,
            'data' => [
                'days' => $totalDays,
                'daily_rate' => $car->daily_rate,
                'formatted_daily_rate' => '₱' . number_format($car->daily_rate, 2),
                'subtotal' => $subtotal,
                'formatted_subtotal' => '₱' . number_format($subtotal, 2),
                'insurance' => $insuranceTotal,
                'formatted_insurance' => '₱' . number_format($insuranceTotal, 2),
                'tax' => $tax,
                'formatted_tax' => '₱' . number_format($tax, 2),
                'total' => $total,
                'formatted_total' => '₱' . number_format($total, 2),
                'currency' => '₱'
            ]
        ]);
    }
}