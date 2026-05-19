<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Http\Request;

class UserPaymentController extends Controller
{
    public function index()
    {
        $payments = auth()->user()->rentals()
            ->whereHas('payment')
            ->with('payment')
            ->get()
            ->pluck('payment');
            
        return view('user.payments.index', compact('payments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rental_id' => 'required|exists:rentals,rental_id',
            'payment_method' => 'required|in:cash,card,bank_transfer',
        ]);
        
        $rental = Rental::findOrFail($validated['rental_id']);
        
        if ($rental->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($rental->payment) {
            return back()->with('error', 'Payment already exists for this rental.');
        }
        
        $payment = Payment::create([
            'rental_id' => $rental->rental_id,
            'amount' => $rental->total_amount,
            'payment_date' => now(),
            'method' => $validated['payment_method'],
            'status' => 'completed',
            'transaction_id' => 'TXN-' . strtoupper(uniqid()),
        ]);
        
        return redirect()->route('user.rentals.show', $rental)
            ->with('success', 'Payment processed successfully!');
    }
    
    public function show(Payment $payment)
    {
        if ($payment->rental->customer_id !== auth()->id()) {
            abort(403);
        }
        
        return view('user.payments.show', compact('payment'));
    }
    
    public function downloadReceipt(Payment $payment)
    {
        if ($payment->rental->customer_id !== auth()->id()) {
            abort(403);
        }
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user.payments.receipt', compact('payment'));
        return $pdf->download('receipt-' . $payment->payment_id . '.pdf');
    }
}