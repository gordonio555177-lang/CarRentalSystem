@extends('layouts.admin')

@section('title', 'Customer Feedback')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Customer Feedback</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
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
                    @foreach($feedbacks as $feedback)
                    <tr>
                        <td>{{ $feedback->feedback_id }}</td>
                        <td>{{ $feedback->customer->name ?? 'N/A' }}</td>
                        <td>{{ $feedback->car->brand }} {{ $feedback->car->model }}</td>
                        <td>
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $feedback->rating ? ' text-warning' : ' text-muted' }}"></i>
                            @endfor
                        </td>
                        <td>{{ Str::limit($feedback->comment, 50) }}</td>
                        <td>{{ $feedback->review_date->format('M d, Y') }}</td>
                        <td>
                            <form action="{{ route('admin.feedback.destroy', $feedback->feedback_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this feedback?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $feedbacks->links() }}
    </div>
</div>
@endsection