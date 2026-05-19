@extends('layouts.admin')

@section('title', 'Edit Payment')

@section('content')
@php
    $user    = $payment->rental->customer;
    $profile = $user?->customer;
@endphp

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Payment #{{ $payment->payment_id }}</h5>
        <a href="{{ route('admin.payments.show', $payment->payment_id) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">

        {{-- Summary --}}
        <div class="alert alert-light border mb-4">
            <div class="row">
                <div class="col-md-4">
                    <small class="text-muted d-block">Customer</small>
                    <strong>{{ $user->name ?? ($profile->full_name ?? 'N/A') }}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Car</small>
                    <strong>{{ $payment->rental->car->brand }} {{ $payment->rental->car->model }}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Rental #</small>
                    <strong>
                        <a href="{{ route('admin.rentals.show', $payment->rental->rental_id) }}">
                            #{{ $payment->rental->rental_id }}
                        </a>
                    </strong>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.payments.update', $payment->payment_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">

                {{-- Amount --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Amount <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="number" name="amount" step="0.01" min="0"
                               class="form-control @error('amount') is-invalid @enderror"
                               value="{{ old('amount', $payment->amount) }}" required>
                        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Payment Date --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                    <input type="date" name="payment_date"
                           class="form-control @error('payment_date') is-invalid @enderror"
                           value="{{ old('payment_date', $payment->payment_date?->format('Y-m-d')) }}" required>
                    @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Method --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                    <select name="method" class="form-select @error('method') is-invalid @enderror" required>
                        @foreach(['cash' => 'Cash', 'credit_card' => 'Credit Card', 'debit_card' => 'Debit Card', 'bank_transfer' => 'Bank Transfer'] as $val => $label)
                            <option value="{{ $val }}" {{ old('method', $payment->method) == $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Status --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach(['pending' => 'Pending', 'completed' => 'Completed', 'failed' => 'Failed', 'refunded' => 'Refunded'] as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $payment->status) == $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Transaction ID --}}
                <div class="col-md-12 mb-3">
                    <label class="form-label">Transaction ID</label>
                    <input type="text" name="transaction_id"
                           class="form-control @error('transaction_id') is-invalid @enderror"
                           value="{{ old('transaction_id', $payment->transaction_id) }}"
                           placeholder="Optional — leave blank to keep existing">
                    @error('transaction_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            </div>

            <div class="mt-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <a href="{{ route('admin.payments.show', $payment->payment_id) }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>

    </div>
</div>
@endsection
