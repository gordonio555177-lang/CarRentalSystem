@extends('layouts.admin')

@section('title', 'Manage Branches')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Branches</h5>
        <a href="{{ route('admin.branches.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Branch
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Phone</th>
                        <th>Manager</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($branches as $branch)
                    <tr>
                        <td>{{ $branch->branch_id }}</td>
                        <td><strong>{{ $branch->name }}</strong></td>
                        <td>{{ Str::limit($branch->address, 30) }}</td>
                        <td>{{ $branch->city }}</td>
                        <td>{{ $branch->phone }}</td>
                        <td>{{ $branch->manager->full_name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('admin.branches.edit', $branch->branch_id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.branches.destroy', $branch->branch_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this branch?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $branches->links() }}
    </div>
</div>
@endsection