{{-- resources/views/user/rentals/show.blade.php --}}
@extends('layouts.user')

@section('title', 'Rental #' . $rental->rental_id)

@section('content')
<div class="row">

    {{-- LEFT: Rental + Insurance + Invoice --}}
    <div class="col-md-8">

        {{-- Rental Info --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Rental #{{ $rental->rental_id }}</h5>
                @if($rental->status == 'pending')
                    <span class="badge bg-warning text-dark fs-6">
                        <i class="fas fa-clock"></i> Pending Approval
                    </span>
                @elseif($rental->status == 'active')
                    <span class="badge bg-success fs-6">
                        <i class="fas fa-car"></i> Rented
                    </span>
                @elseif($rental->status == 'returned')
                    <span class="badge bg-info fs-6">
                        <i class="fas fa-check-circle"></i> Returned
                    </span>
                @elseif($rental->status == 'cancelled')
                    <span class="badge bg-danger fs-6">
                        <i class="fas fa-times-circle"></i> Cancelled
                    </span>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th width="45%">Car:</th>
                                <td>{{ $rental->car->brand }} {{ $rental->car->model }} ({{ $rental->car->year }})</td>
                            </tr>
                            <tr>
                                <th>License Plate:</th>
                                <td>{{ $rental->car->license_plate }}</td>
                            </tr>
                            <tr>
                                <th>Daily Rate:</th>
                                <td>₱{{ number_format($rental->car->daily_rate, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th width="45%">Start Date:</th>
                                <td>{{ $rental->start_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>End Date:</th>
                                <td>{{ $rental->end_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Actual Return:</th>
                                <td>{{ $rental->actual_return_date ? $rental->actual_return_date->format('M d, Y') : '—' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Total Amount</span>
                    <strong class="fs-5 text-primary">₱{{ number_format($rental->total_amount, 2) }}</strong>
                </div>
            </div>
        </div>

        {{-- Insurance --}}
        @if($rental->insurances->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shield-alt me-1 text-primary"></i> Insurance Coverage</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($rental->insurances as $insurance)
                    <div class="col-md-6 mb-2">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex justify-content-between align-items-start">
                                <strong>{{ $insurance->name }}</strong>
                                <span class="badge bg-primary">₱{{ number_format($insurance->daily_rate, 2) }}/day</span>
                            </div>
                            @if($insurance->coverage_details)
                                <small class="text-muted d-block mt-1">{{ $insurance->coverage_details }}</small>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Invoice --}}
        @if($rental->invoice)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-invoice me-1"></i> Invoice</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-end">₱{{ number_format($rental->invoice->subtotal, 2) }}</td>
                    </tr>
                    @if($rental->invoice->insurance_fee > 0)
                    <tr>
                        <td>Insurance Fee</td>
                        <td class="text-end">₱{{ number_format($rental->invoice->insurance_fee, 2) }}</td>
                    </tr>
                    @endif
                    @if($rental->invoice->late_fee > 0)
                    <tr>
                        <td>Late Fee</td>
                        <td class="text-end text-danger">₱{{ number_format($rental->invoice->late_fee, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Tax (12% VAT)</td>
                        <td class="text-end">₱{{ number_format($rental->invoice->tax, 2) }}</td>
                    </tr>
                    <tr class="table-active fw-bold">
                        <td>Total Due</td>
                        <td class="text-end text-primary">
                            ₱{{ number_format($rental->invoice->subtotal + $rental->invoice->insurance_fee + $rental->invoice->late_fee + $rental->invoice->tax, 2) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        @endif

        {{-- Submitted Feedback (view + edit/delete) --}}
        @if($rental->feedback)
        <div class="card mb-4 border-success">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-star me-1"></i> Your Feedback</h5>
                <div>
                    <button class="btn btn-sm btn-light" data-bs-toggle="collapse" data-bs-target="#editFeedbackForm">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <form action="{{ route('user.feedback.destroy', $rental->feedback->feedback_id) }}"
                          method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Delete your feedback?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="text-warning fs-5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= $rental->feedback->rating ? '' : ' text-muted' }}"></i>
                        @endfor
                        <span class="text-dark fs-6 ms-1">{{ $rental->feedback->rating }}/5</span>
                    </div>
                    <small class="text-muted">{{ $rental->feedback->review_date->format('M d, Y') }}</small>
                </div>
                <p class="mb-0">{{ $rental->feedback->comment }}</p>
            </div>

            {{-- Edit Form (collapsed) --}}
            <div class="collapse" id="editFeedbackForm">
                <div class="card-body border-top">
                    <form action="{{ route('user.feedback.update', $rental->feedback->feedback_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rating</label>
                            <div class="rating">
                                @for($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" value="{{ $i }}" id="edit_star{{ $i }}"
                                       {{ $rental->feedback->rating == $i ? 'checked' : '' }} required>
                                <label for="edit_star{{ $i }}"><i class="fas fa-star"></i></label>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Comment</label>
                            <textarea name="comment" class="form-control" rows="3"
                                      minlength="10" required>{{ $rental->feedback->comment }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-save"></i> Update Feedback
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-toggle="collapse" data-bs-target="#editFeedbackForm">
                            Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- RIGHT: Actions + Payment + Leave Feedback --}}
    <div class="col-md-4">

        {{-- Actions --}}
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Actions</h6></div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('user.rentals.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to My Rentals
                </a>
                @if($rental->status == 'pending')
                <form action="{{ route('user.rentals.cancel', $rental->rental_id) }}"
                      method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm w-100"
                            onclick="return confirm('Cancel this booking request?')">
                        <i class="fas fa-times me-1"></i> Cancel Booking
                    </button>
                </form>
                @endif
                @if($rental->invoice)
                <a href="{{ route('user.invoices.download', $rental->rental_id) }}"
                   class="btn btn-info btn-sm text-white">
                    <i class="fas fa-file-invoice me-1"></i> Show Invoice
                </a>
                @endif
            </div>
        </div>

        {{-- Payment Info --}}
        @if($rental->payment)
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0"><i class="fas fa-credit-card me-1"></i> Payment</h6></div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr>
                        <th>Amount:</th>
                        <td>₱{{ number_format($rental->payment->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Method:</th>
                        <td>{{ ucwords(str_replace('_', ' ', $rental->payment->method)) }}</td>
                    </tr>
                    <tr>
                        <th>Date:</th>
                        <td>{{ $rental->payment->payment_date?->format('M d, Y') ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($rental->payment->status == 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($rental->payment->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($rental->payment->status) }}</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        @endif

        {{-- Leave Feedback (only after returned, no feedback yet) --}}
        @if($rental->status == 'returned' && !$rental->feedback)
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="fas fa-star me-1"></i> Leave Feedback</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('user.feedback.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="rental_id" value="{{ $rental->rental_id }}">
                    <input type="hidden" name="car_id"    value="{{ $rental->car_id }}">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Rating <span class="text-danger">*</span></label>
                        <div class="rating">
                            @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                            <label for="star{{ $i }}"><i class="fas fa-star"></i></label>
                            @endfor
                        </div>
                        @error('rating')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Comment <span class="text-danger">*</span></label>
                        <textarea name="comment" class="form-control @error('comment') is-invalid @enderror"
                                  rows="4" minlength="10" placeholder="Share your experience..."
                                  required>{{ old('comment') }}</textarea>
                        @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-paper-plane me-1"></i> Submit Feedback
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>

<style>
    .rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; }
    .rating input { display: none; }
    .rating label { cursor: pointer; color: #ddd; font-size: 28px; margin-right: 4px; }
    .rating input:checked ~ label,
    .rating label:hover,
    .rating label:hover ~ label { color: #ffc107; }
</style>
@endsection
