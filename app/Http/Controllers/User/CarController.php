<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Rental;
use App\Models\Insurance;
use App\Models\FuelRecord;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::where('status', 'available');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate   = $request->end_date;

            $query->whereDoesntHave('rentals', function ($q) use ($startDate, $endDate) {
                $q->whereIn('status', ['active', 'pending'])
                  ->where(function ($subq) use ($startDate, $endDate) {
                      $subq->whereBetween('start_date', [$startDate, $endDate])
                           ->orWhereBetween('end_date', [$startDate, $endDate])
                           ->orWhere(function ($inner) use ($startDate, $endDate) {
                               $inner->where('start_date', '<=', $startDate)
                                     ->where('end_date', '>=', $endDate);
                           });
                  });
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $cars = $query->paginate(12);
        return view('user.cars.index', compact('cars'));
    }

    public function show(Car $car)
    {
        $insurances = Insurance::all();
        $car->load(['feedback' => function ($q) {
            $q->with('customer')->latest();
        }]);

        return view('user.cars.show', compact('car', 'insurances'));
    }

    /**
     * Step 1 — validate dates/insurance, then redirect to checkout page.
     */
    public function book(Request $request, Car $car)
    {
        $request->validate([
            'start_date'    => 'required|date|after_or_equal:today',
            'end_date'      => 'required|date|after:start_date',
            'insurance_ids' => 'nullable|array|exists:insurance,insurance_id',
        ]);

        if (!$car->isAvailable($request->start_date, $request->end_date)) {
            return back()->with('error', 'Car is not available for the selected dates.');
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate   = Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate);
        $subtotal  = $car->daily_rate * $totalDays;

        $insuranceIds   = $request->input('insurance_ids', []);
        $insuranceTotal = 0;
        if (!empty($insuranceIds)) {
            $selectedInsurances = Insurance::whereIn('insurance_id', $insuranceIds)->get();
            foreach ($selectedInsurances as $ins) {
                $insuranceTotal += $ins->daily_rate * $totalDays;
            }
        }

        $tax         = ($subtotal + $insuranceTotal) * 0.12;
        $totalAmount = $subtotal + $insuranceTotal + $tax;

        // Pass booking summary to checkout view
        $booking = [
            'start_date'      => $request->start_date,
            'end_date'        => $request->end_date,
            'total_days'      => $totalDays,
            'subtotal'        => $subtotal,
            'insurance_ids'   => $insuranceIds,
            'insurance_total' => $insuranceTotal,
            'tax'             => $tax,
            'total_amount'    => $totalAmount,
        ];

        return view('user.cars.checkout', compact('car', 'booking'));
    }

    /**
     * Step 2 — process payment, create rental record.
     */
    public function confirmCheckout(Request $request, Car $car)
    {
        $request->validate([
            'start_date'      => 'required|date|after_or_equal:today',
            'end_date'        => 'required|date|after:start_date',
            'total_amount'    => 'required|numeric|min:0',
            'payment_method'  => 'required|in:cash,credit_card,debit_card,bank_transfer,gcash,maya',
            'insurance_ids'   => 'nullable|array|exists:insurance,insurance_id',
        ]);

        if (!$car->isAvailable($request->start_date, $request->end_date)) {
            return back()->with('error', 'Car is no longer available for the selected dates.');
        }

        $user         = auth()->user();
        $insuranceIds = $request->input('insurance_ids', []);

        // Ensure Customer profile exists
        Customer::firstOrCreate(
            ['user_id' => $user->id],
            [
                'first_name'      => explode(' ', $user->name)[0] ?? $user->name,
                'last_name'       => explode(' ', $user->name, 2)[1] ?? '',
                'email'           => $user->email,
                'phone'           => $user->phone ?? '',
                'license_no'      => 'LIC-' . strtoupper(uniqid()),
                'address'         => $user->address ?? '',
                'registered_date' => now()->toDateString(),
            ]
        );

        DB::transaction(function () use ($request, $car, $insuranceIds, $user) {
            // Create rental
            $rental = Rental::create([
                'customer_id'  => $user->id,
                'car_id'       => $car->car_id,
                'start_date'   => $request->start_date,
                'end_date'     => $request->end_date,
                'total_amount' => $request->total_amount,
                'status'       => 'pending',
            ]);

            // Attach insurances
            if (!empty($insuranceIds)) {
                $rental->insurances()->attach($insuranceIds);
            }

            // Create payment record
            Payment::create([
                'rental_id'      => $rental->rental_id,
                'amount'         => $request->total_amount,
                'payment_date'   => now()->toDateString(),
                'method'         => $request->payment_method,
                'status'         => 'completed',
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'notes'          => $request->notes,
            ]);

            // Create fuel record
            FuelRecord::create([
                'rental_id'      => $rental->rental_id,
                'fuel_level_out' => 'full',
            ]);
        });

        return redirect()->route('user.rentals.index')
            ->with('success', 'Booking confirmed and payment processed! Your rental is pending admin approval.');
    }

    public function checkAvailability(Request $request, Car $car)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after:start_date',
        ]);

        $isAvailable = $car->isAvailable($request->start_date, $request->end_date);

        return response()->json([
            'available' => $isAvailable,
            'car'       => $car->brand . ' ' . $car->model,
            'message'   => $isAvailable ? 'Car is available for selected dates' : 'Car is not available for selected dates',
        ]);
    }

    public function calculateCost(Request $request)
    {
        $request->validate([
            'car_id'        => 'required|exists:cars,car_id',
            'start_date'    => 'required|date|after_or_equal:today',
            'end_date'      => 'required|date|after:start_date',
            'insurance_ids' => 'nullable|array|exists:insurance,insurance_id',
        ]);

        $car       = Car::findOrFail($request->car_id);
        $startDate = Carbon::parse($request->start_date);
        $endDate   = Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate);
        $subtotal  = $car->daily_rate * $totalDays;

        $insuranceTotal = 0;
        if ($request->filled('insurance_ids')) {
            $insurances = Insurance::whereIn('insurance_id', $request->insurance_ids)->get();
            foreach ($insurances as $insurance) {
                $insuranceTotal += $insurance->daily_rate * $totalDays;
            }
        }

        $tax   = ($subtotal + $insuranceTotal) * 0.12;
        $total = $subtotal + $insuranceTotal + $tax;

        return response()->json([
            'success' => true,
            'data'    => [
                'days'                => $totalDays,
                'daily_rate'          => $car->daily_rate,
                'formatted_daily_rate'=> '₱' . number_format($car->daily_rate, 2),
                'subtotal'            => $subtotal,
                'formatted_subtotal'  => '₱' . number_format($subtotal, 2),
                'insurance'           => $insuranceTotal,
                'formatted_insurance' => '₱' . number_format($insuranceTotal, 2),
                'tax'                 => $tax,
                'formatted_tax'       => '₱' . number_format($tax, 2),
                'total'               => $total,
                'formatted_total'     => '₱' . number_format($total, 2),
                'currency'            => '₱',
            ],
        ]);
    }
}
