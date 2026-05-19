<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('rental.customer.customer')->latest()->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load('rental.customer.customer', 'rental.car');
        return view('admin.payments.show', compact('payment'));
    }
    
    public function create(Rental $rental = null)
    {
        if ($rental) {
            $rentals = collect([$rental]);
        } else {
            $rentals = Rental::whereDoesntHave('payment')->where('status', 'returned')->get();
        }
        return view('admin.payments.create', compact('rentals'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rental_id' => 'required|exists:rentals,rental_id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,bank_transfer',
            'transaction_id' => 'nullable|string',
        ]);
        
        $payment = Payment::create([
            'rental_id' => $validated['rental_id'],
            'amount' => $validated['amount'],
            'payment_date' => now(),
            'method' => $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'] ?? 'TXN-' . strtoupper(uniqid()),
            'status' => 'completed',
        ]);
        
        return redirect()->route('admin.payments.index')->with('success', 'Payment recorded');
    }
    
    public function refund(Payment $payment)
    {
        $payment->update(['status' => 'refunded']);
        return back()->with('success', 'Payment refunded');
    }

    public function edit(Payment $payment)
    {
        $payment->load('rental.customer.customer', 'rental.car');
        return view('admin.payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount'         => 'required|numeric|min:0',
            'method'         => 'required|in:cash,credit_card,debit_card,bank_transfer',
            'payment_date'   => 'required|date',
            'status'         => 'required|in:pending,completed,failed,refunded',
            'transaction_id' => 'nullable|string|max:255',
        ]);

        $payment->update($validated);

        return redirect()->route('admin.payments.show', $payment->payment_id)
            ->with('success', 'Payment updated successfully.');
    }
    
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Payment deleted');
    }
}