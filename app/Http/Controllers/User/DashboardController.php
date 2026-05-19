<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get statistics
        $totalRentals = Rental::where('customer_id', $user->id)->count();
        $activeRentals = Rental::where('customer_id', $user->id)
            ->where('status', 'active')
            ->count();
        $totalSpent = Rental::where('customer_id', $user->id)
            ->where('status', 'returned')
            ->sum('total_amount');
        $averageRating = Feedback::where('customer_id', $user->id)
            ->avg('rating') ?? 0;
        
        // Get recent rentals
        $recentRentals = Rental::where('customer_id', $user->id)
            ->with('car')
            ->latest()
            ->take(5)
            ->get();
        
        // Get rentals pending feedback
        $pendingFeedback = Rental::where('customer_id', $user->id)
            ->where('status', 'returned')
            ->whereDoesntHave('feedback')
            ->with('car')
            ->get();
        
        return view('user.dashboard', compact(
            'totalRentals', 'activeRentals', 'totalSpent', 'averageRating',
            'recentRentals', 'pendingFeedback'
        ));
    }
}