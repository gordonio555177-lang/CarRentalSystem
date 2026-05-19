@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Customers</h5>
                <h2 class="mb-0">{{ number_format($totalCustomers ?? 0) }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total Cars</h5>
                <h2 class="mb-0">{{ number_format($totalCars ?? 0) }}</h2>
                <small>Available: {{ number_format($availableCars ?? 0) }}</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5 class="card-title">Active Rentals</h5>
                <h2 class="mb-0">{{ number_format($activeRentals ?? 0) }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Total Revenue</h5>
                <h2 class="mb-0">₱{{ number_format($totalRevenue ?? 0, 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Car Status</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label>Available</label>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: {{ $totalCars > 0 ? ($availableCars / $totalCars) * 100 : 0 }}%">
                            {{ number_format(($availableCars / max($totalCars, 1)) * 100, 1) }}%
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Rented</label>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: {{ $totalCars > 0 ? ($rentedCars / $totalCars) * 100 : 0 }}%">
                            {{ number_format(($rentedCars / max($totalCars, 1)) * 100, 1) }}%
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label>Maintenance</label>
                    <div class="progress">
                        <div class="progress-bar bg-danger" style="width: {{ $totalCars > 0 ? ($maintenanceCars / $totalCars) * 100 : 0 }}%">
                            {{ number_format(($maintenanceCars / max($totalCars, 1)) * 100, 1) }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Recent Rentals</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Car</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRentals ?? [] as $rental)
                            <tr>
                                <td>{{ $rental->customer->name ?? 'N/A' }}</td>
                                <td>{{ $rental->car->brand ?? '' }} {{ $rental->car->model ?? '' }}</td>
                                <td>{{ $rental->start_date ? $rental->start_date->format('M d, Y') : 'N/A' }}</td>
                                <td>{{ $rental->end_date ? $rental->end_date->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $rental->status == 'active' ? 'success' : ($rental->status == 'returned' ? 'info' : 'warning') }}">
                                        {{ ucfirst($rental->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.rentals.show', $rental->rental_id) }}" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">No rentals found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Recent Customers</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr><th>Name</th><th>Email</th><th>Registered</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentCustomers ?? [] as $customer)
                            <tr>
                                <td>{{ $customer->full_name ?? $customer->first_name . ' ' . $customer->last_name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->registered_date ? $customer->registered_date->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            @empty
                                <td><td colspan="3" class="text-center">No customers found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Quick Stats</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3 text-center">
                            <h6>Total Staff</h6>
                            <h3>{{ number_format($totalStaff ?? 0) }}</h3>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3 text-center">
                            <h6>Total Branches</h6>
                            <h3>{{ number_format($totalBranches ?? 0) }}</h3>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3 text-center">
                            <h6>Rented Cars</h6>
                            <h3>{{ number_format($rentedCars ?? 0) }}</h3>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3 text-center">
                            <h6>Maintenance</h6>
                            <h3>{{ number_format($maintenanceCars ?? 0) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($monthlyRevenue) && count($monthlyRevenue) > 0)
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Monthly Revenue (Last 7 Months)</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if(isset($monthlyRevenue) && count($monthlyRevenue) > 0)
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($monthlyRevenue, 'month')) !!},
            datasets: [{
                label: 'Revenue (₱)',
                data: {!! json_encode(array_column($monthlyRevenue, 'revenue')) !!},
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '₱' + context.raw.toLocaleString('en-PH', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endif
@endpush