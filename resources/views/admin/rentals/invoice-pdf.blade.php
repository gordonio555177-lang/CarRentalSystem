<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #{{ $rental->rental_id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f6fb; font-family: 'Segoe UI', sans-serif; }
        .invoice-wrapper {
            max-width: 780px; margin: 40px auto; background: #fff;
            border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,0.10); overflow: hidden;
        }
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff; padding: 36px 40px 28px;
        }
        .invoice-header h1 { font-size: 32px; font-weight: 800; letter-spacing: 2px; margin: 0; }
        .invoice-header .subtitle { opacity: .75; font-size: 14px; margin-top: 4px; }
        .invoice-body { padding: 36px 40px; }
        .section-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #999; margin-bottom: 8px; }
        .invoice-table { width: 100%; border-collapse: collapse; margin-top: 28px; }
        .invoice-table thead tr { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; }
        .invoice-table thead th { padding: 12px 16px; font-size: 13px; font-weight: 600; }
        .invoice-table tbody td { padding: 12px 16px; font-size: 14px; border-bottom: 1px solid #f0f0f0; }
        .totals-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; color: #555; border-bottom: 1px solid #f5f5f5; }
        .totals-row.grand-total { font-size: 18px; font-weight: 800; color: #667eea; border-bottom: none; padding-top: 14px; }
        .invoice-footer { background: #f8f9ff; border-top: 1px solid #eee; padding: 20px 40px; text-align: center; font-size: 13px; color: #aaa; }
        .print-actions { max-width: 780px; margin: 0 auto 20px; display: flex; gap: 10px; justify-content: flex-end; }
        @media print {
            body { background: #fff; }
            .print-actions { display: none; }
            .invoice-wrapper { box-shadow: none; margin: 0; border-radius: 0; }
        }
    </style>
</head>
<body>

@php
    $user    = $rental->customer;
    $profile = $user?->customer;
@endphp

<div class="print-actions mt-4">
    <a href="{{ route('admin.rentals.show', $rental->rental_id) }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
    <button onclick="window.print()" class="btn btn-primary btn-sm">
        <i class="fas fa-print me-1"></i> Print / Save as PDF
    </button>
</div>

<div class="invoice-wrapper">
    <div class="invoice-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1><i class="fas fa-car me-2"></i> CarRental</h1>
                <div class="subtitle">Car Rental System &bull; Manila, Philippines</div>
            </div>
            <div class="text-end">
                <div style="font-size:18px;font-weight:700;">INV-{{ str_pad($rental->rental_id, 5, '0', STR_PAD_LEFT) }}</div>
                <div style="font-size:13px;opacity:.8;">
                    {{ $rental->invoice ? $rental->invoice->invoice_date->format('F d, Y') : now()->format('F d, Y') }}
                </div>
            </div>
        </div>
    </div>

    <div class="invoice-body">
        <div class="row mb-4">
            <div class="col-6">
                <div class="section-title">Bill To</div>
                <p class="mb-0"><strong>{{ $user->name ?? ($profile->full_name ?? 'N/A') }}</strong></p>
                <p class="mb-0">{{ $user->email ?? ($profile->email ?? '') }}</p>
                <p class="mb-0">{{ $user->phone ?? ($profile->phone ?? '') }}</p>
                @if($profile?->license_no)
                    <p class="mb-0">License: {{ $profile->license_no }}</p>
                @endif
            </div>
            <div class="col-6 text-end">
                <div class="section-title">Vehicle</div>
                <p class="mb-0"><strong>{{ $rental->car->brand }} {{ $rental->car->model }}</strong></p>
                <p class="mb-0">Year: {{ $rental->car->year }}</p>
                <p class="mb-0">Plate: {{ $rental->car->license_plate }}</p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-4">
                <div class="section-title">Rental Period</div>
                <p class="mb-0 fw-semibold">{{ $rental->start_date->format('M d, Y') }} — {{ $rental->end_date->format('M d, Y') }}</p>
                <small class="text-muted">{{ $rental->start_date->diffInDays($rental->end_date) }} day(s)</small>
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
                <tr>
                    <td>Car Rental — {{ $rental->car->brand }} {{ $rental->car->model }}</td>
                    <td class="text-center">{{ $rental->start_date->diffInDays($rental->end_date) }}</td>
                    <td class="text-center">₱{{ number_format($rental->car->daily_rate, 2) }}</td>
                    <td class="text-end">₱{{ number_format($rental->invoice->subtotal ?? ($rental->car->daily_rate * $rental->start_date->diffInDays($rental->end_date)), 2) }}</td>
                </tr>
                @foreach($rental->insurances as $insurance)
                <tr>
                    <td>Insurance — {{ $insurance->name }}</td>
                    <td class="text-center">{{ $rental->start_date->diffInDays($rental->end_date) }}</td>
                    <td class="text-center">₱{{ number_format($insurance->daily_rate, 2) }}</td>
                    <td class="text-end">₱{{ number_format($insurance->daily_rate * $rental->start_date->diffInDays($rental->end_date), 2) }}</td>
                </tr>
                @endforeach
                @if($rental->invoice && $rental->invoice->late_fee > 0)
                <tr>
                    <td colspan="3" class="text-danger">Late Return Fee</td>
                    <td class="text-end text-danger">₱{{ number_format($rental->invoice->late_fee, 2) }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        <div class="row justify-content-end mt-3">
            <div class="col-md-5">
                @if($rental->invoice)
                <div class="totals-row"><span>Subtotal</span><span>₱{{ number_format($rental->invoice->subtotal, 2) }}</span></div>
                @if($rental->invoice->insurance_fee > 0)
                <div class="totals-row"><span>Insurance</span><span>₱{{ number_format($rental->invoice->insurance_fee, 2) }}</span></div>
                @endif
                @if($rental->invoice->late_fee > 0)
                <div class="totals-row text-danger"><span>Late Fee</span><span>₱{{ number_format($rental->invoice->late_fee, 2) }}</span></div>
                @endif
                <div class="totals-row"><span>Tax (12% VAT)</span><span>₱{{ number_format($rental->invoice->tax, 2) }}</span></div>
                <div class="totals-row grand-total"><span>Total Due</span><span>₱{{ number_format($rental->invoice->subtotal + $rental->invoice->insurance_fee + $rental->invoice->late_fee + $rental->invoice->tax, 2) }}</span></div>
                @else
                <div class="totals-row grand-total"><span>Total</span><span>₱{{ number_format($rental->total_amount, 2) }}</span></div>
                @endif
            </div>
        </div>
    </div>

    <div class="invoice-footer">
        Thank you for choosing CarRental System! &bull; Please pay within 7 days of invoice date.
    </div>
</div>

</body>
</html>
