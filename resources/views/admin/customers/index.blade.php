@extends('layouts.admin')
@section('title', 'Manage Customers')
@section('content')

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-users me-2" style="color:#2563eb;font-size:.85rem;"></i>All Customers</span>
        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add Customer
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>License No</th>
                    <th>Registered</th>
                    <th>Rentals</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td style="color:#94a3b8;font-weight:600;">#{{ $customer->customer_id }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:.65rem;">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#3b82f6);display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:700;color:#fff;flex-shrink:0;">
                                {{ strtoupper(substr($customer->first_name ?? 'C', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:700;color:#0f172a;">{{ $customer->full_name }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $customer->address }}">
                        {{ $customer->address }}
                    </td>
                    <td>
                        <span style="font-family:monospace;font-size:.8rem;background:#f0f4ff;color:#1e40af;padding:2px 8px;border-radius:6px;">
                            {{ $customer->license_no }}
                        </span>
                    </td>
                    <td>{{ $customer->registered_date->format('M d, Y') }}</td>
                    <td>
                        <span class="badge" style="background:#dbeafe;color:#1e40af;">{{ $customer->rentals_count }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:4px;flex-wrap:nowrap;">
                            <a href="{{ route('admin.customers.show', $customer->customer_id) }}"
                               class="btn btn-sm" style="background:#e0f2fe;color:#0369a1;border:none;" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.customers.edit', $customer->customer_id) }}"
                               class="btn btn-sm" style="background:#fef3c7;color:#92400e;border:none;" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.customers.destroy', $customer->customer_id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#991b1b;border:none;" title="Delete"
                                        onclick="return confirm('Delete this customer?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5" style="color:#94a3b8;">
                        <i class="fas fa-users fa-2x mb-2 d-block"></i> No customers found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $customers->links() }}
</div>

@endsection
