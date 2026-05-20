@extends('layouts.user')

@section('title', 'My Rentals')

@section('content')
<style>
    /* ── Page Header ── */
    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.5rem;
        flex-wrap: wrap; gap: .75rem;
    }
    .page-header h4 { font-weight: 800; color: #0f172a; margin: 0; font-size: 1.4rem; }
    .page-header p  { color: #64748b; font-size: .875rem; margin: 0; }

    /* ── Filter Tabs ── */
    .filter-tabs {
        display: flex; gap: .5rem; flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }
    .filter-tab {
        padding: .45rem 1rem; border-radius: 20px;
        font-size: .8rem; font-weight: 600;
        border: 1.5px solid #f0e8dc;
        background: #fff; color: #93785B;
        cursor: pointer; text-decoration: none;
        transition: all .2s;
    }
    .filter-tab:hover, .filter-tab.active {
        background: #865D36; border-color: #865D36; color: #fff;
    }

    /* ── Rental Cards ── */
    .rental-card {
        background: #fff;
        border: 1.5px solid #f0e8dc;
        border-radius: 18px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1rem;
        transition: box-shadow .2s, border-color .2s, transform .2s;
        position: relative;
        overflow: hidden;
    }
    .rental-card:hover {
        box-shadow: 0 8px 28px rgba(62,54,46,.1);
        border-color: #eddcc8;
        transform: translateY(-2px);
    }
    .rental-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        border-radius: 4px 0 0 4px;
    }
    .rental-card.status-pending::before  { background: #eab308; }
    .rental-card.status-active::before   { background: #22c55e; }
    .rental-card.status-returned::before { background: #0ea5e9; }
    .rental-card.status-cancelled::before{ background: #ef4444; }

    .rental-card-top {
        display: flex; align-items: flex-start; gap: 1rem;
        margin-bottom: 1rem;
    }

    .car-image-box {
        width: 80px; height: 60px;
        border-radius: 12px;
        overflow: hidden;
        flex-shrink: 0;
        background: #f1f5f9;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; color: #cbd5e1;
    }
    .car-image-box img { width: 100%; height: 100%; object-fit: cover; }

    .rental-card-info { flex: 1; min-width: 0; }
    .rental-card-info .car-name {
        font-weight: 800; font-size: 1rem; color: #0f172a;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .rental-card-info .rental-id { font-size: .75rem; color: #94a3b8; margin-top: 2px; }

    .rental-card-amount {
        text-align: right; flex-shrink: 0;
    }
    .rental-card-amount .amount { font-size: 1.2rem; font-weight: 800; color: #0f172a; }
    .rental-card-amount .amount-label { font-size: .72rem; color: #94a3b8; }

    /* ── Info Grid ── */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: .75rem;
        padding: .75rem 0;
        border-top: 1px solid #f8fafc;
        border-bottom: 1px solid #f8fafc;
        margin-bottom: 1rem;
    }
    .info-item .info-label { font-size: .7rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: #A69080; margin-bottom: 3px; }
    .info-item .info-value { font-size: .85rem; font-weight: 600; color: #3E362E; }

    /* ── Status Badges ── */
    .status-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 20px;
        font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
    }
    .status-pill .dot { width: 6px; height: 6px; border-radius: 50%; }
    .pill-pending  { background: #fef9c3; color: #854d0e; }
    .pill-pending .dot  { background: #eab308; }
    .pill-active   { background: #dcfce7; color: #14532d; }
    .pill-active .dot   { background: #22c55e; }
    .pill-returned { background: #e0f2fe; color: #0c4a6e; }
    .pill-returned .dot { background: #0ea5e9; }
    .pill-cancelled{ background: #fee2e2; color: #7f1d1d; }
    .pill-cancelled .dot{ background: #ef4444; }

    /* ── Action Buttons ── */
    .card-actions { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }
    .btn-action {
        display: inline-flex; align-items: center; gap: 5px;
        padding: .45rem 1rem; border-radius: 9px;
        font-size: .8rem; font-weight: 600;
        text-decoration: none; border: none; cursor: pointer;
        transition: all .15s;
    }
    .btn-action-view   { background: #f5ede0; color: #865D36; }
    .btn-action-view:hover   { background: #eddcc8; color: #6b4a28; }
    .btn-action-cancel { background: #fef2f2; color: #dc2626; }
    .btn-action-cancel:hover { background: #fee2e2; color: #b91c1c; }

    /* ── Empty State ── */
    .empty-wrap {
        text-align: center; padding: 4rem 2rem;
        background: #fff; border-radius: 18px;
        border: 1.5px dashed #f0e8dc;
    }
    .empty-wrap .empty-icon { font-size: 4rem; color: #eddcc8; margin-bottom: 1rem; }
    .empty-wrap h5 { font-weight: 700; color: #93785B; }
    .empty-wrap p  { color: #A69080; font-size: .875rem; }

    /* ── Pagination ── */
    .pagination .page-link { border-radius: 8px !important; margin: 0 2px; border: 1.5px solid #f0e8dc; color: #865D36; }
    .pagination .page-item.active .page-link { background: #865D36; border-color: #865D36; }

    @media (max-width: 576px) {
        .info-grid { grid-template-columns: repeat(2,1fr); }
        .rental-card-amount { display: none; }
    }
</style>

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h4><i class="fas fa-calendar-check me-2" style="color:#865D36;"></i>My Rentals</h4>
        <p>Track and manage all your rental bookings</p>
    </div>
    <a href="{{ route('user.cars.index') }}"
       style="display:inline-flex;align-items:center;gap:.5rem;padding:.6rem 1.25rem;border-radius:12px;background:linear-gradient(135deg,#865D36,#93785B);color:#fff;font-size:.875rem;font-weight:600;text-decoration:none;box-shadow:0 4px 14px rgba(134,93,54,.35);">
        <i class="fas fa-plus"></i> New Booking
    </a>
</div>

{{-- Filter Tabs --}}
<div class="filter-tabs">
    @php $currentFilter = request('status', 'all'); @endphp
    <a href="{{ route('user.rentals.index') }}" class="filter-tab {{ $currentFilter === 'all' ? 'active' : '' }}">All</a>
    <a href="{{ route('user.rentals.index', ['status' => 'pending']) }}"  class="filter-tab {{ $currentFilter === 'pending'  ? 'active' : '' }}">Pending</a>
    <a href="{{ route('user.rentals.index', ['status' => 'active']) }}"   class="filter-tab {{ $currentFilter === 'active'   ? 'active' : '' }}">Active</a>
    <a href="{{ route('user.rentals.index', ['status' => 'returned']) }}" class="filter-tab {{ $currentFilter === 'returned' ? 'active' : '' }}">Returned</a>
    <a href="{{ route('user.rentals.index', ['status' => 'cancelled']) }}"class="filter-tab {{ $currentFilter === 'cancelled'? 'active' : '' }}">Cancelled</a>
</div>

{{-- Rental Cards --}}
@forelse($rentals as $rental)
<div class="rental-card status-{{ $rental->status }}">

    <div class="rental-card-top">
        {{-- Car Image --}}
        <div class="car-image-box">
            @if($rental->car->image_url ?? null)
                <img src="{{ $rental->car->image_url }}" alt="{{ $rental->car->brand }}">
            @else
                <i class="fas fa-car"></i>
            @endif
        </div>

        {{-- Car Info --}}
        <div class="rental-card-info">
            <div class="car-name">{{ $rental->car->brand ?? '' }} {{ $rental->car->model ?? '' }}</div>
            <div class="rental-id">Rental #{{ $rental->rental_id }} &bull; {{ $rental->car->year ?? '' }}</div>
            <div style="margin-top:.4rem;">
                <span class="status-pill pill-{{ $rental->status }}">
                    <span class="dot"></span>
                    @if($rental->status === 'pending')  Pending Approval
                    @elseif($rental->status === 'active') Active / Rented
                    @elseif($rental->status === 'returned') Returned
                    @elseif($rental->status === 'cancelled') Cancelled
                    @else {{ ucfirst($rental->status) }}
                    @endif
                </span>
            </div>
        </div>

        {{-- Amount --}}
        <div class="rental-card-amount">
            <div class="amount">₱{{ number_format($rental->total_amount, 2) }}</div>
            <div class="amount-label">Total Amount</div>
        </div>
    </div>

    {{-- Info Grid --}}
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label"><i class="fas fa-calendar-alt me-1"></i>Start</div>
            <div class="info-value">{{ $rental->start_date->format('M d, Y') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label"><i class="fas fa-calendar-check me-1"></i>End</div>
            <div class="info-value">{{ $rental->end_date->format('M d, Y') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label"><i class="fas fa-undo me-1"></i>Returned</div>
            <div class="info-value">{{ $rental->actual_return_date ? $rental->actual_return_date->format('M d, Y') : '—' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label"><i class="fas fa-moon me-1"></i>Duration</div>
            <div class="info-value">{{ $rental->start_date->diffInDays($rental->end_date) }} day(s)</div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="card-actions">
        <a href="{{ route('user.rentals.show', $rental->rental_id) }}" class="btn-action btn-action-view">
            <i class="fas fa-eye"></i> View Details
        </a>
        @if($rental->status === 'pending')
        <form action="{{ route('user.rentals.cancel', $rental->rental_id) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Cancel this rental request?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-action btn-action-cancel">
                <i class="fas fa-times"></i> Cancel
            </button>
        </form>
        @endif
        @if($rental->status === 'returned' && !$rental->feedback)
        <a href="{{ route('user.rentals.show', $rental->rental_id) }}"
           style="display:inline-flex;align-items:center;gap:5px;padding:.45rem 1rem;border-radius:9px;background:#fef3c7;color:#92400e;font-size:.8rem;font-weight:600;text-decoration:none;">
            <i class="fas fa-star"></i> Leave Feedback
        </a>
        @endif
    </div>

</div>
@empty
<div class="empty-wrap">
    <div class="empty-icon"><i class="fas fa-car-side"></i></div>
    <h5>No rentals found</h5>
    <p>You haven't made any bookings yet. Browse our fleet and start your journey!</p>
    <a href="{{ route('user.cars.index') }}"
       style="display:inline-flex;align-items:center;gap:.5rem;padding:.6rem 1.5rem;border-radius:12px;background:linear-gradient(135deg,#865D36,#93785B);color:#fff;font-size:.875rem;font-weight:600;text-decoration:none;margin-top:.5rem;">
        <i class="fas fa-car"></i> Browse Cars
    </a>
</div>
@endforelse

{{-- Pagination --}}
@if($rentals->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $rentals->appends(request()->query())->links() }}
</div>
@endif

@endsection
