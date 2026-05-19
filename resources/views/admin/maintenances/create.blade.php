@extends('layouts.admin')

@section('title', 'Add Maintenance')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Add Maintenance Record</h5>
        <a href="{{ route('admin.maintenances.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.maintenances.store') }}" method="POST">
            @csrf
            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Car <span class="text-danger">*</span></label>
                    <select name="car_id" class="form-select @error('car_id') is-invalid @enderror" required>
                        <option value="">— Select Car —</option>
                        @foreach($cars as $car)
                            <option value="{{ $car->car_id }}" {{ old('car_id') == $car->car_id ? 'selected' : '' }}>
                                {{ $car->brand }} {{ $car->model }} ({{ $car->license_plate }})
                            </option>
                        @endforeach
                    </select>
                    @error('car_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="scheduled"   {{ old('status') == 'scheduled'   ? 'selected' : '' }}>Scheduled</option>
                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed"   {{ old('status') == 'completed'   ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Maintenance Date <span class="text-danger">*</span></label>
                    <input type="date" name="maintenance_date"
                           class="form-control @error('maintenance_date') is-invalid @enderror"
                           value="{{ old('maintenance_date', date('Y-m-d')) }}" required>
                    @error('maintenance_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Next Due Date</label>
                    <input type="date" name="next_due_date"
                           class="form-control @error('next_due_date') is-invalid @enderror"
                           value="{{ old('next_due_date') }}">
                    @error('next_due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Cost (₱) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="number" name="cost" step="0.01" min="0"
                               class="form-control @error('cost') is-invalid @enderror"
                               value="{{ old('cost', '0.00') }}" required>
                        @error('cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea name="description" rows="3"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Describe the maintenance work..." required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            </div>

            <div class="mt-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Save Record
                </button>
                <a href="{{ route('admin.maintenances.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
