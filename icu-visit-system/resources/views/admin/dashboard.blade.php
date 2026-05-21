@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="row g-4 animate-fade-up">
    <!-- Stats Row -->
    <div class="col-12 col-sm-6 col-xl-3 delay-1">
        <div class="stat-card primary">
            <div class="stat-icon primary"><i class="fa-solid fa-bed-pulse"></i></div>
            <div class="stat-number" data-count="{{ $stats['total_patients'] }}">0</div>
            <div class="stat-label">Total Patients</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3 delay-2">
        <div class="stat-card success">
            <div class="stat-icon success"><i class="fa-solid fa-user-doctor"></i></div>
            <div class="stat-number" data-count="{{ $stats['total_doctors'] }}">0</div>
            <div class="stat-label">Doctors</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3 delay-3">
        <div class="stat-card warning">
            <div class="stat-icon warning"><i class="fa-solid fa-hourglass-half"></i></div>
            <div class="stat-number" data-count="{{ $stats['pending_requests'] }}">0</div>
            <div class="stat-label">Pending Requests</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3 delay-4">
        <div class="stat-card danger">
            <div class="stat-icon danger"><i class="fa-solid fa-video"></i></div>
            <div class="stat-number" data-count="{{ $stats['total_meetings'] }}">0</div>
            <div class="stat-label">Total Meetings</div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1 animate-fade-up delay-2">
    <!-- Request Trend Chart -->
    <div class="col-12 col-xl-8">
        <div class="icu-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h6 class="mb-0 fw-700" style="font-weight:700">Visit Requests (Last 7 Days)</h6>
                    <small class="text-muted">Daily request volume trend</small>
                </div>
                <span class="badge-icu status-active"><span class="live-dot me-1"></span> Live</span>
            </div>
            <canvas id="trendChart" height="90"></canvas>
        </div>
    </div>

    <!-- Status Doughnut -->
    <div class="col-12 col-xl-4">
        <div class="icu-card p-4 h-100">
            <div class="mb-3">
                <h6 class="mb-0 fw-700" style="font-weight:700">Request Status</h6>
                <small class="text-muted">Distribution breakdown</small>
            </div>
            <canvas id="statusChart" height="200"></canvas>
        </div>
    </div>
</div>

<div class="row g-4 mt-1 animate-fade-up delay-3">
    <!-- Recent Requests -->
    <div class="col-12 col-xl-8">
        <div class="icu-card">
            <div class="d-flex align-items-center justify-content-between p-4 pb-2">
                <h6 class="mb-0 fw-700" style="font-weight:700">Recent Visit Requests</h6>
                <a href="{{ route('visit-requests.index') }}" class="btn-icu btn-icu-outline" style="padding:6px 14px;font-size:12px">View All</a>
            </div>
            <div class="table-responsive">
                <table class="icu-table">
                    <thead>
                        <tr>
                            <th>Patient</th><th>Requested By</th><th>Doctor</th><th>Date</th><th>Status</th><th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRequests as $req)
                        <tr>
                            <td>
                                <div class="fw-600" style="font-weight:600">{{ $req->patient->name ?? 'N/A' }}</div>
                                <div style="font-size:12px;color:var(--text-muted)">{{ $req->patient->ward ?? '' }}</div>
                            </td>
                            <td>{{ $req->requester->name ?? 'N/A' }}</td>
                            <td>{{ $req->assignedDoctor->name ?? 'Unassigned' }}</td>
                            <td>{{ $req->requested_date->format('M d, Y') }}</td>
                            <td>
                                <span class="badge-icu status-{{ $req->status }}">
                                    <i class="fa-solid {{ $req->status_icon }} me-1"></i>
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('visit-requests.show', $req) }}" class="btn-icu btn-icu-outline" style="padding:5px 10px;font-size:11px">Review</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No requests yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Critical Patients + Doctors -->
    <div class="col-12 col-xl-4">
        <div class="icu-card mb-4">
            <div class="p-4 pb-2">
                <h6 class="mb-1 fw-700" style="font-weight:700"><i class="fa-solid fa-heart-pulse text-danger me-2"></i>Critical Patients</h6>
            </div>
            <div class="px-4 pb-3">
                @forelse($criticalPatients as $p)
                <div class="d-flex align-items-center gap-3 py-2" style="border-bottom:1px solid var(--border)">
                    <div style="width:38px;height:38px;border-radius:50%;background:#ffebee;display:flex;align-items:center;justify-content:center;font-weight:700;color:#c62828;font-size:14px;flex-shrink:0">
                        {{ substr($p->name,0,1) }}
                    </div>
                    <div class="flex-1">
                        <div style="font-size:13px;font-weight:600">{{ $p->name }}</div>
                        <div style="font-size:11px;color:var(--text-muted)">{{ $p->bed_number }} · {{ $p->ward }}</div>
                    </div>
                    <span class="badge-icu status-critical" style="font-size:10px">Critical</span>
                </div>
                @empty
                <p class="text-muted text-center py-3" style="font-size:13px">No critical patients.</p>
                @endforelse
            </div>
        </div>

        <div class="icu-card">
            <div class="p-4 pb-2">
                <h6 class="mb-1 fw-700" style="font-weight:700"><i class="fa-solid fa-user-doctor text-primary me-2"></i>Doctors</h6>
            </div>
            <div class="px-4 pb-3">
                @foreach($doctors as $doc)
                <div class="d-flex align-items-center gap-3 py-2" style="border-bottom:1px solid var(--border)">
                    <img src="{{ $doc->avatar_url }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover">
                    <div class="flex-1">
                        <div style="font-size:13px;font-weight:600">{{ $doc->name }}</div>
                        <div style="font-size:11px;color:var(--text-muted)">{{ $doc->specialty }}</div>
                    </div>
                    <span style="font-size:12px;color:var(--text-muted)">{{ $doc->patients_count }} pts</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const trendLabels = @json(collect($trend)->pluck('label'));
const trendData   = @json(collect($trend)->pluck('count'));
const statusLabels = ['Pending', 'Approved', 'Rejected', 'Completed', 'Cancelled'];
const statusData   = [
    {{ $statusCounts['pending']   ?? 0 }},
    {{ $statusCounts['approved']  ?? 0 }},
    {{ $statusCounts['rejected']  ?? 0 }},
    {{ $statusCounts['completed'] ?? 0 }},
    {{ $statusCounts['cancelled'] ?? 0 }},
];

// Trend Chart
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: trendLabels,
        datasets: [{
            label: 'Requests',
            data: trendData,
            borderColor: '#1a73e8',
            backgroundColor: 'rgba(26,115,232,.08)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#1a73e8',
            pointRadius: 5,
            pointHoverRadius: 7,
            borderWidth: 2.5,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,.05)' } },
            x: { grid: { display: false } }
        }
    }
});

// Status Doughnut
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: statusLabels,
        datasets: [{
            data: statusData,
            backgroundColor: ['#fbbc04','#34a853','#ea4335','#1a73e8','#9e9e9e'],
            borderWidth: 0, hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 12 }, padding: 12 } }
        },
        cutout: '68%',
    }
});
</script>
@endpush
