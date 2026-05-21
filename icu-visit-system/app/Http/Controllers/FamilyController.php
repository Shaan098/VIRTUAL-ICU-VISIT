<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\VisitRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamilyController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $myRequests = VisitRequest::where('requested_by', $user->id)
            ->with(['patient', 'meeting'])->latest()->take(5)->get();

        $upcomingMeeting = Meeting::whereHas('visitRequest', fn($q) =>
            $q->where('requested_by', $user->id)
        )->where('status', 'scheduled')
         ->where('scheduled_at', '>=', now())
         ->orderBy('scheduled_at')->first();

        $unreadCount = Notification::where('user_id', $user->id)
            ->whereNull('read_at')->count();

        $stats = [
            'total_requests'   => VisitRequest::where('requested_by', $user->id)->count(),
            'pending'          => VisitRequest::where('requested_by', $user->id)->where('status', 'pending')->count(),
            'approved'         => VisitRequest::where('requested_by', $user->id)->where('status', 'approved')->count(),
            'completed'        => VisitRequest::where('requested_by', $user->id)->where('status', 'completed')->count(),
        ];

        $patients = Patient::whereHas('visitRequests', fn($q) =>
            $q->where('requested_by', $user->id)
        )->take(5)->get();

        return view('family.dashboard', compact(
            'myRequests', 'upcomingMeeting', 'unreadCount', 'stats', 'patients'
        ));
    }
}
