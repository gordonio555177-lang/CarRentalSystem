@extends('layouts.admin')

@section('title', 'Manage Rentals')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Rentals</h5>
        <a href="{{ route('admin.rentals.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Rental
        </a>
    </div>
    <div class="card-body">

        {{-- Pending count notice --}}
        @php $pendingCount = $rentals->where('status', 'pending')->count(); @endphp
        @if($pendingCount > 0)
        <div class="alert alert-warning d-flex align-items-center mb-3">
            <i class="fas fa-clock me-2 fa-lg"></i>
            <span>
                <strong>{{ $pendingCount }} pending rental{{ $pendingCount > 1 ? 's' : '' }}</strong>
                awaiting your approval.
            </span>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Car</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rentals as $rental)
                    <tr class="{{ $rental->status == 'pending' ? 'table-warning' : '' }}">
                        <td>#{{ $rental->rental_id }}</td>
                        <td>{{ $rental->customer->customer->full_name ?? $rental->customer->name ?? 'N/A' }}</td>
                        <td>{{ $rental->car->brand ?? '' }} {{ $rental->car->model ?? '' }}</td>
                        <td>{{ $rental->start_date->format('M d, Y') }}</td>
                        <td>{{ $rental->end_date->format('M d, Y') }}</td>
                        <td>₱{{ number_format($rental->total_amount, 2) }}</td>
                        <td>
                            @if($rental->status == 'active')
                                <span class="badge bg-success">Active (Rented)</span>
                            @elseif($rental->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($rental->status == 'returned')
                                <span class="badge bg-info">Returned</span>
                            @elseif($rental->status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($rental->status) }}</span>
                            @endif
                        </td>
                        <td>
                            {{-- View --}}
                            <a href="{{ route('admin.rentals.show', $rental->rental_id) }}"
                               class="btn btn-sm btn-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>

                            {{-- Approve (pending only) --}}
                            @if($rental->status == 'pending')
                            <form action="{{ route('admin.rentals.approve', $rental->rental_id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success"
                                        title="Approve — set to Active"
                                        onclick="return confirm('Approve rental #{{ $rental->rental_id }}? This will mark the car as Rented.')">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                            @endif

                            {{-- Edit --}}
                            <a href="{{ route('admin.rentals.edit', $rental->rental_id) }}"
                               class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- Process Return (active only) --}}
                            @if($rental->status == 'active')
                            <a href="{{ route('admin.rentals.return', $rental->rental_id) }}"
                               class="btn btn-sm btn-primary" title="Process Return">
                                <i class="fas fa-undo"></i> Return
                            </a>
                            @endif

                            {{-- Delete --}}
                            <form action="{{ route('admin.rentals.destroy', $rental->rental_id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                        onclick="return confirm('Delete rental #{{ $rental->rental_id }}?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $rentals->links() }}
    </div>
</div>
@endsection
