@extends('layouts.admin')

@section('title', 'Payment Details')

@section('content')
@php
    $user    = $payment->rental->customer;       // User model
    $profile = $user?->customer;                 // Customer profile (license, address)
@endphp

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Payment #{{ $payment->payment_id }}</h5>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">

        <div class="row">
            {{-- Customer Info --}}
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title text-muted text-uppercase mb-3">
                            <i class="fas fa-user me-1"></i> Customer
                        </h6>
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th width="40%">Name</th>
                                <td>{{ $user->name ?? ($profile->full_name ?? 'N/A') }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email ?? ($profile->email ?? 'N/A') }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $user->phone ?? ($profile->phone ?? 'N/A') }}</td>
                            </tr>
                            <tr>
                                <th>License No</th>
                                <td>{{ $profile->license_no ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Car Info --}}
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title text-muted text-uppercase mb-3">
                            <i class="fas fa-car me-1"></i> Car
                        </h6>
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th width="40%">Car</th>
                                <td>{{ $payment->rental->car->brand }} {{ $payment->rental->car->model }}</td>
                            </tr>
                            <tr>
                                <th>Year</th>
                                <td>{{ $payment->rental->car->year }}</td>
                            </tr>
                            <tr>
                                <th>Plate</th>
                                <td>{{ $payment->rental->car->license_plate }}</td>
                            </tr>
                            <tr>
                                <th>Daily Rate</th>
                                <td>₱{{ number_format($payment->rental->car->daily_rate, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Rental Info --}}
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title text-muted text-uppercase mb-3">
                            <i class="fas fa-calendar-check me-1"></i> Rental
                        </h6>
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th width="40%">Rental #</th>
                                <td>
                                    <a href="{{ route('admin.rentals.show', $payment->rental->rental_id) }}">
                                        #{{ $payment->rental->rental_id }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Start</th>
                                <td>{{ $payment->rental->start_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>End</th>
                                <td>{{ $payment->rental->end_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($payment->rental->status == 'active')
                                        <span class="badge bg-success">Active (Rented)</span>
                                    @elseif($payment->rental->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($payment->rental->status == 'returned')
                                        <span class="badge bg-info">Returned</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($payment->rental->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Details --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="card-title text-muted text-uppercase mb-3">
                    <i class="fas fa-credit-card me-1"></i> Payment Details
                </h6>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <small class="text-muted d-block">Amount</small>
                        <strong class="fs-5">₱{{ number_format($payment->amount, 2) }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted d-block">Payment Date</small>
                        <strong>{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'N/A' }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted d-block">Method</small>
                        <strong>{{ ucwords(str_replace('_', ' ', $payment->method)) }}</strong>
                    </div>
                    <div class="col-md-3 mb-3">
                        <small class="text-muted d-block">Status</small>
                        @if($payment->status == 'completed')
                            <span class="badge bg-success fs-6">Completed</span>
                        @elseif($payment->status == 'pending')
                            <span class="badge bg-warning text-dark fs-6">Pending</span>
                        @elseif($payment->status == 'refunded')
                            <span class="badge bg-secondary fs-6">Refunded</span>
                        @else
                            <span class="badge bg-danger fs-6">{{ ucfirst($payment->status) }}</span>
                        @endif
                    </div>
                    @if($payment->transaction_id)
                    <div class="col-md-6 mb-3">
                        <small class="text-muted d-block">Transaction ID</small>
                        <strong>{{ $payment->transaction_id }}</strong>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-3">
            @if($payment->status == 'pending')
                <a href="{{ route('admin.payments.edit', $payment->payment_id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Update Payment
                </a>
            @endif
            @if($payment->status == 'completed')
                <form action="{{ route('admin.payments.refund', $payment->payment_id) }}"
                      method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-secondary"
                            onclick="return confirm('Refund this payment?')">
                        <i class="fas fa-undo"></i> Refund
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Payments
            </a>
        </div>

    </div>
</div>
@endsection
