{{-- resources/views/user/dashboard.blade.php --}}
@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')
<style>
    .stats-card {
        transition: transform 0.2s;
        border: none;
        border-radius: 10px;
    }
    .stats-card:hover {
        transform: translateY(-3px);
    }
    .welcome-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        color: white;
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
</style>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card welcome-banner">
            <div class="card-body">
                <h4 class="mb-2">Welcome back, {{ Auth::user()->name ?? 'Customer' }}! 🚗</h4>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stats-card bg-primary text-white">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-white-50">Total Rentals</h6>
                <h2 class="mb-0">{{ number_format($totalRentals ?? 0) }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card bg-success text-white">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-white-50">Active Rentals</h6>
                <h2 class="mb-0">{{ number_format($activeRentals ?? 0) }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card bg-warning text-white">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-white-50">Total Spent</h6>
                <h2 class="mb-0">₱{{ number_format($totalSpent ?? 0, 2) }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card bg-info text-white">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-white-50">Average Rating</h6>
                <h2 class="mb-0">{{ number_format($averageRating ?? 0, 1) }}/5</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Rentals</h5>
                <a href="{{ route('user.rentals.index') }}" class="btn btn-sm btn-outline-primary">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                @if(isset($recentRentals) && $recentRentals->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Car</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRentals as $rental)
                                <tr>
                                    <td>{{ $rental->car->brand }} {{ $rental->car->model }}</td>
                                    <td>{{ $rental->start_date->format('M d, Y') }}</td>
                                    <td>{{ $rental->end_date->format('M d, Y') }}</td>
                                    <td>₱{{ number_format($rental->total_amount, 2) }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'active' => 'success',
                                                'returned' => 'info',
                                                'cancelled' => 'danger',
                                            ];
                                            $statusColor = $statusColors[$rental->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }}">
                                            {{ ucfirst($rental->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('user.rentals.show', $rental->rental_id) }}" class="btn btn-sm btn-primary">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No rentals yet</h5>
                        <p>Start your first rental journey with us!</p>
                        <a href="{{ route('user.cars.index') }}" class="btn btn-primary">
                            Browse Available Cars
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('user.cars.index') }}" class="btn btn-primary">
                        <i class="fas fa-car me-2"></i> Browse Available Cars
                    </a>
                    <a href="{{ route('user.rentals.index') }}" class="btn btn-info text-white">
                        <i class="fas fa-history me-2"></i> View Rental History
                    </a>
                    <a href="{{ route('profile.edit') }}" class="btn btn-secondary">
                        <i class="fas fa-user me-2"></i> Update Profile
                    </a>
                </div>
            </div>
        </div>
        
        @if(isset($pendingFeedback) && $pendingFeedback->count() > 0)
        <div class="card mt-3 border-warning">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">Pending Feedback</h5>
            </div>
            <div class="card-body">
                @foreach($pendingFeedback as $rental)
                    <div class="alert alert-warning mb-3">
                        <strong>{{ $rental->car->brand }} {{ $rental->car->model }}</strong>
                        <br>
                        <small>Rental ended on {{ $rental->end_date->format('M d, Y') }}</small>
                        <hr>
                        <a href="{{ route('user.rentals.show', $rental->rental_id) }}" class="btn btn-warning btn-sm w-100">
                            Leave Feedback
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection