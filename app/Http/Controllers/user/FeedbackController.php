<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Rental;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rental_id' => 'required|exists:rentals,rental_id',
            'car_id' => 'required|exists:cars,car_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);
        
        $rental = Rental::findOrFail($validated['rental_id']);
        
        // Verify rental belongs to user and is returned
        if ($rental->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($rental->status !== 'returned') {
            return back()->with('error', 'You can only leave feedback for returned rentals.');
        }
        
        // Check if feedback already exists
        if ($rental->feedback) {
            return back()->with('error', 'Feedback already submitted for this rental.');
        }
        
        Feedback::create([
            'customer_id' => auth()->id(),   // users.id — matches Feedback->customer() relation
            'car_id'      => $validated['car_id'],
            'rental_id'   => $validated['rental_id'],
            'rating'      => $validated['rating'],
            'comment'     => $validated['comment'],
            'review_date' => now(),
        ]);
        
        return redirect()->route('user.rentals.show', $rental)
            ->with('success', 'Thank you for your feedback!');
    }
    
    public function update(Request $request, Feedback $feedback)
    {
        if ($feedback->customer_id !== auth()->id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);
        
        $feedback->update($validated);
        
        return redirect()->route('user.rentals.show', $feedback->rental_id)
            ->with('success', 'Feedback updated successfully.');
    }
    
    public function destroy(Feedback $feedback)
    {
        if ($feedback->customer_id !== auth()->id()) {
            abort(403);
        }
        
        $feedback->delete();
        
        return redirect()->route('user.rentals.index')
            ->with('success', 'Feedback deleted successfully.');
    }
}