@extends('layouts.admin')

@section('title', 'Manage Maintenance')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Maintenance Records</h5>
        <a href="{{ route('admin.maintenances.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Maintenance
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Car</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Cost</th>
                        <th>Next Due</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($maintenances as $maintenance)
                    <tr>
                        <td>{{ $maintenance->maintenance_id }}</td>
                        <td>{{ $maintenance->car->brand }} {{ $maintenance->car->model }}</td>
                        <td>{{ $maintenance->maintenance_date->format('M d, Y') }}</td>
                        <td>{{ Str::limit($maintenance->description, 30) }}</td>
                        <td>₱{{ number_format($maintenance->cost, 2) }}</td>
                        <td>{{ $maintenance->next_due_date ? $maintenance->next_due_date->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            @if($maintenance->status == 'scheduled')
                                <span class="badge bg-warning">Scheduled</span>
                            @elseif($maintenance->status == 'in_progress')
                                <span class="badge bg-info">In Progress</span>
                            @else
                                <span class="badge bg-success">Completed</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.maintenances.edit', $maintenance->maintenance_id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.maintenances.destroy', $maintenance->maintenance_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $maintenances->links() }}
    </div>
</div>
@endsection