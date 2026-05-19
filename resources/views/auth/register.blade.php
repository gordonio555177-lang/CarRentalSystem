<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Account — Car Rental Management</title>
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

        .brand { position: relative; z-index: 2; }

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

        .steps {
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .step-num {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: rgba(59,130,246,0.2);
            border: 1.5px solid #3b82f6;
            color: #3b82f6;
            font-size: 0.75rem;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .step-text strong {
            display: block;
            color: #e2e8f0;
            font-size: 0.88rem;
            font-weight: 600;
        }

        .step-text span {
            color: #64748b;
            font-size: 0.8rem;
        }

        /* ── RIGHT PANEL ── */
        .right-panel {
            width: 520px;
            flex-shrink: 0;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2.5rem 3rem;
            overflow-y: auto;
        }

        .form-header { margin-bottom: 1.75rem; }

        .form-header .welcome {
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #3b82f6;
            margin-bottom: 0.5rem;
        }

        .form-header h2 {
            font-size: 1.85rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.4rem;
        }

        .form-header p { color: #64748b; font-size: 0.9rem; }

        /* alert */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 0.85rem 1rem;
            color: #dc2626;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* two-column grid */
        .field-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 1rem;
        }

        .field { margin-bottom: 1.1rem; }
        .field.full { grid-column: 1 / -1; }

        .field label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.4rem;
        }

        .input-wrap { position: relative; }

        .input-wrap i.icon-left {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 0.85rem;
            pointer-events: none;
        }

        .input-wrap input,
        .input-wrap textarea {
            width: 100%;
            padding: 0.72rem 1rem 0.72rem 2.65rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.88rem;
            font-family: 'Inter', sans-serif;
            color: #0f172a;
            background: #f8fafc;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            outline: none;
        }

        .input-wrap textarea {
            padding-top: 0.72rem;
            resize: vertical;
            min-height: 80px;
        }

        .input-wrap input:focus,
        .input-wrap textarea:focus {
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
            font-size: 0.85rem;
            transition: color 0.2s;
        }

        .input-wrap .toggle-pw:hover { color: #3b82f6; }

        .field-error {
            color: #dc2626;
            font-size: 0.76rem;
            margin-top: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        /* password strength */
        .pw-strength {
            margin-top: 0.4rem;
            display: flex;
            gap: 4px;
        }

        .pw-bar {
            flex: 1;
            height: 3px;
            border-radius: 2px;
            background: #e2e8f0;
            transition: background 0.3s;
        }

        /* submit */
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
            transition: transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 16px rgba(59,130,246,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            letter-spacing: 0.02em;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(59,130,246,0.45);
        }

        .btn-submit:active { transform: translateY(0); }

        .terms {
            font-size: 0.78rem;
            color: #94a3b8;
            text-align: center;
            margin-top: 0.75rem;
            line-height: 1.5;
        }

        .form-footer {
            text-align: center;
            font-size: 0.875rem;
            color: #64748b;
            margin-top: 1.25rem;
        }

        .form-footer a {
            color: #3b82f6;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .form-footer a:hover { color: #1d4ed8; text-decoration: underline; }

        /* section label */
        .section-label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 0.75rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #f1f5f9;
        }

        @media (max-width: 960px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 2rem 1.5rem; }
            .field-grid { grid-template-columns: 1fr; }
            .field.full { grid-column: 1; }
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

            <h1>Start Your<br><span>Rental</span> Experience</h1>
            <p>Create your free account in minutes and get instant access to our full fleet of premium vehicles.</p>

            <div class="steps">
                <div class="step-item">
                    <div class="step-num">1</div>
                    <div class="step-text">
                        <strong>Create your account</strong>
                        <span>Fill in your personal details below</span>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">2</div>
                    <div class="step-text">
                        <strong>Browse our fleet</strong>
                        <span>Choose from sedans, SUVs, and more</span>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">3</div>
                    <div class="step-text">
                        <strong>Book & drive</strong>
                        <span>Confirm your rental and hit the road</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right-panel">
        <div class="form-header">
            <div class="welcome">Get started</div>
            <h2>Create your account</h2>
            <p>Join thousands of happy customers today</p>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="section-label">Personal Information</div>

            <div class="field-grid">
                <!-- Full Name -->
                <div class="field full">
                    <label for="name">Full Name</label>
                    <div class="input-wrap">
                        <i class="fas fa-user icon-left"></i>
                        <input id="name" type="text" name="name"
                               value="{{ old('name') }}"
                               placeholder="Juan dela Cruz"
                               required autofocus autocomplete="name">
                    </div>
                    @error('name')
                        <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="field">
                    <label for="email">Email Address</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope icon-left"></i>
                        <input id="email" type="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="you@example.com"
                               required autocomplete="username">
                    </div>
                    @error('email')
                        <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="field">
                    <label for="phone">Phone Number</label>
                    <div class="input-wrap">
                        <i class="fas fa-phone icon-left"></i>
                        <input id="phone" type="tel" name="phone"
                               value="{{ old('phone') }}"
                               placeholder="09XX XXX XXXX"
                               required autocomplete="tel">
                    </div>
                    @error('phone')
                        <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Address -->
                <div class="field full">
                    <label for="address">Home Address</label>
                    <div class="input-wrap">
                        <i class="fas fa-location-dot icon-left" style="top:1.1rem;transform:none;"></i>
                        <textarea id="address" name="address"
                                  placeholder="Street, Barangay, City, Province"
                                  required>{{ old('address') }}</textarea>
                    </div>
                    @error('address')
                        <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="section-label" style="margin-top:0.5rem;">Security</div>

            <div class="field-grid">
                <!-- Password -->
                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock icon-left"></i>
                        <input id="password" type="password" name="password"
                               placeholder="Min. 8 characters"
                               required autocomplete="new-password"
                               oninput="checkStrength(this.value)">
                        <span class="toggle-pw" onclick="togglePw('password', this)">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <div class="pw-strength">
                        <div class="pw-bar" id="bar1"></div>
                        <div class="pw-bar" id="bar2"></div>
                        <div class="pw-bar" id="bar3"></div>
                        <div class="pw-bar" id="bar4"></div>
                    </div>
                    @error('password')
                        <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="field">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock icon-left"></i>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                               placeholder="Repeat password"
                               required autocomplete="new-password">
                        <span class="toggle-pw" onclick="togglePw('password_confirmation', this)">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-user-plus"></i> Create Account
            </button>

            <p class="terms">By registering, you agree to our Terms of Service and Privacy Policy.</p>
        </form>

        <div class="form-footer">
            Already have an account? <a href="{{ route('login') }}">Sign in here</a>
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

        function checkStrength(val) {
            const bars   = [1,2,3,4].map(n => document.getElementById('bar' + n));
            const colors = ['#ef4444','#f59e0b','#3b82f6','#10b981'];
            let score = 0;
            if (val.length >= 8)              score++;
            if (/[A-Z]/.test(val))            score++;
            if (/[0-9]/.test(val))            score++;
            if (/[^A-Za-z0-9]/.test(val))     score++;

            bars.forEach((bar, i) => {
                bar.style.background = i < score ? colors[score - 1] : '#e2e8f0';
            });
        }
    </script>
</body>
</html>
