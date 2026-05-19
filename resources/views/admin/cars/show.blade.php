@extends('layouts.admin')

@section('title', 'Car Details')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Car Details: {{ $car->brand }} {{ $car->model }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr><th>Brand:</th><td>{{ $car->brand }}</td></tr>
                    <tr><th>Model:</th><td>{{ $car->model }}</td></tr>
                    <tr><th>Year:</th><td>{{ $car->year }}</td></tr>
                    <tr><th>License Plate:</th><td>{{ $car->license_plate }}</td></tr>
                    <tr><th>Daily Rate:</th><td>₱{{ number_format($car->daily_rate, 2) }}</td></tr>
                    <tr><th>Mileage:</th><td>{{ number_format($car->mileage) }} km</td></tr>
                    <tr><th>Status:</th><td>
                        @if($car->status == 'available')
                            <span class="badge bg-success">Available</span>
                        @elseif($car->status == 'rented')
                            <span class="badge bg-warning">Rented</span>
                        @elseif($car->status == 'maintenance')
                            <span class="badge bg-danger">Maintenance</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($car->status) }}</span>
                        @endif
                    </td></tr>
                    <tr><th>Branch:</th><td>{{ $car->branch->name ?? 'N/A' }}</td></tr>
                </table>
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ route('admin.cars.edit', $car->car_id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>

@if($car->rentals->count() > 0)
<div class="card mt-4">
    <div class="card-header">
        <h5>Rental History</h5>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr><th>Customer</th><th>Start Date</th><th>End Date</th><th>Total</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($car->rentals as $rental)
                <tr>
                    <td>{{ $rental->customer->full_name ?? 'N/A' }}</td>
                    <td>{{ $rental->start_date->format('M d, Y') }}</td>
                    <td>{{ $rental->end_date->format('M d, Y') }}</td>
                    <td>₱{{ number_format($rental->total_amount, 2) }}</td>
                    <td><span class="badge bg-{{ $rental->status == 'active' ? 'success' : 'info' }}">{{ ucfirst($rental->status) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection