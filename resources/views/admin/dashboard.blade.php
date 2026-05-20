@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')

{{-- ── Stat Cards ── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card stat-card stat-blue">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-val">{{ number_format($totalCustomers ?? 0) }}</div>
            <div class="stat-lbl">Total Customers</div>
            <i class="fas fa-users stat-bg"></i>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card stat-green">
            <div class="stat-icon"><i class="fas fa-car"></i></div>
            <div class="stat-val">{{ number_format($totalCars ?? 0) }}</div>
            <div class="stat-lbl">Total Cars</div>
            <i class="fas fa-car stat-bg"></i>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card stat-amber">
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-val">{{ number_format($activeRentals ?? 0) }}</div>
            <div class="stat-lbl">Active Rentals</div>
            <i class="fas fa-calendar-check stat-bg"></i>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card stat-purple">
            <div class="stat-icon"><i class="fas fa-peso-sign"></i></div>
            <div class="stat-val" style="font-size:1.4rem;">₱{{ number_format($totalRevenue ?? 0, 0) }}</div>
            <div class="stat-lbl">Total Revenue</div>
            <i class="fas fa-coins stat-bg"></i>
        </div>
    </div>
</div>

{{-- ── Row 2: Car Status + Revenue Chart ── --}}
<div class="row g-3 mb-4">

    {{-- Car Status --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="fas fa-car me-2" style="color:#2563eb;font-size:.85rem;"></i>Fleet Status</span>
                <span class="badge" style="background:#dbeafe;color:#1e40af;">{{ number_format($totalCars ?? 0) }} total</span>
            </div>
            <div class="card-body">
                @php
                    $total = max($totalCars ?? 1, 1);
                    $avPct = round(($availableCars ?? 0) / $total * 100, 1);
                    $rnPct = round(($rentedCars ?? 0) / $total * 100, 1);
                    $mnPct = round(($maintenanceCars ?? 0) / $total * 100, 1);
                @endphp

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:.82rem;font-weight:600;color:#15803d;">
                            <i class="fas fa-circle me-1" style="font-size:.5rem;"></i>Available
                        </span>
                        <span style="font-size:.82rem;font-weight:700;color:#15803d;">{{ $availableCars ?? 0 }} ({{ $avPct }}%)</span>
                    </div>
                    <div class="progress" style="height:8px;border-radius:4px;background:#f0fdf4;">
                        <div class="progress-bar" style="width:{{ $avPct }}%;background:linear-gradient(90deg,#22c55e,#16a34a);border-radius:4px;"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:.82rem;font-weight:600;color:#b45309;">
                            <i class="fas fa-circle me-1" style="font-size:.5rem;"></i>Rented
                        </span>
                        <span style="font-size:.82rem;font-weight:700;color:#b45309;">{{ $rentedCars ?? 0 }} ({{ $rnPct }}%)</span>
                    </div>
                    <div class="progress" style="height:8px;border-radius:4px;background:#fffbeb;">
                        <div class="progress-bar" style="width:{{ $rnPct }}%;background:linear-gradient(90deg,#f59e0b,#d97706);border-radius:4px;"></div>
                    </div>
                </div>

                <div class="mb-2">
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:.82rem;font-weight:600;color:#be123c;">
                            <i class="fas fa-circle me-1" style="font-size:.5rem;"></i>Maintenance
                        </span>
                        <span style="font-size:.82rem;font-weight:700;color:#be123c;">{{ $maintenanceCars ?? 0 }} ({{ $mnPct }}%)</span>
                    </div>
                    <div class="progress" style="height:8px;border-radius:4px;background:#fff1f2;">
                        <div class="progress-bar" style="width:{{ $mnPct }}%;background:linear-gradient(90deg,#f43f5e,#e11d48);border-radius:4px;"></div>
                    </div>
                </div>

                <hr style="border-color:#f1f5f9;margin:1.25rem 0 1rem;">

                <div class="row g-2 text-center">
                    <div class="col-4">
                        <div style="background:#f0f4ff;border-radius:10px;padding:.6rem .4rem;">
                            <div style="font-size:1.1rem;font-weight:800;color:#1e40af;">{{ number_format($totalStaff ?? 0) }}</div>
                            <div style="font-size:.68rem;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.04em;">Staff</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div style="background:#f0fdf4;border-radius:10px;padding:.6rem .4rem;">
                            <div style="font-size:1.1rem;font-weight:800;color:#15803d;">{{ number_format($totalBranches ?? 0) }}</div>
                            <div style="font-size:.68rem;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.04em;">Branches</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div style="background:#fffbeb;border-radius:10px;padding:.6rem .4rem;">
                            <div style="font-size:1.1rem;font-weight:800;color:#b45309;">{{ number_format($activeRentals ?? 0) }}</div>
                            <div style="font-size:.68rem;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.04em;">Active</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Revenue Chart --}}
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="fas fa-chart-line me-2" style="color:#2563eb;font-size:.85rem;"></i>Monthly Revenue</span>
                <span style="font-size:.75rem;color:#64748b;">Last 7 months</span>
            </div>
            <div class="card-body" style="padding-top:.75rem !important;">
                <canvas id="revenueChart" height="90"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ── Row 3: Recent Rentals ── --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="fas fa-clock me-2" style="color:#2563eb;font-size:.85rem;"></i>Recent Rentals</span>
                <a href="{{ route('admin.rentals.index') }}"
                   style="font-size:.78rem;color:#2563eb;font-weight:600;text-decoration:none;">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Car</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRentals ?? [] as $rental)
                        <tr>
                            <td style="color:#94a3b8;font-weight:600;">#{{ $rental->rental_id }}</td>
                            <td>
                                <div style="font-weight:600;color:#0f172a;">
                                    {{ $rental->customer->customer->full_name ?? $rental->customer->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="font-weight:600;">{{ $rental->car->brand ?? '' }} {{ $rental->car->model ?? '' }}</div>
                                <div style="font-size:.72rem;color:#94a3b8;">{{ $rental->car->year ?? '' }}</div>
                            </td>
                            <td>{{ $rental->start_date ? $rental->start_date->format('M d, Y') : '—' }}</td>
                            <td>{{ $rental->end_date ? $rental->end_date->format('M d, Y') : '—' }}</td>
                            <td style="font-weight:700;">₱{{ number_format($rental->total_amount ?? 0, 2) }}</td>
                            <td>
                                @php
                                    $sc = ['pending'=>'warning text-dark','active'=>'success','returned'=>'info','cancelled'=>'danger'];
                                    $sc2 = $sc[$rental->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $sc2 }}">{{ ucfirst($rental->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.rentals.show', $rental->rental_id) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center py-4" style="color:#94a3b8;">No rentals found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ── Row 4: Recent Customers ── --}}
<div class="row g-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="fas fa-user-plus me-2" style="color:#2563eb;font-size:.85rem;"></i>Recent Customers</span>
                <a href="{{ route('admin.customers.index') }}"
                   style="font-size:.78rem;color:#2563eb;font-weight:600;text-decoration:none;">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentCustomers ?? [] as $customer)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:.65rem;">
                                    <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#3b82f6);display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:700;color:#fff;flex-shrink:0;">
                                        {{ strtoupper(substr($customer->first_name ?? 'C', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:700;color:#0f172a;">{{ $customer->full_name ?? ($customer->first_name . ' ' . $customer->last_name) }}</div>
                                        <div style="font-size:.72rem;color:#94a3b8;">ID #{{ $customer->customer_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone ?? '—' }}</td>
                            <td>{{ $customer->registered_date ? $customer->registered_date->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4" style="color:#94a3b8;">No customers found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if(isset($monthlyRevenue) && count($monthlyRevenue) > 0)
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($monthlyRevenue, 'month')) !!},
            datasets: [{
                label: 'Revenue (₱)',
                data: {!! json_encode(array_column($monthlyRevenue, 'revenue')) !!},
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.08)',
                borderWidth: 2.5,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#2563eb',
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => '₱' + ctx.raw.toLocaleString('en-PH', {minimumFractionDigits: 2})
                    }
                }
            },
            scales: {
                y: {
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        font: { size: 11 },
                        callback: v => '₱' + v.toLocaleString()
                    }
                },
                x: { grid: { display: false }, ticks: { font: { size: 11 } } }
            }
        }
    });
</script>
@endif
@endpush
