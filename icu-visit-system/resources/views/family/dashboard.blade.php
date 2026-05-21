@extends('layouts.app')
@section('title', 'Family Dashboard')
@section('page-title', 'Family Dashboard')

@section('content')
<!-- Hero Welcome Banner -->
<div class="glass-panel p-4 mb-4 animate-fade-up" style="background:linear-gradient(135deg,rgba(26,115,232,.12),rgba(0,188,212,.08));border:1px solid rgba(26,115,232,.2)">
    <div class="d-flex align-items-center gap-3">
        <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;flex-shrink:0">
            <i class="fa-solid fa-heart"></i>
        </div>
        <div class="flex-1">
            <h5 class="mb-1 fw-bold">Welcome, {{ auth()->user()->name }} 👋</h5>
            <p class="mb-0 text-muted" style="font-size:13.5px">Stay connected with your loved ones in the ICU. Request a virtual visit anytime.</p>
        </div>
        <a href="{{ route('visit-requests.create') }}" class="btn-icu btn-icu-primary flex-shrink-0">
            <i class="fa-solid fa-plus-circle"></i> Book a Visit
        </a>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 animate-fade-up delay-1">
    <div class="col-6 col-lg-3">
        <div class="stat-card primary">
            <div class="stat-icon primary"><i class="fa-solid fa-calendar-plus"></i></div>
            <div class="stat-number" data-count="{{ $stats['total_requests'] }}">0</div>
            <div class="stat-label">Total Requests</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card warning">
            <div class="stat-icon warning"><i class="fa-solid fa-hourglass-half"></i></div>
            <div class="stat-number" data-count="{{ $stats['pending'] }}">0</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card success">
            <div class="stat-icon success"><i class="fa-solid fa-check-circle"></i></div>
            <div class="stat-number" data-count="{{ $stats['approved'] }}">0</div>
            <div class="stat-label">Approved</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card danger">
            <div class="stat-icon danger"><i class="fa-solid fa-flag-checkered"></i></div>
            <div class="stat-number" data-count="{{ $stats['completed'] }}">0</div>
            <div class="stat-label">Completed</div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1 animate-fade-up delay-2">
    <!-- Upcoming Meeting Card -->
    <div class="col-12 col-lg-5">
        @if($upcomingMeeting)
        <div class="icu-card p-4" style="background:linear-gradient(135deg,#1a73e8,#0f4c81);color:#fff;border:none">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="fa-solid fa-video fa-lg"></i>
                <h6 class="mb-0 fw-bold">Upcoming Meeting</h6>
                <span class="badge ms-auto" style="background:rgba(255,255,255,.2)"><span class="live-dot me-1" style="background:#4cff91"></span> Scheduled</span>
            </div>
            <div class="mb-3">
                <div style="font-size:18px;font-weight:700">{{ $upcomingMeeting->visitRequest->patient->name ?? 'Patient' }}</div>
                <div style="font-size:13px;opacity:.8;margin-top:4px">
                    <i class="fa-regular fa-calendar me-1"></i>{{ $upcomingMeeting->scheduled_at->format('l, M d Y') }}
                </div>
                <div style="font-size:13px;opacity:.8">
                    <i class="fa-regular fa-clock me-1"></i>{{ $upcomingMeeting->scheduled_at->format('h:i A') }}
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('meetings.room', $upcomingMeeting) }}" class="btn-icu flex-1" style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.4);text-align:center">
                    <i class="fa-solid fa-video"></i> Join Meeting
                </a>
            </div>
        </div>
        @else
        <div class="icu-card p-4 text-center" style="border:2px dashed var(--border)">
            <i class="fa-solid fa-video-slash fa-3x mb-3" style="color:var(--text-muted);opacity:.3"></i>
            <h6 class="fw-bold">No Upcoming Meetings</h6>
            <p class="text-muted mb-3" style="font-size:13px">Submit a visit request to connect with your loved one.</p>
            <a href="{{ route('visit-requests.create') }}" class="btn-icu btn-icu-primary">
                <i class="fa-solid fa-plus"></i> Request a Visit
            </a>
        </div>
        @endif
    </div>

    <!-- Recent Requests -->
    <div class="col-12 col-lg-7">
        <div class="icu-card h-100">
            <div class="d-flex align-items-center justify-content-between p-4 pb-2">
                <h6 class="mb-0 fw-bold">My Visit Requests</h6>
                <a href="{{ route('visit-requests.index') }}" class="btn-icu btn-icu-outline" style="padding:5px 12px;font-size:11px">View All</a>
            </div>
            <div class="px-4 pb-3">
                @forelse($myRequests as $req)
                <div class="d-flex align-items-center gap-3 py-3" style="border-bottom:1px solid var(--border)">
                    <div style="width:40px;height:40px;border-radius:10px;background:var(--primary-light);display:flex;align-items:center;justify-content:center;color:var(--primary);flex-shrink:0">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <div class="flex-1">
                        <div style="font-weight:600;font-size:13px">{{ $req->patient->name ?? 'N/A' }}</div>
                        <div style="font-size:12px;color:var(--text-muted)">{{ $req->requested_date->format('M d, Y') }} at {{ $req->requested_time }}</div>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-1">
                        <span class="badge-icu status-{{ $req->status }}" style="font-size:10px">{{ ucfirst($req->status) }}</span>
                        @if($req->meeting && in_array($req->meeting->status, ['scheduled','active']))
                        <a href="{{ route('meetings.room', $req->meeting) }}" class="btn-icu btn-icu-success" style="padding:3px 8px;font-size:10px">
                            <i class="fa-solid fa-video"></i> Join
                        </a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fa-solid fa-clipboard-list fa-2x mb-2 d-block" style="color:var(--text-muted);opacity:.25"></i>
                    <p class="text-muted mb-3" style="font-size:13px">No requests yet.</p>
                    <a href="{{ route('visit-requests.create') }}" class="btn-icu btn-icu-primary">Book First Visit</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
