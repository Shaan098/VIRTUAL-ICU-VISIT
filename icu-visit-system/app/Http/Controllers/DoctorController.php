<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Patient;
use App\Models\VisitRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function dashboard()
    {
        $doctor = Auth::user();

        $assignedPatients = Patient::where('assigned_doctor_id', $doctor->id)
            ->whereIn('status', ['active', 'critical', 'stable'])->count();

        $pendingRequests = VisitRequest::where('assigned_doctor_id', $doctor->id)
            ->where('status', 'pending')->count();

        $upcomingMeetings = Meeting::whereHas('visitRequest', function ($q) use ($doctor) {
            $q->where('assigned_doctor_id', $doctor->id);
        })->where('status', 'scheduled')
          ->where('scheduled_at', '>=', now())
          ->orderBy('scheduled_at')->take(5)->get();

        $recentPatients = Patient::where('assigned_doctor_id', $doctor->id)
            ->latest()->take(6)->get();

        $recentRequests = VisitRequest::where('assigned_doctor_id', $doctor->id)
            ->with(['patient', 'requester'])->latest()->take(6)->get();

        $stats = [
            'assigned_patients' => $assignedPatients,
            'pending_requests'  => $pendingRequests,
            'upcoming_meetings' => Meeting::whereHas('visitRequest', fn($q) =>
                $q->where('assigned_doctor_id', $doctor->id)
            )->where('status', 'scheduled')->count(),
            'completed_today'   => VisitRequest::where('assigned_doctor_id', $doctor->id)
                ->where('status', 'completed')->whereDate('updated_at', today())->count(),
        ];

        return view('doctor.dashboard', compact(
            'stats', 'upcomingMeetings', 'recentPatients', 'recentRequests'
        ));
    }
}
