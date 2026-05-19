<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - Car Rental System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden; /* prevent body scroll — only inner columns scroll */
        }

        .layout-wrapper {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar — fixed height, never scrolls with content */
        .sidebar-col {
            width: 220px;
            min-width: 220px;
            flex-shrink: 0;
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
        }

        .sidebar {
            min-height: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .sidebar .nav-link {
            color: #fff;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            transform: translateX(5px);
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }

        /* Main content — this column scrolls */
        .main-col {
            flex: 1;
            height: 100vh;
            overflow-y: auto;
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="layout-wrapper">
        {{-- Sidebar --}}
        <div class="sidebar-col">
            <div class="sidebar">
                <div class="text-center py-4">
                    <i class="fas fa-car fa-3x text-white"></i>
                    <h4 class="text-white mt-2">Car Rental Management</h4>
                    <p class="text-white-50">{{ Auth::guard('staff')->user()->first_name ?? 'Admin' }}</p>
                </div>
                <nav class="nav flex-column px-3">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
                        <i class="fas fa-users"></i> Customers
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.cars.*') ? 'active' : '' }}" href="{{ route('admin.cars.index') }}">
                        <i class="fas fa-car"></i> Cars
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.rentals.*') ? 'active' : '' }}" href="{{ route('admin.rentals.index') }}">
                        <i class="fas fa-calendar-check"></i> Rentals
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.insurances.*') ? 'active' : '' }}" href="{{ route('admin.insurances.index') }}">
                        <i class="fas fa-shield-alt"></i> Insurance
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.maintenances.*') ? 'active' : '' }}" href="{{ route('admin.maintenances.index') }}">
                        <i class="fas fa-wrench"></i> Maintenance
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                        <i class="fas fa-credit-card"></i> Payments
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}" href="{{ route('admin.feedback.index') }}">
                        <i class="fas fa-star"></i> Feedback
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}" href="{{ route('admin.branches.index') }}">
                        <i class="fas fa-store"></i> Branches
                    </a>
                    <hr class="bg-light">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link text-danger" style="background: none; border: none; width: 100%; text-align: left;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="main-col">
            <nav class="navbar navbar-light bg-white shadow-sm px-4 py-2 sticky-top">
                <div class="container-fluid">
                    <span class="navbar-brand mb-0 h4">@yield('title')</span>
                    <div class="d-flex">
                        <span class="text-muted">{{ now()->format('F d, Y') }}</span>
                    </div>
                </div>
            </nav>

            <div class="container-fluid py-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>