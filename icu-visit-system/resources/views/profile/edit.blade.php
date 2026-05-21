@extends('layouts.app')
@section('title', 'Profile Settings')
@section('page-title', 'Profile Settings')

@section('content')
<div class="row g-4">
    <!-- Profile Card -->
    <div class="col-12 col-lg-4">
        <div class="icu-card p-4 text-center">
            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar"
                style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--primary-light);margin-bottom:14px">
            <h5 class="fw-bold mb-1">{{ auth()->user()->name }}</h5>
            <p class="text-muted mb-2" style="font-size:13px">{{ auth()->user()->email }}</p>
            <span class="badge-icu" style="background:var(--primary-light);color:var(--primary);text-transform:capitalize">
                {{ auth()->user()->role }}
            </span>
            @if(auth()->user()->specialty)
            <p class="text-muted mt-2 mb-0" style="font-size:13px">{{ auth()->user()->specialty }}</p>
            @endif
        </div>
    </div>

    <!-- Update Form -->
    <div class="col-12 col-lg-8">
        <div class="icu-card p-4 mb-4">
            <h6 class="fw-bold mb-4" style="color:var(--primary)"><i class="fa-solid fa-user-pen me-2"></i>Personal Information</h6>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf @method('PATCH')
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label-icu">Full Name</label>
                        <input type="text" name="name" class="form-control form-control-icu @error('name') is-invalid @enderror"
                            value="{{ old('name', auth()->user()->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-icu">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-icu @error('email') is-invalid @enderror"
                            value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-icu">Phone Number</label>
                        <input type="text" name="phone" class="form-control form-control-icu"
                            value="{{ old('phone', auth()->user()->phone) }}" placeholder="+1-555-0000">
                    </div>
                    @if(auth()->user()->isDoctor())
                    <div class="col-12 col-md-6">
                        <label class="form-label-icu">Specialty</label>
                        <input type="text" name="specialty" class="form-control form-control-icu"
                            value="{{ old('specialty', auth()->user()->specialty) }}" placeholder="e.g. Intensivist">
                    </div>
                    @endif
                </div>

                @if(session('status') === 'profile-updated')
                <div class="alert alert-success mt-3 rounded-3" style="font-size:13px"><i class="fa-solid fa-check-circle me-2"></i>Profile updated successfully!</div>
                @endif

                <div class="mt-3">
                    <button type="submit" class="btn-icu btn-icu-primary"><i class="fa-solid fa-save"></i> Save Changes</button>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="icu-card p-4 mb-4">
            <h6 class="fw-bold mb-4" style="color:var(--primary)"><i class="fa-solid fa-lock me-2"></i>Change Password</h6>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label-icu">Current Password</label>
                        <input type="password" name="current_password" class="form-control form-control-icu @error('current_password','updatePassword') is-invalid @enderror" placeholder="Enter current password">
                        @error('current_password','updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-icu">New Password</label>
                        <input type="password" name="password" class="form-control form-control-icu @error('password','updatePassword') is-invalid @enderror" placeholder="Minimum 8 characters">
                        @error('password','updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label-icu">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control form-control-icu" placeholder="Repeat new password">
                    </div>
                </div>
                @if(session('status') === 'password-updated')
                <div class="alert alert-success mt-3 rounded-3" style="font-size:13px"><i class="fa-solid fa-check-circle me-2"></i>Password changed successfully!</div>
                @endif
                <div class="mt-3">
                    <button type="submit" class="btn-icu btn-icu-primary"><i class="fa-solid fa-key"></i> Update Password</button>
                </div>
            </form>
        </div>

        <!-- Danger Zone -->
        <div class="icu-card p-4" style="border:2px solid rgba(234,67,53,.2)">
            <h6 class="fw-bold mb-2 text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i>Danger Zone</h6>
            <p class="text-muted mb-3" style="font-size:13px">Deleting your account is permanent and cannot be undone. All your data will be removed.</p>
            <button type="button" class="btn-icu btn-icu-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="fa-solid fa-trash"></i> Delete Account
            </button>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i>Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="font-size:13.5px;color:var(--text-muted)">Are you sure? This action is irreversible. Enter your password to confirm.</p>
                <form method="POST" action="{{ route('profile.destroy') }}" id="deleteForm">
                    @csrf @method('DELETE')
                    <input type="password" name="password" class="form-control" placeholder="Your password" required>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-secondary btn-sm rounded-3" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger btn-sm rounded-3" onclick="document.getElementById('deleteForm').submit()">
                    Delete Permanently
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
