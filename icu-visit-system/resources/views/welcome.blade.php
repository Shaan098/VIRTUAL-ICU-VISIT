<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Virtual ICU Visit Management System — Connecting families with loved ones in critical care through secure video visits.">
    <title>Virtual ICU Visit — Compassionate Connections for Critical Care</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #1a73e8;
            --primary-dark: #0f4c81;
            --accent: #00bcd4;
            --text: #1a1f36;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; color: var(--text); overflow-x: hidden; }

        /* NAV */
        .landing-nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            padding: 16px 0;
            background: rgba(255,255,255,.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0,0,0,.06);
            transition: all .3s;
        }
        .landing-nav .nav-brand {
            display: flex; align-items: center; gap: 10px;
            font-weight: 800; font-size: 18px; color: var(--primary); text-decoration: none;
        }
        .nav-brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 16px;
        }

        /* HERO */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f2444 0%, #1a3a6b 40%, #0a5299 70%, #00bcd4 100%);
            display: flex; align-items: center;
            position: relative;
            overflow: hidden;
            padding-top: 80px;
        }
        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 16px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 30px;
            color: rgba(255,255,255,.9);
            font-size: 12px; font-weight: 600;
            margin-bottom: 24px;
            backdrop-filter: blur(10px);
        }
        .hero h1 {
            font-size: clamp(2.2rem, 5vw, 4rem);
            font-weight: 900;
            color: #fff;
            line-height: 1.1;
            margin-bottom: 20px;
        }
        .hero h1 span {
            background: linear-gradient(90deg, #4fc3f7, #00e5ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero p {
            font-size: 17px;
            color: rgba(255,255,255,.78);
            line-height: 1.7;
            margin-bottom: 36px;
            max-width: 520px;
        }
        .btn-hero-primary {
            padding: 14px 32px;
            background: linear-gradient(135deg, var(--primary), #42a5f5);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 10px;
            box-shadow: 0 8px 30px rgba(26,115,232,.5);
            transition: all .3s;
        }
        .btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(26,115,232,.6); color: #fff; }
        .btn-hero-secondary {
            padding: 14px 28px;
            background: rgba(255,255,255,.1);
            color: #fff;
            border: 1.5px solid rgba(255,255,255,.35);
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 8px;
            backdrop-filter: blur(10px);
            transition: all .3s;
        }
        .btn-hero-secondary:hover { background: rgba(255,255,255,.2); color: #fff; }

        /* Hero Visual Card */
        .hero-card {
            background: rgba(255,255,255,.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 20px;
            padding: 28px;
            color: #fff;
        }
        .hero-stat-item {
            text-align: center;
            padding: 16px;
            background: rgba(255,255,255,.08);
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,.12);
        }
        .hero-stat-number { font-size: 28px; font-weight: 800; color: #fff; }
        .hero-stat-label  { font-size: 12px; color: rgba(255,255,255,.65); }

        /* FEATURES */
        .section { padding: 100px 0; }
        .section-tag {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 5px 14px;
            background: rgba(26,115,232,.1);
            border-radius: 20px;
            color: var(--primary);
            font-size: 12px; font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 14px;
        }
        .section-title { font-size: clamp(1.8rem, 3.5vw, 2.8rem); font-weight: 800; line-height: 1.2; }
        .feature-card {
            padding: 30px;
            border-radius: 20px;
            border: 1px solid rgba(0,0,0,.07);
            background: #fff;
            height: 100%;
            transition: all .3s;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
        }
        .feature-card:hover { box-shadow: 0 12px 40px rgba(26,115,232,.15); transform: translateY(-4px); }
        .feature-icon {
            width: 56px; height: 56px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            margin-bottom: 18px;
        }

        /* HOW IT WORKS */
        .how-section { background: linear-gradient(135deg, #f0f4f8 0%, #e8f0fe 100%); }
        .step-card {
            text-align: center; padding: 40px 28px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,.07);
            height: 100%;
            position: relative;
        }
        .step-number {
            width: 52px; height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; font-weight: 800;
            margin: 0 auto 18px;
            box-shadow: 0 6px 20px rgba(26,115,232,.4);
        }

        /* ROLES */
        .roles-section { background: #fff; }
        .role-card {
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,.08);
            transition: all .3s;
        }
        .role-card:hover { box-shadow: 0 16px 48px rgba(26,115,232,.15); transform: translateY(-4px); }
        .role-card-header {
            padding: 28px;
            color: #fff;
        }
        .role-card-body { padding: 24px 28px; }

        /* CTA */
        .cta-section {
            background: linear-gradient(135deg, #0f2444, #1a3a6b);
            padding: 100px 0; text-align: center;
        }

        /* FOOTER */
        footer {
            background: #0d1117;
            padding: 40px 0;
            color: rgba(255,255,255,.5);
            text-align: center;
            font-size: 13px;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }
        .float-anim { animation: float 4s ease-in-out infinite; }
    </style>
</head>
<body>

<!-- NAV -->
<nav class="landing-nav">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="nav-brand">
                <div class="nav-brand-icon"><i class="fa-solid fa-hospital-user"></i></div>
                Virtual ICU Visit
            </a>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('about') }}" class="text-muted text-decoration-none" style="font-size:14px;font-weight:500">About</a>
                @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm rounded-3 fw-600">
                    <i class="fa-solid fa-gauge-high me-1"></i>Dashboard
                </a>
                @else
                <a href="{{ route('login') }}" class="text-muted text-decoration-none" style="font-size:14px;font-weight:500">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm rounded-3 fw-600">Get Started</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="container position-relative z-1">
        <div class="row align-items-center g-5">
            <div class="col-12 col-lg-6">
                <div class="hero-badge">
                    <span class="live-dot" style="background:#4cff91;width:8px;height:8px;border-radius:50%;animation:pulse-dot 1.5s infinite;display:inline-block"></span>
                    HIPAA-Compliant Platform
                </div>
                <h1>Connecting Families with <span>Critical Care</span></h1>
                <p>A secure, compassionate platform that bridges the distance between families and ICU patients through encrypted virtual visits — when every moment matters.</p>
                <div class="d-flex flex-wrap gap-3">
                    @auth
                    <a href="{{ route('dashboard') }}" class="btn-hero-primary">
                        <i class="fa-solid fa-gauge-high"></i> Go to Dashboard
                    </a>
                    @else
                    <a href="{{ route('register') }}" class="btn-hero-primary">
                        <i class="fa-solid fa-rocket"></i> Get Started Free
                    </a>
                    <a href="{{ route('login') }}" class="btn-hero-secondary">
                        <i class="fa-solid fa-sign-in-alt"></i> Sign In
                    </a>
                    @endauth
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="hero-card float-anim">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#1a73e8,#00bcd4);display:flex;align-items:center;justify-content:center;color:#fff">
                            <i class="fa-solid fa-video"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:15px">Active Meeting</div>
                            <div style="font-size:12px;color:rgba(255,255,255,.6)">ICU Ward A · Bed 01</div>
                        </div>
                        <span style="background:#4cff91;color:#0d1117;border-radius:20px;padding:3px 10px;font-size:11px;font-weight:700;margin-left:auto">● LIVE</span>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-4"><div class="hero-stat-item"><div class="hero-stat-number">247</div><div class="hero-stat-label">Visits This Month</div></div></div>
                        <div class="col-4"><div class="hero-stat-item"><div class="hero-stat-number">98%</div><div class="hero-stat-label">Satisfaction Rate</div></div></div>
                        <div class="col-4"><div class="hero-stat-item"><div class="hero-stat-number">< 5m</div><div class="hero-stat-label">Avg. Wait Time</div></div></div>
                    </div>

                    <div style="background:rgba(255,255,255,.08);border-radius:12px;padding:14px;border:1px solid rgba(255,255,255,.12)">
                        <div style="font-size:12px;color:rgba(255,255,255,.6);margin-bottom:8px">Latest Activity</div>
                        @foreach([['✅','Visit approved for Michael J.','2m ago'],['📅','New request from Emily C.','8m ago'],['🎉','Meeting completed','15m ago']] as $item)
                        <div class="d-flex align-items-center gap-2 py-1" style="font-size:12px;border-bottom:1px solid rgba(255,255,255,.07)">
                            <span>{{ $item[0] }}</span>
                            <span style="color:rgba(255,255,255,.8)">{{ $item[1] }}</span>
                            <span style="color:rgba(255,255,255,.4);margin-left:auto">{{ $item[2] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-tag"><i class="fa-solid fa-star"></i> Features</div>
            <h2 class="section-title">Everything You Need for<br>Virtual ICU Visits</h2>
        </div>
        <div class="row g-4">
            @foreach([
                ['fa-shield-halved','#e8f0fe','#1a73e8','Bank-Level Security','End-to-end encrypted meetings with HIPAA compliance, CSRF protection, and role-based access control.'],
                ['fa-video','#e8f5e9','#34a853','HD Video Meetings','Crystal-clear Jitsi Meet powered video calls with no downloads required — straight from any browser.'],
                ['fa-bell','#fff8e1','#f57f17','Real-time Notifications','Instant in-app notifications for request approvals, rejections, and meeting reminders.'],
                ['fa-chart-line','#fce4ec','#c62828','Analytics Dashboard','Comprehensive analytics for admins — track visits, patient status, and doctor performance.'],
                ['fa-user-doctor','#f3e5f5','#7b1fa2','Multi-role Access','Dedicated portals for Admins, Doctors, and Family members with tailored dashboards.'],
                ['fa-mobile-screen','#e0f2f1','#00695c','Fully Responsive','Works beautifully on phones, tablets, and desktops. Visit from anywhere, anytime.'],
            ] as $feat)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:{{ $feat[1] }};color:{{ $feat[2] }}"><i class="fa-solid {{ $feat[0] }}"></i></div>
                    <h5 style="font-weight:700;margin-bottom:10px">{{ $feat[3] }}</h5>
                    <p class="text-muted mb-0" style="font-size:14px;line-height:1.6">{{ $feat[4] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="section how-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-tag"><i class="fa-solid fa-map"></i> Process</div>
            <h2 class="section-title">How It Works</h2>
            <p class="text-muted mt-3" style="font-size:16px;max-width:500px;margin:0 auto">A simple, 4-step process to connect with your loved one in the ICU</p>
        </div>
        <div class="row g-4">
            @foreach([
                ['1','fa-user-plus','Register','Create your family member account in under 2 minutes. No technical knowledge required.'],
                ['2','fa-calendar-plus','Request a Visit','Select your patient, choose a date/time, and submit your request with a reason.'],
                ['3','fa-user-doctor','Doctor Reviews','The assigned physician reviews and approves your request, scheduling a secure meeting time.'],
                ['4','fa-video','Join the Meeting','Click your meeting link at the scheduled time and connect face-to-face via secure video.'],
            ] as $step)
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">{{ $step[0] }}</div>
                    <i class="fa-solid {{ $step[1] }} fa-2x mb-3 text-primary opacity-75"></i>
                    <h6 style="font-weight:700;margin-bottom:10px">{{ $step[2] }}</h6>
                    <p class="text-muted mb-0" style="font-size:13.5px;line-height:1.6">{{ $step[3] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ROLES -->
<section class="section roles-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-tag"><i class="fa-solid fa-users"></i> Roles</div>
            <h2 class="section-title">Built for Every Stakeholder</h2>
        </div>
        <div class="row g-4">
            @foreach([
                ['fa-shield-halved','linear-gradient(135deg,#0f2444,#1a73e8)','Admin','Full Control','Manage all users, patients, and visit requests. Access comprehensive analytics and system settings.',['Manage patients & doctors','Approve/reject all requests','System analytics','User management']],
                ['fa-user-doctor','linear-gradient(135deg,#1565c0,#42a5f5)','Doctor','Clinical Authority','Review assigned patient visit requests, approve meetings, and conduct virtual examinations.',['Patient monitoring','Approve visit requests','Conduct video meetings','Clinical notes']],
                ['fa-heart','linear-gradient(135deg,#1b5e20,#43a047)','Family','Stay Connected','Request secure visits, join meetings, and stay updated with real-time notifications.',['Book virtual visits','Join secure meetings','Track request status','Receive notifications']],
            ] as $role)
            <div class="col-12 col-md-4">
                <div class="role-card">
                    <div class="role-card-header" style="background:{{ $role[2] }}">
                        <i class="fa-solid {{ $role[0] }} fa-2x mb-3 opacity-90"></i>
                        <div style="font-size:11px;font-weight:700;opacity:.7;text-transform:uppercase;letter-spacing:1px">{{ $role[3] }}</div>
                        <h4 style="font-weight:800;margin-bottom:6px">{{ $role[4] }}</h4>
                        <p style="font-size:13px;opacity:.8;margin:0">{{ $role[5] }}</p>
                    </div>
                    <div class="role-card-body">
                        @foreach($role[6] as $feat)
                        <div class="d-flex align-items-center gap-2 py-2" style="border-bottom:1px solid rgba(0,0,0,.06);font-size:13.5px">
                            <i class="fa-solid fa-check-circle text-success"></i>
                            {{ $feat }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-7 text-center">
                <i class="fa-solid fa-hospital-user fa-3x mb-4 text-white opacity-75"></i>
                <h2 style="color:#fff;font-size:2.4rem;font-weight:800;margin-bottom:16px">Ready to Connect?</h2>
                <p style="color:rgba(255,255,255,.7);font-size:16px;margin-bottom:36px">Join thousands of families staying close to their loved ones in critical care through our secure virtual visit platform.</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('register') }}" class="btn-hero-primary">
                        <i class="fa-solid fa-rocket"></i> Get Started Free
                    </a>
                    <a href="{{ route('about') }}" class="btn-hero-secondary">Learn More</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="container">
        <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
            <div style="width:28px;height:28px;background:linear-gradient(135deg,#1a73e8,#00bcd4);border-radius:7px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px">
                <i class="fa-solid fa-hospital-user"></i>
            </div>
            <span style="color:rgba(255,255,255,.7);font-weight:600">Virtual ICU Visit</span>
        </div>
        <p>&copy; {{ date('Y') }} Virtual ICU Visit Management System. Built with ❤️ for compassionate healthcare.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<style>
@keyframes pulse-dot { 0%,100%{transform:scale(1);opacity:1;} 50%{transform:scale(1.4);opacity:.7;} }
</style>
</body>
</html>
