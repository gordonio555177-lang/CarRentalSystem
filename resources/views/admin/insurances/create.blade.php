@extends('layouts.admin')

@section('title', 'Add Insurance Plan')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Add New Insurance Plan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.insurances.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Insurance Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Coverage Details</label>
                    <textarea name="coverage_details" class="form-control @error('coverage_details') is-invalid @enderror" 
                              rows="4" required>{{ old('coverage_details') }}</textarea>
                    @error('coverage_details')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Daily Rate (₱)</label>
                    <input type="number" step="0.01" name="daily_rate" class="form-control @error('daily_rate') is-invalid @enderror" 
                           value="{{ old('daily_rate') }}" required>
                    @error('daily_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save Insurance Plan</button>
                <a href="{{ route('admin.insurances.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection