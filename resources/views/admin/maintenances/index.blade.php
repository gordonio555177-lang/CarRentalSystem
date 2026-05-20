@extends('layouts.admin')
@section('title', 'Maintenance Records')
@section('content')

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-wrench me-2" style="color:#2563eb;font-size:.85rem;"></i>Maintenance Records</span>
        <a href="{{ route('admin.maintenances.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add Record
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
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
                @forelse($maintenances as $maintenance)
                <tr>
                    <td style="color:#94a3b8;font-weight:600;">#{{ $maintenance->maintenance_id }}</td>
                    <td>
                        <div style="font-weight:700;color:#0f172a;">
                            {{ $maintenance->car->brand ?? '' }} {{ $maintenance->car->model ?? '' }}
                        </div>
                        <div style="font-size:.72rem;color:#94a3b8;">{{ $maintenance->car->license_plate ?? '' }}</div>
                    </td>
                    <td style="white-space:nowrap;color:#64748b;">{{ $maintenance->maintenance_date->format('M d, Y') }}</td>
                    <td style="max-width:200px;color:#475569;">
                        {{ Str::limit($maintenance->description, 40) }}
                    </td>
                    <td style="font-weight:700;color:#0f172a;">₱{{ number_format($maintenance->cost, 2) }}</td>
                    <td style="white-space:nowrap;color:#64748b;">
                        {{ $maintenance->next_due_date ? $maintenance->next_due_date->format('M d, Y') : '—' }}
                    </td>
                    <td>
                        @if($maintenance->status == 'scheduled')
                            <span class="badge bg-warning text-dark">Scheduled</span>
                        @elseif($maintenance->status == 'in_progress')
                            <span class="badge bg-info">In Progress</span>
                        @else
                            <span class="badge bg-success">Completed</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            @if($maintenance->status !== 'completed')
                            <form action="{{ route('admin.maintenances.complete', $maintenance->maintenance_id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm" style="background:#dcfce7;color:#15803d;border:none;" title="Mark Complete"
                                        onclick="return confirm('Mark as completed?')">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('admin.maintenances.edit', $maintenance->maintenance_id) }}"
                               class="btn btn-sm" style="background:#fef3c7;color:#92400e;border:none;" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.maintenances.destroy', $maintenance->maintenance_id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#991b1b;border:none;" title="Delete"
                                        onclick="return confirm('Delete this record?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5" style="color:#94a3b8;">
                        <i class="fas fa-wrench fa-2x mb-2 d-block"></i> No maintenance records found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $maintenances->links() }}
</div>

@endsection
