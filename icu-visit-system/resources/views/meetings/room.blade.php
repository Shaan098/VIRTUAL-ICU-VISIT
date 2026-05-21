@extends('layouts.app')
@section('title', 'Meeting Room — ' . $meeting->room_name)
@section('page-title', 'Video Meeting Room')

@push('styles')
<style>
    .page-content { padding: 0 !important; }
    .meeting-wrapper {
        display: flex;
        flex-direction: column;
        height: calc(100vh - var(--navbar-height));
        background: #0d1117;
    }
    .meeting-topbar {
        display: flex; align-items: center; gap: 16px;
        padding: 12px 20px;
        background: rgba(255,255,255,.05);
        border-bottom: 1px solid rgba(255,255,255,.1);
        flex-shrink: 0;
    }
    .meeting-info-chip {
        display: flex; align-items: center; gap: 8px;
        padding: 5px 12px;
        border-radius: 20px;
        background: rgba(255,255,255,.08);
        font-size: 12px; color: rgba(255,255,255,.8);
    }
    .jitsi-container {
        flex: 1;
        position: relative;
    }
    #jitsi-meet-iframe {
        width: 100%; height: 100%;
        border: none;
    }
    .meeting-loading {
        position: absolute; inset: 0;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        background: #0d1117; color: rgba(255,255,255,.7);
        gap: 16px; z-index: 10;
    }
    .spinner-ring {
        width: 52px; height: 52px;
        border: 3px solid rgba(255,255,255,.1);
        border-top-color: #1a73e8;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

@section('content')
<div class="meeting-wrapper">
    <!-- Meeting top bar -->
    <div class="meeting-topbar">
        <a href="{{ route('visit-requests.show', $meeting->visitRequest) }}" class="btn-icu" style="background:rgba(255,255,255,.1);color:#fff;padding:6px 12px;font-size:12px">
            <i class="fa-solid fa-arrow-left"></i>
        </a>

        <div class="meeting-info-chip">
            <span class="live-dot" style="background:#4cff91"></span>
            <span>Live</span>
        </div>
        <div class="meeting-info-chip">
            <i class="fa-solid fa-bed-pulse"></i>
            <span>{{ $meeting->visitRequest->patient->name ?? 'Patient' }}</span>
        </div>
        <div class="meeting-info-chip">
            <i class="fa-solid fa-clock"></i>
            <span id="meeting-timer">00:00</span>
        </div>

        <div class="ms-auto d-flex gap-2">
            <div class="meeting-info-chip">
                <i class="fa-solid fa-key"></i>
                <span>{{ $meeting->room_password }}</span>
            </div>

            @if(auth()->user()->isAdmin() || auth()->user()->isDoctor())
            <form method="POST" action="{{ route('meetings.end', $meeting) }}">
                @csrf
                <button type="submit" class="btn-icu btn-icu-danger" style="font-size:12px;padding:6px 14px" onclick="return confirm('End this meeting?')">
                    <i class="fa-solid fa-phone-slash"></i> End Meeting
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Jitsi Frame -->
    <div class="jitsi-container">
        <div class="meeting-loading" id="meetingLoader">
            <div class="spinner-ring"></div>
            <div>
                <div style="font-size:16px;font-weight:600;margin-bottom:4px">Connecting to meeting room…</div>
                <div style="font-size:13px;color:rgba(255,255,255,.5)">Room: {{ $meeting->room_name }}</div>
            </div>
        </div>
        <div id="jitsi-meet-iframe"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://meet.jit.si/external_api.js"></script>
<script>
const domain   = 'meet.jit.si';
const roomName = '{{ $meeting->room_name }}';
const password = '{{ $meeting->room_password }}';
const userName = '{{ auth()->user()->name }}';
const userEmail= '{{ auth()->user()->email }}';

const options = {
    roomName: roomName,
    width: '100%',
    height: '100%',
    parentNode: document.getElementById('jitsi-meet-iframe'),
    userInfo: { displayName: userName, email: userEmail },
    configOverwrite: {
        startWithAudioMuted: false,
        startWithVideoMuted: false,
        prejoinPageEnabled: false,
        disableDeepLinking: true,
        subject: 'ICU Virtual Visit — {{ $meeting->visitRequest->patient->name ?? "Patient" }}',
    },
    interfaceConfigOverwrite: {
        SHOW_JITSI_WATERMARK: false,
        SHOW_WATERMARK_FOR_GUESTS: false,
        TOOLBAR_BUTTONS: [
            'microphone','camera','desktop','fullscreen',
            'fodeviceselection','hangup','chat','tileview',
            'videoquality','filmstrip','shortcuts','raisehand','participants-pane'
        ],
    },
};

const api = new JitsiMeetExternalAPI(domain, options);

api.addEventListener('videoConferenceJoined', () => {
    document.getElementById('meetingLoader').style.display = 'none';
    // Set password
    api.executeCommand('password', password);
    startTimer();
});

api.addEventListener('readyToClose', () => {
    window.location.href = '{{ route("visit-requests.show", $meeting->visitRequest) }}';
});

// Meeting timer
let seconds = 0;
function startTimer() {
    setInterval(() => {
        seconds++;
        const m = String(Math.floor(seconds/60)).padStart(2,'0');
        const s = String(seconds%60).padStart(2,'0');
        document.getElementById('meeting-timer').textContent = m+':'+s;
    }, 1000);
}
</script>
@endpush
