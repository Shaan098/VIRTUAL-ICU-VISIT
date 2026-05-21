<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    public function room(Meeting $meeting)
    {
        $meeting->load(['visitRequest.patient', 'visitRequest.requester', 'host']);

        // Mark as active when host joins
        if ($meeting->status === 'scheduled' && Auth::id() === $meeting->host_id) {
            $meeting->update(['status' => 'active', 'started_at' => now()]);
        }

        return view('meetings.room', compact('meeting'));
    }

    public function end(Meeting $meeting)
    {
        $meeting->update([
            'status'   => 'completed',
            'ended_at' => now(),
        ]);

        $meeting->visitRequest->update(['status' => 'completed']);

        return redirect()->route('visit-requests.index')
            ->with('success', 'Meeting ended and marked as completed.');
    }

    public function index()
    {
        $user  = Auth::user();
        $query = Meeting::with(['visitRequest.patient', 'visitRequest.requester', 'host']);

        if ($user->isDoctor()) {
            $query->where('host_id', $user->id);
        } elseif ($user->isFamily()) {
            $query->whereHas('visitRequest', fn($q) =>
                $q->where('requested_by', $user->id)
            );
        }

        $meetings = $query->orderBy('scheduled_at', 'desc')->paginate(10);
        return view('meetings.index', compact('meetings'));
    }
}
