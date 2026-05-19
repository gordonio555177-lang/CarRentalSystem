@extends('layouts.admin')

@section('title', 'Manage Cars')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Cars</h5>
        <a href="{{ route('admin.cars.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Car
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Car</th>
                        <th>License Plate</th>
                        <th>Daily Rate</th>
                        <th>Mileage</th>
                        <th>Status</th>
                        <th>Branch</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cars as $car)
                    <tr>
                        <td>{{ $car->car_id }}</td>
                        <td><strong>{{ $car->brand }} {{ $car->model }}</strong><br><small>{{ $car->year }}</small></td>
                        <td>{{ $car->license_plate }}</td>
                        <td>₱{{ number_format($car->daily_rate, 2) }}</td>
                        <td>{{ number_format($car->mileage) }} km</td>
                        <td>
                            @if($car->status == 'available')
                                <span class="badge bg-success">Available</span>
                            @elseif($car->status == 'rented')
                                <span class="badge bg-warning">Rented</span>
                            @elseif($car->status == 'maintenance')
                                <span class="badge bg-danger">Maintenance</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($car->status) }}</span>
                            @endif
                        </td>
                        <td>{{ $car->branch->name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('admin.cars.show', $car->car_id) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('admin.cars.edit', $car->car_id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.cars.destroy', $car->car_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this car?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $cars->links() }}
    </div>
</div>
@endsection