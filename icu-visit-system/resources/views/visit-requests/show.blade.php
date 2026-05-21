@extends('layouts.app')
@section('title', 'Visit Request #' . $visitRequest->id)
@section('page-title', 'Visit Request Details')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('visit-requests.index') }}" class="btn-icu btn-icu-outline" style="padding:7px 14px"><i class="fa-solid fa-arrow-left"></i></a>
    <div class="flex-1">
        <h5 class="mb-0 fw-bold">Request #{{ $visitRequest->id }}</h5>
        <small class="text-muted">Submitted {{ $visitRequest->created_at->diffForHumans() }}</small>
    </div>
    <span class="badge-icu status-{{ $visitRequest->status }}" style="font-size:13px;padding:6px 14px">
        <i class="fa-solid {{ $visitRequest->status_icon }} me-1"></i>{{ ucfirst($visitRequest->status) }}
    </span>
</div>

<div class="row g-4">
    <!-- Left: Request Info -->
    <div class="col-12 col-lg-7">
        <div class="icu-card p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="fa-solid fa-info-circle text-primary me-2"></i>Request Information</h6>
            <div class="row g-3">
                <div class="col-6">
                    <div class="form-label-icu text-muted mb-1">Patient</div>
                    <div style="font-weight:600">{{ $visitRequest->patient->name ?? 'N/A' }}</div>
                    <div style="font-size:12px;color:var(--text-muted)">{{ $visitRequest->patient->ward ?? '' }} · Bed {{ $visitRequest->patient->bed_number ?? '' }}</div>
                </div>
                <div class="col-6">
                    <div class="form-label-icu text-muted mb-1">Requested By</div>
                    <div style="font-weight:600">{{ $visitRequest->requester->name ?? 'N/A' }}</div>
                    <div style="font-size:12px;color:var(--text-muted)">{{ $visitRequest->requester->email ?? '' }}</div>
                </div>
                <div class="col-6">
                    <div class="form-label-icu text-muted mb-1">Preferred Date</div>
                    <div style="font-weight:600">{{ $visitRequest->requested_date->format('l, M d Y') }}</div>
                </div>
                <div class="col-6">
                    <div class="form-label-icu text-muted mb-1">Preferred Time</div>
                    <div style="font-weight:600">{{ date('h:i A', strtotime($visitRequest->requested_time)) }}</div>
                </div>
                <div class="col-6">
                    <div class="form-label-icu text-muted mb-1">Assigned Doctor</div>
                    <div style="font-weight:600">{{ $visitRequest->assignedDoctor->name ?? 'Unassigned' }}</div>
                </div>
                <div class="col-12">
                    <div class="form-label-icu text-muted mb-1">Reason for Visit</div>
                    <div class="p-3" style="background:var(--bg);border-radius:10px;font-size:13.5px">{{ $visitRequest->reason }}</div>
                </div>
                @if($visitRequest->rejection_reason)
                <div class="col-12">
                    <div class="alert" style="background:#ffebee;border:none;border-radius:10px;border-left:4px solid var(--danger)">
                        <strong style="color:var(--danger)"><i class="fa-solid fa-times-circle me-1"></i>Rejection Reason:</strong>
                        <p class="mb-0 mt-1" style="font-size:13px">{{ $visitRequest->rejection_reason }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Meeting Info -->
        @if($visitRequest->meeting)
        <div class="icu-card p-4" style="border:2px solid rgba(26,115,232,.2)">
            <h6 class="fw-bold mb-3"><i class="fa-solid fa-video text-primary me-2"></i>Meeting Room</h6>
            <div class="row g-3">
                <div class="col-6">
                    <div class="form-label-icu text-muted mb-1">Scheduled</div>
                    <div style="font-weight:600">{{ $visitRequest->meeting->scheduled_at->format('M d, Y h:i A') }}</div>
                </div>
                <div class="col-6">
                    <div class="form-label-icu text-muted mb-1">Duration</div>
                    <div style="font-weight:600">{{ $visitRequest->meeting->duration_minutes ?? 30 }} minutes</div>
                </div>
                <div class="col-6">
                    <div class="form-label-icu text-muted mb-1">Room Name</div>
                    <div style="font-family:monospace;font-size:13px">{{ $visitRequest->meeting->room_name }}</div>
                </div>
                <div class="col-6">
                    <div class="form-label-icu text-muted mb-1">Password</div>
                    <div style="font-family:monospace;font-size:13px">{{ $visitRequest->meeting->room_password }}</div>
                </div>
                <div class="col-12">
                    <a href="{{ route('meetings.room', $visitRequest->meeting) }}" class="btn-icu btn-icu-primary w-100 justify-content-center" style="font-size:14px">
                        <i class="fa-solid fa-video"></i> Join Meeting Room
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Right: Actions -->
    <div class="col-12 col-lg-5">
        @if($visitRequest->status === 'pending' && (auth()->user()->isAdmin() || auth()->user()->isDoctor()))
        <div class="icu-card p-4 mb-4" style="border:2px solid rgba(52,168,83,.3)">
            <h6 class="fw-bold mb-3 text-success"><i class="fa-solid fa-check-circle me-2"></i>Approve Request</h6>
            <form method="POST" action="{{ route('visit-requests.approve', $visitRequest) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label-icu">Schedule Meeting At *</label>
                    <input type="datetime-local" name="scheduled_at" class="form-control form-control-icu @error('scheduled_at') is-invalid @enderror"
                        min="{{ now()->addHour()->format('Y-m-d\TH:i') }}" required>
                    @error('scheduled_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label-icu">Duration (minutes) *</label>
                    <select name="duration_minutes" class="form-select form-control-icu" required>
                        <option value="15">15 minutes</option>
                        <option value="30" selected>30 minutes</option>
                        <option value="45">45 minutes</option>
                        <option value="60">1 hour</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label-icu">Notes (optional)</label>
                    <textarea name="notes" class="form-control form-control-icu" rows="2" placeholder="Additional instructions…"></textarea>
                </div>
                <button type="submit" class="btn-icu btn-icu-success w-100 justify-content-center">
                    <i class="fa-solid fa-check"></i> Approve & Generate Room
                </button>
            </form>
        </div>

        <div class="icu-card p-4" style="border:2px solid rgba(234,67,53,.2)">
            <h6 class="fw-bold mb-3 text-danger"><i class="fa-solid fa-times-circle me-2"></i>Reject Request</h6>
            <form method="POST" action="{{ route('visit-requests.reject', $visitRequest) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label-icu">Reason for Rejection *</label>
                    <textarea name="rejection_reason" class="form-control form-control-icu @error('rejection_reason') is-invalid @enderror"
                        rows="3" placeholder="Explain why this request is being rejected…" required></textarea>
                    @error('rejection_reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn-icu btn-icu-danger w-100 justify-content-center" onclick="return confirm('Reject this visit request?')">
                    <i class="fa-solid fa-times"></i> Reject Request
                </button>
            </form>
        </div>
        @endif

        @if(in_array($visitRequest->status, ['pending','cancelled']) && auth()->user()->id === $visitRequest->requested_by)
        <div class="icu-card p-4" style="border:2px solid rgba(234,67,53,.2)">
            <h6 class="fw-bold mb-2 text-danger">Cancel Request</h6>
            <p class="text-muted mb-3" style="font-size:13px">You can delete this pending request if plans have changed.</p>
            <form method="POST" action="{{ route('visit-requests.destroy', $visitRequest) }}" onsubmit="return confirm('Delete this request?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-icu btn-icu-danger w-100 justify-content-center">
                    <i class="fa-solid fa-trash"></i> Delete Request
                </button>
            </form>
        </div>
        @endif

        <!-- Patient card quick view -->
        <div class="icu-card p-4 mt-4">
            <h6 class="fw-bold mb-3"><i class="fa-solid fa-bed-pulse text-primary me-2"></i>Patient Summary</h6>
            @if($visitRequest->patient)
            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="width:46px;height:46px;border-radius:50%;background:var(--primary-light);display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;color:var(--primary)">
                    {{ substr($visitRequest->patient->name,0,1) }}
                </div>
                <div>
                    <div style="font-weight:600">{{ $visitRequest->patient->name }}</div>
                    <div style="font-size:12px;color:var(--text-muted)">{{ $visitRequest->patient->diagnosis }}</div>
                </div>
            </div>
            <div class="d-flex flex-column gap-1">
                <div style="font-size:12.5px"><span class="text-muted">Status:</span> <span class="badge-icu status-{{ $visitRequest->patient->status }}" style="font-size:10px">{{ ucfirst($visitRequest->patient->status) }}</span></div>
                <div style="font-size:12.5px"><span class="text-muted">Bed:</span> {{ $visitRequest->patient->bed_number ?? 'N/A' }}</div>
                <div style="font-size:12.5px"><span class="text-muted">Ward:</span> {{ $visitRequest->patient->ward ?? 'N/A' }}</div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
