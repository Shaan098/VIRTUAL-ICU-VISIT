<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account — Virtual ICU Visit</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family:'Inter',sans-serif; margin:0; min-height:100vh; background:linear-gradient(135deg,#f0f4f8 0%,#e8f0fe 100%); display:flex; align-items:center; justify-content:center; padding:40px 16px; }
        .register-box { width:100%; max-width:520px; }
        .register-card { background:#fff; border-radius:20px; padding:40px; box-shadow:0 8px 40px rgba(26,115,232,.12); border:1px solid rgba(0,0,0,.06); }
        .form-control {
            border-radius:10px; border:1.5px solid #e2e8f0;
            padding:11px 14px; font-size:13.5px; transition:all .2s;
        }
        .form-control:focus { border-color:#1a73e8; box-shadow:0 0 0 3px rgba(26,115,232,.1); outline:none; }
        .form-select { border-radius:10px; border:1.5px solid #e2e8f0; padding:11px 14px; font-size:13.5px; }
        .form-select:focus { border-color:#1a73e8; box-shadow:0 0 0 3px rgba(26,115,232,.1); }
        .btn-register {
            background:linear-gradient(135deg,#1a73e8,#2196f3); color:#fff; border:none;
            padding:13px; border-radius:12px; font-size:15px; font-weight:700;
            width:100%; cursor:pointer; box-shadow:0 6px 20px rgba(26,115,232,.4); transition:all .2s;
        }
        .btn-register:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(26,115,232,.5); }
        .role-option input { display:none; }
        .role-option label {
            display:flex; flex-direction:column; align-items:center;
            padding:14px 10px; border:2px solid #e2e8f0;
            border-radius:12px; cursor:pointer; transition:all .2s;
            font-size:12px; font-weight:600; color:#6b7280;
            text-align:center;
        }
        .role-option label i { font-size:20px; margin-bottom:6px; color:#9ca3af; transition:all .2s; }
        .role-option input:checked + label { border-color:#1a73e8; background:#e8f0fe; color:#1a73e8; }
        .role-option input:checked + label i { color:#1a73e8; }
    </style>
</head>
<body>
<div class="register-box">
    <div class="text-center mb-4">
        <a href="{{ route('home') }}" class="text-decoration-none d-inline-flex align-items-center gap-2">
            <div style="width:38px;height:38px;background:linear-gradient(135deg,#1a73e8,#00bcd4);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px">
                <i class="fa-solid fa-hospital-user"></i>
            </div>
            <span style="font-weight:800;color:#1a1f36;font-size:16px">Virtual ICU Visit</span>
        </a>
    </div>

    <div class="register-card">
        <h4 class="fw-bold mb-1">Create your account</h4>
        <p class="text-muted mb-4" style="font-size:13.5px">Join us to request and manage virtual ICU visits</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Role Selection -->
            <div class="mb-4">
                <label class="form-label" style="font-size:13px;font-weight:600">I am a…</label>
                <div class="row g-2">
                    @foreach([['doctor','fa-user-doctor','Doctor'],['family','fa-heart','Family Member']] as $r)
                    <div class="col-6 role-option">
                        <input type="radio" name="role" id="role_{{ $r[0] }}" value="{{ $r[0] }}" {{ old('role','family')===$r[0] ? 'checked':'' }}>
                        <label for="role_{{ $r[0] }}">
                            <i class="fa-solid {{ $r[1] }}"></i> {{ $r[2] }}
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('role')<div class="text-danger mt-1" style="font-size:12px">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label" style="font-size:13px;font-weight:600">Full Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" placeholder="Your full name" required autofocus>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label" style="font-size:13px;font-weight:600">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="your@email.com" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label" style="font-size:13px;font-weight:600">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+1-555-0000">
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label" style="font-size:13px;font-weight:600">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="Min. 8 characters" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-6">
                    <label class="form-label" style="font-size:13px;font-weight:600">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                </div>
            </div>

            <button type="submit" class="btn-register">
                <i class="fa-solid fa-user-plus me-2"></i>Create Account
            </button>
        </form>

        <p class="text-center mt-4 mb-0" style="font-size:13.5px;color:#6b7280">
            Already have an account? <a href="{{ route('login') }}" style="color:#1a73e8;font-weight:600">Sign in</a>
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
