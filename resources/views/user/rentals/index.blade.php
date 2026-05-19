{{-- resources/views/user/rentals/index.blade.php --}}
@extends('layouts.user')

@section('title', 'My Rentals')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">My Rental History</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Rental #</th>
                        <th>Car</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Return Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rentals as $rental)
                    <tr>
                        <td>#{{ $rental->rental_id }}</td>
                        <td>{{ $rental->car->brand }} {{ $rental->car->model }}</td>
                        <td>{{ $rental->start_date->format('M d, Y') }}</td>
                        <td>{{ $rental->end_date->format('M d, Y') }}</td>
                        <td>{{ $rental->actual_return_date ? $rental->actual_return_date->format('M d, Y') : '-' }}</td>
                        <td>₱{{ number_format($rental->total_amount, 2) }}</td>
                        <td>
                            @if($rental->status == 'pending')
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-clock"></i> Pending Approval
                                </span>
                            @elseif($rental->status == 'active')
                                <span class="badge bg-success">
                                    <i class="fas fa-car"></i> Rented
                                </span>
                            @elseif($rental->status == 'returned')
                                <span class="badge bg-info">
                                    <i class="fas fa-check-circle"></i> Returned
                                </span>
                            @elseif($rental->status == 'cancelled')
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle"></i> Cancelled
                                </span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($rental->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('user.rentals.show', $rental->rental_id) }}"
                               class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> View
                            </a>
                            @if($rental->status == 'pending')
                            <form action="{{ route('user.rentals.cancel', $rental->rental_id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Cancel this rental request?')">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x mb-3 d-block text-muted"></i>
                            <p class="text-muted">No rental history found.</p>
                            <a href="{{ route('user.cars.index') }}" class="btn btn-primary">
                                <i class="fas fa-car"></i> Browse Cars
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $rentals->links() }}
    </div>
</div>
@endsection
