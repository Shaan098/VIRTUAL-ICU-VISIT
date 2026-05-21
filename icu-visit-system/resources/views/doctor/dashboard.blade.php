@extends('layouts.app')
@section('title', 'Doctor Dashboard')
@section('page-title', 'Doctor Dashboard')

@section('content')
<div class="row g-4 animate-fade-up">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card primary">
            <div class="stat-icon primary"><i class="fa-solid fa-bed-pulse"></i></div>
            <div class="stat-number" data-count="{{ $stats['assigned_patients'] }}">0</div>
            <div class="stat-label">Assigned Patients</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card warning">
            <div class="stat-icon warning"><i class="fa-solid fa-clock"></i></div>
            <div class="stat-number" data-count="{{ $stats['pending_requests'] }}">0</div>
            <div class="stat-label">Pending Requests</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card success">
            <div class="stat-icon success"><i class="fa-solid fa-video"></i></div>
            <div class="stat-number" data-count="{{ $stats['upcoming_meetings'] }}">0</div>
            <div class="stat-label">Upcoming Meetings</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card danger">
            <div class="stat-icon danger"><i class="fa-solid fa-flag-checkered"></i></div>
            <div class="stat-number" data-count="{{ $stats['completed_today'] }}">0</div>
            <div class="stat-label">Completed Today</div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1 animate-fade-up delay-2">
    <!-- Upcoming Meetings -->
    <div class="col-12 col-xl-5">
        <div class="icu-card h-100">
            <div class="d-flex align-items-center justify-content-between p-4 pb-2">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-calendar-days text-primary me-2"></i>Upcoming Meetings</h6>
                <a href="{{ route('meetings.index') }}" class="btn-icu btn-icu-outline" style="padding:5px 12px;font-size:11px">All</a>
            </div>
            <div class="px-4 pb-3">
                @forelse($upcomingMeetings as $meeting)
                <div class="d-flex gap-3 py-3" style="border-bottom:1px solid var(--border)">
                    <div style="width:44px;height:44px;border-radius:10px;background:var(--primary-light);display:flex;align-items:center;justify-content:center;color:var(--primary);flex-shrink:0">
                        <i class="fa-solid fa-video"></i>
                    </div>
                    <div class="flex-1">
                        <div style="font-weight:600;font-size:13px">{{ $meeting->visitRequest->patient->name ?? 'N/A' }}</div>
                        <div style="font-size:12px;color:var(--text-muted)">
                            <i class="fa-regular fa-clock me-1"></i>{{ $meeting->scheduled_at->format('M d, Y h:i A') }}
                        </div>
                        <div style="font-size:11px;color:var(--text-muted)">Room: {{ $meeting->room_name }}</div>
                    </div>
                    <a href="{{ route('meetings.room', $meeting) }}" class="btn-icu btn-icu-primary" style="padding:6px 12px;font-size:11px;align-self:center">
                        <i class="fa-solid fa-video"></i> Join
                    </a>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-calendar-xmark fa-2x mb-2 d-block opacity-25"></i>
                    No upcoming meetings
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Visit Requests -->
    <div class="col-12 col-xl-7">
        <div class="icu-card h-100">
            <div class="d-flex align-items-center justify-content-between p-4 pb-2">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-list-check text-success me-2"></i>Recent Visit Requests</h6>
                <a href="{{ route('visit-requests.index') }}" class="btn-icu btn-icu-outline" style="padding:5px 12px;font-size:11px">All</a>
            </div>
            <div class="table-responsive">
                <table class="icu-table">
                    <thead><tr><th>Patient</th><th>Requester</th><th>Date</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @forelse($recentRequests as $req)
                        <tr>
                            <td><div style="font-weight:600;font-size:13px">{{ $req->patient->name ?? 'N/A' }}</div></td>
                            <td style="font-size:13px">{{ $req->requester->name ?? 'N/A' }}</td>
                            <td style="font-size:12px">{{ $req->requested_date->format('M d') }}</td>
                            <td><span class="badge-icu status-{{ $req->status }}" style="font-size:11px">{{ ucfirst($req->status) }}</span></td>
                            <td><a href="{{ route('visit-requests.show', $req) }}" class="btn-icu btn-icu-outline" style="padding:4px 10px;font-size:11px">Review</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No requests.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Recent Patients -->
<div class="row g-4 mt-1 animate-fade-up delay-3">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="mb-0 fw-bold">My Patients</h6>
            <a href="{{ route('patients.index') }}" class="btn-icu btn-icu-primary"><i class="fa-solid fa-bed-pulse"></i> View All</a>
        </div>
        <div class="row g-3">
            @forelse($recentPatients as $patient)
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="icu-card p-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div style="width:42px;height:42px;border-radius:50%;background:var(--primary-light);display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--primary);font-size:16px;flex-shrink:0">
                            {{ substr($patient->name,0,1) }}
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:13.5px">{{ $patient->name }}</div>
                            <div style="font-size:11px;color:var(--text-muted)">{{ $patient->bed_number }} · {{ $patient->ward }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="badge-icu status-{{ $patient->status }}" style="font-size:11px"><i class="fa-solid {{ $patient->status_icon }} me-1" style="font-size:9px"></i>{{ ucfirst($patient->status) }}</span>
                        <a href="{{ route('patients.show', $patient) }}" class="btn-icu btn-icu-outline" style="padding:4px 10px;font-size:11px">Details</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12"><p class="text-muted text-center">No patients assigned yet.</p></div>
            @endforelse
        </div>
    </div>
</div>
@endsection
