@extends('layouts.admin')

@section('title', 'Add Branch')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Add New Branch</h5>
        <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.branches.store') }}" method="POST">
            @csrf
            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="e.g. Makati Branch" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">City <span class="text-danger">*</span></label>
                    <input type="text" name="city"
                           class="form-control @error('city') is-invalid @enderror"
                           value="{{ old('city') }}" placeholder="e.g. Makati" required>
                    @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="text" name="phone"
                           class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone') }}" placeholder="e.g. +63 2 8123 4567" required>
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Branch Manager</label>
                    <select name="manager_staff_id" class="form-select @error('manager_staff_id') is-invalid @enderror">
                        <option value="">— No Manager Assigned —</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->staff_id }}"
                                {{ old('manager_staff_id') == $manager->staff_id ? 'selected' : '' }}>
                                {{ $manager->first_name }} {{ $manager->last_name }} ({{ ucfirst($manager->role) }})
                            </option>
                        @endforeach
                    </select>
                    @error('manager_staff_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Address <span class="text-danger">*</span></label>
                    <textarea name="address" rows="2"
                              class="form-control @error('address') is-invalid @enderror"
                              placeholder="Full street address" required>{{ old('address') }}</textarea>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            </div>

            <div class="mt-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Save Branch
                </button>
                <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
