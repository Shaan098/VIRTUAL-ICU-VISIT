@extends('layouts.app')
@section('title', 'Add Patient')
@section('page-title', 'Add New Patient')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-9">
        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('patients.index') }}" class="btn-icu btn-icu-outline" style="padding:7px 14px">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h5 class="mb-0 fw-bold">Add New Patient</h5>
                <small class="text-muted">Fill in the patient details below</small>
            </div>
        </div>

        <form method="POST" action="{{ route('patients.store') }}">
            @csrf
            <div class="row g-4">
                <!-- Personal Info -->
                <div class="col-12">
                    <div class="icu-card p-4">
                        <h6 class="fw-bold mb-4" style="color:var(--primary)"><i class="fa-solid fa-user me-2"></i>Personal Information</h6>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-icu">Full Name *</label>
                                <input type="text" name="name" class="form-control form-control-icu @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" placeholder="Patient full name" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label-icu">Age</label>
                                <input type="number" name="age" class="form-control form-control-icu" value="{{ old('age') }}" min="0" max="150" placeholder="e.g. 45">
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label-icu">Gender *</label>
                                <select name="gender" class="form-select form-control-icu" required>
                                    <option value="male"   {{ old('gender')=='male'   ? 'selected':'' }}>Male</option>
                                    <option value="female" {{ old('gender')=='female' ? 'selected':'' }}>Female</option>
                                    <option value="other"  {{ old('gender')=='other'  ? 'selected':'' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label-icu">Date of Birth</label>
                                <input type="date" name="dob" class="form-control form-control-icu" value="{{ old('dob') }}">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label-icu">Blood Group</label>
                                <select name="blood_group" class="form-select form-control-icu">
                                    <option value="">Select…</option>
                                    @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group')==$bg ? 'selected':'' }}>{{ $bg }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label-icu">Admission Date</label>
                                <input type="date" name="admission_date" class="form-control form-control-icu" value="{{ old('admission_date', date('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medical Info -->
                <div class="col-12">
                    <div class="icu-card p-4">
                        <h6 class="fw-bold mb-4" style="color:var(--primary)"><i class="fa-solid fa-stethoscope me-2"></i>Medical Information</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label-icu">Diagnosis / Condition *</label>
                                <input type="text" name="diagnosis" class="form-control form-control-icu @error('diagnosis') is-invalid @enderror"
                                    value="{{ old('diagnosis') }}" placeholder="Primary diagnosis or condition" required>
                                @error('diagnosis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label-icu">Status *</label>
                                <select name="status" class="form-select form-control-icu" required>
                                    <option value="active"     {{ old('status')=='active'     ? 'selected':'' }}>Active</option>
                                    <option value="critical"   {{ old('status')=='critical'   ? 'selected':'' }}>Critical</option>
                                    <option value="stable"     {{ old('status')=='stable'     ? 'selected':'' }}>Stable</option>
                                    <option value="discharged" {{ old('status')=='discharged' ? 'selected':'' }}>Discharged</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label-icu">Bed Number</label>
                                <input type="text" name="bed_number" class="form-control form-control-icu" value="{{ old('bed_number') }}" placeholder="e.g. A-01">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label-icu">Ward</label>
                                <input type="text" name="ward" class="form-control form-control-icu" value="{{ old('ward') }}" placeholder="e.g. ICU Ward A">
                            </div>
                            <div class="col-12">
                                <label class="form-label-icu">Assigned Doctor</label>
                                <select name="assigned_doctor_id" class="form-select form-control-icu">
                                    <option value="">— Not assigned —</option>
                                    @foreach($doctors as $doc)
                                    <option value="{{ $doc->id }}" {{ old('assigned_doctor_id')==$doc->id ? 'selected':'' }}>
                                        {{ $doc->name }} @if($doc->specialty)({{ $doc->specialty }})@endif
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label-icu">Clinical Notes</label>
                                <textarea name="notes" class="form-control form-control-icu" rows="3" placeholder="Additional notes…">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="col-12">
                    <div class="icu-card p-4">
                        <h6 class="fw-bold mb-4" style="color:var(--primary)"><i class="fa-solid fa-phone-volume me-2"></i>Emergency Contact</h6>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label-icu">Contact Name</label>
                                <input type="text" name="emergency_contact_name" class="form-control form-control-icu" value="{{ old('emergency_contact_name') }}" placeholder="Next of kin name">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label-icu">Contact Phone</label>
                                <input type="text" name="emergency_contact_phone" class="form-control form-control-icu" value="{{ old('emergency_contact_phone') }}" placeholder="+1-555-0000">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 d-flex gap-2 justify-content-end">
                    <a href="{{ route('patients.index') }}" class="btn-icu btn-icu-outline">Cancel</a>
                    <button type="submit" class="btn-icu btn-icu-primary">
                        <i class="fa-solid fa-save"></i> Save Patient
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
