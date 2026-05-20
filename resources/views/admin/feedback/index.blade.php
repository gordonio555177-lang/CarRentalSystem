@extends('layouts.admin')
@section('title', 'Customer Feedback')
@section('content')

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-star me-2" style="color:#2563eb;font-size:.85rem;"></i>Customer Feedback</span>
        <span class="badge" style="background:#dbeafe;color:#1e40af;">{{ $feedbacks->total() }} reviews</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Car</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($feedbacks as $feedback)
                <tr>
                    <td style="color:#94a3b8;font-weight:600;">#{{ $feedback->feedback_id }}</td>
                    <td>
                        <div style="font-weight:600;color:#0f172a;">
                            {{ $feedback->customer->customer->full_name ?? $feedback->customer->name ?? 'N/A' }}
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ $feedback->car->brand ?? '' }} {{ $feedback->car->model ?? '' }}</div>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:4px;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star" style="font-size:.75rem;color:{{ $i <= $feedback->rating ? '#f59e0b' : '#e2e8f0' }};"></i>
                            @endfor
                            <span style="font-size:.78rem;font-weight:700;color:#0f172a;margin-left:4px;">{{ $feedback->rating }}/5</span>
                        </div>
                    </td>
                    <td style="max-width:220px;">
                        <div style="font-size:.85rem;color:#475569;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $feedback->comment }}">
                            "{{ $feedback->comment }}"
                        </div>
                    </td>
                    <td style="color:#64748b;white-space:nowrap;">{{ $feedback->review_date->format('M d, Y') }}</td>
                    <td>
                        <form action="{{ route('admin.feedback.destroy', $feedback->feedback_id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#991b1b;border:none;"
                                    onclick="return confirm('Delete this feedback?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5" style="color:#94a3b8;">
                        <i class="fas fa-star fa-2x mb-2 d-block"></i> No feedback yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $feedbacks->links() }}
</div>

@endsection
