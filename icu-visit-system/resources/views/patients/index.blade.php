@extends('layouts.app')
@section('title', 'Patients')
@section('page-title', 'Patient Management')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-1 fw-bold">All Patients</h5>
        <small class="text-muted">{{ $patients->total() }} total records</small>
    </div>
    @can('admin', auth()->user())
    @endcan
    @if(auth()->user()->isAdmin() || auth()->user()->isDoctor())
    <a href="{{ route('patients.create') }}" class="btn-icu btn-icu-primary">
        <i class="fa-solid fa-plus"></i> Add Patient
    </a>
    @endif
</div>

<!-- Filters -->
<div class="icu-card p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-12 col-md-4">
            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input type="text" name="search" class="form-control" placeholder="Search name, diagnosis, bed…" value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <select name="status" class="form-select form-select-sm" style="border-radius:10px">
                <option value="">All Status</option>
                <option value="active"     {{ request('status')=='active'     ? 'selected':'' }}>Active</option>
                <option value="critical"   {{ request('status')=='critical'   ? 'selected':'' }}>Critical</option>
                <option value="stable"     {{ request('status')=='stable'     ? 'selected':'' }}>Stable</option>
                <option value="discharged" {{ request('status')=='discharged' ? 'selected':'' }}>Discharged</option>
            </select>
        </div>
        @if(auth()->user()->isAdmin())
        <div class="col-6 col-md-3">
            <select name="doctor" class="form-select form-select-sm" style="border-radius:10px">
                <option value="">All Doctors</option>
                @foreach($doctors as $doc)
                <option value="{{ $doc->id }}" {{ request('doctor')==$doc->id ? 'selected':'' }}>{{ $doc->name }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="col-6 col-md-2">
            <button type="submit" class="btn-icu btn-icu-primary w-100"><i class="fa-solid fa-filter"></i> Filter</button>
        </div>
        <div class="col-6 col-md-1">
            <a href="{{ route('patients.index') }}" class="btn-icu btn-icu-outline w-100"><i class="fa-solid fa-xmark"></i></a>
        </div>
    </form>
</div>

<!-- Patient Grid -->
<div class="row g-3">
    @forelse($patients as $patient)
    <div class="col-12 col-md-6 col-xl-4 animate-fade-up">
        <div class="icu-card p-4">
            <div class="d-flex align-items-start gap-3 mb-3">
                <div style="width:50px;height:50px;border-radius:14px;background:var(--primary-light);display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:700;color:var(--primary);flex-shrink:0">
                    {{ substr($patient->name,0,1) }}
                </div>
                <div class="flex-1">
                    <div style="font-weight:700;font-size:14px">{{ $patient->name }}</div>
                    <div style="font-size:12px;color:var(--text-muted)">
                        Age {{ $patient->age ?? 'N/A' }} · {{ ucfirst($patient->gender) }} · {{ $patient->blood_group ?? '' }}
                    </div>
                </div>
                <span class="badge-icu status-{{ $patient->status }}" style="font-size:11px">
                    <i class="fa-solid {{ $patient->status_icon }}"></i> {{ ucfirst($patient->status) }}
                </span>
            </div>

            <div class="d-flex flex-column gap-1 mb-3">
                <div style="font-size:12.5px"><i class="fa-solid fa-stethoscope me-2 text-primary opacity-60" style="width:14px"></i><strong>Dx:</strong> {{ Str::limit($patient->diagnosis, 45) }}</div>
                <div style="font-size:12.5px"><i class="fa-solid fa-bed me-2 text-primary opacity-60" style="width:14px"></i>Bed {{ $patient->bed_number ?? 'N/A' }} · {{ $patient->ward ?? 'N/A' }}</div>
                <div style="font-size:12.5px"><i class="fa-solid fa-user-doctor me-2 text-primary opacity-60" style="width:14px"></i>{{ $patient->assignedDoctor->name ?? 'Unassigned' }}</div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('patients.show', $patient) }}" class="btn-icu btn-icu-outline flex-1 justify-content-center" style="font-size:12px">
                    <i class="fa-solid fa-eye"></i> View
                </a>
                @if(auth()->user()->isAdmin() || auth()->user()->isDoctor())
                <a href="{{ route('patients.edit', $patient) }}" class="btn-icu btn-icu-primary flex-1 justify-content-center" style="font-size:12px">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="fa-solid fa-bed-pulse fa-3x mb-3 opacity-25" style="color:var(--text-muted)"></i>
        <h6 class="text-muted">No patients found</h6>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('patients.create') }}" class="btn-icu btn-icu-primary mt-2">Add First Patient</a>
        @endif
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $patients->withQueryString()->links() }}</div>
@endsection
