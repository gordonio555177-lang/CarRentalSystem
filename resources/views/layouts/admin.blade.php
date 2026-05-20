<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — @yield('title', 'Car Rental System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ══════════════════════════════════════════
           NAVY BLUE PALETTE
           #0a1628  deepest navy  (sidebar bg)
           #0f2044  dark navy     (sidebar hover bg)
           #1a3a6b  medium navy   (accents)
           #2563eb  bright blue   (primary action)
           #1e40af  deep blue     (hover)
           #e8f0fe  light blue    (table header bg)
           #f0f4ff  page bg
        ══════════════════════════════════════════ */
        :root {
            --navy-deep:   #0a1628;
            --navy-dark:   #0f2044;
            --navy-mid:    #1a3a6b;
            --navy-accent: #2563eb;
            --navy-hover:  #1e40af;
            --navy-light:  #e8f0fe;
            --page-bg:     #f0f4ff;
            --card-bg:     #ffffff;
            --text-main:   #0f172a;
            --text-muted:  #64748b;
            --border:      #e2e8f0;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            background: var(--page-bg);
            color: var(--text-main);
            overflow: hidden;
        }

        /* ── Layout ── */
        .layout-wrapper { display: flex; height: 100vh; overflow: hidden; }

        /* ══════════════════════════════════════════
           SIDEBAR
        ══════════════════════════════════════════ */
        .sidebar-col {
            width: 240px;
            min-width: 240px;
            flex-shrink: 0;
            height: 100vh;
            position: sticky;
            top: 0;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,.15) transparent;
        }
        .sidebar-col::-webkit-scrollbar { width: 4px; }
        .sidebar-col::-webkit-scrollbar-thumb { background: rgba(255,255,255,.15); border-radius: 2px; }

        .sidebar {
            min-height: 100%;
            background: linear-gradient(180deg, var(--navy-deep) 0%, #0d1f3c 100%);
            display: flex;
            flex-direction: column;
        }

        /* Brand */
        .sidebar-brand {
            padding: 1.5rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-brand .brand-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--navy-accent), #3b82f6);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: #fff;
            box-shadow: 0 4px 14px rgba(37,99,235,.4);
            margin-bottom: .75rem;
        }
        .sidebar-brand h5 {
            font-size: .9rem; font-weight: 800; color: #fff;
            line-height: 1.2; margin: 0;
        }
        .sidebar-brand small {
            font-size: .7rem; color: rgba(255,255,255,.45);
            text-transform: uppercase; letter-spacing: .08em;
        }

        /* Admin badge */
        .admin-badge {
            margin: .75rem 1.25rem;
            background: rgba(37,99,235,.15);
            border: 1px solid rgba(37,99,235,.25);
            border-radius: 10px;
            padding: .6rem .85rem;
            display: flex; align-items: center; gap: .6rem;
        }
        .admin-badge .avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--navy-accent), #60a5fa);
            display: flex; align-items: center; justify-content: center;
            font-size: .75rem; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .admin-badge .info .name { font-size: .8rem; font-weight: 600; color: #fff; }
        .admin-badge .info .role { font-size: .68rem; color: rgba(255,255,255,.45); }

        /* Nav section label */
        .nav-section {
            padding: .85rem 1.25rem .35rem;
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(255,255,255,.3);
        }

        /* Nav links */
        .sidebar nav { padding: 0 .75rem; flex: 1; }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: .65rem;
            color: rgba(255,255,255,.65);
            padding: .65rem .85rem;
            margin-bottom: 2px;
            border-radius: 10px;
            font-size: .85rem;
            font-weight: 500;
            text-decoration: none;
            transition: background .2s, color .2s, transform .15s;
            position: relative;
        }
        .sidebar .nav-link i {
            width: 18px;
            text-align: center;
            font-size: .85rem;
            flex-shrink: 0;
        }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,.08);
            color: #fff;
            transform: translateX(3px);
        }
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--navy-accent), #3b82f6);
            color: #fff;
            box-shadow: 0 4px 14px rgba(37,99,235,.35);
        }
        .sidebar .nav-link.active i { color: #fff; }

        /* Sidebar footer */
        .sidebar-footer {
            padding: .75rem;
            border-top: 1px solid rgba(255,255,255,.07);
            margin-top: auto;
        }
        .sidebar-footer .logout-btn {
            display: flex; align-items: center; gap: .65rem;
            width: 100%; padding: .65rem .85rem;
            border-radius: 10px; border: none;
            background: rgba(239,68,68,.1);
            color: #fca5a5;
            font-size: .85rem; font-weight: 500;
            cursor: pointer;
            transition: background .2s;
            text-align: left;
        }
        .sidebar-footer .logout-btn:hover { background: rgba(239,68,68,.2); color: #f87171; }

        /* ══════════════════════════════════════════
           MAIN CONTENT
        ══════════════════════════════════════════ */
        .main-col {
            flex: 1;
            height: 100vh;
            overflow-y: auto;
            background: var(--page-bg);
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }

        /* Top bar */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(240,244,255,.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: .85rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .topbar .page-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--navy-deep);
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .topbar .page-title::before {
            content: '';
            width: 4px; height: 20px;
            background: linear-gradient(180deg, var(--navy-accent), #60a5fa);
            border-radius: 2px;
        }
        .topbar .topbar-right {
            display: flex; align-items: center; gap: 1rem;
        }
        .topbar .date-chip {
            background: var(--navy-light);
            color: var(--navy-mid);
            padding: .35rem .85rem;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 600;
        }

        /* Content area */
        .content-area { padding: 1.5rem 1.75rem 2.5rem; }

        /* ══════════════════════════════════════════
           CARDS
        ══════════════════════════════════════════ */
        .card {
            border: none !important;
            border-radius: 16px !important;
            box-shadow: 0 2px 16px rgba(10,22,40,.07) !important;
            background: var(--card-bg) !important;
            overflow: hidden;
        }
        .card-header {
            background: var(--card-bg) !important;
            border-bottom: 1px solid var(--border) !important;
            padding: 1.1rem 1.5rem !important;
            font-weight: 700 !important;
            color: var(--navy-deep) !important;
        }
        .card-body { padding: 1.25rem 1.5rem !important; }

        /* ══════════════════════════════════════════
           TABLES
        ══════════════════════════════════════════ */
        .table {
            margin: 0 !important;
            font-size: .875rem;
            color: var(--text-main);
        }
        .table thead th {
            background: var(--navy-light) !important;
            color: var(--navy-mid) !important;
            font-size: .72rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: .07em !important;
            padding: .85rem 1.1rem !important;
            border-bottom: 2px solid #c7d7f8 !important;
            white-space: nowrap;
        }
        .table tbody td {
            padding: .85rem 1.1rem !important;
            vertical-align: middle !important;
            border-bottom: 1px solid #f1f5f9 !important;
            color: var(--text-main);
        }
        .table tbody tr:last-child td { border-bottom: none !important; }
        .table-hover tbody tr:hover td {
            background: #f8faff !important;
        }

        /* ══════════════════════════════════════════
           BADGES
        ══════════════════════════════════════════ */
        .badge {
            font-size: .72rem !important;
            font-weight: 700 !important;
            padding: .35em .75em !important;
            border-radius: 20px !important;
        }

        /* ══════════════════════════════════════════
           BUTTONS
        ══════════════════════════════════════════ */
        .btn {
            font-size: .82rem !important;
            font-weight: 600 !important;
            border-radius: 9px !important;
            transition: all .15s !important;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--navy-accent), #3b82f6) !important;
            border: none !important;
            box-shadow: 0 3px 10px rgba(37,99,235,.3) !important;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--navy-hover), var(--navy-accent)) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 5px 16px rgba(37,99,235,.4) !important;
        }
        .btn-sm { padding: .35rem .75rem !important; font-size: .78rem !important; }

        /* ══════════════════════════════════════════
           ALERTS
        ══════════════════════════════════════════ */
        .alert {
            border: none !important;
            border-radius: 12px !important;
            font-size: .875rem !important;
        }
        .alert-success {
            background: #f0fdf4 !important;
            color: #14532d !important;
            border-left: 4px solid #22c55e !important;
        }
        .alert-danger {
            background: #fef2f2 !important;
            color: #7f1d1d !important;
            border-left: 4px solid #ef4444 !important;
        }
        .alert-warning {
            background: #fffbeb !important;
            color: #78350f !important;
            border-left: 4px solid #f59e0b !important;
        }

        /* ══════════════════════════════════════════
           PAGINATION FIX
           Override Bootstrap's default pagination
           so it renders as a clean pill row
        ══════════════════════════════════════════ */
        .pagination {
            display: flex !important;
            align-items: center !important;
            gap: 4px !important;
            flex-wrap: wrap !important;
            margin: 0 !important;
            padding: 0 !important;
            list-style: none !important;
        }
        .pagination .page-item .page-link {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 36px !important;
            height: 36px !important;
            padding: 0 .65rem !important;
            border-radius: 9px !important;
            border: 1.5px solid var(--border) !important;
            background: #fff !important;
            color: var(--navy-mid) !important;
            font-size: .82rem !important;
            font-weight: 600 !important;
            text-decoration: none !important;
            transition: all .15s !important;
            line-height: 1 !important;
        }
        .pagination .page-item .page-link:hover {
            background: var(--navy-light) !important;
            border-color: var(--navy-accent) !important;
            color: var(--navy-accent) !important;
        }
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--navy-accent), #3b82f6) !important;
            border-color: var(--navy-accent) !important;
            color: #fff !important;
            box-shadow: 0 3px 10px rgba(37,99,235,.3) !important;
        }
        .pagination .page-item.disabled .page-link {
            background: #f8fafc !important;
            color: #cbd5e1 !important;
            border-color: #f1f5f9 !important;
            cursor: not-allowed !important;
        }
        /* Hide the raw text arrows that Bootstrap renders */
        .pagination .page-item:first-child .page-link span[aria-hidden],
        .pagination .page-item:last-child .page-link span[aria-hidden] {
            font-size: .75rem !important;
        }

        /* Pagination wrapper */
        .pagination-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .85rem 1.5rem;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
            gap: .5rem;
        }
        .pagination-info {
            font-size: .8rem;
            color: var(--text-muted);
        }

        /* ══════════════════════════════════════════
           FORMS
        ══════════════════════════════════════════ */
        .form-control, .form-select {
            border: 1.5px solid var(--border) !important;
            border-radius: 10px !important;
            font-size: .875rem !important;
            padding: .6rem .9rem !important;
            transition: border-color .2s, box-shadow .2s !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--navy-accent) !important;
            box-shadow: 0 0 0 3px rgba(37,99,235,.12) !important;
        }
        .form-label {
            font-size: .82rem !important;
            font-weight: 600 !important;
            color: var(--text-main) !important;
            margin-bottom: .4rem !important;
        }

        /* ══════════════════════════════════════════
           STAT CARDS (dashboard)
        ══════════════════════════════════════════ */
        .stat-card {
            border-radius: 16px !important;
            border: none !important;
            padding: 1.4rem !important;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(10,22,40,.08) !important;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(10,22,40,.12) !important; }
        .stat-card .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            margin-bottom: .85rem;
        }
        .stat-card .stat-val { font-size: 1.9rem; font-weight: 800; line-height: 1; margin-bottom: .2rem; }
        .stat-card .stat-lbl { font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; opacity: .65; }
        .stat-card .stat-bg { position: absolute; right: -16px; bottom: -16px; font-size: 5.5rem; opacity: .06; }

        .stat-blue   { background: linear-gradient(135deg,#eff6ff,#dbeafe); color: #1e40af; }
        .stat-green  { background: linear-gradient(135deg,#f0fdf4,#dcfce7); color: #15803d; }
        .stat-amber  { background: linear-gradient(135deg,#fffbeb,#fef3c7); color: #b45309; }
        .stat-purple { background: linear-gradient(135deg,#faf5ff,#ede9fe); color: #6d28d9; }
        .stat-teal   { background: linear-gradient(135deg,#f0fdfa,#ccfbf1); color: #0f766e; }
        .stat-rose   { background: linear-gradient(135deg,#fff1f2,#ffe4e6); color: #be123c; }

        .stat-blue   .stat-icon { background: #dbeafe; color: #2563eb; }
        .stat-green  .stat-icon { background: #dcfce7; color: #16a34a; }
        .stat-amber  .stat-icon { background: #fef3c7; color: #d97706; }
        .stat-purple .stat-icon { background: #ede9fe; color: #7c3aed; }
        .stat-teal   .stat-icon { background: #ccfbf1; color: #0d9488; }
        .stat-rose   .stat-icon { background: #ffe4e6; color: #e11d48; }

        /* ══════════════════════════════════════════
           SCROLLBAR (main col)
        ══════════════════════════════════════════ */
        .main-col::-webkit-scrollbar { width: 5px; }
        .main-col::-webkit-scrollbar-track { background: transparent; }
        .main-col::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    </style>
</head>
<body>
<div class="layout-wrapper">

    {{-- ══ SIDEBAR ══ --}}
    <div class="sidebar-col">
        <div class="sidebar">

            {{-- Brand --}}
            <div class="sidebar-brand">
                <div class="brand-icon"><i class="fas fa-car"></i></div>
                <h5>Car Rental</h5>
                <small>Management System</small>
            </div>

            {{-- Admin info --}}
            <div class="admin-badge">
                <div class="avatar">
                    {{ strtoupper(substr(Auth::guard('staff')->user()->first_name ?? 'A', 0, 1)) }}
                </div>
                <div class="info">
                    <div class="name">{{ Auth::guard('staff')->user()->first_name ?? 'Admin' }}</div>
                    <div class="role">Administrator</div>
                </div>
            </div>

            {{-- Navigation --}}
            <div class="nav-section">Main Menu</div>
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                   href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-gauge-high"></i> Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}"
                   href="{{ route('admin.customers.index') }}">
                    <i class="fas fa-users"></i> Customers
                </a>
                <a class="nav-link {{ request()->routeIs('admin.cars.*') ? 'active' : '' }}"
                   href="{{ route('admin.cars.index') }}">
                    <i class="fas fa-car"></i> Cars
                </a>
                <a class="nav-link {{ request()->routeIs('admin.rentals.*') ? 'active' : '' }}"
                   href="{{ route('admin.rentals.index') }}">
                    <i class="fas fa-calendar-check"></i> Rentals
                </a>

                <div class="nav-section" style="padding-top:.6rem;">Management</div>

                <a class="nav-link {{ request()->routeIs('admin.insurances.*') ? 'active' : '' }}"
                   href="{{ route('admin.insurances.index') }}">
                    <i class="fas fa-shield-alt"></i> Insurance
                </a>
                <a class="nav-link {{ request()->routeIs('admin.maintenances.*') ? 'active' : '' }}"
                   href="{{ route('admin.maintenances.index') }}">
                    <i class="fas fa-wrench"></i> Maintenance
                </a>
                <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}"
                   href="{{ route('admin.payments.index') }}">
                    <i class="fas fa-credit-card"></i> Payments
                </a>
                <a class="nav-link {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}"
                   href="{{ route('admin.feedback.index') }}">
                    <i class="fas fa-star"></i> Feedback
                </a>
                <a class="nav-link {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}"
                   href="{{ route('admin.branches.index') }}">
                    <i class="fas fa-store"></i> Branches
                </a>
            </nav>

            {{-- Logout --}}
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>

        </div>
    </div>

    {{-- ══ MAIN CONTENT ══ --}}
    <div class="main-col">

        {{-- Top bar --}}
        <div class="topbar">
            <div class="page-title">@yield('title', 'Dashboard')</div>
            <div class="topbar-right">
                <span class="date-chip">
                    <i class="fas fa-calendar-alt me-1"></i>
                    {{ now()->format('F d, Y') }}
                </span>
            </div>
        </div>

        {{-- Content --}}
        <div class="content-area">

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

</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
