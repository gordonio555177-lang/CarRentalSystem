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

        html, body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ══════════════════════════════════════
           TOP SECTION  (left panel + right form)
        ══════════════════════════════════════ */
        .top-section {
            display: flex;
            flex: 1;
            min-height: 100vh;
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
            background: url('https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1400&q=80') center/cover no-repeat;
            opacity: 0.18;
        }

        .orb { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.35; animation: drift 8s ease-in-out infinite alternate; }
        .orb-1 { width: 420px; height: 420px; background: #3b82f6; top: -100px; left: -100px; animation-delay: 0s; }
        .orb-2 { width: 300px; height: 300px; background: #f59e0b; bottom: 80px; right: -60px; animation-delay: 3s; }
        .orb-3 { width: 200px; height: 200px; background: #10b981; top: 40%; left: 40%; animation-delay: 1.5s; }

        @keyframes drift {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(30px,20px) scale(1.08); }
        }

        .brand { position: relative; z-index: 2; }

        .brand-logo { display: flex; align-items: center; gap: .75rem; margin-bottom: 2.5rem; }
        .brand-logo .icon-wrap {
            width: 52px; height: 52px;
            background: linear-gradient(135deg,#3b82f6,#1d4ed8);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 24px rgba(59,130,246,.4);
        }
        .brand-logo .icon-wrap i { color:#fff; font-size:1.4rem; }
        .brand-logo span { font-size:1.1rem; font-weight:700; color:#fff; line-height:1.2; }
        .brand-logo span small { display:block; font-size:.7rem; font-weight:400; color:#94a3b8; letter-spacing:.08em; text-transform:uppercase; }

        .brand h1 { font-size:2.8rem; font-weight:800; color:#fff; line-height:1.15; margin-bottom:1rem; }
        .brand h1 span { color:#3b82f6; }
        .brand p { color:#94a3b8; font-size:1rem; line-height:1.7; max-width:380px; margin-bottom:2.5rem; }

        .features { display:flex; flex-direction:column; gap:.85rem; }
        .feature-item { display:flex; align-items:center; gap:.75rem; color:#cbd5e1; font-size:.9rem; }
        .feature-item .dot { width:8px; height:8px; border-radius:50%; background:#3b82f6; flex-shrink:0; }

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
        .form-header .welcome { font-size:.8rem; font-weight:600; letter-spacing:.12em; text-transform:uppercase; color:#3b82f6; margin-bottom:.5rem; }
        .form-header h2 { font-size:2rem; font-weight:800; color:#0f172a; margin-bottom:.4rem; }
        .form-header p { color:#64748b; font-size:.9rem; }

        .alert-error { background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:.85rem 1rem; color:#dc2626; font-size:.85rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:.5rem; }
        .alert-success { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:.85rem 1rem; color:#16a34a; font-size:.85rem; margin-bottom:1.5rem; }

        .field { margin-bottom: 1.25rem; }
        .field label { display:block; font-size:.82rem; font-weight:600; color:#374151; margin-bottom:.45rem; }
        .input-wrap { position:relative; }
        .input-wrap i { position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:.9rem; pointer-events:none; }
        .input-wrap input { width:100%; padding:.75rem 1rem .75rem 2.75rem; border:1.5px solid #e2e8f0; border-radius:10px; font-size:.9rem; font-family:'Inter',sans-serif; color:#0f172a; background:#f8fafc; transition:border-color .2s,box-shadow .2s,background .2s; outline:none; }
        .input-wrap input:focus { border-color:#3b82f6; background:#fff; box-shadow:0 0 0 3px rgba(59,130,246,.12); }
        .input-wrap .toggle-pw { position:absolute; right:1rem; top:50%; transform:translateY(-50%); color:#9ca3af; cursor:pointer; font-size:.9rem; transition:color .2s; }
        .input-wrap .toggle-pw:hover { color:#3b82f6; }

        .field-error { color:#dc2626; font-size:.78rem; margin-top:.35rem; display:flex; align-items:center; gap:.3rem; }

        .field-row { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; }
        .checkbox-label { display:flex; align-items:center; gap:.5rem; font-size:.85rem; color:#475569; cursor:pointer; }
        .checkbox-label input[type="checkbox"] { width:16px; height:16px; accent-color:#3b82f6; cursor:pointer; }
        .forgot-link { font-size:.85rem; color:#3b82f6; text-decoration:none; font-weight:500; transition:color .2s; }
        .forgot-link:hover { color:#1d4ed8; text-decoration:underline; }

        .btn-submit { width:100%; padding:.85rem; background:linear-gradient(135deg,#3b82f6,#1d4ed8); color:#fff; border:none; border-radius:10px; font-size:.95rem; font-weight:700; font-family:'Inter',sans-serif; cursor:pointer; transition:transform .15s,box-shadow .2s; box-shadow:0 4px 16px rgba(59,130,246,.35); display:flex; align-items:center; justify-content:center; gap:.5rem; letter-spacing:.02em; }
        .btn-submit:hover { transform:translateY(-1px); box-shadow:0 8px 24px rgba(59,130,246,.45); }
        .btn-submit:active { transform:translateY(0); }

        .form-footer { text-align:center; font-size:.875rem; color:#64748b; margin-top:1.5rem; }
        .form-footer a { color:#3b82f6; font-weight:600; text-decoration:none; transition:color .2s; }
        .form-footer a:hover { color:#1d4ed8; text-decoration:underline; }

        /* ══════════════════════════════════════
           TESTIMONIALS SECTION
        ══════════════════════════════════════ */
        .testimonials-section {
            background: #0f172a;
            padding: 4rem 2rem 3rem;
            position: relative;
            overflow: hidden;
        }

        .testimonials-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        }

        .testimonials-inner { position: relative; z-index: 1; max-width: 1200px; margin: 0 auto; }

        .testimonials-header { text-align: center; margin-bottom: 2.5rem; }
        .testimonials-header .label {
            font-size: .75rem; font-weight: 700; letter-spacing: .15em;
            text-transform: uppercase; color: #3b82f6; margin-bottom: .5rem;
        }
        .testimonials-header h2 { font-size: 1.8rem; font-weight: 800; color: #fff; margin-bottom: .5rem; }
        .testimonials-header p { color: #64748b; font-size: .9rem; }

        /* carousel track */
        .carousel-outer { overflow: hidden; position: relative; }

        .carousel-track {
            display: flex;
            gap: 1.25rem;
            transition: transform .5s cubic-bezier(.4,0,.2,1);
            will-change: transform;
        }

        .review-card {
            flex: 0 0 calc(33.333% - 0.84rem);
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: .75rem;
            transition: transform .2s, border-color .2s;
        }

        .review-card:hover { transform: translateY(-4px); border-color: rgba(59,130,246,.35); }

        .review-card .stars { display: flex; gap: 3px; }
        .review-card .stars i { font-size: .8rem; color: #f59e0b; }
        .review-card .stars i.empty { color: #334155; }

        .review-card .comment {
            color: #cbd5e1;
            font-size: .88rem;
            line-height: 1.65;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .review-card .reviewer {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding-top: .75rem;
            border-top: 1px solid rgba(255,255,255,.06);
        }

        .reviewer-avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }

        .reviewer-info .name { font-size: .85rem; font-weight: 600; color: #e2e8f0; }
        .reviewer-info .car  { font-size: .75rem; color: #64748b; margin-top: 1px; }

        /* carousel controls */
        .carousel-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .carousel-btn {
            width: 40px; height: 40px;
            border-radius: 50%;
            border: 1.5px solid rgba(255,255,255,.15);
            background: rgba(255,255,255,.05);
            color: #94a3b8;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem;
            transition: all .2s;
        }

        .carousel-btn:hover { background: #3b82f6; border-color: #3b82f6; color: #fff; }

        .carousel-dots { display: flex; gap: 6px; }
        .carousel-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: rgba(255,255,255,.2);
            cursor: pointer;
            transition: background .2s, transform .2s;
        }
        .carousel-dot.active { background: #3b82f6; transform: scale(1.3); }

        /* empty state */
        .no-reviews {
            text-align: center;
            padding: 3rem;
            color: #475569;
        }
        .no-reviews i { font-size: 2.5rem; margin-bottom: 1rem; color: #1e293b; }
        .no-reviews p { font-size: .9rem; }

        /* footer bar */
        .footer-bar {
            background: #0a0f1e;
            padding: .75rem 2rem;
            text-align: center;
            font-size: .75rem;
            color: #334155;
        }

        /* responsive */
        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 2.5rem 2rem; }
            .review-card { flex: 0 0 calc(100% - 0rem); }
        }

        @media (min-width: 901px) and (max-width: 1100px) {
            .review-card { flex: 0 0 calc(50% - 0.625rem); }
        }
    </style>
</head>
<body>

    <!-- ══ TOP: LEFT PANEL + LOGIN FORM ══ -->
    <div class="top-section">

        <!-- LEFT PANEL -->
        <div class="left-panel">
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>

            <div class="brand">
                <div class="brand-logo">
                    <div class="icon-wrap"><i class="fas fa-car"></i></div>
                    <span>Car Rental <small>Management System</small></span>
                </div>
                <h1>Drive Your<br><span>Journey</span> Forward</h1>
                <p>Access your account to manage bookings, track rentals, and explore our premium fleet — all in one place.</p>
                <div class="features">
                    <div class="feature-item"><span class="dot"></span> Browse &amp; book from our premium vehicle fleet</div>
                    <div class="feature-item"><span class="dot"></span> Real-time rental tracking and status updates</div>
                    <div class="feature-item"><span class="dot"></span> Flexible insurance options for every trip</div>
                    <div class="feature-item"><span class="dot"></span> Secure payments and instant invoices</div>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL: LOGIN FORM -->
        <div class="right-panel">
            <div class="form-header">
                <div class="welcome">Welcome back</div>
                <h2>Sign in to your account</h2>
                <p>Enter your credentials to continue</p>
            </div>

            @if (session('status'))
                <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label for="email">Email Address</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               placeholder="you@example.com" required autofocus autocomplete="username">
                    </div>
                    @error('email')
                        <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-lock"></i>
                        <input id="password" type="password" name="password"
                               placeholder="••••••••" required autocomplete="current-password">
                        <span class="toggle-pw" onclick="togglePw('password', this)">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field-row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" id="remember_me"> Remember me
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
    </div>

    <!-- ══ BOTTOM: CUSTOMER TESTIMONIALS ══ -->
    <div class="testimonials-section">
        <div class="testimonials-inner">

            <div class="testimonials-header">
                <div class="label"><i class="fas fa-star me-1"></i> Customer Reviews</div>
                <h2>What Our Customers Say</h2>
                <p>Real feedback from people who've rented with us</p>
            </div>

            @if(isset($feedbacks) && $feedbacks->count() > 0)

                <div class="carousel-outer">
                    <div class="carousel-track" id="carouselTrack">
                        @foreach($feedbacks as $fb)
                        <div class="review-card">
                            {{-- Stars --}}
                            <div class="stars">
                                @for($s = 1; $s <= 5; $s++)
                                    <i class="fas fa-star{{ $s <= $fb->rating ? '' : ' empty' }}"></i>
                                @endfor
                            </div>

                            {{-- Comment --}}
                            <p class="comment">"{{ $fb->comment }}"</p>

                            {{-- Reviewer --}}
                            <div class="reviewer">
                                <div class="reviewer-avatar">
                                    {{ strtoupper(substr($fb->customer->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="reviewer-info">
                                    <div class="name">{{ $fb->customer->customer->full_name ?? $fb->customer->name ?? 'Customer' }}</div>
                                    <div class="car">
                                        <i class="fas fa-car" style="font-size:.65rem; margin-right:3px;"></i>
                                        {{ $fb->car->brand ?? '' }} {{ $fb->car->model ?? 'Vehicle' }}
                                        &bull; {{ $fb->review_date ? $fb->review_date->format('M Y') : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="carousel-controls">
                    <button class="carousel-btn" id="prevBtn" onclick="moveCarousel(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="carousel-dots" id="carouselDots"></div>
                    <button class="carousel-btn" id="nextBtn" onclick="moveCarousel(1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

            @else
                <div class="no-reviews">
                    <i class="fas fa-comment-slash d-block"></i>
                    <p>No customer reviews yet. Be the first to share your experience!</p>
                </div>
            @endif

        </div>
    </div>

    <!-- Footer bar -->
    <div class="footer-bar">
        &copy; {{ date('Y') }} Car Rental Management System. All rights reserved.
    </div>

    <script>
        /* ── Password toggle ── */
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

        /* ── Testimonials carousel ── */
        (function () {
            const track  = document.getElementById('carouselTrack');
            const dotsEl = document.getElementById('carouselDots');
            if (!track) return;

            const cards      = track.querySelectorAll('.review-card');
            const total      = cards.length;
            let   current    = 0;
            let   perPage    = getPerPage();
            let   pages      = Math.ceil(total / perPage);
            let   autoTimer  = null;

            function getPerPage() {
                if (window.innerWidth <= 900)  return 1;
                if (window.innerWidth <= 1100) return 2;
                return 3;
            }

            function buildDots() {
                dotsEl.innerHTML = '';
                pages = Math.ceil(total / perPage);
                for (let i = 0; i < pages; i++) {
                    const d = document.createElement('div');
                    d.className = 'carousel-dot' + (i === current ? ' active' : '');
                    d.onclick = () => goTo(i);
                    dotsEl.appendChild(d);
                }
            }

            function goTo(page) {
                current = Math.max(0, Math.min(page, pages - 1));
                const cardWidth = cards[0].offsetWidth + 20; // gap = 1.25rem ≈ 20px
                track.style.transform = `translateX(-${current * perPage * cardWidth}px)`;
                dotsEl.querySelectorAll('.carousel-dot').forEach((d, i) => {
                    d.classList.toggle('active', i === current);
                });
            }

            window.moveCarousel = function (dir) {
                resetAuto();
                goTo(current + dir);
            };

            function resetAuto() {
                clearInterval(autoTimer);
                autoTimer = setInterval(() => {
                    goTo(current + 1 < pages ? current + 1 : 0);
                }, 5000);
            }

            window.addEventListener('resize', () => {
                perPage = getPerPage();
                pages   = Math.ceil(total / perPage);
                current = 0;
                buildDots();
                goTo(0);
            });

            buildDots();
            resetAuto();
        })();
    </script>
</body>
</html>
