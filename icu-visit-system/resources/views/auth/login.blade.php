<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In — Virtual ICU Visit</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family:'Inter',sans-serif; margin:0; min-height:100vh; display:flex; background:#f0f4f8; }
        .auth-left {
            width: 45%;
            background: linear-gradient(135deg, #0f2444 0%, #1a3a6b 50%, #0a5299 100%);
            display: flex; flex-direction: column; justify-content: center; padding: 60px;
            position: relative; overflow: hidden;
        }
        .auth-left::before {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        @media (max-width:768px) { .auth-left { display:none; } }
        .auth-right {
            flex: 1;
            display: flex; align-items: center; justify-content: center;
            padding: 40px 20px;
        }
        .auth-box { width: 100%; max-width: 440px; }
        .auth-logo { display:flex;align-items:center;gap:10px;margin-bottom:36px; }
        .auth-logo-icon {
            width:42px;height:42px;border-radius:12px;
            background:linear-gradient(135deg,#1a73e8,#00bcd4);
            display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;
        }
        .form-control {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 11px 14px;
            font-size: 13.5px;
            transition: all .2s;
        }
        .form-control:focus { border-color: #1a73e8; box-shadow: 0 0 0 3px rgba(26,115,232,.1); }
        .btn-signin {
            background: linear-gradient(135deg, #1a73e8, #2196f3);
            color: #fff; border: none;
            padding: 12px; border-radius: 12px;
            font-size: 15px; font-weight: 700;
            width: 100%; cursor: pointer;
            box-shadow: 0 6px 20px rgba(26,115,232,.4);
            transition: all .2s;
        }
        .btn-signin:hover { transform: translateY(-1px); box-shadow: 0 8px 28px rgba(26,115,232,.5); }
        .demo-creds {
            background: linear-gradient(135deg,rgba(26,115,232,.08),rgba(0,188,212,.05));
            border: 1px solid rgba(26,115,232,.15);
            border-radius: 12px;
            padding: 16px;
            margin-top: 20px;
        }
        .demo-badge {
            display:inline-block;padding:2px 8px;border-radius:6px;
            font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;
        }
    </style>
</head>
<body>

<!-- Left Panel -->
<div class="auth-left d-none d-md-flex flex-column position-relative" style="z-index:1">
    <div style="margin-bottom:48px">
        <div style="width:50px;height:50px;background:linear-gradient(135deg,#1a73e8,#00bcd4);border-radius:14px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;margin-bottom:20px">
            <i class="fa-solid fa-hospital-user"></i>
        </div>
        <h2 style="color:#fff;font-weight:800;font-size:1.8rem;line-height:1.3;margin-bottom:14px">
            Virtual ICU<br>Visit System
        </h2>
        <p style="color:rgba(255,255,255,.65);font-size:14px;line-height:1.7">
            Compassionate care through technology. Stay connected with your loved ones in the ICU from anywhere in the world.
        </p>
    </div>

    @foreach([
        ['fa-shield-halved','#4fc3f7','Encrypted & HIPAA Compliant','Your data is always protected'],
        ['fa-video','#81c784','HD Video Meetings','Crystal-clear Jitsi-powered calls'],
        ['fa-bell','#ffb74d','Real-time Notifications','Instant updates on every request'],
    ] as $f)
    <div class="d-flex align-items-center gap-3 mb-3" style="background:rgba(255,255,255,.07);border-radius:14px;padding:14px 18px">
        <div style="width:38px;height:38px;border-radius:10px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:{{ $f[1] }};flex-shrink:0">
            <i class="fa-solid {{ $f[0] }}"></i>
        </div>
        <div>
            <div style="color:#fff;font-weight:600;font-size:13px">{{ $f[2] }}</div>
            <div style="color:rgba(255,255,255,.5);font-size:12px">{{ $f[3] }}</div>
        </div>
    </div>
    @endforeach
</div>

<!-- Right: Login Form -->
<div class="auth-right">
    <div class="auth-box">
        <a href="{{ route('home') }}" class="auth-logo text-decoration-none">
            <div class="auth-logo-icon"><i class="fa-solid fa-hospital-user"></i></div>
            <span style="font-weight:800;color:#1a1f36;font-size:16px">Virtual ICU Visit</span>
        </a>

        <h4 style="font-weight:800;margin-bottom:6px">Welcome back</h4>
        <p class="text-muted mb-4" style="font-size:14px">Sign in to your account to continue</p>

        @if(session('status'))
        <div class="alert alert-success rounded-3 mb-3" style="font-size:13px">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label" style="font-size:13px;font-weight:600">Email Address</label>
                <div class="position-relative">
                    <input type="email" name="email" class="form-control ps-4 @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="your@email.com" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label d-flex justify-content-between" style="font-size:13px;font-weight:600">
                    Password
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-weight:400;color:#1a73e8;font-size:12px">Forgot password?</a>
                    @endif
                </label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="Enter password" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="d-flex align-items-center gap-2" style="font-size:13px;cursor:pointer">
                    <input type="checkbox" name="remember" class="form-check-input m-0"> Remember me for 30 days
                </label>
            </div>
            <button type="submit" class="btn-signin">
                <i class="fa-solid fa-sign-in-alt me-2"></i>Sign In
            </button>
        </form>

        <p class="text-center mt-4 mb-3" style="font-size:13.5px;color:#6b7280">
            Don't have an account?
            <a href="{{ route('register') }}" style="color:#1a73e8;font-weight:600">Create one free</a>
        </p>

        <!-- Demo Credentials -->
        <div class="demo-creds">
            <div style="font-size:12px;font-weight:700;color:#1a73e8;margin-bottom:10px"><i class="fa-solid fa-flask me-1"></i>Demo Credentials</div>
            <div class="d-flex flex-column gap-1">
                @foreach([
                    ['Admin','admin@icuvisit.com','danger'],
                    ['Doctor','doctor1@icuvisit.com','primary'],
                    ['Family','family1@icuvisit.com','success'],
                ] as $cred)
                <div style="font-size:12px;display:flex;align-items:center;gap:6px">
                    <span class="demo-badge" style="background:{{ $cred[2]==='danger'?'#ffebee':($cred[2]==='primary'?'#e8f0fe':'#e8f5e9') }};color:{{ $cred[2]==='danger'?'#c62828':($cred[2]==='primary'?'#1a73e8':'#2e7d32') }}">{{ $cred[0] }}</span>
                    <code style="font-size:11px;color:#374151">{{ $cred[1] }}</code>
                    <span style="color:#9ca3af">/ password: <code style="font-size:11px">password</code></span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
