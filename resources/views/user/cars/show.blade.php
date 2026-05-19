{{-- resources/views/user/cars/show.blade.php --}}
@extends('layouts.user')

@section('title', $car->brand . ' ' . $car->model)

@section('content')
<div class="row">
    {{-- Car Image / Icon --}}
    <div class="col-md-5 mb-4">
        <div class="card h-100" style="border:none; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.1);">
            @if($car->image_url)
                <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}"
                     style="width:100%; height:280px; object-fit:cover;">
                <div class="p-3 d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge bg-{{ $car->status == 'available' ? 'success' : 'danger' }} fs-6">
                        {{ ucfirst($car->status) }}
                    </span>
                    @if($car->category)
                        <span class="badge bg-{{ $car->category == 'luxury' ? 'warning text-dark' : ($car->category == 'suv' ? 'info text-dark' : 'secondary') }}">
                            {{ ucfirst($car->category) }}
                        </span>
                    @endif
                    @if($car->color)
                        <span class="badge bg-light text-dark border">{{ $car->color }}</span>
                    @endif
                </div>
            @else
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-5">
                    <i class="fas fa-car fa-8x text-secondary mb-3"></i>
                    <span class="badge bg-{{ $car->status == 'available' ? 'success' : 'danger' }} fs-6">
                        {{ ucfirst($car->status) }}
                    </span>
                </div>
            @endif
        </div>
    </div>

    {{-- Car Details + Booking Form --}}
    <div class="col-md-7 mb-4">
        <div class="card">
            <div class="card-body">
                <h2 class="mb-0">{{ $car->brand }} {{ $car->model }}</h2>
                <p class="text-muted mb-3">{{ $car->year }} &bull; {{ $car->license_plate }}</p>

                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted d-block">Mileage</small>
                        <strong>{{ number_format($car->mileage) }} km</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Daily Rate</small>
                        <strong class="text-primary fs-5">₱{{ number_format($car->daily_rate, 2) }}</strong>
                    </div>
                </div>

                @if($car->status == 'available')
                <hr>
                <form action="{{ route('user.cars.book', $car->car_id) }}" method="POST" id="bookingForm">
                    @csrf

                    {{-- Dates --}}
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="start_date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('start_date', request('start_date')) }}" required>
                            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="end_date"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   value="{{ old('end_date', request('end_date')) }}" required>
                            @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Insurance --}}
                    @if($insurances->count() > 0)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-shield-alt me-1 text-primary"></i> Add Insurance (Optional)
                        </label>
                        <div class="border rounded p-3 bg-light">
                            @foreach($insurances as $insurance)
                            <div class="form-check mb-2">
                                <input class="form-check-input insurance-check" type="checkbox"
                                       name="insurance_ids[]"
                                       value="{{ $insurance->insurance_id }}"
                                       id="ins_{{ $insurance->insurance_id }}"
                                       data-rate="{{ $insurance->daily_rate }}"
                                       {{ is_array(old('insurance_ids')) && in_array($insurance->insurance_id, old('insurance_ids')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="ins_{{ $insurance->insurance_id }}">
                                    <strong>{{ $insurance->name }}</strong>
                                    <span class="text-primary ms-1">₱{{ number_format($insurance->daily_rate, 2) }}/day</span>
                                    @if($insurance->coverage_details)
                                        <br><small class="text-muted">{{ $insurance->coverage_details }}</small>
                                    @endif
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Live Cost Summary --}}
                    <div class="card bg-light border-0 mb-3" id="costSummary" style="display:none!important;">
                        <div class="card-body py-2">
                            <h6 class="mb-2 text-muted">Cost Summary</h6>
                            <div class="d-flex justify-content-between">
                                <span>Car rental (<span id="summaryDays">0</span> days × ₱{{ number_format($car->daily_rate, 2) }})</span>
                                <span>₱<span id="summaryCarCost">0.00</span></span>
                            </div>
                            <div id="summaryInsuranceRow" class="d-flex justify-content-between" style="display:none!important;">
                                <span>Insurance</span>
                                <span>₱<span id="summaryInsuranceCost">0.00</span></span>
                            </div>
                            <hr class="my-1">
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total</span>
                                <span class="text-primary">₱<span id="summaryTotal">0.00</span></span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-book w-100">
                        <i class="fas fa-arrow-right me-1"></i> Proceed to Payment
                    </button>
                </form>

                @else
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    This car is currently <strong>{{ $car->status }}</strong> and not available for booking.
                </div>
                @endif

                <a href="{{ route('user.cars.index') }}" class="btn btn-outline-secondary mt-3 w-100">
                    <i class="fas fa-arrow-left me-1"></i> Back to Cars
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Customer Reviews --}}
@if($car->feedback->count() > 0)
<div class="card mt-2">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-star text-warning me-1"></i> Customer Reviews</h5>
        <span class="badge bg-warning text-dark">
            {{ number_format($car->feedback->avg('rating'), 1) }} / 5
        </span>
    </div>
    <div class="card-body">
        @foreach($car->feedback as $fb)
        <div class="{{ !$loop->last ? 'border-bottom mb-3 pb-3' : '' }}">
            <div class="d-flex justify-content-between align-items-center">
                <strong>{{ $fb->customer->name ?? 'Customer' }}</strong>
                <div class="text-warning">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star{{ $i <= $fb->rating ? '' : ' text-muted' }}"></i>
                    @endfor
                </div>
            </div>
            <p class="mt-1 mb-1">{{ $fb->comment }}</p>
            <small class="text-muted">{{ $fb->review_date->format('M d, Y') }}</small>
        </div>
        @endforeach
    </div>
</div>
@endif

@push('scripts')
<script>
    const dailyRate = {{ $car->daily_rate }};

    function recalculate() {
        const start = document.getElementById('start_date').value;
        const end   = document.getElementById('end_date').value;

        if (!start || !end || end <= start) {
            document.getElementById('costSummary').style.display = 'none';
            return;
        }

        const days = Math.round((new Date(end) - new Date(start)) / 86400000);
        if (days <= 0) return;

        const carCost = dailyRate * days;

        let insuranceCost = 0;
        document.querySelectorAll('.insurance-check:checked').forEach(cb => {
            insuranceCost += parseFloat(cb.dataset.rate) * days;
        });

        const total = carCost + insuranceCost;

        document.getElementById('summaryDays').textContent        = days;
        document.getElementById('summaryCarCost').textContent     = carCost.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('summaryInsuranceCost').textContent = insuranceCost.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('summaryTotal').textContent       = total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        const insRow = document.getElementById('summaryInsuranceRow');
        insRow.style.display = insuranceCost > 0 ? 'flex' : 'none';

        document.getElementById('costSummary').style.removeProperty('display');
    }

    document.getElementById('start_date').addEventListener('change', recalculate);
    document.getElementById('end_date').addEventListener('change', recalculate);
    document.querySelectorAll('.insurance-check').forEach(cb => cb.addEventListener('change', recalculate));
</script>
@endpush
@endsection
