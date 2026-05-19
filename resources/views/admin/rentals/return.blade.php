@extends('layouts.admin')

@section('title', 'Process Return')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Process Return - Rental #{{ $rental->rental_id }}</h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">Customer:</th>
                        <td>{{ $rental->customer->name ?? ($rental->customer->customer->full_name ?? 'N/A') }}</td>
                    </tr>
                    <tr>
                        <th>Car:</th>
                        <td>{{ $rental->car->brand }} {{ $rental->car->model }} ({{ $rental->car->license_plate }})</td>
                    </tr>
                    <tr>
                        <th>Rental Period:</th>
                        <td>{{ $rental->start_date->format('M d, Y') }} - {{ $rental->end_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <th>Daily Rate:</th>
                        <td>₱{{ number_format($rental->car->daily_rate, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Current Odometer:</th>
                        <td>{{ number_format($rental->car->mileage) }} km</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <div class="alert alert-info">
                    <strong>Rental Information:</strong><br>
                    Status: <span class="badge bg-success">{{ ucfirst($rental->status) }}</span><br>
                    Start: {{ $rental->start_date->format('F d, Y') }}<br>
                    End: {{ $rental->end_date->format('F d, Y') }}
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.rentals.process-return', $rental->rental_id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Return Odometer (km) <span class="text-danger">*</span></label>
                    <input type="number" name="return_odometer" class="form-control @error('return_odometer') is-invalid @enderror" 
                           value="{{ old('return_odometer', $rental->car->mileage) }}" required min="{{ $rental->car->mileage }}">
                    @error('return_odometer')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Must be greater than or equal to current odometer</small>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Fuel Level <span class="text-danger">*</span></label>
                    <select name="fuel_level_in" class="form-control @error('fuel_level_in') is-invalid @enderror" required>
                        <option value="">Select Fuel Level</option>
                        <option value="full">Full</option>
                        <option value="three_quarters">3/4</option>
                        <option value="half">1/2</option>
                        <option value="quarter">1/4</option>
                        <option value="empty">Empty</option>
                    </select>
                    @error('fuel_level_in')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-12 mb-3">
                    <label class="form-label">Condition Notes</label>
                    <textarea name="condition_notes" class="form-control @error('condition_notes') is-invalid @enderror" 
                              rows="3" placeholder="Any damages, scratches, or issues?">{{ old('condition_notes') }}</textarea>
                    @error('condition_notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check-circle"></i> Complete Return & Generate Invoice
                </button>
                <a href="{{ route('admin.rentals.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection