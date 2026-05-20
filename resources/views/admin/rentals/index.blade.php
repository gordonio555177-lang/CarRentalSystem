@extends('layouts.admin')
@section('title', 'Manage Rentals')
@section('content')

@php $pendingCount = $rentals->where('status', 'pending')->count(); @endphp

@if($pendingCount > 0)
<div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
    <i class="fas fa-clock me-2 fa-lg"></i>
    <span>
        <strong>{{ $pendingCount }} pending rental{{ $pendingCount > 1 ? 's' : '' }}</strong>
        awaiting your approval.
    </span>
</div>
@endif

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-calendar-check me-2" style="color:#2563eb;font-size:.85rem;"></i>All Rentals</span>
        <a href="{{ route('admin.rentals.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> New Rental
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Car</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rentals as $rental)
                <tr style="{{ $rental->status == 'pending' ? 'background:#fffbeb;' : '' }}">
                    <td style="color:#94a3b8;font-weight:600;">#{{ $rental->rental_id }}</td>
                    <td>
                        <div style="font-weight:700;color:#0f172a;">
                            {{ $rental->customer->customer->full_name ?? $rental->customer->name ?? 'N/A' }}
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600;color:#0f172a;">{{ $rental->car->brand ?? '' }} {{ $rental->car->model ?? '' }}</div>
                        <div style="font-size:.72rem;color:#94a3b8;">{{ $rental->car->year ?? '' }}</div>
                    </td>
                    <td style="white-space:nowrap;color:#64748b;">{{ $rental->start_date->format('M d, Y') }}</td>
                    <td style="white-space:nowrap;color:#64748b;">{{ $rental->end_date->format('M d, Y') }}</td>
                    <td style="font-weight:700;color:#0f172a;">₱{{ number_format($rental->total_amount, 2) }}</td>
                    <td>
                        @if($rental->status == 'active')
                            <span class="badge bg-success">Active</span>
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
                        <div style="display:flex;gap:4px;flex-wrap:nowrap;">
                            <a href="{{ route('admin.rentals.show', $rental->rental_id) }}"
                               class="btn btn-sm" style="background:#e0f2fe;color:#0369a1;border:none;" title="View">
                                <i class="fas fa-eye"></i>
                            </a>

                            @if($rental->status == 'pending')
                            <form action="{{ route('admin.rentals.approve', $rental->rental_id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm" style="background:#dcfce7;color:#15803d;border:none;" title="Approve"
                                        onclick="return confirm('Approve rental #{{ $rental->rental_id }}?')">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif

                            <a href="{{ route('admin.rentals.edit', $rental->rental_id) }}"
                               class="btn btn-sm" style="background:#fef3c7;color:#92400e;border:none;" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            @if($rental->status == 'active')
                            <a href="{{ route('admin.rentals.return', $rental->rental_id) }}"
                               class="btn btn-sm" style="background:#ede9fe;color:#6d28d9;border:none;" title="Return">
                                <i class="fas fa-undo"></i>
                            </a>
                            @endif

                            <form action="{{ route('admin.rentals.destroy', $rental->rental_id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#991b1b;border:none;" title="Delete"
                                        onclick="return confirm('Delete rental #{{ $rental->rental_id }}?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5" style="color:#94a3b8;">
                        <i class="fas fa-calendar-times fa-2x mb-2 d-block"></i> No rentals found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $rentals->links() }}
</div>

@endsection
