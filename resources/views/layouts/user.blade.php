{{-- resources/views/layouts/user.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Car Rental System - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ── Palette ──────────────────────────────
           #3E362E  dark charcoal
           #865D36  warm brown (primary)
           #93785B  medium tan
           #AC8968  light caramel
           #A69080  soft greige
        ─────────────────────────────────────── */
        :root {
            --c-dark:    #3E362E;
            --c-brown:   #865D36;
            --c-tan:     #93785B;
            --c-caramel: #AC8968;
            --c-greige:  #A69080;
            --c-cream:   #F5EFE6;
            --c-light:   #FAF6F1;
        }

        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            font-family: 'Inter', sans-serif;
            background: var(--c-light);
            min-height: 100vh;
            color: #2c2218;
        }

        /* ── Navbar ── */
        .navbar-custom {
            background: linear-gradient(135deg, var(--c-dark) 0%, #2a2018 100%);
            box-shadow: 0 2px 16px rgba(62,54,46,.45);
            padding: .75rem 0;
        }

        .navbar-custom .navbar-brand {
            font-weight: 800;
            font-size: 1.05rem;
            color: #fff !important;
            letter-spacing: .01em;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .navbar-custom .navbar-brand .brand-icon {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--c-brown), var(--c-caramel));
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem;
            box-shadow: 0 4px 10px rgba(134,93,54,.4);
        }

        .navbar-custom .nav-link {
            color: rgba(255,255,255,.8) !important;
            font-size: .875rem;
            font-weight: 500;
            padding: .45rem .85rem !important;
            border-radius: 8px;
            transition: background .2s, color .2s;
        }

        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            background: rgba(172,137,104,.2);
            color: #fff !important;
        }

        .navbar-custom .nav-link i { margin-right: 5px; font-size: .8rem; }

        .navbar-custom .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 28px rgba(62,54,46,.18);
            padding: .5rem;
            background: #fff;
        }

        .navbar-custom .dropdown-item {
            border-radius: 8px;
            font-size: .875rem;
            font-weight: 500;
            color: var(--c-dark);
            padding: .5rem .85rem;
            transition: background .15s;
        }

        .navbar-custom .dropdown-item:hover { background: var(--c-cream); color: var(--c-brown); }

        .navbar-custom .dropdown-divider { border-color: #f1ebe3; margin: .35rem .5rem; }

        /* active nav indicator */
        .navbar-custom .nav-item.active > .nav-link {
            background: rgba(172,137,104,.25);
            color: #fff !important;
        }

        /* ── Page wrapper ── */
        .page-wrapper {
            min-height: calc(100vh - 62px);
            background:
                radial-gradient(ellipse at 0% 0%, rgba(172,137,104,.12) 0%, transparent 55%),
                radial-gradient(ellipse at 100% 100%, rgba(134,93,54,.10) 0%, transparent 55%),
                var(--c-light);
            padding: 2rem 0 3rem;
        }

        /* ── Alerts ── */
        .alert-success {
            background: #f0fdf4;
            border: 1.5px solid #bbf7d0;
            border-radius: 12px;
            color: #14532d;
            font-size: .875rem;
        }

        .alert-danger {
            background: #fef2f2;
            border: 1.5px solid #fecaca;
            border-radius: 12px;
            color: #7f1d1d;
            font-size: .875rem;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--c-light); }
        ::-webkit-scrollbar-thumb { background: var(--c-caramel); border-radius: 3px; }

        /* ── Utility ── */
        .text-brown   { color: var(--c-brown) !important; }
        .bg-brown     { background: var(--c-brown) !important; }
        .btn-brown {
            background: linear-gradient(135deg, var(--c-brown), var(--c-tan));
            color: #fff; border: none; border-radius: 10px;
            font-weight: 600; transition: opacity .2s, transform .15s;
            box-shadow: 0 4px 14px rgba(134,93,54,.3);
        }
        .btn-brown:hover { opacity: .9; color: #fff; transform: translateY(-1px); }

        /* ── Footer ── */
        .user-footer {
            background: var(--c-dark);
            color: rgba(255,255,255,.5);
            text-align: center;
            padding: 1rem;
            font-size: .78rem;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('user.dashboard') }}">
                <div class="brand-icon"><i class="fas fa-car"></i></div>
                CarRental<span style="color:var(--c-caramel);margin-left:2px;">System</span>
            </a>

            <button class="navbar-toggler border-0" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    style="color:#fff;">
                <i class="fas fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                    <li class="nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('user.dashboard') }}">
                            <i class="fas fa-gauge-high"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('user.cars.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('user.cars.index') }}">
                            <i class="fas fa-car"></i> Browse Cars
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('user.rentals.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('user.rentals.index') }}">
                            <i class="fas fa-calendar-check"></i> My Rentals
                        </a>
                    </li>
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
                            <span style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--c-brown),var(--c-caramel));display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;color:#fff;flex-shrink:0;">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </span>
                            <span style="max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                {{ auth()->user()->customer->full_name ?? auth()->user()->name ?? 'Customer' }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <div style="padding:.5rem .85rem .25rem; font-size:.75rem; color:var(--c-tan); font-weight:600; text-transform:uppercase; letter-spacing:.06em;">
                                    My Account
                                </div>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-edit me-2" style="color:var(--c-caramel);width:16px;"></i> Edit Profile
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('user.rentals.index') }}">
                                <i class="fas fa-history me-2" style="color:var(--c-caramel);width:16px;"></i> My Rentals
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="color:#dc2626;">
                                        <i class="fas fa-right-from-bracket me-2" style="width:16px;"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="page-wrapper">
        <div class="container">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <div class="user-footer">
        &copy; {{ date('Y') }} Car Rental Management System. All rights reserved.
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
