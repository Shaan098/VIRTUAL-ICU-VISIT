<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Virtual ICU Visit Management System - Secure, compassionate connections for critical care.">
    <title>@yield('title', 'Dashboard') — Virtual ICU Visit</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        /* ── Design Tokens ────────────────────────────────────── */
        :root {
            --primary:        #1a73e8;
            --primary-dark:   #0f4c81;
            --primary-light:  #e8f0fe;
            --accent:         #00bcd4;
            --success:        #34a853;
            --warning:        #fbbc04;
            --danger:         #ea4335;
            --info:           #4fc3f7;
            --bg:             #f0f4f8;
            --bg-card:        rgba(255,255,255,0.9);
            --sidebar-bg:     linear-gradient(180deg, #0f2444 0%, #1a3a6b 60%, #0f4c81 100%);
            --sidebar-width:  260px;
            --navbar-height:  64px;
            --text:           #1a1f36;
            --text-muted:     #6b7280;
            --border:         rgba(0,0,0,0.08);
            --shadow-sm:      0 1px 4px rgba(26,115,232,.1);
            --shadow-md:      0 4px 20px rgba(26,115,232,.15);
            --shadow-lg:      0 8px 40px rgba(26,115,232,.2);
            --radius:         14px;
            --radius-sm:      8px;
            --transition:     all 0.25s cubic-bezier(.4,0,.2,1);
        }

        [data-theme="dark"] {
            --bg:       #0d1117;
            --bg-card:  rgba(22,27,34,0.95);
            --text:     #e6edf3;
            --text-muted: #8b949e;
            --border:   rgba(255,255,255,0.08);
            --shadow-sm: 0 1px 4px rgba(0,0,0,.4);
            --shadow-md: 0 4px 20px rgba(0,0,0,.5);
        }

        /* ── Base ─────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            overflow-x: hidden;
            transition: background .3s, color .3s;
        }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #c5d0e6; border-radius: 10px; }

        /* ── Sidebar ──────────────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform .3s cubic-bezier(.4,0,.2,1), width .3s;
            box-shadow: 4px 0 24px rgba(0,0,0,.25);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,.1);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .sidebar-brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(26,115,232,.4);
        }
        .sidebar-brand-text { line-height: 1.2; }
        .sidebar-brand-name { font-size: 13px; font-weight: 700; color: #fff; display: block; }
        .sidebar-brand-sub  { font-size: 10px; color: rgba(255,255,255,.5); display: block; }

        .sidebar-nav { padding: 12px 0; flex: 1; }
        .nav-section-title {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,.35);
            text-transform: uppercase;
            padding: 12px 20px 4px;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            color: rgba(255,255,255,.65);
            font-size: 13.5px;
            font-weight: 500;
            border-radius: 0;
            text-decoration: none;
            transition: var(--transition);
            position: relative;
            margin: 2px 10px;
            border-radius: 10px;
        }
        .sidebar .nav-link .nav-icon {
            width: 34px; height: 34px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px;
            font-size: 14px;
            background: rgba(255,255,255,.05);
            flex-shrink: 0;
            transition: var(--transition);
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,.1);
        }
        .sidebar .nav-link:hover .nav-icon {
            background: rgba(26,115,232,.5);
            transform: scale(1.05);
        }
        .sidebar .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, rgba(26,115,232,.8), rgba(0,188,212,.4));
            box-shadow: 0 4px 12px rgba(26,115,232,.3);
        }
        .sidebar .nav-link.active .nav-icon {
            background: rgba(255,255,255,.2);
        }
        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 70%;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,.1);
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 10px;
            border-radius: 10px;
            background: rgba(255,255,255,.07);
        }
        .sidebar-user img {
            width: 36px; height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,.3);
        }
        .sidebar-user-name { font-size: 12px; font-weight: 600; color: #fff; }
        .sidebar-user-role {
            font-size: 10px;
            color: rgba(255,255,255,.5);
            text-transform: capitalize;
        }

        /* ── Main Layout ──────────────────────────────────────── */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left .3s;
        }

        /* ── Top Navbar ───────────────────────────────────────── */
        .top-navbar {
            position: sticky;
            top: 0;
            height: var(--navbar-height);
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 16px;
            box-shadow: var(--shadow-sm);
        }
        .navbar-toggle {
            width: 36px; height: 36px;
            border: none; background: none;
            cursor: pointer;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 5px; padding: 0;
        }
        .navbar-toggle span {
            display: block;
            width: 20px; height: 2px;
            background: var(--text);
            border-radius: 2px;
            transition: var(--transition);
        }
        .page-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
            flex: 1;
        }

        /* Notification Bell */
        .notif-bell {
            position: relative;
            width: 38px; height: 38px;
            border-radius: 50%;
            border: 1px solid var(--border);
            background: var(--bg);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
            transition: var(--transition);
            font-size: 15px;
        }
        .notif-bell:hover { background: var(--primary-light); color: var(--primary); }
        .notif-badge {
            position: absolute;
            top: -4px; right: -4px;
            width: 18px; height: 18px;
            background: var(--danger);
            border-radius: 50%;
            font-size: 10px;
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            border: 2px solid var(--bg-card);
            display: none;
        }

        /* Dark mode toggle */
        .dark-toggle {
            width: 38px; height: 38px;
            border-radius: 50%;
            border: 1px solid var(--border);
            background: var(--bg);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--text-muted);
            font-size: 15px;
            transition: var(--transition);
        }
        .dark-toggle:hover { background: var(--primary-light); color: var(--primary); }

        /* Profile dropdown */
        .profile-btn {
            display: flex; align-items: center; gap: 8px;
            padding: 4px 10px 4px 4px;
            border-radius: 30px;
            border: 1px solid var(--border);
            background: var(--bg);
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }
        .profile-btn:hover { box-shadow: var(--shadow-sm); }
        .profile-btn img {
            width: 30px; height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-btn-name { font-size: 13px; font-weight: 600; color: var(--text); }

        /* ── Page Content ─────────────────────────────────────── */
        .page-content { padding: 28px 28px; }

        /* ── Cards ────────────────────────────────────────────── */
        .icu-card {
            background: var(--bg-card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            overflow: hidden;
        }
        .icu-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }

        .stat-card {
            background: var(--bg-card);
            border-radius: var(--radius);
            padding: 22px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }
        .stat-card.primary::before   { background: linear-gradient(90deg, var(--primary), var(--accent)); }
        .stat-card.success::before   { background: linear-gradient(90deg, var(--success), #81c784); }
        .stat-card.warning::before   { background: linear-gradient(90deg, var(--warning), #ffb74d); }
        .stat-card.danger::before    { background: linear-gradient(90deg, var(--danger), #ef9a9a); }
        .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-3px); }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            margin-bottom: 14px;
        }
        .stat-icon.primary { background: var(--primary-light); color: var(--primary); }
        .stat-icon.success { background: #e8f5e9; color: var(--success); }
        .stat-icon.warning { background: #fff8e1; color: #f57f17; }
        .stat-icon.danger  { background: #ffebee; color: var(--danger); }

        .stat-number {
            font-size: 32px;
            font-weight: 800;
            color: var(--text);
            line-height: 1;
            margin-bottom: 4px;
        }
        .stat-label { font-size: 13px; color: var(--text-muted); font-weight: 500; }

        /* ── Glassmorphism Panel ──────────────────────────────── */
        .glass-panel {
            background: rgba(255,255,255,.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,.8);
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
        }
        [data-theme="dark"] .glass-panel {
            background: rgba(22,27,34,.8);
            border-color: rgba(255,255,255,.1);
        }

        /* ── Tables ───────────────────────────────────────────── */
        .icu-table { width: 100%; border-collapse: collapse; }
        .icu-table thead th {
            background: var(--primary-light);
            color: var(--primary);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 12px 16px;
            border: none;
        }
        [data-theme="dark"] .icu-table thead th {
            background: rgba(26,115,232,.2);
        }
        .icu-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: var(--transition);
        }
        .icu-table tbody tr:last-child { border-bottom: none; }
        .icu-table tbody tr:hover { background: rgba(26,115,232,.04); }
        .icu-table td { padding: 14px 16px; font-size: 13.5px; vertical-align: middle; color: var(--text); }

        /* ── Badges ───────────────────────────────────────────── */
        .badge-icu {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex; align-items: center; gap: 4px;
        }

        /* ── Buttons ──────────────────────────────────────────── */
        .btn-icu {
            padding: 9px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            display: inline-flex; align-items: center; gap: 7px;
        }
        .btn-icu-primary {
            background: linear-gradient(135deg, var(--primary), #2196f3);
            color: #fff;
            box-shadow: 0 4px 14px rgba(26,115,232,.4);
        }
        .btn-icu-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(26,115,232,.5);
            color: #fff;
        }
        .btn-icu-success {
            background: linear-gradient(135deg, var(--success), #66bb6a);
            color: #fff;
            box-shadow: 0 4px 14px rgba(52,168,83,.4);
        }
        .btn-icu-success:hover { transform: translateY(-1px); color: #fff; }
        .btn-icu-danger {
            background: linear-gradient(135deg, var(--danger), #ef5350);
            color: #fff;
            box-shadow: 0 4px 14px rgba(234,67,53,.4);
        }
        .btn-icu-danger:hover { transform: translateY(-1px); color: #fff; }
        .btn-icu-outline {
            background: transparent;
            border: 1.5px solid var(--primary);
            color: var(--primary);
        }
        .btn-icu-outline:hover { background: var(--primary-light); }

        /* ── Animations ───────────────────────────────────────── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse-dot {
            0%, 100% { transform: scale(1); opacity: 1; }
            50%       { transform: scale(1.4); opacity: .7; }
        }
        @keyframes countUp { from { opacity: 0; } to { opacity: 1; } }

        .animate-fade-up { animation: fadeInUp .5s ease both; }
        .delay-1 { animation-delay: .1s; }
        .delay-2 { animation-delay: .2s; }
        .delay-3 { animation-delay: .3s; }
        .delay-4 { animation-delay: .4s; }

        .live-dot {
            width: 8px; height: 8px;
            background: var(--success);
            border-radius: 50%;
            display: inline-block;
            animation: pulse-dot 1.5s infinite;
        }

        /* ── Skeleton Loader ──────────────────────────────────── */
        .skeleton {
            background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%);
            background-size: 200% 100%;
            animation: skeleton-shimmer 1.5s infinite;
            border-radius: 6px;
        }
        @keyframes skeleton-shimmer {
            0%   { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* ── Notification Dropdown ────────────────────────────── */
        .notif-dropdown {
            width: 360px;
            max-height: 440px;
            overflow-y: auto;
            border-radius: var(--radius) !important;
            border: 1px solid var(--border) !important;
            box-shadow: var(--shadow-lg) !important;
            padding: 0 !important;
        }
        .notif-item {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            transition: var(--transition);
            cursor: pointer;
            display: block;
            text-decoration: none;
            color: var(--text);
        }
        .notif-item:hover { background: var(--primary-light); }
        .notif-item.unread { background: rgba(26,115,232,.04); border-left: 3px solid var(--primary); }

        /* ── Status Pills ─────────────────────────────────────── */
        .status-pending    { background: #fff8e1; color: #f57f17; }
        .status-approved   { background: #e8f5e9; color: #2e7d32; }
        .status-rejected   { background: #ffebee; color: #c62828; }
        .status-completed  { background: #e3f2fd; color: #1565c0; }
        .status-cancelled  { background: #f3f4f6; color: #6b7280; }
        .status-scheduled  { background: #e8f0fe; color: #1a73e8; }
        .status-active     { background: #e3f2fd; color: #1565c0; }
        .status-critical   { background: #ffebee; color: #c62828; }
        .status-stable     { background: #e8f5e9; color: #2e7d32; }
        .status-discharged { background: #f3e5f5; color: #6a1b9a; }

        /* ── Search box ───────────────────────────────────────── */
        .search-box {
            position: relative;
        }
        .search-box input {
            padding-left: 38px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: var(--bg);
            color: var(--text);
            font-size: 13px;
            transition: var(--transition);
        }
        .search-box input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,115,232,.1);
            background: var(--bg-card);
            outline: none;
        }
        .search-box i {
            position: absolute;
            left: 12px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 13px;
        }

        /* ── Toast ────────────────────────────────────────────── */
        .toast-container-custom {
            position: fixed;
            top: 80px; right: 20px;
            z-index: 9999;
        }
        .toast-icu {
            background: var(--bg-card);
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
            padding: 14px 18px;
            min-width: 300px;
            display: flex; gap: 12px;
            animation: fadeInUp .4s ease;
        }

        /* ── Mobile Sidebar Overlay ───────────────────────────── */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 1039;
        }
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.show { display: block; }
            .main-wrapper { margin-left: 0; }
        }

        /* ── Form Styling ─────────────────────────────────────── */
        .form-label-icu {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 6px;
        }
        .form-control-icu, .form-select-icu {
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: var(--bg);
            color: var(--text);
            font-size: 13.5px;
            padding: 10px 14px;
            transition: var(--transition);
        }
        .form-control-icu:focus, .form-select-icu:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,115,232,.12);
            background: var(--bg-card);
            outline: none;
        }

        /* ── Alert Toasts from Laravel ────────────────────────── */
        .alert-flash {
            border-radius: 12px;
            border: none;
            font-size: 13.5px;
            font-weight: 500;
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- ── Sidebar ───────────────────────────────────────────────────────── -->
<aside class="sidebar" id="sidebar">
    <a href="{{ route('home') }}" class="sidebar-brand">
        <div class="sidebar-brand-icon"><i class="fa-solid fa-hospital-user"></i></div>
        <div class="sidebar-brand-text">
            <span class="sidebar-brand-name">Virtual ICU Visit</span>
            <span class="sidebar-brand-sub">Management System</span>
        </div>
    </a>

    <nav class="sidebar-nav">
        @auth
            @if(auth()->user()->isAdmin())
                <div class="nav-section-title">Admin</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-gauge-high"></i></span> Dashboard
                </a>
                <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-bed-pulse"></i></span> Patients
                </a>
                <a href="{{ route('visit-requests.index') }}" class="nav-link {{ request()->routeIs('visit-requests.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-calendar-check"></i></span> Visit Requests
                    @php $pending = \App\Models\VisitRequest::where('status','pending')->count(); @endphp
                    @if($pending > 0)<span class="badge bg-danger ms-auto rounded-pill" style="font-size:10px">{{ $pending }}</span>@endif
                </a>
                <a href="{{ route('meetings.index') }}" class="nav-link {{ request()->routeIs('meetings.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-video"></i></span> Meetings
                </a>
                <div class="nav-section-title">Management</div>
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-users"></i></span> Users
                </a>

            @elseif(auth()->user()->isDoctor())
                <div class="nav-section-title">Doctor</div>
                <a href="{{ route('doctor.dashboard') }}" class="nav-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-gauge-high"></i></span> Dashboard
                </a>
                <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-bed-pulse"></i></span> My Patients
                </a>
                <a href="{{ route('visit-requests.index') }}" class="nav-link {{ request()->routeIs('visit-requests.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-calendar-check"></i></span> Visit Requests
                </a>
                <a href="{{ route('meetings.index') }}" class="nav-link {{ request()->routeIs('meetings.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-video"></i></span> Meetings
                </a>

            @elseif(auth()->user()->isFamily())
                <div class="nav-section-title">Family</div>
                <a href="{{ route('family.dashboard') }}" class="nav-link {{ request()->routeIs('family.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-gauge-high"></i></span> Dashboard
                </a>
                <a href="{{ route('visit-requests.create') }}" class="nav-link {{ request()->routeIs('visit-requests.create') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-plus-circle"></i></span> Book a Visit
                </a>
                <a href="{{ route('visit-requests.index') }}" class="nav-link {{ request()->routeIs('visit-requests.index') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-list-check"></i></span> My Requests
                </a>
                <a href="{{ route('meetings.index') }}" class="nav-link {{ request()->routeIs('meetings.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fa-solid fa-video"></i></span> Meetings
                </a>
            @endif

            <div class="nav-section-title">Account</div>
            <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-bell"></i></span> Notifications
            </a>
            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-user-circle"></i></span> Profile
            </a>
        @endauth
    </nav>

    <div class="sidebar-footer">
        @auth
        <div class="sidebar-user">
            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar">
            <div>
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">{{ auth()->user()->role }}</div>
            </div>
        </div>
        @endauth
    </div>
</aside>

<!-- ── Main Wrapper ───────────────────────────────────────────────────── -->
<div class="main-wrapper">

    <!-- Top Navbar -->
    <header class="top-navbar">
        <button class="navbar-toggle d-lg-none" onclick="toggleSidebar()" aria-label="Toggle sidebar">
            <span></span><span></span><span></span>
        </button>

        <h1 class="page-title mb-0">@yield('page-title', 'Dashboard')</h1>

        <div class="d-flex align-items-center gap-2 ms-auto">

            <!-- Dark Mode Toggle -->
            <button class="dark-toggle" onclick="toggleDarkMode()" title="Toggle dark mode" id="darkToggle">
                <i class="fa-solid fa-moon" id="darkIcon"></i>
            </button>

            <!-- Notification Bell -->
            <div class="dropdown">
                <div class="notif-bell dropdown-toggle" data-bs-toggle="dropdown" id="notifBell" title="Notifications">
                    <i class="fa-solid fa-bell"></i>
                    <span class="notif-badge" id="notifBadge">0</span>
                </div>
                <div class="dropdown-menu notif-dropdown" id="notifDropdown">
                    <div class="d-flex align-items-center justify-content-between px-3 py-2" style="border-bottom:1px solid var(--border)">
                        <strong style="font-size:14px">Notifications</strong>
                        <a href="{{ route('notifications.mark-all-read') }}" class="text-primary" style="font-size:12px" onclick="event.preventDefault(); document.getElementById('markAllForm').submit()">Mark all read</a>
                        <form id="markAllForm" method="POST" action="{{ route('notifications.mark-all-read') }}" class="d-none">@csrf</form>
                    </div>
                    <div id="notifList">
                        <div class="text-center py-4 text-muted" style="font-size:13px">
                            <i class="fa-solid fa-bell-slash mb-2 d-block fa-2x opacity-25"></i>
                            No new notifications
                        </div>
                    </div>
                    <div class="text-center py-2" style="border-top:1px solid var(--border)">
                        <a href="{{ route('notifications.index') }}" class="text-primary" style="font-size:12px">View all</a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            @auth
            <div class="dropdown">
                <a class="profile-btn dropdown-toggle" data-bs-toggle="dropdown" href="#" id="profileBtn">
                    <img src="{{ auth()->user()->avatar_url }}" alt="Avatar">
                    <span class="profile-btn-name d-none d-sm-block">{{ Str::words(auth()->user()->name, 1, '') }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end mt-2" style="min-width:220px;border-radius:12px;border:1px solid var(--border);box-shadow:var(--shadow-lg)">
                    <li class="px-3 py-2">
                        <div style="font-weight:700;font-size:14px">{{ auth()->user()->name }}</div>
                        <div style="font-size:12px;color:var(--text-muted)">{{ auth()->user()->email }}</div>
                        <span class="badge mt-1" style="background:var(--primary-light);color:var(--primary);font-size:10px;text-transform:capitalize">{{ auth()->user()->role }}</span>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fa-solid fa-user-pen me-2 text-muted"></i>Profile Settings</a></li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fa-solid fa-right-from-bracket me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @endauth
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="toast-container-custom" id="flashContainer"></div>

    @if(session('success'))
    <div class="px-4 pt-3">
        <div class="alert alert-success alert-flash alert-dismissible fade show d-flex align-items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="px-4 pt-3">
        <div class="alert alert-danger alert-flash alert-dismissible fade show d-flex align-items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation"></i>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <!-- Page Content -->
    <main class="page-content">
        @yield('content')
    </main>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ── Dark Mode ─────────────────────────────────────────────────────── */
function toggleDarkMode() {
    const html = document.documentElement;
    const isDark = html.getAttribute('data-theme') === 'dark';
    html.setAttribute('data-theme', isDark ? 'light' : 'dark');
    localStorage.setItem('theme', isDark ? 'light' : 'dark');
    document.getElementById('darkIcon').className = isDark ? 'fa-solid fa-moon' : 'fa-solid fa-sun';
}
(function() {
    const theme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', theme);
    const icon = document.getElementById('darkIcon');
    if (icon) icon.className = theme === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
})();

/* ── Sidebar Toggle ────────────────────────────────────────────────── */
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}

/* ── Notification Polling ──────────────────────────────────────────── */
@auth
function fetchNotifications() {
    fetch('{{ route("notifications.fetch") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        const badge = document.getElementById('notifBadge');
        const list  = document.getElementById('notifList');
        if (data.count > 0) {
            badge.style.display = 'flex';
            badge.textContent   = data.count > 9 ? '9+' : data.count;
        } else {
            badge.style.display = 'none';
        }
        if (data.notifications.length > 0) {
            list.innerHTML = data.notifications.map(n => `
                <a href="${n.action_url || '#'}" class="notif-item unread">
                    <div style="font-size:13px;font-weight:600">${n.title}</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:2px">${n.message.substring(0,80)}${n.message.length>80?'...':''}</div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:4px"><i class="fa-regular fa-clock me-1"></i>${n.created_at}</div>
                </a>
            `).join('');
        }
    }).catch(() => {});
}
fetchNotifications();
setInterval(fetchNotifications, 30000);
@endauth

/* ── Stat Counter Animation ────────────────────────────────────────── */
document.querySelectorAll('[data-count]').forEach(el => {
    const target = parseInt(el.dataset.count);
    const duration = 1200;
    const step = target / (duration / 16);
    let current = 0;
    const timer = setInterval(() => {
        current = Math.min(current + step, target);
        el.textContent = Math.floor(current).toLocaleString();
        if (current >= target) clearInterval(timer);
    }, 16);
});
</script>

@stack('scripts')
</body>
</html>
