@extends('layouts.app')
@section('title', 'Book a Visit')
@section('page-title', 'Book a Virtual Visit')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-8">
        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('visit-requests.index') }}" class="btn-icu btn-icu-outline" style="padding:7px 14px"><i class="fa-solid fa-arrow-left"></i></a>
            <div>
                <h5 class="mb-0 fw-bold">Request an ICU Virtual Visit</h5>
                <small class="text-muted">Connect with your loved one through a secure video call</small>
            </div>
        </div>

        <!-- Info Banner -->
        <div class="glass-panel p-3 mb-4" style="background:rgba(26,115,232,.06);border-color:rgba(26,115,232,.2)">
            <div class="d-flex gap-3">
                <i class="fa-solid fa-circle-info text-primary mt-1" style="font-size:18px"></i>
                <div style="font-size:13px">
                    <strong>How it works:</strong> Submit your request below. The assigned doctor will review and approve a meeting time. You'll receive a notification with a secure video link.
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('visit-requests.store') }}">
            @csrf
            <div class="icu-card p-4 mb-4">
                <h6 class="fw-bold mb-4" style="color:var(--primary)"><i class="fa-solid fa-hospital-user me-2"></i>Visit Details</h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label-icu">Select Patient *</label>
                        <select name="patient_id" class="form-select form-control-icu @error('patient_id') is-invalid @enderror" required>
                            <option value="">— Choose a patient —</option>
                            @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id')==$patient->id ? 'selected':'' }}>
                                {{ $patient->name }} — {{ $patient->ward }} (Bed {{ $patient->bed_number }})
                            </option>
                            @endforeach
                        </select>
                        @error('patient_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-icu">Preferred Date *</label>
                        <input type="date" name="requested_date" class="form-control form-control-icu @error('requested_date') is-invalid @enderror"
                            value="{{ old('requested_date') }}" min="{{ date('Y-m-d') }}" required>
                        @error('requested_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-icu">Preferred Time *</label>
                        <select name="requested_time" class="form-select form-control-icu @error('requested_time') is-invalid @enderror" required>
                            <option value="">— Select time slot —</option>
                            @foreach(['09:00','10:00','11:00','14:00','15:00','16:00','17:00'] as $t)
                            <option value="{{ $t }}" {{ old('requested_time')==$t ? 'selected':'' }}>{{ date('h:i A', strtotime($t)) }}</option>
                            @endforeach
                        </select>
                        @error('requested_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label-icu">Reason for Visit *</label>
                        <textarea name="reason" class="form-control form-control-icu @error('reason') is-invalid @enderror"
                            rows="4" placeholder="Please describe the purpose of your visit and anything the doctor should know…" required>{{ old('reason') }}</textarea>
                        @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('visit-requests.index') }}" class="btn-icu btn-icu-outline">Cancel</a>
                <button type="submit" class="btn-icu btn-icu-primary">
                    <i class="fa-solid fa-paper-plane"></i> Submit Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
