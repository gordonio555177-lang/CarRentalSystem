@extends('layouts.admin')

@section('title', 'Edit Rental')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Rental #{{ $rental->rental_id }}</h5>
        <a href="{{ route('admin.rentals.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">

        {{-- Current status alert --}}
        <div class="alert
            @if($rental->status == 'pending') alert-warning
            @elseif($rental->status == 'active') alert-success
            @elseif($rental->status == 'returned') alert-info
            @elseif($rental->status == 'cancelled') alert-danger
            @else alert-secondary @endif mb-4">
            <i class="fas fa-info-circle me-1"></i>
            Current Status:
            <strong>{{ ucfirst($rental->status) }}</strong>
            @if($rental->status == 'pending')
                — Awaiting approval. Change status to <strong>Active</strong> to confirm the rental and mark the car as rented.
            @elseif($rental->status == 'active')
                — Car is currently rented out.
            @endif
        </div>

        <form action="{{ route('admin.rentals.update', $rental->rental_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                {{-- Customer (read-only) --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Customer</label>
                    <input type="text" class="form-control"
                        value="{{ $rental->customer->name ?? ($rental->customer->customer->full_name ?? 'N/A') }}" disabled>
                </div>

                {{-- Car (read-only) --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Car</label>
                    <input type="text" class="form-control"
                        value="{{ $rental->car->brand }} {{ $rental->car->model }} ({{ $rental->car->license_plate }})" disabled>
                </div>

                {{-- Start Date --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date"
                        class="form-control @error('start_date') is-invalid @enderror"
                        value="{{ old('start_date', $rental->start_date->format('Y-m-d')) }}" required>
                    @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- End Date --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                    <input type="date" name="end_date"
                        class="form-control @error('end_date') is-invalid @enderror"
                        value="{{ old('end_date', $rental->end_date->format('Y-m-d')) }}" required>
                    @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Status --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="pending"   {{ old('status', $rental->status) == 'pending'   ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="active"    {{ old('status', $rental->status) == 'active'    ? 'selected' : '' }}>
                            Active (Rented)
                        </option>
                        <option value="returned"  {{ old('status', $rental->status) == 'returned'  ? 'selected' : '' }}>
                            Returned
                        </option>
                        <option value="cancelled" {{ old('status', $rental->status) == 'cancelled' ? 'selected' : '' }}>
                            Cancelled
                        </option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">
                        Setting to <strong>Active</strong> will mark the car as <em>Rented</em>.
                        Setting to <strong>Returned</strong> or <strong>Cancelled</strong> will free the car.
                    </small>
                </div>

                {{-- Insurance --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Insurance</label>
                    <select name="insurance_ids[]" class="form-control" multiple>
                        @foreach($insurances as $insurance)
                            <option value="{{ $insurance->insurance_id }}"
                                {{ $rental->insurances->contains('insurance_id', $insurance->insurance_id) ? 'selected' : '' }}>
                                {{ $insurance->name }} — ₱{{ number_format($insurance->daily_rate, 2) }}/day
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hold Ctrl to select multiple</small>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Rental
                </button>
                <a href="{{ route('admin.rentals.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
