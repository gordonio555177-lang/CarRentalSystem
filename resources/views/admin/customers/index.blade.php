{{-- resources/views/admin/customers/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Customers')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Customers</h5>
        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Customer
        </a>
    </div>
    <div class="card-body">
        <table class="table table-hover" id="customersTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>License No</th>
                    <th>Registered Date</th>
                    <th>Rentals</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->customer_id }}</td>
                    <td>{{ $customer->full_name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->address }}</td>
                    <td>{{ $customer->license_no }}</td>
                    <td>{{ $customer->registered_date->format('M d, Y') }}</td>
                    <td>
                        <span class="badge bg-info">{{ $customer->rentals_count }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.customers.show', $customer->customer_id) }}" class="btn btn-sm btn-info btn-action">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('admin.customers.edit', $customer->customer_id) }}" class="btn btn-sm btn-warning btn-action">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.customers.destroy', $customer->customer_id) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $customers->links() }}
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#customersTable').DataTable({
            pageLength: 10,
            responsive: true
        });
    });
</script>
@endpush
@endsection