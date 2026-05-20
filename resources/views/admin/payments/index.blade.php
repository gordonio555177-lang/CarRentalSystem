@extends('layouts.admin')
@section('title', 'Manage Payments')
@section('content')

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-credit-card me-2" style="color:#2563eb;font-size:.85rem;"></i>All Payments</span>
        <a href="{{ route('admin.payments.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> New Payment
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Rental</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td style="color:#94a3b8;font-weight:600;">#{{ $payment->payment_id }}</td>
                    <td>
                        <a href="{{ route('admin.rentals.show', $payment->rental_id) }}"
                           style="font-weight:600;color:#2563eb;text-decoration:none;">
                            #{{ $payment->rental_id }}
                        </a>
                    </td>
                    <td>
                        <div style="font-weight:600;color:#0f172a;">
                            {{ $payment->rental->customer->customer->full_name ?? $payment->rental->customer->name ?? 'N/A' }}
                        </div>
                    </td>
                    <td style="font-weight:800;color:#0f172a;">₱{{ number_format($payment->amount, 2) }}</td>
                    <td style="white-space:nowrap;color:#64748b;">{{ $payment->payment_date->format('M d, Y') }}</td>
                    <td>
                        @php
                            $methodIcons = ['cash'=>'fa-money-bill-wave','credit_card'=>'fa-credit-card','debit_card'=>'fa-credit-card','bank_transfer'=>'fa-university','gcash'=>'fa-mobile-alt','maya'=>'fa-wallet'];
                            $icon = $methodIcons[$payment->method] ?? 'fa-credit-card';
                        @endphp
                        <span style="display:inline-flex;align-items:center;gap:5px;font-size:.82rem;font-weight:600;color:#475569;">
                            <i class="fas {{ $icon }}" style="color:#2563eb;"></i>
                            {{ ucwords(str_replace('_',' ',$payment->method)) }}
                        </span>
                    </td>
                    <td>
                        @if($payment->status == 'completed')
                            <span class="badge bg-success">Completed</span>
                        @elseif($payment->status == 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($payment->status == 'refunded')
                            <span class="badge bg-secondary">Refunded</span>
                        @else
                            <span class="badge bg-danger">{{ ucfirst($payment->status) }}</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <a href="{{ route('admin.payments.show', $payment->payment_id) }}"
                               class="btn btn-sm" style="background:#e0f2fe;color:#0369a1;border:none;" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.payments.destroy', $payment->payment_id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#991b1b;border:none;" title="Delete"
                                        onclick="return confirm('Delete this payment?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5" style="color:#94a3b8;">
                        <i class="fas fa-credit-card fa-2x mb-2 d-block"></i> No payments found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $payments->links() }}
</div>

@endsection
