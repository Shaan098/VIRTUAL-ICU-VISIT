<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\User;
use App\Models\VisitRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitRequestController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = VisitRequest::with(['patient', 'requester', 'assignedDoctor', 'meeting']);

        if ($user->isFamily()) {
            $query->where('requested_by', $user->id);
        } elseif ($user->isDoctor()) {
            $query->where('assigned_doctor_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->whereHas('patient', fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
            );
        }

        $requests = $query->latest()->paginate(10);
        return view('visit-requests.index', compact('requests'));
    }

    public function create()
    {
        $patients = Patient::whereIn('status', ['active', 'critical', 'stable'])->orderBy('name')->get();
        $doctors  = User::where('role', 'doctor')->orderBy('name')->get();
        return view('visit-requests.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'         => 'required|exists:patients,id',
            'assigned_doctor_id' => 'nullable|exists:users,id',
            'requested_date'     => 'required|date|after_or_equal:today',
            'requested_time'     => 'required',
            'reason'             => 'required|string|max:1000',
        ]);

        $validated['requested_by'] = Auth::id();
        $validated['status']       = 'pending';

        // If doctor not selected, use patient's assigned doctor
        if (empty($validated['assigned_doctor_id'])) {
            $patient = Patient::find($validated['patient_id']);
            $validated['assigned_doctor_id'] = $patient?->assigned_doctor_id;
        }

        $visitRequest = VisitRequest::create($validated);

        // Notify admin and doctor
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id'       => $admin->id,
                'title'         => 'New Visit Request',
                'message'       => Auth::user()->name . ' has requested a visit for patient ' . $visitRequest->patient->name,
                'type'          => 'info',
                'related_model' => 'VisitRequest',
                'related_id'    => $visitRequest->id,
                'action_url'    => route('visit-requests.show', $visitRequest),
            ]);
        }

        if ($visitRequest->assigned_doctor_id) {
            Notification::create([
                'user_id'       => $visitRequest->assigned_doctor_id,
                'title'         => 'New Visit Request',
                'message'       => 'A visit request for your patient ' . $visitRequest->patient->name . ' needs your review.',
                'type'          => 'info',
                'related_model' => 'VisitRequest',
                'related_id'    => $visitRequest->id,
                'action_url'    => route('visit-requests.show', $visitRequest),
            ]);
        }

        return redirect()->route('visit-requests.index')
            ->with('success', 'Visit request submitted successfully! You will be notified once approved.');
    }

    public function show(VisitRequest $visitRequest)
    {
        $visitRequest->load(['patient', 'requester', 'assignedDoctor', 'meeting']);
        return view('visit-requests.show', compact('visitRequest'));
    }

    public function approve(Request $request, VisitRequest $visitRequest)
    {
        $request->validate([
            'scheduled_at'     => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:5|max:120',
        ]);

        $visitRequest->update(['status' => 'approved']);

        // Generate Jitsi room
        $roomName     = 'ICUVisit-' . strtoupper(substr(md5($visitRequest->id . time()), 0, 10));
        $roomPassword = strtoupper(substr(md5(rand()), 0, 12));

        Meeting::create([
            'visit_request_id' => $visitRequest->id,
            'room_name'        => $roomName,
            'room_password'    => $roomPassword,
            'jitsi_url'        => "https://meet.jit.si/{$roomName}",
            'scheduled_at'     => $request->scheduled_at,
            'status'           => 'scheduled',
            'host_id'          => $visitRequest->assigned_doctor_id ?? Auth::id(),
            'duration_minutes' => $request->duration_minutes,
            'notes'            => $request->notes,
        ]);

        // Notify family
        Notification::create([
            'user_id'       => $visitRequest->requested_by,
            'title'         => '✅ Visit Request Approved!',
            'message'       => "Your visit request for {$visitRequest->patient->name} has been approved. Meeting scheduled for " . \Carbon\Carbon::parse($request->scheduled_at)->format('M d, Y h:i A'),
            'type'          => 'success',
            'related_model' => 'VisitRequest',
            'related_id'    => $visitRequest->id,
            'action_url'    => route('visit-requests.show', $visitRequest),
        ]);

        return back()->with('success', 'Visit request approved and meeting room created.');
    }

    public function reject(Request $request, VisitRequest $visitRequest)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $visitRequest->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        Notification::create([
            'user_id'       => $visitRequest->requested_by,
            'title'         => '❌ Visit Request Rejected',
            'message'       => "Your visit request for {$visitRequest->patient->name} has been rejected. Reason: {$request->rejection_reason}",
            'type'          => 'danger',
            'related_model' => 'VisitRequest',
            'related_id'    => $visitRequest->id,
            'action_url'    => route('visit-requests.show', $visitRequest),
        ]);

        return back()->with('success', 'Visit request rejected.');
    }

    public function destroy(VisitRequest $visitRequest)
    {
        if (!in_array($visitRequest->status, ['pending', 'cancelled'])) {
            return back()->with('error', 'Only pending requests can be deleted.');
        }
        $visitRequest->delete();
        return redirect()->route('visit-requests.index')
            ->with('success', 'Visit request deleted.');
    }
}
