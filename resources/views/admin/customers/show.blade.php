{{-- resources/views/admin/customers/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Customer Details')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Customer Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Full Name:</th>
                        <td>{{ $customer->full_name }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $customer->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone:</th>
                        <td>{{ $customer->phone }}</td>
                    </tr>
                    <tr>
                        <th>License No:</th>
                        <td>{{ $customer->license_no }}</td>
                    </tr>
                    <tr>
                        <th>Address:</th>
                        <td>{{ $customer->address }}</td>
                    </tr>
                    <tr>
                        <th>Registered:</th>
                        <td>{{ $customer->registered_date->format('F d, Y') }}</td>
                    </tr>
                </table>
                <div class="mt-3">
                    <a href="{{ route('admin.customers.edit', $customer->customer_id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Rental History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr><th>Rental ID</th><th>Car</th><th>Start Date</th><th>End Date</th><th>Total</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse($customer->rentals as $rental)
                            <tr>
                                <td>#{{ $rental->rental_id }}</td>
                                <td>{{ $rental->car->brand }} {{ $rental->car->model }}</td>
                                <td>{{ $rental->start_date->format('M d, Y') }}</td>
                                <td>{{ $rental->end_date->format('M d, Y') }}</td>
                                <td>₱{{ number_format($rental->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $rental->status == 'active' ? 'success' : ($rental->status == 'returned' ? 'info' : 'warning') }}">
                                        {{ ucfirst($rental->status) }}
                                    </span>
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
        
        <div class="card mt-3">
            <div class="card-header">
                <h5>Feedback History</h5>
            </div>
            <div class="card-body">
                @forelse($customer->feedback as $feedback)
                    <div class="border-bottom mb-3 pb-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $feedback->car->brand }} {{ $feedback->car->model }}</strong>
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $feedback->rating ? '' : '-o' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="mt-2">{{ $feedback->comment }}</p>
                        <small class="text-muted">{{ $feedback->review_date->format('M d, Y') }}</small>
                    </div>
                @empty
                    <p class="text-muted">No feedback yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection