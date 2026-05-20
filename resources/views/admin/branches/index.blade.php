@extends('layouts.admin')
@section('title', 'Manage Branches')
@section('content')

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-store me-2" style="color:#2563eb;font-size:.85rem;"></i>All Branches</span>
        <a href="{{ route('admin.branches.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add Branch
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Branch Name</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Phone</th>
                    <th>Manager</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($branches as $branch)
                <tr>
                    <td style="color:#94a3b8;font-weight:600;">#{{ $branch->branch_id }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:.65rem;">
                            <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#2563eb,#3b82f6);display:flex;align-items:center;justify-content:center;font-size:.8rem;color:#fff;flex-shrink:0;">
                                <i class="fas fa-store"></i>
                            </div>
                            <div style="font-weight:700;color:#0f172a;">{{ $branch->name }}</div>
                        </div>
                    </td>
                    <td style="color:#64748b;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $branch->address }}">
                        {{ $branch->address }}
                    </td>
                    <td style="font-weight:600;">{{ $branch->city }}</td>
                    <td style="color:#64748b;">{{ $branch->phone }}</td>
                    <td>
                        @if($branch->manager)
                            <span style="font-weight:600;color:#0f172a;">{{ $branch->manager->full_name ?? 'N/A' }}</span>
                        @else
                            <span style="color:#94a3b8;">—</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <a href="{{ route('admin.branches.edit', $branch->branch_id) }}"
                               class="btn btn-sm" style="background:#fef3c7;color:#92400e;border:none;" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.branches.destroy', $branch->branch_id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#991b1b;border:none;" title="Delete"
                                        onclick="return confirm('Delete this branch?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5" style="color:#94a3b8;">
                        <i class="fas fa-store fa-2x mb-2 d-block"></i> No branches found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $branches->links() }}
</div>

@endsection
