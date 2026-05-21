@extends('layouts.app')
@section('title', 'Visit Requests')
@section('page-title', 'Visit Requests')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-1 fw-bold">Visit Requests</h5>
        <small class="text-muted">{{ $requests->total() }} total records</small>
    </div>
    @if(auth()->user()->isFamily())
    <a href="{{ route('visit-requests.create') }}" class="btn-icu btn-icu-primary">
        <i class="fa-solid fa-plus"></i> New Request
    </a>
    @endif
</div>

<!-- Filter -->
<div class="icu-card p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-12 col-md-5">
            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input type="text" name="search" class="form-control" placeholder="Search by patient name…" value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-12 col-md-3">
            <select name="status" class="form-select form-select-sm" style="border-radius:10px">
                <option value="">All Status</option>
                @foreach(['pending','approved','rejected','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status')==$s ? 'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2"><button type="submit" class="btn-icu btn-icu-primary w-100"><i class="fa-solid fa-filter"></i> Filter</button></div>
        <div class="col-6 col-md-2"><a href="{{ route('visit-requests.index') }}" class="btn-icu btn-icu-outline w-100">Reset</a></div>
    </form>
</div>

<div class="icu-card">
    <div class="table-responsive">
        <table class="icu-table">
            <thead>
                <tr>
                    <th>#</th><th>Patient</th><th>Requested By</th><th>Doctor</th><th>Date & Time</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr>
                    <td style="font-size:12px;color:var(--text-muted)">{{ $req->id }}</td>
                    <td>
                        <div style="font-weight:600;font-size:13px">{{ $req->patient->name ?? 'N/A' }}</div>
                        <div style="font-size:11px;color:var(--text-muted)">{{ $req->patient->ward ?? '' }}</div>
                    </td>
                    <td style="font-size:13px">{{ $req->requester->name ?? 'N/A' }}</td>
                    <td style="font-size:13px">{{ $req->assignedDoctor->name ?? '—' }}</td>
                    <td>
                        <div style="font-size:13px">{{ $req->requested_date->format('M d, Y') }}</div>
                        <div style="font-size:12px;color:var(--text-muted)">{{ $req->requested_time }}</div>
                    </td>
                    <td>
                        <span class="badge-icu status-{{ $req->status }}">
                            <i class="fa-solid {{ $req->status_icon }}"></i> {{ ucfirst($req->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            <a href="{{ route('visit-requests.show', $req) }}" class="btn-icu btn-icu-outline" style="padding:5px 10px;font-size:11px">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if($req->meeting && in_array($req->meeting->status, ['scheduled','active']))
                            <a href="{{ route('meetings.room', $req->meeting) }}" class="btn-icu btn-icu-success" style="padding:5px 10px;font-size:11px">
                                <i class="fa-solid fa-video"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">
                    <i class="fa-solid fa-calendar-xmark fa-2x mb-2 d-block opacity-25"></i>No visit requests found.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $requests->withQueryString()->links() }}</div>
</div>
@endsection
