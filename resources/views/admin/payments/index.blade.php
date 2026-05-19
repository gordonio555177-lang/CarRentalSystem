@extends('layouts.admin')

@section('title', 'Manage Payments')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Payments</h5>
        <a href="{{ route('admin.payments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Payment
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Rental ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Payment Date</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_id }}</td>
                        <td>#{{ $payment->rental_id }}</td>
                        <td>{{ $payment->rental->customer->name ?? ($payment->rental->customer->customer->full_name ?? 'N/A') }}</td>
                        <td>₱{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td>{{ ucfirst($payment->method) }}</td>
                        <td>
                            @if($payment->status == 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($payment->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst($payment->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.payments.show', $payment->payment_id) }}" class="btn btn-sm btn-info">View</a>
                            <form action="{{ route('admin.payments.destroy', $payment->payment_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this payment?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $payments->links() }}
    </div>
</div>
@endsection