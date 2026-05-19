<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #{{ $rental->rental_id }} — Car Rental System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f6fb; font-family: 'Segoe UI', sans-serif; }

        .invoice-wrapper {
            max-width: 780px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.10);
            overflow: hidden;
        }

        /* Header */
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 36px 40px 28px;
        }
        .invoice-header h1 { font-size: 32px; font-weight: 800; letter-spacing: 2px; margin: 0; }
        .invoice-header .subtitle { opacity: .75; font-size: 14px; margin-top: 4px; }
        .invoice-header .inv-number { font-size: 18px; font-weight: 700; }
        .invoice-header .inv-date   { font-size: 13px; opacity: .8; }

        /* Body */
        .invoice-body { padding: 36px 40px; }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #999;
            margin-bottom: 8px;
        }

        .info-block p { margin: 0; font-size: 14px; line-height: 1.7; color: #333; }
        .info-block strong { color: #1a1a2e; }

        /* Table */
        .invoice-table { width: 100%; border-collapse: collapse; margin-top: 28px; }
        .invoice-table thead tr {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }
        .invoice-table thead th { padding: 12px 16px; font-size: 13px; font-weight: 600; }
        .invoice-table tbody td { padding: 12px 16px; font-size: 14px; border-bottom: 1px solid #f0f0f0; }
        .invoice-table tbody tr:last-child td { border-bottom: none; }

        /* Totals */
        .totals-box { margin-top: 24px; }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
            color: #555;
            border-bottom: 1px solid #f5f5f5;
        }
        .totals-row.grand-total {
            font-size: 18px;
            font-weight: 800;
            color: #667eea;
            border-bottom: none;
            padding-top: 14px;
        }

        /* Status badge */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Footer */
        .invoice-footer {
            background: #f8f9ff;
            border-top: 1px solid #eee;
            padding: 20px 40px;
            text-align: center;
            font-size: 13px;
            color: #aaa;
        }

        /* Print actions */
        .print-actions {
            max-width: 780px;
            margin: 0 auto 20px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        @media print {
            body { background: #fff; }
            .print-actions { display: none; }
            .invoice-wrapper { box-shadow: none; margin: 0; border-radius: 0; }
        }
    </style>
</head>
<body>

{{-- Action buttons (hidden on print) --}}
<div class="print-actions mt-4">
    <a href="{{ route('user.rentals.show', $rental->rental_id) }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<div class="invoice-wrapper">

    {{-- Header --}}
    <div class="invoice-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1><i class="fas fa-car me-2"></i> CarRental</h1>
                <div class="subtitle">Car Rental System &bull; Manila, Philippines</div>
            </div>
            <div class="text-end">
                <div class="inv-number">INV-{{ str_pad($rental->rental_id, 5, '0', STR_PAD_LEFT) }}</div>
                <div class="inv-date">
                    {{ $rental->invoice ? $rental->invoice->invoice_date->format('F d, Y') : now()->format('F d, Y') }}
                </div>
            </div>
        </div>
    </div>

    <div class="invoice-body">

        {{-- Bill To / Car --}}
        <div class="row mb-4">
            <div class="col-6">
                <div class="section-title">Bill To</div>
                <div class="info-block">
                    <p><strong>{{ auth()->user()->name }}</strong></p>
                    <p>{{ auth()->user()->email }}</p>
                    @if(auth()->user()->phone)
                        <p>{{ auth()->user()->phone }}</p>
                    @endif
                    @if(auth()->user()->customer?->license_no)
                        <p>License: {{ auth()->user()->customer->license_no }}</p>
                    @endif
                </div>
            </div>
            <div class="col-6 text-end">
                <div class="section-title">Vehicle</div>
                <div class="info-block">
                    <p><strong>{{ $rental->car->brand }} {{ $rental->car->model }}</strong></p>
                    <p>Year: {{ $rental->car->year }}</p>
                    <p>Plate: {{ $rental->car->license_plate }}</p>
                    <p>
                        @if($rental->status == 'returned')
                            <span class="status-badge bg-info text-white">Returned</span>
                        @elseif($rental->status == 'active')
                            <span class="status-badge bg-success text-white">Active</span>
                        @else
                            <span class="status-badge bg-secondary text-white">{{ ucfirst($rental->status) }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Rental Period --}}
        <div class="row mb-2">
            <div class="col-4">
                <div class="section-title">Rental Period</div>
                <p class="mb-0 fw-semibold">{{ $rental->start_date->format('M d, Y') }} — {{ $rental->end_date->format('M d, Y') }}</p>
                <small class="text-muted">
                    {{ $rental->start_date->diffInDays($rental->end_date) }} day(s)
                </small>
            </div>
            @if($rental->actual_return_date)
            <div class="col-4">
                <div class="section-title">Actual Return</div>
                <p class="mb-0 fw-semibold">{{ $rental->actual_return_date->format('M d, Y') }}</p>
            </div>
            @endif
            <div class="col-4">
                <div class="section-title">Daily Rate</div>
                <p class="mb-0 fw-semibold">₱{{ number_format($rental->car->daily_rate, 2) }}</p>
            </div>
        </div>

        {{-- Line Items --}}
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-center">Days</th>
                    <th class="text-center">Rate</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                {{-- Car rental --}}
                <tr>
                    <td>Car Rental — {{ $rental->car->brand }} {{ $rental->car->model }}</td>
                    <td class="text-center">{{ $rental->start_date->diffInDays($rental->end_date) }}</td>
                    <td class="text-center">₱{{ number_format($rental->car->daily_rate, 2) }}</td>
                    <td class="text-end">₱{{ number_format($rental->invoice->subtotal ?? ($rental->car->daily_rate * $rental->start_date->diffInDays($rental->end_date)), 2) }}</td>
                </tr>

                {{-- Insurance lines --}}
                @foreach($rental->insurances as $insurance)
                <tr>
                    <td>Insurance — {{ $insurance->name }}</td>
                    <td class="text-center">{{ $rental->start_date->diffInDays($rental->end_date) }}</td>
                    <td class="text-center">₱{{ number_format($insurance->daily_rate, 2) }}</td>
                    <td class="text-end">₱{{ number_format($insurance->daily_rate * $rental->start_date->diffInDays($rental->end_date), 2) }}</td>
                </tr>
                @endforeach

                {{-- Late fee --}}
                @if($rental->invoice && $rental->invoice->late_fee > 0)
                <tr>
                    <td colspan="3" class="text-danger">Late Return Fee</td>
                    <td class="text-end text-danger">₱{{ number_format($rental->invoice->late_fee, 2) }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="row justify-content-end">
            <div class="col-md-5">
                <div class="totals-box">
                    @if($rental->invoice)
                    <div class="totals-row">
                        <span>Subtotal</span>
                        <span>₱{{ number_format($rental->invoice->subtotal, 2) }}</span>
                    </div>
                    @if($rental->invoice->insurance_fee > 0)
                    <div class="totals-row">
                        <span>Insurance</span>
                        <span>₱{{ number_format($rental->invoice->insurance_fee, 2) }}</span>
                    </div>
                    @endif
                    @if($rental->invoice->late_fee > 0)
                    <div class="totals-row text-danger">
                        <span>Late Fee</span>
                        <span>₱{{ number_format($rental->invoice->late_fee, 2) }}</span>
                    </div>
                    @endif
                    <div class="totals-row">
                        <span>Tax (12% VAT)</span>
                        <span>₱{{ number_format($rental->invoice->tax, 2) }}</span>
                    </div>
                    <div class="totals-row grand-total">
                        <span>Total Due</span>
                        <span>₱{{ number_format($rental->invoice->subtotal + $rental->invoice->insurance_fee + $rental->invoice->late_fee + $rental->invoice->tax, 2) }}</span>
                    </div>
                    @else
                    <div class="totals-row grand-total">
                        <span>Total</span>
                        <span>₱{{ number_format($rental->total_amount, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Payment status --}}
        @if($rental->payment)
        <div class="mt-4 p-3 rounded" style="background:#f0fff4; border:1px solid #b2f5c8;">
            <i class="fas fa-check-circle text-success me-2"></i>
            <strong>Payment {{ ucfirst($rental->payment->status) }}</strong>
            — ₱{{ number_format($rental->payment->amount, 2) }}
            via {{ ucwords(str_replace('_', ' ', $rental->payment->method)) }}
            @if($rental->payment->payment_date)
                on {{ $rental->payment->payment_date->format('M d, Y') }}
            @endif
        </div>
        @endif

    </div>

    <div class="invoice-footer">
        Thank you for choosing CarRental System! &bull; Please pay within 7 days of invoice date.
    </div>
</div>

</body>
</html>
