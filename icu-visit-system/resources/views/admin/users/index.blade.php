@extends('layouts.app')
@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-1 fw-bold">All Users</h5>
        <small class="text-muted">Manage admin, doctors and family accounts</small>
    </div>
</div>

<!-- Filter Bar -->
<div class="icu-card p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-12 col-md-5">
            <div class="search-box">
                <i class="fa-solid fa-search"></i>
                <input type="text" name="search" class="form-control" placeholder="Search name or email…" value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-12 col-md-3">
            <select name="role" class="form-select form-select-sm" style="border-radius:10px">
                <option value="">All Roles</option>
                <option value="admin"  {{ request('role')=='admin'  ? 'selected':'' }}>Admin</option>
                <option value="doctor" {{ request('role')=='doctor' ? 'selected':'' }}>Doctor</option>
                <option value="family" {{ request('role')=='family' ? 'selected':'' }}>Family</option>
            </select>
        </div>
        <div class="col-12 col-md-2">
            <button type="submit" class="btn-icu btn-icu-primary w-100"><i class="fa-solid fa-filter"></i> Filter</button>
        </div>
        <div class="col-12 col-md-2">
            <a href="{{ route('admin.users') }}" class="btn-icu btn-icu-outline w-100"><i class="fa-solid fa-xmark"></i> Reset</a>
        </div>
    </form>
</div>

<div class="icu-card">
    <div class="table-responsive">
        <table class="icu-table">
            <thead>
                <tr><th>User</th><th>Email</th><th>Role</th><th>Phone</th><th>Status</th><th>Joined</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $user->avatar_url }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover">
                            <div>
                                <div style="font-weight:600;font-size:13.5px">{{ $user->name }}</div>
                                @if($user->specialty)<div style="font-size:11px;color:var(--text-muted)">{{ $user->specialty }}</div>@endif
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px">{{ $user->email }}</td>
                    <td>
                        <span class="badge-icu" style="background:var(--primary-light);color:var(--primary);text-transform:capitalize">{{ $user->role }}</span>
                    </td>
                    <td style="font-size:13px">{{ $user->phone ?? '—' }}</td>
                    <td>
                        @if($user->is_active)
                            <span class="badge-icu status-approved"><i class="fa-solid fa-circle" style="font-size:7px"></i> Active</span>
                        @else
                            <span class="badge-icu status-rejected"><i class="fa-solid fa-circle" style="font-size:7px"></i> Inactive</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:var(--text-muted)">{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm" style="border-radius:8px;font-size:11px;padding:4px 8px"
                                    title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}"
                                    class="{{ $user->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                    <i class="fa-solid {{ $user->is_active ? 'fa-toggle-on text-success' : 'fa-toggle-off text-warning' }}"></i>
                                </button>
                            </form>
                            @if(!$user->isAdmin())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-size:11px;padding:4px 8px">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted"><i class="fa-solid fa-users-slash fa-2x mb-2 d-block opacity-25"></i>No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $users->withQueryString()->links() }}</div>
</div>
@endsection
