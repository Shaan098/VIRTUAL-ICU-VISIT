<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Patient;
use App\Models\User;
use App\Models\VisitRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_patients'   => Patient::count(),
            'total_doctors'    => User::where('role', 'doctor')->count(),
            'total_families'   => User::where('role', 'family')->count(),
            'pending_requests' => VisitRequest::where('status', 'pending')->count(),
            'approved_today'   => VisitRequest::where('status', 'approved')
                                    ->whereDate('updated_at', today())->count(),
            'active_meetings'  => Meeting::where('status', 'active')->count(),
            'total_meetings'   => Meeting::count(),
        ];

        // Chart data — requests by status
        $statusCounts = VisitRequest::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')->pluck('count', 'status');

        // Last 7 days request trend
        $trend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $trend[] = [
                'label' => $date->format('D'),
                'count' => VisitRequest::whereDate('created_at', $date)->count(),
            ];
        }

        $recentRequests = VisitRequest::with(['patient', 'requester', 'assignedDoctor'])
            ->latest()->take(8)->get();

        $doctors = User::where('role', 'doctor')->withCount('patients')->get();

        $criticalPatients = Patient::where('status', 'critical')
            ->with('assignedDoctor')->take(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'statusCounts', 'trend', 'recentRequests', 'doctors', 'criticalPatients'
        ));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', "User {$user->name} status updated.");
    }

    public function destroyUser(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot delete an admin account.');
        }
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}
