@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')
<style>
    /* ── Welcome Banner ── */
    .welcome-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2rem 2.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.75rem;
        box-shadow: 0 8px 32px rgba(102,126,234,.35);
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        right: -60px; top: -60px;
        width: 260px; height: 260px;
        border-radius: 50%;
        background: rgba(255,255,255,.07);
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        right: 60px; bottom: -80px;
        width: 180px; height: 180px;
        border-radius: 50%;
        background: rgba(255,255,255,.05);
    }
    .welcome-banner h3 { font-weight: 800; font-size: 1.6rem; margin-bottom: .3rem; }
    .welcome-banner p  { opacity: .85; font-size: .95rem; margin: 0; }
    .welcome-banner .car-icon {
        position: absolute; right: 2.5rem; top: 50%;
        transform: translateY(-50%);
        font-size: 5rem; opacity: .12;
    }

    /* ── Stat Cards ── */
    .stat-card {
        border: none;
        border-radius: 18px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,.07);
        transition: transform .2s, box-shadow .2s;
        height: 100%;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 30px rgba(0,0,0,.12); }
    .stat-card .stat-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        margin-bottom: 1rem;
    }
    .stat-card .stat-value { font-size: 2rem; font-weight: 800; line-height: 1; margin-bottom: .25rem; }
    .stat-card .stat-label { font-size: .8rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; opacity: .65; }
    .stat-card .stat-bg {
        position: absolute; right: -20px; bottom: -20px;
        font-size: 6rem; opacity: .06;
    }

    .stat-blue   { background: linear-gradient(135deg,#eff6ff,#dbeafe); color: #1d4ed8; }
    .stat-green  { background: linear-gradient(135deg,#f0fdf4,#dcfce7); color: #15803d; }
    .stat-amber  { background: linear-gradient(135deg,#fffbeb,#fef3c7); color: #b45309; }
    .stat-purple { background: linear-gradient(135deg,#faf5ff,#ede9fe); color: #7c3aed; }

    .stat-blue   .stat-icon { background: #dbeafe; color: #2563eb; }
    .stat-green  .stat-icon { background: #dcfce7; color: #16a34a; }
    .stat-amber  .stat-icon { background: #fef3c7; color: #d97706; }
    .stat-purple .stat-icon { background: #ede9fe; color: #7c3aed; }

    /* ── Section Cards ── */
    .section-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .section-card .card-header {
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .section-card .card-header h5 {
        font-weight: 700; font-size: 1rem; margin: 0; color: #0f172a;
    }
    .section-card .card-body { padding: 0; }

    /* ── Rental Table ── */
    .rental-table { width: 100%; border-collapse: collapse; }
    .rental-table thead th {
        background: #f8fafc;
        padding: .75rem 1.25rem;
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #64748b;
        border-bottom: 1px solid #f1f5f9;
        white-space: nowrap;
    }
    .rental-table tbody td {
        padding: 1rem 1.25rem;
        font-size: .875rem;
        color: #334155;
        border-bottom: 1px solid #f8fafc;
        vertical-align: middle;
    }
    .rental-table tbody tr:last-child td { border-bottom: none; }
    .rental-table tbody tr:hover td { background: #f8fafc; }

    .car-thumb {
        width: 44px; height: 44px;
        border-radius: 10px;
        object-fit: cover;
        background: #f1f5f9;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; color: #94a3b8;
        flex-shrink: 0;
    }
    .car-thumb img { width: 100%; height: 100%; object-fit: cover; border-radius: 10px; }

    /* ── Status Badges ── */
    .status-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px; border-radius: 20px;
        font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
    }
    .status-badge .dot { width: 6px; height: 6px; border-radius: 50%; }
    .badge-pending  { background: #fef9c3; color: #854d0e; }
    .badge-pending .dot  { background: #eab308; }
    .badge-active   { background: #dcfce7; color: #14532d; }
    .badge-active .dot   { background: #22c55e; }
    .badge-returned { background: #e0f2fe; color: #0c4a6e; }
    .badge-returned .dot { background: #0ea5e9; }
    .badge-cancelled{ background: #fee2e2; color: #7f1d1d; }
    .badge-cancelled .dot{ background: #ef4444; }

    /* ── Quick Action Buttons ── */
    .action-btn {
        display: flex; align-items: center; gap: .75rem;
        padding: .9rem 1.25rem;
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        color: #334155;
        font-size: .875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
        margin-bottom: .75rem;
    }
    .action-btn:last-child { margin-bottom: 0; }
    .action-btn .action-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: .95rem;
        flex-shrink: 0;
    }
    .action-btn:hover { border-color: #667eea; color: #667eea; transform: translateX(3px); box-shadow: 0 4px 12px rgba(102,126,234,.15); }
    .action-btn:hover .action-icon { background: #667eea !important; color: #fff !important; }

    .action-btn-primary .action-icon { background: #eff6ff; color: #2563eb; }
    .action-btn-teal    .action-icon { background: #f0fdfa; color: #0d9488; }
    .action-btn-gray    .action-icon { background: #f8fafc; color: #64748b; }

    /* ── Feedback Alert ── */
    .feedback-alert {
        background: linear-gradient(135deg,#fffbeb,#fef3c7);
        border: 1.5px solid #fde68a;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        margin-bottom: .75rem;
    }
    .feedback-alert:last-child { margin-bottom: 0; }
    .feedback-alert .car-name { font-weight: 700; color: #92400e; font-size: .9rem; }
    .feedback-alert .date-text { font-size: .78rem; color: #b45309; margin-top: 2px; }

    /* ── Empty State ── */
    .empty-state { text-align: center; padding: 3rem 1rem; }
    .empty-state .empty-icon { font-size: 3.5rem; color: #e2e8f0; margin-bottom: 1rem; }
    .empty-state h6 { font-weight: 700; color: #64748b; margin-bottom: .5rem; }
    .empty-state p  { font-size: .85rem; color: #94a3b8; margin-bottom: 1.25rem; }

    .btn-view-detail {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 14px; border-radius: 8px;
        background: #eff6ff; color: #2563eb;
        font-size: .78rem; font-weight: 600;
        text-decoration: none; border: none;
        transition: background .2s;
    }
    .btn-view-detail:hover { background: #dbeafe; color: #1d4ed8; }
</style>

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <i class="fas fa-car car-icon"></i>
    <h3>Welcome back, {{ auth()->user()->customer->full_name ?? auth()->user()->name ?? 'Customer' }}! 👋</h3>
    <p>Here's an overview of your rental activity.</p>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-blue">
            <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
            <div class="stat-value">{{ number_format($totalRentals ?? 0) }}</div>
            <div class="stat-label">Total Rentals</div>
            <i class="fas fa-file-alt stat-bg"></i>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-green">
            <div class="stat-icon"><i class="fas fa-car"></i></div>
            <div class="stat-value">{{ number_format($activeRentals ?? 0) }}</div>
            <div class="stat-label">Active Rentals</div>
            <i class="fas fa-car stat-bg"></i>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-amber">
            <div class="stat-icon"><i class="fas fa-peso-sign"></i></div>
            <div class="stat-value" style="font-size:1.4rem;">₱{{ number_format($totalSpent ?? 0, 0) }}</div>
            <div class="stat-label">Total Spent</div>
            <i class="fas fa-coins stat-bg"></i>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-purple">
            <div class="stat-icon"><i class="fas fa-star"></i></div>
            <div class="stat-value">{{ number_format($averageRating ?? 0, 1) }}<span style="font-size:1rem;font-weight:500;">/5</span></div>
            <div class="stat-label">Avg. Rating</div>
            <i class="fas fa-star stat-bg"></i>
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="row g-4">

    {{-- Recent Rentals --}}
    <div class="col-lg-8">
        <div class="card section-card">
            <div class="card-header">
                <h5><i class="fas fa-clock me-2 text-primary" style="font-size:.9rem;"></i>Recent Rentals</h5>
                <a href="{{ route('user.rentals.index') }}" style="font-size:.82rem; color:#667eea; font-weight:600; text-decoration:none;">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                @if(isset($recentRentals) && $recentRentals->count() > 0)
                <div class="table-responsive">
                    <table class="rental-table">
                        <thead>
                            <tr>
                                <th>Car</th>
                                <th>Dates</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentRentals as $rental)
                            <tr>
                                <td>
                                    <div style="display:flex; align-items:center; gap:.75rem;">
                                        <div class="car-thumb">
                                            @if($rental->car->image_url ?? null)
                                                <img src="{{ $rental->car->image_url }}" alt="">
                                            @else
                                                <i class="fas fa-car"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div style="font-weight:700; color:#0f172a; font-size:.875rem;">
                                                {{ $rental->car->brand ?? '' }} {{ $rental->car->model ?? '' }}
                                            </div>
                                            <div style="font-size:.75rem; color:#94a3b8;">{{ $rental->car->year ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size:.82rem; color:#334155;">
                                        {{ $rental->start_date->format('M d') }} – {{ $rental->end_date->format('M d, Y') }}
                                    </div>
                                    <div style="font-size:.75rem; color:#94a3b8;">
                                        {{ $rental->start_date->diffInDays($rental->end_date) }} day(s)
                                    </div>
                                </td>
                                <td style="font-weight:700; color:#0f172a;">₱{{ number_format($rental->total_amount, 2) }}</td>
                                <td>
                                    @php $s = $rental->status; @endphp
                                    <span class="status-badge badge-{{ $s }}">
                                        <span class="dot"></span>
                                        {{ $s === 'active' ? 'Active' : ucfirst($s) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('user.rentals.show', $rental->rental_id) }}" class="btn-view-detail">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-car-side"></i></div>
                    <h6>No rentals yet</h6>
                    <p>Start your first rental journey with us!</p>
                    <a href="{{ route('user.cars.index') }}" class="btn btn-sm" style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border-radius:10px;padding:.5rem 1.25rem;font-weight:600;">
                        Browse Cars
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col-lg-4">

        {{-- Quick Actions --}}
        <div class="card section-card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-bolt me-2 text-warning" style="font-size:.9rem;"></i>Quick Actions</h5>
            </div>
            <div class="card-body p-3">
                <a href="{{ route('user.cars.index') }}" class="action-btn action-btn-primary">
                    <div class="action-icon"><i class="fas fa-car"></i></div>
                    <div>
                        <div>Browse Available Cars</div>
                        <div style="font-size:.75rem;font-weight:400;color:#94a3b8;">Find your next ride</div>
                    </div>
                    <i class="fas fa-chevron-right ms-auto" style="font-size:.7rem;color:#cbd5e1;"></i>
                </a>
                <a href="{{ route('user.rentals.index') }}" class="action-btn action-btn-teal">
                    <div class="action-icon"><i class="fas fa-history"></i></div>
                    <div>
                        <div>My Rental History</div>
                        <div style="font-size:.75rem;font-weight:400;color:#94a3b8;">View all bookings</div>
                    </div>
                    <i class="fas fa-chevron-right ms-auto" style="font-size:.7rem;color:#cbd5e1;"></i>
                </a>
                <a href="{{ route('profile.edit') }}" class="action-btn action-btn-gray">
                    <div class="action-icon"><i class="fas fa-user-edit"></i></div>
                    <div>
                        <div>Update Profile</div>
                        <div style="font-size:.75rem;font-weight:400;color:#94a3b8;">Edit your details</div>
                    </div>
                    <i class="fas fa-chevron-right ms-auto" style="font-size:.7rem;color:#cbd5e1;"></i>
                </a>
            </div>
        </div>

        {{-- Pending Feedback --}}
        @if(isset($pendingFeedback) && $pendingFeedback->count() > 0)
        <div class="card section-card">
            <div class="card-header">
                <h5><i class="fas fa-star me-2 text-warning" style="font-size:.9rem;"></i>Leave Feedback</h5>
                <span class="badge" style="background:#fef3c7;color:#92400e;font-size:.72rem;">{{ $pendingFeedback->count() }} pending</span>
            </div>
            <div class="card-body p-3">
                @foreach($pendingFeedback as $rental)
                <div class="feedback-alert">
                    <div class="car-name">
                        <i class="fas fa-car me-1"></i>
                        {{ $rental->car->brand ?? '' }} {{ $rental->car->model ?? '' }}
                    </div>
                    <div class="date-text">Returned {{ $rental->end_date->format('M d, Y') }}</div>
                    <a href="{{ route('user.rentals.show', $rental->rental_id) }}"
                       style="display:inline-flex;align-items:center;gap:5px;margin-top:.6rem;padding:5px 14px;border-radius:8px;background:#f59e0b;color:#fff;font-size:.78rem;font-weight:600;text-decoration:none;">
                        <i class="fas fa-star"></i> Rate Now
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
