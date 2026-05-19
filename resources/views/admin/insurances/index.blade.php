@extends('layouts.admin')

@section('title', 'Manage Insurance')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Insurance Plans</h5>
        <a href="{{ route('admin.insurances.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Insurance Plan
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Coverage Details</th>
                        <th>Daily Rate</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($insurances as $insurance)
                    <tr>
                        <td>{{ $insurance->insurance_id }}</td>
                        <td><strong>{{ $insurance->name }}</strong></td>
                        <td>{{ Str::limit($insurance->coverage_details, 50) }}</td>
                        <td>₱{{ number_format($insurance->daily_rate, 2) }} / day</td>
                        <td>
                            <a href="{{ route('admin.insurances.edit', $insurance->insurance_id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.insurances.destroy', $insurance->insurance_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this insurance plan?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $insurances->links() }}
    </div>
</div>
@endsection