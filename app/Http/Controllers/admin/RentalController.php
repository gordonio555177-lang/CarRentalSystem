<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Insurance;
use App\Models\FuelRecord;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::with(['customer.customer', 'car', 'staff'])->latest()->paginate(10);
        return view('admin.rentals.index', compact('rentals'));
    }

    public function create()
    {
        $customers = Customer::all();
        $cars = Car::where('status', 'available')->get();
        $insurances = Insurance::all();
        
        return view('admin.rentals.create', compact('customers', 'cars', 'insurances'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'car_id' => 'required|exists:cars,car_id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'insurance_ids' => 'nullable|array|exists:insurance,insurance_id',
        ]);

        $car = Car::find($validated['car_id']);
        
        if (!$car->isAvailable($validated['start_date'], $validated['end_date'])) {
            return back()->with('error', 'Car is not available for selected dates!')->withInput();
        }

        DB::transaction(function () use ($validated, $car) {
            $rental = Rental::create([
                'customer_id' => $validated['customer_id'],
                'car_id' => $validated['car_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => 'active',
                'staff_id' => auth()->guard('staff')->id(),
            ]);

            if (!empty($validated['insurance_ids'])) {
                $rental->insurances()->attach($validated['insurance_ids']);
            }

            FuelRecord::create([
                'rental_id' => $rental->rental_id,
                'fuel_level_out' => 'full',
            ]);

            $car->update(['status' => 'rented']);
        });

        return redirect()->route('admin.rentals.index')
            ->with('success', 'Rental created successfully!');
    }

    public function show(Rental $rental)
    {
        $rental->load(['customer.customer', 'car', 'insurances', 'fuelRecord', 'invoice', 'payment']);
        return view('admin.rentals.show', compact('rental'));
    }

    public function edit(Rental $rental)
    {
        $rental->load(['customer.customer', 'car', 'insurances']);
        $customers = Customer::all();
        $cars = Car::all();
        $insurances = Insurance::all();
        return view('admin.rentals.edit', compact('rental', 'customers', 'cars', 'insurances'));
    }

    public function update(Request $request, Rental $rental)
    {
        $validated = $request->validate([
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after:start_date',
            'status'         => 'required|in:pending,active,returned,cancelled',
            'insurance_ids'  => 'nullable|array|exists:insurance,insurance_id',
        ]);

        $oldStatus = $rental->status;
        $newStatus = $validated['status'];

        DB::transaction(function () use ($validated, $rental, $oldStatus, $newStatus) {
            $rental->update([
                'start_date' => $validated['start_date'],
                'end_date'   => $validated['end_date'],
                'status'     => $newStatus,
            ]);

            if (isset($validated['insurance_ids'])) {
                $rental->insurances()->sync($validated['insurance_ids']);
            }

            // Sync car status when rental status changes
            if ($oldStatus !== $newStatus) {
                if ($newStatus === 'active') {
                    // Pending → Active: mark car as rented
                    $rental->car->update(['status' => 'rented']);
                } elseif (in_array($newStatus, ['returned', 'cancelled'])) {
                    // Active/Pending → Returned/Cancelled: free the car
                    $rental->car->update(['status' => 'available']);
                }
            }
        });

        return redirect()->route('admin.rentals.index')
            ->with('success', 'Rental updated successfully!');
    }

    /**
     * Quick-approve a pending rental (AJAX or redirect)
     */
    public function approve(Rental $rental)
    {
        if ($rental->status !== 'pending') {
            return redirect()->route('admin.rentals.index')
                ->with('error', 'Only pending rentals can be approved.');
        }

        DB::transaction(function () use ($rental) {
            $rental->update(['status' => 'active']);
            $rental->car->update(['status' => 'rented']);
        });

        return redirect()->route('admin.rentals.index')
            ->with('success', "Rental #{$rental->rental_id} approved — status set to Active (Rented).");
    }

    public function destroy(Rental $rental)
    {
        if ($rental->status === 'active') {
            $rental->car->update(['status' => 'available']);
        }
        
        $rental->delete();
        
        return redirect()->route('admin.rentals.index')
            ->with('success', 'Rental deleted successfully!');
    }

    /**
     * Show the form for processing a rental return
     */
    public function return(Rental $rental)
    {
        if ($rental->status !== 'active') {
            return redirect()->route('admin.rentals.index')
                ->with('error', 'This rental is not active and cannot be returned!');
        }

        $rental->load('customer.customer');
        return view('admin.rentals.return', compact('rental'));
    }

    /**
     * Process the rental return
     */
    public function processReturn(Request $request, Rental $rental)
    {
        // Validate the request
        $validated = $request->validate([
            'return_odometer' => 'required|integer|min:' . $rental->car->mileage,
            'fuel_level_in' => 'required|in:full,three_quarters,half,quarter,empty',
            'condition_notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($validated, $rental) {
            // Update rental
            $rental->actual_return_date = now();
            $rental->status = 'returned';

            // Calculate costs
            $totalDays      = max(1, $rental->start_date->diffInDays($rental->actual_return_date));
            $lateFee        = $rental->calculateLateFee();
            $insuranceTotal = $rental->calculateInsuranceTotal();
            $subtotal       = $rental->car->daily_rate * $totalDays;
            $tax            = ($subtotal + $insuranceTotal) * 0.12; // 12% VAT on car + insurance
            $totalDue       = $subtotal + $insuranceTotal + $lateFee + $tax;

            $rental->total_amount = $totalDue;
            $rental->save();

            // Update fuel record
            $fuelRecord = $rental->fuelRecord;
            if ($fuelRecord) {
                $fuelRecord->fuel_level_in = $validated['fuel_level_in'];
                $fuelRecord->save();
            }

            // Create invoice
            Invoice::create([
                'rental_id'     => $rental->rental_id,
                'invoice_date'  => now(),
                'subtotal'      => $subtotal,
                'insurance_fee' => $insuranceTotal,
                'late_fee'      => $lateFee,
                'tax'           => $tax,
                'total_due'     => $totalDue,
            ]);

            // Update car mileage and status
            $rental->car->update([
                'mileage' => $validated['return_odometer'],
                'status'  => 'available',
            ]);

            // Create a pending payment record
            Payment::create([
                'rental_id'    => $rental->rental_id,
                'amount'       => $totalDue,
                'payment_date' => now()->toDateString(),
                'method'       => 'cash',
                'status'       => 'pending',
            ]);
        });

        return redirect()->route('admin.rentals.show', $rental)
            ->with('success', 'Car returned successfully! Invoice generated. Total amount due: ₱' . number_format($rental->total_amount, 2));
    }

    /**
     * Show a printable invoice page
     */
    public function generateInvoice($rental)
    {
        $rental = Rental::with(['customer.customer', 'car', 'invoice', 'insurances'])->findOrFail($rental);

        // Auto-create invoice if missing
        if (!$rental->invoice) {
            $totalDays     = max(1, $rental->start_date->diffInDays($rental->actual_return_date ?? now()));
            $lateFee       = $rental->calculateLateFee();
            $insuranceTotal = $rental->calculateInsuranceTotal();
            $subtotal      = $rental->car->daily_rate * $totalDays;

            $invoice = Invoice::create([
                'rental_id'     => $rental->rental_id,
                'invoice_date'  => now(),
                'subtotal'      => $subtotal,
                'insurance_fee' => $insuranceTotal,
                'late_fee'      => $lateFee,
                'tax'           => $subtotal * 0.12,
                'total_due'     => $subtotal + $insuranceTotal + $lateFee,
            ]);

            $rental->setRelation('invoice', $invoice);
        }

        return view('admin.rentals.invoice-pdf', compact('rental'));
    }
}