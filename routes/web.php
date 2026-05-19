<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboardController,
    CustomerController as AdminCustomerController,
    CarController as AdminCarController,
    RentalController as AdminRentalController,
    InsuranceController as AdminInsuranceController,
    MaintenanceController as AdminMaintenanceController,
    PaymentController as AdminPaymentController,
    FeedbackController as AdminFeedbackController,
    BranchController as AdminBranchController
};
use App\Http\Controllers\User\{
    DashboardController as UserDashboardController,
    CarController as UserCarController,
    RentalController as UserRentalController,
    FeedbackController as UserFeedbackController,
    UserPaymentController
};
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== PUBLIC ROUTES ====================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ==================== BREEZE AUTH ROUTES (Keep these) ====================
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');
    
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('login');

// Registration routes (Breeze default)
Route::get('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])
    ->name('register');
    
Route::post('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Password reset routes
Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])
    ->name('password.request');
    
Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
    ->name('password.email');
    
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\NewPasswordController::class, 'create'])
    ->name('password.reset');
    
Route::post('/reset-password', [App\Http\Controllers\Auth\NewPasswordController::class, 'store'])
    ->name('password.store');

// ==================== DASHBOARD REDIRECT ====================
Route::get('/dashboard', function () {
    if (auth()->guard('staff')->check()) {
        return redirect()->route('admin.dashboard');
    }
    if (auth()->check()) {
        return redirect()->route('user.dashboard');
    }
    return redirect()->route('login');
})->middleware(['auth'])->name('dashboard');

// Breeze Profile Routes (for regular users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
});

// ==================== ADMIN ROUTES (Staff Guard) ====================
Route::prefix('admin')->name('admin.')->middleware('auth:staff')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Customers Management
    Route::resource('customers', AdminCustomerController::class);
    
    // Cars Management
    Route::resource('cars', AdminCarController::class);
    
    // Rentals Management
    Route::resource('rentals', AdminRentalController::class);
    Route::get('rentals/{rental}/invoice', [AdminRentalController::class, 'generateInvoice'])->name('rentals.invoice');
    Route::get('rentals/{rental}/return', [AdminRentalController::class, 'return'])->name('rentals.return');
    Route::post('rentals/{rental}/process-return', [AdminRentalController::class, 'processReturn'])->name('rentals.process-return');
    Route::post('rentals/{rental}/approve', [AdminRentalController::class, 'approve'])->name('rentals.approve');
    
    // Insurance Management
    Route::resource('insurances', AdminInsuranceController::class);
    
    // Maintenance Management
    Route::resource('maintenances', AdminMaintenanceController::class);
    Route::post('maintenances/{maintenance}/complete', [AdminMaintenanceController::class, 'complete'])->name('maintenances.complete');
    
    // Payments Management
    Route::resource('payments', AdminPaymentController::class);
    Route::post('payments/{payment}/refund', [AdminPaymentController::class, 'refund'])->name('payments.refund');
    
    // Feedback Management
    Route::resource('feedback', AdminFeedbackController::class);
    Route::post('feedback/{feedback}/publish', [AdminFeedbackController::class, 'publish'])->name('feedback.publish');
    
    // Branches Management
    Route::resource('branches', AdminBranchController::class);
    
    // Reports
    Route::get('reports/rentals', [AdminDashboardController::class, 'rentalReport'])->name('reports.rentals');
    Route::get('reports/revenue', [AdminDashboardController::class, 'revenueReport'])->name('reports.revenue');
    Route::get('reports/customers', [AdminDashboardController::class, 'customerReport'])->name('reports.customers');
    Route::get('reports/export/{type}', [AdminDashboardController::class, 'exportReport'])->name('reports.export');
});

// ==================== CUSTOMER ROUTES (Regular Users) ====================
Route::prefix('user')->name('user.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    
    // Cars Browsing & Booking
    Route::get('/cars', [UserCarController::class, 'index'])->name('cars.index');
    Route::get('/cars/{car}', [UserCarController::class, 'show'])->name('cars.show');
    Route::post('/cars/{car}/book', [UserCarController::class, 'book'])->name('cars.book');
    
    // Rentals History
    Route::get('/rentals', [UserRentalController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/{rental}', [UserRentalController::class, 'show'])->name('rentals.show');
    Route::delete('/rentals/{rental}/cancel', [UserRentalController::class, 'cancel'])->name('rentals.cancel');
    Route::post('/rentals/{rental}/extend', [UserRentalController::class, 'extend'])->name('rentals.extend');
    
    // Feedback
    Route::post('/feedback', [UserFeedbackController::class, 'store'])->name('feedback.store');
    Route::put('/feedback/{feedback}', [UserFeedbackController::class, 'update'])->name('feedback.update');
    Route::delete('/feedback/{feedback}', [UserFeedbackController::class, 'destroy'])->name('feedback.destroy');
    
    // Invoices & Payments
    Route::get('/invoices/{rental}', [UserRentalController::class, 'downloadInvoice'])->name('invoices.download');
    Route::post('/payments', [UserPaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}/receipt', [UserPaymentController::class, 'downloadReceipt'])->name('payments.receipt');
});

// ==================== API ROUTES (AJAX) ====================
Route::prefix('api')->middleware(['auth'])->group(function () {
    Route::get('car-availability/{car}', [UserCarController::class, 'checkAvailability'])->name('api.car.availability');
    Route::get('rental/{rental}', [UserRentalController::class, 'getDetails'])->name('api.rental.details');
    Route::post('calculate-cost', [UserCarController::class, 'calculateCost'])->name('api.calculate.cost');
    Route::get('customer/history', [UserDashboardController::class, 'getHistory'])->name('api.customer.history');
});