@extends('layouts.admin')
@section('title', 'Manage Insurance')
@section('content')

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-shield-alt me-2" style="color:#2563eb;font-size:.85rem;"></i>Insurance Plans</span>
        <a href="{{ route('admin.insurances.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add Plan
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Plan Name</th>
                    <th>Coverage Details</th>
                    <th>Daily Rate</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($insurances as $insurance)
                <tr>
                    <td style="color:#94a3b8;font-weight:600;">#{{ $insurance->insurance_id }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:.65rem;">
                            <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#0d9488,#14b8a6);display:flex;align-items:center;justify-content:center;font-size:.8rem;color:#fff;flex-shrink:0;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div style="font-weight:700;color:#0f172a;">{{ $insurance->name }}</div>
                        </div>
                    </td>
                    <td style="color:#64748b;max-width:280px;">
                        {{ Str::limit($insurance->coverage_details, 70) }}
                    </td>
                    <td>
                        <span style="font-weight:800;color:#0f172a;">₱{{ number_format($insurance->daily_rate, 2) }}</span>
                        <span style="font-size:.72rem;color:#94a3b8;"> / day</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <a href="{{ route('admin.insurances.edit', $insurance->insurance_id) }}"
                               class="btn btn-sm" style="background:#fef3c7;color:#92400e;border:none;" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.insurances.destroy', $insurance->insurance_id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#991b1b;border:none;" title="Delete"
                                        onclick="return confirm('Delete this insurance plan?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5" style="color:#94a3b8;">
                        <i class="fas fa-shield-alt fa-2x mb-2 d-block"></i> No insurance plans found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $insurances->links() }}
</div>

@endsection
