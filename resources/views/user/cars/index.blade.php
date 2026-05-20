{{-- resources/views/user/cars/index.blade.php --}}
@extends('layouts.user')

@section('title', 'Browse Cars')

@section('content')

<style>
    /* Car Cards */
    .car-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(62,54,46,.09);
        transition: transform .25s, box-shadow .25s;
        overflow: hidden;
        background: #fff;
    }
    .car-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 32px rgba(62,54,46,.16);
    }
    .car-icon-wrap {
        background: linear-gradient(135deg, #f5ede0 0%, #eddcc8 100%);
        height: 160px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .car-icon-wrap i {
        font-size: 5rem;
        color: #AC8968;
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
        color: #3E362E;
    }
    .car-card .car-meta {
        font-size: 13px;
        color: #93785B;
        margin-bottom: 14px;
    }
    .car-card .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-top: 1px solid #f5ede0;
    }
    .car-card .info-row:first-of-type {
        border-top: none;
        padding-top: 0;
    }
    .car-card .info-label {
        font-size: 12px;
        color: #A69080;
        text-transform: uppercase;
        letter-spacing: .4px;
    }
    .car-card .info-value {
        font-size: 14px;
        font-weight: 600;
        color: #3E362E;
    }
    .car-card .daily-rate {
        font-size: 22px;
        font-weight: 800;
        color: #865D36;
    }
    .car-card .btn-view {
        background: linear-gradient(135deg, #865D36 0%, #93785B 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px;
        font-weight: 600;
        font-size: 14px;
        width: 100%;
        margin-top: 16px;
        transition: opacity .2s, transform .15s;
        box-shadow: 0 4px 12px rgba(134,93,54,.3);
    }
    .car-card .btn-view:hover { opacity: .9; color: #fff; transform: translateY(-1px); }

    /* Page header */
    .browse-header {
        background: linear-gradient(135deg, #3E362E 0%, #865D36 100%);
        border-radius: 18px;
        padding: 1.5rem 2rem;
        color: #fff;
        margin-bottom: 1.75rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 6px 24px rgba(62,54,46,.3);
    }
    .browse-header::after {
        content: '\f1b9';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: 2rem; top: 50%;
        transform: translateY(-50%);
        font-size: 5rem;
        opacity: .08;
    }
    .browse-header h4 { font-weight: 800; margin: 0 0 .25rem; }
    .browse-header p  { margin: 0; opacity: .75; font-size: .875rem; }

    /* Results header */
    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .results-header h5 {
        font-weight: 700;
        color: #3E362E;
        margin: 0;
    }
    .results-header .results-count {
        font-size: 13px;
        color: #93785B;
        background: #f5ede0;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #A69080;
    }
    .empty-state i { font-size: 4rem; margin-bottom: 16px; color: #eddcc8; }
    .empty-state h5 { color: #93785B; font-weight: 700; }

    /* Category badge overrides */
    .badge-luxury  { background: #f5ede0; color: #865D36; }
    .badge-suv     { background: #ede8e0; color: #93785B; }
    .badge-std     { background: #f0ebe4; color: #A69080; }
</style>



{{-- Browse Header --}}
<div class="browse-header">
    <h4><i class="fas fa-car me-2"></i>Browse Available Cars</h4>
    <p>Choose from our premium fleet and book your next ride</p>
</div>

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

            {{-- Image / Icon area --}}
            <div class="car-icon-wrap" style="position:relative; overflow:hidden;">
                @if($car->image_url)
                    <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}"
                         style="width:100%; height:100%; object-fit:cover; position:absolute; inset:0;">
                @else
                    <i class="fas fa-car"></i>
                @endif
                <span class="status-pill bg-{{ $car->status == 'available' ? 'success' : 'danger' }} text-white">
                    {{ $car->status == 'available' ? 'Available' : ucfirst($car->status) }}
                </span>
            </div>

            <div class="card-body d-flex flex-column">
                <div class="car-title">{{ $car->brand }} {{ $car->model }}</div>
                <div class="car-meta">{{ $car->year }} &bull; {{ $car->license_plate }}
                    @if($car->category)
                        &bull; <span class="badge bg-{{ $car->category == 'luxury' ? 'warning text-dark' : ($car->category == 'suv' ? 'info text-dark' : 'secondary') }}" style="font-size:10px;">{{ ucfirst($car->category) }}</span>
                    @endif
                </div>

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
