<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In — Car Rental Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0f172a;
        }

        /* ── LEFT PANEL ── */
        .left-panel {
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3rem;
            overflow: hidden;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
        }

        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                url('https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1400&q=80')
                center/cover no-repeat;
            opacity: 0.18;
        }

        /* animated gradient orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.35;
            animation: drift 8s ease-in-out infinite alternate;
        }
        .orb-1 { width: 420px; height: 420px; background: #3b82f6; top: -100px; left: -100px; animation-delay: 0s; }
        .orb-2 { width: 300px; height: 300px; background: #f59e0b; bottom: 80px; right: -60px; animation-delay: 3s; }
        .orb-3 { width: 200px; height: 200px; background: #10b981; top: 40%; left: 40%; animation-delay: 1.5s; }

        @keyframes drift {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(30px, 20px) scale(1.08); }
        }

        .brand {
            position: relative;
            z-index: 2;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2.5rem;
        }

        .brand-logo .icon-wrap {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 24px rgba(59,130,246,0.4);
        }

        .brand-logo .icon-wrap i { color: #fff; font-size: 1.4rem; }

        .brand-logo span {
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
        }

        .brand-logo span small {
            display: block;
            font-size: 0.7rem;
            font-weight: 400;
            color: #94a3b8;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .brand h1 {
            font-size: 2.8rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 1rem;
        }

        .brand h1 span { color: #3b82f6; }

        .brand p {
            color: #94a3b8;
            font-size: 1rem;
            line-height: 1.7;
            max-width: 380px;
            margin-bottom: 2.5rem;
        }

        .features {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #cbd5e1;
            font-size: 0.9rem;
        }

        .feature-item .dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #3b82f6;
            flex-shrink: 0;
        }

        /* ── RIGHT PANEL ── */
        .right-panel {
            width: 480px;
            flex-shrink: 0;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 3.5rem;
            overflow-y: auto;
        }

        .form-header { margin-bottom: 2.25rem; }

        .form-header .welcome {
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #3b82f6;
            margin-bottom: 0.5rem;
        }

        .form-header h2 {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.4rem;
        }

        .form-header p {
            color: #64748b;
            font-size: 0.9rem;
        }

        /* alert */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 0.85rem 1rem;
            color: #dc2626;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 0.85rem 1rem;
            color: #16a34a;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
        }

        /* form fields */
        .field { margin-bottom: 1.25rem; }

        .field label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.45rem;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 0.9rem;
            pointer-events: none;
        }

        .input-wrap input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            color: #0f172a;
            background: #f8fafc;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            outline: none;
        }

        .input-wrap input:focus {
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }

        .input-wrap .toggle-pw {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            cursor: pointer;
            font-size: 0.9rem;
            pointer-events: all;
            transition: color 0.2s;
        }

        .input-wrap .toggle-pw:hover { color: #3b82f6; }

        .field-error {
            color: #dc2626;
            font-size: 0.78rem;
            margin-top: 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        /* row */
        .field-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #475569;
            cursor: pointer;
        }

        .checkbox-label input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #3b82f6;
            cursor: pointer;
        }

        .forgot-link {
            font-size: 0.85rem;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .forgot-link:hover { color: #1d4ed8; text-decoration: underline; }

        /* submit button */
        .btn-submit {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.2s, opacity 0.2s;
            box-shadow: 0 4px 16px rgba(59,130,246,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            letter-spacing: 0.02em;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(59,130,246,0.45);
        }

        .btn-submit:active { transform: translateY(0); }

        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
            color: #cbd5e1;
            font-size: 0.8rem;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .form-footer {
            text-align: center;
            font-size: 0.875rem;
            color: #64748b;
            margin-top: 1.5rem;
        }

        .form-footer a {
            color: #3b82f6;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .form-footer a:hover { color: #1d4ed8; text-decoration: underline; }

        /* responsive */
        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 2.5rem 2rem; }
        }
    </style>
</head>
<body>

    <!-- LEFT PANEL -->
    <div class="left-panel">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>

        <div class="brand">
            <div class="brand-logo">
                <div class="icon-wrap">
                    <i class="fas fa-car"></i>
                </div>
                <span>
                    Car Rental
                    <small>Management System</small>
                </span>
            </div>

            <h1>Drive Your<br><span>Journey</span> Forward</h1>
            <p>Access your account to manage bookings, track rentals, and explore our premium fleet — all in one place.</p>

            <div class="features">
                <div class="feature-item"><span class="dot"></span> Browse & book from our premium vehicle fleet</div>
                <div class="feature-item"><span class="dot"></span> Real-time rental tracking and status updates</div>
                <div class="feature-item"><span class="dot"></span> Flexible insurance options for every trip</div>
                <div class="feature-item"><span class="dot"></span> Secure payments and instant invoices</div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right-panel">
        <div class="form-header">
            <div class="welcome">Welcome back</div>
            <h2>Sign in to your account</h2>
            <p>Enter your credentials to continue</p>
        </div>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> {{ session('status') }}
            </div>
        @endif

        {{-- Validation errors summary --}}
        @if ($errors->any())
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="field">
                <label for="email">Email Address</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope"></i>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="you@example.com"
                           required autofocus autocomplete="username">
                </div>
                @error('email')
                    <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input id="password" type="password" name="password"
                           placeholder="••••••••"
                           required autocomplete="current-password">
                    <span class="toggle-pw" onclick="togglePw('password', this)">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                @error('password')
                    <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- Remember + Forgot -->
            <div class="field-row">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" id="remember_me">
                    Remember me
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-right-to-bracket"></i> Sign In
            </button>
        </form>

        <div class="form-footer">
            Don't have an account? <a href="{{ route('register') }}">Create one free</a>
        </div>
    </div>

    <script>
        function togglePw(id, el) {
            const input = document.getElementById(id);
            const icon  = el.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>
