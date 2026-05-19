<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Feedback;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    /**
     * List the authenticated user's rentals.
     */
    public function index()
    {
        $rentals = Rental::where('customer_id', auth()->id())
            ->with('car')
            ->latest()
            ->paginate(10);

        return view('user.rentals.index', compact('rentals'));
    }

    /**
     * Show a single rental — only the owner may view it.
     */
    public function show(Rental $rental)
    {
        $this->authorizeRental($rental);

        $rental->load(['car', 'invoice', 'payment', 'feedback', 'fuelRecord', 'insurances']);

        return view('user.rentals.show', compact('rental'));
    }

    /**
     * Cancel a pending rental.
     */
    public function cancel(Rental $rental)
    {
        $this->authorizeRental($rental);

        if (!in_array($rental->status, ['pending', 'active'])) {
            return back()->with('error', 'This rental cannot be cancelled.');
        }

        if ($rental->status === 'active') {
            $rental->car->update(['status' => 'available']);
        }

        $rental->update(['status' => 'cancelled']);

        return redirect()->route('user.rentals.index')
            ->with('success', 'Rental cancelled successfully.');
    }

    /**
     * Extend an active rental's end date.
     */
    public function extend(Request $request, Rental $rental)
    {
        $this->authorizeRental($rental);

        $request->validate([
            'new_end_date' => 'required|date|after:' . $rental->end_date,
        ]);

        if (!$rental->car->isAvailable($rental->end_date, $request->new_end_date)) {
            return back()->with('error', 'Car is not available for the extended dates.');
        }

        $newEndDate      = \Carbon\Carbon::parse($request->new_end_date);
        $currentEndDate  = \Carbon\Carbon::parse($rental->end_date);
        $extraDays       = $currentEndDate->diffInDays($newEndDate);
        $additionalCost  = $rental->car->daily_rate * $extraDays;

        $rental->end_date     = $request->new_end_date;
        $rental->total_amount += $additionalCost;
        $rental->save();

        return redirect()->route('user.rentals.show', $rental)
            ->with('success', 'Rental extended successfully. Additional cost: ₱' . number_format($additionalCost, 2));
    }

    /**
     * Return rental details as JSON (AJAX).
     */
    public function getDetails(Rental $rental)
    {
        $this->authorizeRental($rental);

        return response()->json($rental->load(['car', 'invoice']));
    }

    /**
     * Show a printable invoice page for a rental.
     */
    public function downloadInvoice(Rental $rental)
    {
        $this->authorizeRental($rental);

        $rental->load(['car', 'invoice', 'insurances', 'payment']);

        return view('user.rentals.invoice', compact('rental'));
    }

    /**
     * Ensure the authenticated user owns this rental.
     */
    private function authorizeRental(Rental $rental): void
    {
        if ($rental->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
    }
}
