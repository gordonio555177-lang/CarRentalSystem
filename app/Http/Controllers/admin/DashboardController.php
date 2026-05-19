<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Rental;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Staff;
use App\Models\Branch;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalCustomers = Customer::count();
        $totalCars = Car::count();
        $availableCars = Car::where('status', 'available')->count();
        $activeRentals = Rental::where('status', 'active')->count();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $totalStaff = Staff::count();
        $totalBranches = Branch::count();
        
        // Recent rentals
        $recentRentals = Rental::with(['customer.customer', 'car'])
            ->latest()
            ->take(10)
            ->get();
        
        // Recent customers
        $recentCustomers = Customer::latest()->take(5)->get();
        
        // Cars by status
        $rentedCars = Car::where('status', 'rented')->count();
        $maintenanceCars = Car::where('status', 'maintenance')->count();
        
        // Monthly revenue chart data
        $monthlyRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenue = Payment::where('status', 'completed')
                ->whereYear('payment_date', $month->year)
                ->whereMonth('payment_date', $month->month)
                ->sum('amount');
            $monthlyRevenue[] = [
                'month' => $month->format('M'),
                'revenue' => $revenue
            ];
        }
        
        return view('admin.dashboard', compact(
            'totalCustomers', 'totalCars', 'availableCars', 'activeRentals',
            'totalRevenue', 'totalStaff', 'totalBranches', 'recentRentals',
            'recentCustomers', 'rentedCars', 'maintenanceCars', 'monthlyRevenue'
        ));
    }
    
    public function rentalReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now());
        
        $rentals = Rental::with(['customer', 'car'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->paginate(20);
            
        return view('admin.reports.rentals', compact('rentals', 'startDate', 'endDate'));
    }
    
    public function revenueReport(Request $request)
    {
        $year = $request->get('year', now()->year);
        
        $monthlyRevenue = [];
        for ($month = 1; $month <= 12; $month++) {
            $revenue = Payment::where('status', 'completed')
                ->whereYear('payment_date', $year)
                ->whereMonth('payment_date', $month)
                ->sum('amount');
            $monthlyRevenue[] = [
                'month' => date('F', mktime(0, 0, 0, $month, 1)),
                'revenue' => $revenue
            ];
        }
        
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $totalRentals = Rental::count();
        
        return view('admin.reports.revenue', compact('monthlyRevenue', 'totalRevenue', 'totalRentals', 'year'));
    }
    
    public function customerReport(Request $request)
    {
        $customers = Customer::withCount('rentals')
            ->withSum('rentals', 'total_amount')
            ->orderBy('rentals_count', 'desc')
            ->paginate(20);
            
        return view('admin.reports.customers', compact('customers'));
    }
    
    public function exportReport($type)
    {
        // Implement export logic (Excel/PDF)
        return redirect()->back()->with('success', 'Report exported successfully!');
    }
}