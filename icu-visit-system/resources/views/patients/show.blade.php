@extends('layouts.app')
@section('title', $patient->name)
@section('page-title', 'Patient Details')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('patients.index') }}" class="btn-icu btn-icu-outline" style="padding:7px 14px"><i class="fa-solid fa-arrow-left"></i></a>
    <div class="flex-1">
        <h5 class="mb-0 fw-bold">{{ $patient->name }}</h5>
        <small class="text-muted">Patient Profile</small>
    </div>
    @if(auth()->user()->isAdmin() || auth()->user()->isDoctor())
    <div class="d-flex gap-2">
        <a href="{{ route('patients.edit', $patient) }}" class="btn-icu btn-icu-primary"><i class="fa-solid fa-pen"></i> Edit</a>
        <form method="POST" action="{{ route('patients.destroy', $patient) }}" onsubmit="return confirm('Delete this patient?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-icu btn-icu-danger"><i class="fa-solid fa-trash"></i> Delete</button>
        </form>
    </div>
    @endif
</div>

<div class="row g-4">
    <!-- Profile Card -->
    <div class="col-12 col-lg-4">
        <div class="icu-card p-4 text-center mb-4">
            <div style="width:80px;height:80px;border-radius:50%;background:var(--primary-light);display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:700;color:var(--primary);margin:0 auto 12px">
                {{ substr($patient->name,0,1) }}
            </div>
            <h5 class="fw-bold mb-1">{{ $patient->name }}</h5>
            <p class="text-muted mb-2" style="font-size:13px">Age {{ $patient->age ?? 'N/A' }} · {{ ucfirst($patient->gender) }}</p>
            <span class="badge-icu status-{{ $patient->status }}">
                <i class="fa-solid {{ $patient->status_icon }}"></i> {{ ucfirst($patient->status) }}
            </span>

            <hr class="my-3">
            <div class="d-flex flex-column gap-2 text-start">
                <div class="d-flex justify-content-between" style="font-size:13px">
                    <span class="text-muted">Blood Group</span><strong>{{ $patient->blood_group ?? 'N/A' }}</strong>
                </div>
                <div class="d-flex justify-content-between" style="font-size:13px">
                    <span class="text-muted">Bed</span><strong>{{ $patient->bed_number ?? 'N/A' }}</strong>
                </div>
                <div class="d-flex justify-content-between" style="font-size:13px">
                    <span class="text-muted">Ward</span><strong>{{ $patient->ward ?? 'N/A' }}</strong>
                </div>
                <div class="d-flex justify-content-between" style="font-size:13px">
                    <span class="text-muted">Admitted</span><strong>{{ $patient->admission_date?->format('M d, Y') ?? 'N/A' }}</strong>
                </div>
            </div>
        </div>

        <div class="icu-card p-4">
            <h6 class="fw-bold mb-3"><i class="fa-solid fa-user-doctor text-primary me-2"></i>Assigned Doctor</h6>
            @if($patient->assignedDoctor)
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $patient->assignedDoctor->avatar_url }}" style="width:42px;height:42px;border-radius:50%;object-fit:cover">
                <div>
                    <div style="font-weight:600;font-size:13.5px">{{ $patient->assignedDoctor->name }}</div>
                    <div style="font-size:12px;color:var(--text-muted)">{{ $patient->assignedDoctor->specialty ?? 'Doctor' }}</div>
                </div>
            </div>
            @else
            <p class="text-muted" style="font-size:13px">No doctor assigned</p>
            @endif
        </div>
    </div>

    <!-- Details -->
    <div class="col-12 col-lg-8">
        <div class="icu-card p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="fa-solid fa-stethoscope text-primary me-2"></i>Medical Information</h6>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label-icu">Diagnosis</label>
                    <div class="p-3" style="background:var(--bg);border-radius:10px;font-size:13.5px">{{ $patient->diagnosis }}</div>
                </div>
                @if($patient->notes)
                <div class="col-12">
                    <label class="form-label-icu">Clinical Notes</label>
                    <div class="p-3" style="background:var(--bg);border-radius:10px;font-size:13.5px">{{ $patient->notes }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="icu-card p-4 mb-4">
            <h6 class="fw-bold mb-3"><i class="fa-solid fa-phone-volume text-primary me-2"></i>Emergency Contact</h6>
            <div class="row g-2">
                <div class="col-6" style="font-size:13px"><span class="text-muted">Name:</span> {{ $patient->emergency_contact_name ?? 'N/A' }}</div>
                <div class="col-6" style="font-size:13px"><span class="text-muted">Phone:</span> {{ $patient->emergency_contact_phone ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Visit History -->
        <div class="icu-card">
            <div class="d-flex align-items-center justify-content-between p-4 pb-2">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-calendar-check text-primary me-2"></i>Visit History</h6>
                @if(auth()->user()->isFamily())
                <a href="{{ route('visit-requests.create') }}" class="btn-icu btn-icu-primary" style="font-size:12px;padding:6px 12px">
                    <i class="fa-solid fa-plus"></i> Request Visit
                </a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="icu-table">
                    <thead><tr><th>Requested By</th><th>Date</th><th>Status</th><th>Meeting</th></tr></thead>
                    <tbody>
                        @forelse($patient->visitRequests as $req)
                        <tr>
                            <td style="font-size:13px">{{ $req->requester->name ?? 'N/A' }}</td>
                            <td style="font-size:13px">{{ $req->requested_date->format('M d, Y') }}</td>
                            <td><span class="badge-icu status-{{ $req->status }}" style="font-size:11px">{{ ucfirst($req->status) }}</span></td>
                            <td>
                                @if($req->meeting)
                                <a href="{{ route('meetings.room', $req->meeting) }}" class="btn-icu btn-icu-outline" style="font-size:11px;padding:4px 8px">
                                    <i class="fa-solid fa-video"></i> Room
                                </a>
                                @else
                                <span class="text-muted" style="font-size:12px">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted" style="font-size:13px">No visit history.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
