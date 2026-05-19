{{-- resources/views/user/cars/index.blade.php --}}
@extends('layouts.user')

@section('title', 'Browse Cars')

@section('content')

<style>
    /* Car Cards */
    .car-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        transition: transform .25s, box-shadow .25s;
        overflow: hidden;
    }
    .car-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.14);
    }
    .car-icon-wrap {
        background: linear-gradient(135deg, #f0f2ff 0%, #e8ecff 100%);
        height: 160px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .car-icon-wrap i {
        font-size: 5rem;
        color: #667eea;
        opacity: .75;
    }
    .status-pill {
        position: absolute;
        top: 12px;
        right: 12px;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .car-card .card-body {
        padding: 20px;
    }
    .car-card .car-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 2px;
        color: #1a1a2e;
    }
    .car-card .car-meta {
        font-size: 13px;
        color: #888;
        margin-bottom: 14px;
    }
    .car-card .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-top: 1px solid #f0f0f0;
    }
    .car-card .info-row:first-of-type {
        border-top: none;
        padding-top: 0;
    }
    .car-card .info-label {
        font-size: 12px;
        color: #aaa;
        text-transform: uppercase;
        letter-spacing: .4px;
    }
    .car-card .info-value {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }
    .car-card .daily-rate {
        font-size: 22px;
        font-weight: 800;
        color: #667eea;
    }
    .car-card .btn-view {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px;
        font-weight: 600;
        font-size: 14px;
        width: 100%;
        margin-top: 16px;
        transition: opacity .2s;
    }
    .car-card .btn-view:hover { opacity: .88; color: #fff; }

    /* Results header */
    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .results-header h5 {
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }
    .results-header .results-count {
        font-size: 13px;
        color: #888;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #aaa;
    }
    .empty-state i { font-size: 4rem; margin-bottom: 16px; color: #d0d0e0; }
    .empty-state h5 { color: #555; font-weight: 700; }
</style>



{{-- Results Header --}}
<div class="results-header">
    <h5>Available Cars</h5>
    <span class="results-count">{{ $cars->total() }} car{{ $cars->total() != 1 ? 's' : '' }} found</span>
</div>

{{-- Car Grid --}}
<div class="row g-4">
    @forelse($cars as $car)
    <div class="col-sm-6 col-lg-4">
        <div class="card car-card h-100">

            {{-- Icon / Image area --}}
            <div class="car-icon-wrap">
                <i class="fas fa-car"></i>
                <span class="status-pill bg-{{ $car->status == 'available' ? 'success' : 'danger' }} text-white">
                    {{ $car->status == 'available' ? 'Available' : ucfirst($car->status) }}
                </span>
            </div>

            <div class="card-body d-flex flex-column">
                <div class="car-title">{{ $car->brand }} {{ $car->model }}</div>
                <div class="car-meta">{{ $car->year }} &bull; {{ $car->license_plate }}</div>

                <div class="info-row">
                    <span class="info-label">Daily Rate</span>
                    <span class="daily-rate">₱{{ number_format($car->daily_rate, 2) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Mileage</span>
                    <span class="info-value">{{ number_format($car->mileage) }} km</span>
                </div>

                <a href="{{ route('user.cars.show', $car->car_id) }}"
                   class="btn btn-view mt-auto">
                    <i class="fas fa-eye me-1"></i> View & Book
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state">
            <i class="fas fa-car-side d-block"></i>
            <h5>No cars available</h5>
            <p>No cars are currently available. Check back soon.</p>
        </div>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($cars->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $cars->appends(request()->query())->links() }}
</div>
@endif

@push('scripts')
@endpush
@endsection
