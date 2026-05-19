@extends('layouts.user')

@section('title', 'Payment Checkout')

@section('content')
<style>
    .checkout-wrap { max-width: 960px; margin: 0 auto; }

    /* ── Order Summary Card ── */
    .summary-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .summary-card .car-banner {
        height: 220px;
        object-fit: cover;
        width: 100%;
    }
    .summary-card .car-banner-placeholder {
        height: 220px;
        background: linear-gradient(135deg,#667eea,#764ba2);
        display: flex; align-items: center; justify-content: center;
    }
    .summary-card .car-banner-placeholder i { font-size: 5rem; color: rgba(255,255,255,.6); }

    .line-item { display: flex; justify-content: space-between; padding: 8px 0; font-size: 0.9rem; }
    .line-item.total { font-size: 1.1rem; font-weight: 800; color: #667eea; border-top: 2px solid #f0f0f0; padding-top: 14px; margin-top: 6px; }
    .line-item .label { color: #666; }
    .line-item .value { font-weight: 600; color: #222; }

    /* ── Payment Form Card ── */
    .payment-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    }
    .payment-card .card-header {
        background: linear-gradient(135deg,#667eea,#764ba2);
        color: #fff;
        border-radius: 18px 18px 0 0 !important;
        padding: 1.25rem 1.5rem;
    }
    .payment-card .card-header h5 { margin: 0; font-weight: 700; }

    /* method selector */
    .method-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; margin-bottom: 1.5rem; }
    .method-option { display: none; }
    .method-label {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 6px; padding: 14px 8px;
        border: 2px solid #e2e8f0; border-radius: 12px;
        cursor: pointer; transition: all .2s; font-size: 0.8rem; font-weight: 600; color: #555;
        text-align: center;
    }
    .method-label i { font-size: 1.5rem; color: #9ca3af; transition: color .2s; }
    .method-option:checked + .method-label {
        border-color: #667eea; background: #f0f0ff; color: #667eea;
    }
    .method-option:checked + .method-label i { color: #667eea; }

    /* card details */
    .card-details { display: none; }
    .card-details.show { display: block; }

    .form-label { font-size: 0.82rem; font-weight: 600; color: #374151; }
    .form-control, .form-select {
        border: 1.5px solid #e2e8f0; border-radius: 10px;
        font-size: 0.9rem; padding: 0.65rem 1rem;
        transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,.12);
    }

    /* secure badge */
    .secure-badge {
        display: flex; align-items: center; gap: 8px;
        background: #f0fdf4; border: 1px solid #bbf7d0;
        border-radius: 10px; padding: 10px 14px;
        font-size: 0.8rem; color: #16a34a; font-weight: 600;
        margin-bottom: 1.25rem;
    }

    /* submit */
    .btn-pay {
        background: linear-gradient(135deg,#667eea,#764ba2);
        color: #fff; border: none; border-radius: 12px;
        padding: 0.9rem; font-size: 1rem; font-weight: 700;
        width: 100%; transition: transform .15s, box-shadow .2s;
        box-shadow: 0 4px 16px rgba(102,126,234,.35);
    }
    .btn-pay:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(102,126,234,.45); color: #fff; }

    /* step indicator */
    .steps-bar { display: flex; align-items: center; margin-bottom: 2rem; }
    .step { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; }
    .step .num {
        width: 28px; height: 28px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 700;
    }
    .step.done .num { background: #10b981; color: #fff; }
    .step.active .num { background: #667eea; color: #fff; }
    .step.pending .num { background: #e2e8f0; color: #9ca3af; }
    .step.done span, .step.active span { color: #222; }
    .step.pending span { color: #9ca3af; }
    .step-line { flex: 1; height: 2px; background: #e2e8f0; margin: 0 8px; }
    .step-line.done { background: #10b981; }
</style>

<div class="checkout-wrap">

    {{-- Step Indicator --}}
    <div class="steps-bar">
        <div class="step done">
            <div class="num"><i class="fas fa-check" style="font-size:.65rem"></i></div>
            <span>Select Car</span>
        </div>
        <div class="step-line done"></div>
        <div class="step done">
            <div class="num"><i class="fas fa-check" style="font-size:.65rem"></i></div>
            <span>Choose Dates</span>
        </div>
        <div class="step-line done"></div>
        <div class="step active">
            <div class="num">3</div>
            <span>Payment</span>
        </div>
        <div class="step-line"></div>
        <div class="step pending">
            <div class="num">4</div>
            <span>Confirmation</span>
        </div>
    </div>

    <div class="row g-4">

        {{-- LEFT: Order Summary --}}
        <div class="col-lg-5">
            <div class="card summary-card">
                @if($car->image_url)
                    <img src="{{ $car->image_url }}" class="car-banner" alt="{{ $car->brand }} {{ $car->model }}">
                @else
                    <div class="car-banner-placeholder"><i class="fas fa-car"></i></div>
                @endif

                <div class="card-body p-4">
                    <h5 class="fw-bold mb-0">{{ $car->brand }} {{ $car->model }}</h5>
                    <p class="text-muted mb-3" style="font-size:.85rem">{{ $car->year }} &bull; {{ ucfirst($car->category ?? 'standard') }} &bull; {{ $car->color ?? '' }}</p>

                    <div class="line-item">
                        <span class="label"><i class="fas fa-calendar-alt me-1 text-primary"></i> Start Date</span>
                        <span class="value">{{ \Carbon\Carbon::parse($booking['start_date'])->format('M d, Y') }}</span>
                    </div>
                    <div class="line-item">
                        <span class="label"><i class="fas fa-calendar-check me-1 text-primary"></i> End Date</span>
                        <span class="value">{{ \Carbon\Carbon::parse($booking['end_date'])->format('M d, Y') }}</span>
                    </div>
                    <div class="line-item">
                        <span class="label"><i class="fas fa-moon me-1 text-primary"></i> Duration</span>
                        <span class="value">{{ $booking['total_days'] }} day{{ $booking['total_days'] != 1 ? 's' : '' }}</span>
                    </div>

                    <hr class="my-2">

                    <div class="line-item">
                        <span class="label">Car Rental ({{ $booking['total_days'] }}d × ₱{{ number_format($car->daily_rate, 2) }})</span>
                        <span class="value">₱{{ number_format($booking['subtotal'], 2) }}</span>
                    </div>

                    @if($booking['insurance_total'] > 0)
                    <div class="line-item">
                        <span class="label"><i class="fas fa-shield-alt me-1 text-success"></i> Insurance</span>
                        <span class="value">₱{{ number_format($booking['insurance_total'], 2) }}</span>
                    </div>
                    @endif

                    <div class="line-item">
                        <span class="label">VAT (12%)</span>
                        <span class="value">₱{{ number_format($booking['tax'], 2) }}</span>
                    </div>

                    <div class="line-item total">
                        <span class="label">Total Amount</span>
                        <span class="value">₱{{ number_format($booking['total_amount'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Payment Form --}}
        <div class="col-lg-7">
            <div class="card payment-card">
                <div class="card-header">
                    <h5><i class="fas fa-credit-card me-2"></i> Payment Details</h5>
                </div>
                <div class="card-body p-4">

                    <div class="secure-badge">
                        <i class="fas fa-lock"></i>
                        Your payment information is encrypted and secure
                    </div>

                    <form method="POST" action="{{ route('user.cars.checkout.confirm', $car->car_id) }}" id="paymentForm">
                        @csrf

                        {{-- Hidden booking data --}}
                        <input type="hidden" name="start_date"      value="{{ $booking['start_date'] }}">
                        <input type="hidden" name="end_date"        value="{{ $booking['end_date'] }}">
                        <input type="hidden" name="total_days"      value="{{ $booking['total_days'] }}">
                        <input type="hidden" name="subtotal"        value="{{ $booking['subtotal'] }}">
                        <input type="hidden" name="insurance_total" value="{{ $booking['insurance_total'] }}">
                        <input type="hidden" name="tax"             value="{{ $booking['tax'] }}">
                        <input type="hidden" name="total_amount"    value="{{ $booking['total_amount'] }}">
                        @foreach($booking['insurance_ids'] as $insId)
                            <input type="hidden" name="insurance_ids[]" value="{{ $insId }}">
                        @endforeach

                        {{-- Payment Method --}}
                        <div class="mb-3">
                            <label class="form-label mb-2">Select Payment Method</label>
                            <div class="method-grid">
                                <div>
                                    <input type="radio" name="payment_method" id="pm_cash" value="cash" class="method-option" checked>
                                    <label for="pm_cash" class="method-label">
                                        <i class="fas fa-money-bill-wave"></i> Cash
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" name="payment_method" id="pm_credit" value="credit_card" class="method-option">
                                    <label for="pm_credit" class="method-label">
                                        <i class="fas fa-credit-card"></i> Credit Card
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" name="payment_method" id="pm_debit" value="debit_card" class="method-option">
                                    <label for="pm_debit" class="method-label">
                                        <i class="fas fa-credit-card"></i> Debit Card
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" name="payment_method" id="pm_bank" value="bank_transfer" class="method-option">
                                    <label for="pm_bank" class="method-label">
                                        <i class="fas fa-university"></i> Bank Transfer
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" name="payment_method" id="pm_gcash" value="gcash" class="method-option">
                                    <label for="pm_gcash" class="method-label">
                                        <i class="fas fa-mobile-alt"></i> GCash
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" name="payment_method" id="pm_maya" value="maya" class="method-option">
                                    <label for="pm_maya" class="method-label">
                                        <i class="fas fa-wallet"></i> Maya
                                    </label>
                                </div>
                            </div>
                            @error('payment_method')
                                <div class="text-danger" style="font-size:.8rem">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Card Details (shown for credit/debit) --}}
                        <div class="card-details" id="cardDetails">
                            <div class="mb-3">
                                <label class="form-label">Cardholder Name</label>
                                <input type="text" class="form-control" name="card_name"
                                       placeholder="As printed on card"
                                       value="{{ auth()->user()->name }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Card Number</label>
                                <input type="text" class="form-control" name="card_number"
                                       placeholder="1234 5678 9012 3456"
                                       maxlength="19" id="cardNumber">
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Expiry Date</label>
                                    <input type="text" class="form-control" name="card_expiry"
                                           placeholder="MM / YY" maxlength="7" id="cardExpiry">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">CVV</label>
                                    <input type="password" class="form-control" name="card_cvv"
                                           placeholder="•••" maxlength="4">
                                </div>
                            </div>
                        </div>

                        {{-- GCash / Maya reference --}}
                        <div class="gcash-details" id="gcashDetails" style="display:none">
                            <div class="alert alert-info" style="font-size:.85rem; border-radius:10px;">
                                <i class="fas fa-info-circle me-1"></i>
                                Send payment to <strong>0961-033-6427</strong> ({{ auth()->user()->name }}) then enter your reference number below.
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Reference Number</label>
                                <input type="text" class="form-control" name="reference_number"
                                       placeholder="e.g. 1234567890">
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="mb-4">
                            <label class="form-label">Special Requests <span class="text-muted fw-normal">(optional)</span></label>
                            <textarea class="form-control" name="notes" rows="2"
                                      placeholder="Any special requests or notes for your rental..."></textarea>
                        </div>

                        {{-- Terms --}}
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                            <label class="form-check-label" for="agreeTerms" style="font-size:.85rem">
                                I agree to the <a href="#" class="text-primary">Terms & Conditions</a> and confirm the booking details above are correct.
                            </label>
                        </div>

                        <button type="submit" class="btn btn-pay" id="payBtn">
                            <i class="fas fa-lock me-2"></i>
                            Pay ₱{{ number_format($booking['total_amount'], 2) }} & Confirm Booking
                        </button>

                        <a href="{{ route('user.cars.show', $car->car_id) }}" class="btn btn-outline-secondary w-100 mt-2" style="border-radius:12px;">
                            <i class="fas fa-arrow-left me-1"></i> Back to Car Details
                        </a>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    // Show/hide card details based on payment method
    const methodInputs = document.querySelectorAll('.method-option');
    const cardDetails  = document.getElementById('cardDetails');
    const gcashDetails = document.getElementById('gcashDetails');

    function updateMethodUI() {
        const val = document.querySelector('.method-option:checked').value;
        cardDetails.classList.toggle('show', val === 'credit_card' || val === 'debit_card');
        gcashDetails.style.display = (val === 'gcash' || val === 'maya') ? 'block' : 'none';
    }

    methodInputs.forEach(i => i.addEventListener('change', updateMethodUI));
    updateMethodUI();

    // Card number formatting
    document.getElementById('cardNumber').addEventListener('input', function () {
        let v = this.value.replace(/\D/g, '').substring(0, 16);
        this.value = v.replace(/(.{4})/g, '$1 ').trim();
    });

    // Expiry formatting
    document.getElementById('cardExpiry').addEventListener('input', function () {
        let v = this.value.replace(/\D/g, '').substring(0, 4);
        if (v.length >= 2) v = v.substring(0,2) + ' / ' + v.substring(2);
        this.value = v;
    });

    // Prevent double submit
    document.getElementById('paymentForm').addEventListener('submit', function () {
        const btn = document.getElementById('payBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
    });
</script>
@endpush
@endsection
