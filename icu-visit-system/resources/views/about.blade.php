<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About — Virtual ICU Visit</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family:'Inter',sans-serif; color:#1a1f36; }
        .hero-about {
            background: linear-gradient(135deg,#0f2444,#1a73e8);
            padding: 120px 0 80px;
            color: #fff;
        }
        .section { padding: 80px 0; }
        .team-card {
            border-radius: 16px; border: 1px solid rgba(0,0,0,.07);
            padding: 28px; text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,.07);
            transition: all .3s;
        }
        .team-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(26,115,232,.12); }
        .team-avatar {
            width: 72px; height: 72px; border-radius: 50%;
            background: linear-gradient(135deg,#1a73e8,#00bcd4);
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; color: #fff; font-weight: 700;
            margin: 0 auto 14px;
        }
        .nav-brand { display:flex;align-items:center;gap:10px;font-weight:800;color:#1a73e8;text-decoration:none;font-size:16px; }
        .nav-brand-icon { width:36px;height:36px;background:linear-gradient(135deg,#1a73e8,#00bcd4);border-radius:9px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:15px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom py-3 sticky-top">
    <div class="container">
        <a class="nav-brand" href="{{ route('home') }}">
            <div class="nav-brand-icon"><i class="fa-solid fa-hospital-user"></i></div>
            Virtual ICU Visit
        </a>
        <div class="d-flex gap-3 ms-auto align-items-center">
            <a href="{{ route('home') }}" class="text-muted text-decoration-none" style="font-size:14px">Home</a>
            @auth
            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm rounded-3">Dashboard</a>
            @else
            <a href="{{ route('login') }}" class="text-muted text-decoration-none" style="font-size:14px">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm rounded-3">Get Started</a>
            @endauth
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero-about text-center">
    <div class="container">
        <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.25);border-radius:30px;padding:5px 16px;font-size:12px;font-weight:600;margin-bottom:20px">
            <i class="fa-solid fa-heart-pulse"></i> Our Mission
        </div>
        <h1 style="font-size:clamp(2rem,5vw,3.2rem);font-weight:900;margin-bottom:16px">About Virtual ICU Visit</h1>
        <p style="font-size:17px;color:rgba(255,255,255,.75);max-width:560px;margin:0 auto;line-height:1.7">
            We bridge the physical distance between families and their critically ill loved ones through secure, compassionate technology.
        </p>
    </div>
</section>

<!-- Mission -->
<section class="section bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-12 col-lg-6">
                <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(26,115,232,.1);border-radius:20px;padding:5px 14px;color:#1a73e8;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;margin-bottom:14px">
                    <i class="fa-solid fa-bullseye"></i> Our Purpose
                </div>
                <h2 style="font-size:2rem;font-weight:800;margin-bottom:16px;line-height:1.2">Compassion Through Technology</h2>
                <p class="text-muted" style="font-size:15px;line-height:1.8">
                    Families of ICU patients often face immense stress — not just from worry, but from not being there. Our platform was built to close that gap, enabling scheduled, doctor-supervised virtual visits that keep families informed and emotionally connected.
                </p>
                <p class="text-muted" style="font-size:15px;line-height:1.8">
                    Every feature — from role-based access control to encrypted video meetings — was designed with both clinical safety and human empathy in mind.
                </p>
            </div>
            <div class="col-12 col-lg-6">
                <div class="row g-3">
                    @foreach([
                        ['fa-shield-halved','#e8f0fe','#1a73e8','HIPAA Compliant','All data encrypted and protected.'],
                        ['fa-video','#e8f5e9','#34a853','HD Video Calls','Crystal-clear Jitsi-powered meetings.'],
                        ['fa-user-doctor','#fff8e1','#f57f17','Doctor Supervised','All visits reviewed by clinical staff.'],
                        ['fa-bell','#fce4ec','#c62828','Real-time Alerts','Instant notifications at every step.'],
                    ] as $f)
                    <div class="col-6">
                        <div style="padding:20px;border-radius:14px;border:1px solid rgba(0,0,0,.07);height:100%">
                            <div style="width:44px;height:44px;border-radius:12px;background:{{ $f[1] }};display:flex;align-items:center;justify-content:center;color:{{ $f[2] }};font-size:18px;margin-bottom:12px">
                                <i class="fa-solid {{ $f[0] }}"></i>
                            </div>
                            <div style="font-weight:700;font-size:14px;margin-bottom:4px">{{ $f[3] }}</div>
                            <div class="text-muted" style="font-size:12.5px">{{ $f[4] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="section" style="background:linear-gradient(135deg,#f0f4f8,#e8f0fe)">
    <div class="container text-center">
        <h2 style="font-size:2rem;font-weight:800;margin-bottom:48px">Impact by the Numbers</h2>
        <div class="row g-4">
            @foreach([['2,400+','Virtual Visits Facilitated'],['98%','Family Satisfaction Rate'],['< 5min','Average Approval Time'],['24/7','Platform Availability']] as $s)
            <div class="col-6 col-lg-3">
                <div style="font-size:2.4rem;font-weight:900;color:#1a73e8;margin-bottom:6px">{{ $s[0] }}</div>
                <div class="text-muted" style="font-size:14px">{{ $s[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section bg-white text-center">
    <div class="container">
        <h2 style="font-size:2rem;font-weight:800;margin-bottom:12px">Ready to Get Started?</h2>
        <p class="text-muted mb-4" style="font-size:15px">Join families who stay connected with their loved ones in the ICU.</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('register') }}" class="btn btn-primary px-5 py-3 rounded-3 fw-bold">
                <i class="fa-solid fa-rocket me-2"></i>Create Free Account
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline-primary px-5 py-3 rounded-3 fw-bold">Back to Home</a>
        </div>
    </div>
</section>

<footer style="background:#0d1117;padding:32px 0;text-align:center;color:rgba(255,255,255,.5);font-size:13px">
    &copy; {{ date('Y') }} Virtual ICU Visit Management System
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
