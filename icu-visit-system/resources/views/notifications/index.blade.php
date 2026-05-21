@extends('layouts.app')
@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="mb-1 fw-bold">All Notifications</h5>
        <small class="text-muted">{{ $notifications->total() }} total</small>
    </div>
    <form method="POST" action="{{ route('notifications.mark-all-read') }}">
        @csrf
        <button type="submit" class="btn-icu btn-icu-outline">
            <i class="fa-solid fa-check-double"></i> Mark All Read
        </button>
    </form>
</div>

<div class="icu-card">
    @forelse($notifications as $notif)
    <div class="notif-item {{ !$notif->isRead() ? 'unread' : '' }}" style="padding:16px 20px">
        <div class="d-flex align-items-start gap-3">
            <div style="width:38px;height:38px;border-radius:50%;background:{{ $notif->bg_color }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fa-solid {{ $notif->icon }}"></i>
            </div>
            <div class="flex-1">
                <div style="font-weight:600;font-size:14px">{{ $notif->title }}</div>
                <div style="font-size:13px;color:var(--text-muted);margin-top:2px">{{ $notif->message }}</div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:6px">
                    <i class="fa-regular fa-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
                    @if($notif->isRead())
                    · <i class="fa-solid fa-check-double text-success"></i> Read
                    @endif
                </div>
            </div>
            <div class="d-flex flex-column gap-1 align-items-end">
                @if(!$notif->isRead())
                <form method="POST" action="{{ route('notifications.read', $notif) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:11px;padding:4px 8px">Mark Read</button>
                </form>
                @endif
                @if($notif->action_url)
                <a href="{{ $notif->action_url }}" class="btn-icu btn-icu-outline" style="padding:4px 10px;font-size:11px">View</a>
                @endif
                <form method="POST" action="{{ route('notifications.destroy', $notif) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-size:11px;padding:4px 8px">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="fa-solid fa-bell-slash fa-3x mb-3 d-block" style="color:var(--text-muted);opacity:.2"></i>
        <h6 class="text-muted">No notifications</h6>
    </div>
    @endforelse

    <div class="p-3">{{ $notifications->links() }}</div>
</div>
@endsection
