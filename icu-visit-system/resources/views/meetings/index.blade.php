@extends('layouts.app')
@section('title', 'Meetings')
@section('page-title', 'Meetings')

@section('content')
<div class="icu-card">
    <div class="d-flex align-items-center justify-content-between p-4 pb-2">
        <h6 class="mb-0 fw-bold">All Meetings</h6>
    </div>
    <div class="table-responsive">
        <table class="icu-table">
            <thead>
                <tr><th>Patient</th><th>Host Doctor</th><th>Scheduled</th><th>Duration</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
                @forelse($meetings as $meeting)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:13px">{{ $meeting->visitRequest->patient->name ?? 'N/A' }}</div>
                        <div style="font-size:11px;color:var(--text-muted)">Requested by: {{ $meeting->visitRequest->requester->name ?? 'N/A' }}</div>
                    </td>
                    <td style="font-size:13px">{{ $meeting->host->name ?? 'N/A' }}</td>
                    <td>
                        <div style="font-size:13px">{{ $meeting->scheduled_at->format('M d, Y') }}</div>
                        <div style="font-size:12px;color:var(--text-muted)">{{ $meeting->scheduled_at->format('h:i A') }}</div>
                    </td>
                    <td style="font-size:13px">{{ $meeting->duration_minutes ?? 30 }} min</td>
                    <td>
                        <span class="badge-icu status-{{ $meeting->status }}" style="font-size:11px">{{ ucfirst($meeting->status) }}</span>
                    </td>
                    <td>
                        @if(in_array($meeting->status, ['scheduled','active']))
                        <a href="{{ route('meetings.room', $meeting) }}" class="btn-icu btn-icu-primary" style="padding:5px 12px;font-size:11px">
                            <i class="fa-solid fa-video"></i> Join
                        </a>
                        @else
                        <span class="text-muted" style="font-size:12px">Ended</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">
                    <i class="fa-solid fa-video-slash fa-2x mb-2 d-block opacity-25"></i>No meetings found.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $meetings->links() }}</div>
</div>
@endsection
