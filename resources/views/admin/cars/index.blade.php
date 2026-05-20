@extends('layouts.admin')
@section('title', 'Manage Cars')
@section('content')

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-car me-2" style="color:#2563eb;font-size:.85rem;"></i>All Cars</span>
        <a href="{{ route('admin.cars.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add Car
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Car</th>
                    <th>Category</th>
                    <th>License Plate</th>
                    <th>Daily Rate</th>
                    <th>Mileage</th>
                    <th>Status</th>
                    <th>Branch</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cars as $car)
                <tr>
                    <td style="color:#94a3b8;font-weight:600;">#{{ $car->car_id }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:.65rem;">
                            @if($car->image_url)
                                <img src="{{ $car->image_url }}" alt=""
                                     style="width:44px;height:34px;object-fit:cover;border-radius:8px;flex-shrink:0;">
                            @else
                                <div style="width:44px;height:34px;border-radius:8px;background:#e8f0fe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-car" style="color:#2563eb;font-size:.85rem;"></i>
                                </div>
                            @endif
                            <div>
                                <div style="font-weight:700;color:#0f172a;">{{ $car->brand }} {{ $car->model }}</div>
                                <div style="font-size:.72rem;color:#94a3b8;">{{ $car->year }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $catColors = ['luxury'=>'#fef3c7:#92400e','suv'=>'#dbeafe:#1e40af','economy'=>'#dcfce7:#15803d','standard'=>'#f1f5f9:#475569','van'=>'#ede9fe:#6d28d9'];
                            [$catBg,$catTxt] = explode(':', $catColors[$car->category ?? 'standard'] ?? '#f1f5f9:#475569');
                        @endphp
                        <span style="background:{{ $catBg }};color:{{ $catTxt }};padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;">
                            {{ ucfirst($car->category ?? 'Standard') }}
                        </span>
                    </td>
                    <td>
                        <span style="font-family:monospace;font-size:.8rem;background:#f0f4ff;color:#1e40af;padding:2px 8px;border-radius:6px;">
                            {{ $car->license_plate }}
                        </span>
                    </td>
                    <td style="font-weight:700;color:#0f172a;">₱{{ number_format($car->daily_rate, 2) }}</td>
                    <td style="color:#64748b;">{{ number_format($car->mileage) }} km</td>
                    <td>
                        @if($car->status == 'available')
                            <span class="badge bg-success">Available</span>
                        @elseif($car->status == 'rented')
                            <span class="badge bg-warning text-dark">Rented</span>
                        @elseif($car->status == 'maintenance')
                            <span class="badge bg-danger">Maintenance</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($car->status) }}</span>
                        @endif
                    </td>
                    <td style="color:#64748b;">{{ $car->branch->name ?? 'N/A' }}</td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <a href="{{ route('admin.cars.show', $car->car_id) }}"
                               class="btn btn-sm" style="background:#e0f2fe;color:#0369a1;border:none;" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.cars.edit', $car->car_id) }}"
                               class="btn btn-sm" style="background:#fef3c7;color:#92400e;border:none;" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.cars.destroy', $car->car_id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#991b1b;border:none;" title="Delete"
                                        onclick="return confirm('Delete this car?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5" style="color:#94a3b8;">
                        <i class="fas fa-car fa-2x mb-2 d-block"></i> No cars found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $cars->links() }}
</div>

@endsection
