@extends('layouts.admin')

@section('title', 'Create New Rental')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Create New Rental</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.rentals.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Customer</label>
                    <select name="customer_id" class="form-control @error('customer_id') is-invalid @enderror" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->customer_id }}">{{ $customer->full_name }} ({{ $customer->email }})</option>
                        @endforeach
                    </select>
                    @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Car</label>
                    <select name="car_id" class="form-control @error('car_id') is-invalid @enderror" required>
                        <option value="">Select Car</option>
                        @foreach($cars as $car)
                            <option value="{{ $car->car_id }}">{{ $car->brand }} {{ $car->model }} - ₱{{ number_format($car->daily_rate, 2) }}/day</option>
                        @endforeach
                    </select>
                    @error('car_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                    @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                    @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Insurance (Optional)</label>
                    <select name="insurance_ids[]" class="form-control" multiple>
                        @foreach($insurances as $insurance)
                            <option value="{{ $insurance->insurance_id }}">{{ $insurance->name }} - ₱{{ number_format($insurance->daily_rate, 2) }}/day</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hold Ctrl to select multiple</small>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Create Rental</button>
                <a href="{{ route('admin.rentals.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection