@extends('layouts.admin')

@section('title', 'Rental Details')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Rental Details #{{ $rental->rental_id }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Customer Information</h6>
                @php
                    $user    = $rental->customer;           // User model
                    $profile = $user?->customer;            // Customer profile (has license_no, address)
                @endphp
                <table class="table table-borderless">
                    <tr>
                        <th>Name:</th>
                        <td>{{ $user->name ?? ($profile->full_name ?? 'N/A') }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $user->email ?? ($profile->email ?? 'N/A') }}</td>
                    </tr>
                    <tr>
                        <th>Phone:</th>
                        <td>{{ $user->phone ?? ($profile->phone ?? 'N/A') }}</td>
                    </tr>
                    <tr>
                        <th>License No:</th>
                        <td>{{ $profile->license_no ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Address:</th>
                        <td>{{ $user->address ?? ($profile->address ?? 'N/A') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Car Information</h6>
                <table class="table table-borderless">
                    <tr><th>Car:</th><td>{{ $rental->car->brand }} {{ $rental->car->model }}</td></tr>
                    <tr><th>Year:</th><td>{{ $rental->car->year }}</td></tr>
                    <tr><th>License Plate:</th><td>{{ $rental->car->license_plate }}</td></tr>
                    <tr><th>Daily Rate:</th><td>₱{{ number_format($rental->car->daily_rate, 2) }}</td></tr>
                </table>
            </div>
        </div>
        
        <hr>
        
        <div class="row">
            <div class="col-md-12">
                <h6>Rental Information</h6>
                <table class="table table-bordered">
                    <tr>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Actual Return Date</th>
                        <th>Status</th>
                        <th>Total Amount</th>
                    </tr>
                    <tr>
                        <td>{{ $rental->start_date->format('F d, Y') }}</td>
                        <td>{{ $rental->end_date->format('F d, Y') }}</td>
                        <td>{{ $rental->actual_return_date ? $rental->actual_return_date->format('F d, Y') : 'Not returned' }}</td>
                        <td>
                            @if($rental->status == 'active')
                                <span class="badge bg-success">Active (Rented)</span>
                            @elseif($rental->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($rental->status == 'returned')
                                <span class="badge bg-info">Returned</span>
                            @elseif($rental->status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($rental->status) }}</span>
                            @endif
                        </td>
                        <td>₱{{ number_format($rental->total_amount, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        @if($rental->invoice)
        <div class="row mt-3">
            <div class="col-md-12">
                <h6>Invoice Details</h6>
                <table class="table table-bordered">
                    <tr>
                        <th>Subtotal</th>
                        <th>Insurance Fee</th>
                        <th>Late Fee</th>
                        <th>Tax</th>
                        <th>Total Due</th>
                    </tr>
                    <tr>
                        <td>₱{{ number_format($rental->invoice->subtotal, 2) }}</td>
                        <td>₱{{ number_format($rental->invoice->insurance_fee, 2) }}</td>
                        <td>₱{{ number_format($rental->invoice->late_fee, 2) }}</td>
                        <td>₱{{ number_format($rental->invoice->tax, 2) }}</td>
                        <td>₱{{ number_format($rental->invoice->total_due, 2) }}</td>
                    </tr>
                </table>
                <a href="{{ route('admin.rentals.invoice', $rental->rental_id) }}" class="btn btn-info">
                    <i class="fas fa-file-invoice me-1"></i> Show Invoice
                </a>
            </div>
        </div>
        @endif
        
        <div class="mt-3">
            @if($rental->status == 'pending')
                <form action="{{ route('admin.rentals.approve', $rental->rental_id) }}"
                      method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success"
                            onclick="return confirm('Approve this rental? The car will be marked as Rented.')">
                        <i class="fas fa-check-circle"></i> Approve Rental
                    </button>
                </form>
            @endif
            @if($rental->status == 'active')
                <a href="{{ route('admin.rentals.return', $rental->rental_id) }}" class="btn btn-primary">
                    <i class="fas fa-undo"></i> Process Return
                </a>
            @endif
            <a href="{{ route('admin.rentals.edit', $rental->rental_id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.rentals.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection