<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with('assignedDoctor');

        // Doctors only see their own patients
        if (Auth::user()->isDoctor()) {
            $query->where('assigned_doctor_id', Auth::id());
        }

        // Family only see active/critical/stable (not discharged) patients
        if (Auth::user()->isFamily()) {
            $query->whereIn('status', ['active', 'critical', 'stable']);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('diagnosis', 'like', "%{$search}%")
                  ->orWhere('bed_number', 'like', "%{$search}%")
                  ->orWhere('ward', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('doctor') && Auth::user()->isAdmin()) {
            $query->where('assigned_doctor_id', $request->doctor);
        }

        $patients = $query->latest()->paginate(12);
        $doctors  = User::where('role', 'doctor')->orderBy('name')->get();

        return view('patients.index', compact('patients', 'doctors'));
    }

    public function create()
    {
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        return view('patients.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                    => 'required|string|max:255',
            'dob'                     => 'nullable|date|before:today',
            'gender'                  => 'required|in:male,female,other',
            'diagnosis'               => 'required|string|max:500',
            'bed_number'              => 'nullable|string|max:20',
            'ward'                    => 'nullable|string|max:100',
            'status'                  => 'required|in:active,critical,stable,discharged',
            'assigned_doctor_id'      => 'nullable|exists:users,id',
            'admission_date'          => 'nullable|date',
            'notes'                   => 'nullable|string',
            'emergency_contact_name'  => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'blood_group'             => 'nullable|string|max:10',
            'age'                     => 'nullable|integer|min:0|max:150',
        ]);

        Patient::create($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Patient added successfully.');
    }

    public function show(Patient $patient)
    {
        $patient->load(['assignedDoctor', 'visitRequests.requester', 'visitRequests.meeting']);
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $doctors = User::where('role', 'doctor')->orderBy('name')->get();
        return view('patients.edit', compact('patient', 'doctors'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name'                    => 'required|string|max:255',
            'dob'                     => 'nullable|date|before:today',
            'gender'                  => 'required|in:male,female,other',
            'diagnosis'               => 'required|string|max:500',
            'bed_number'              => 'nullable|string|max:20',
            'ward'                    => 'nullable|string|max:100',
            'status'                  => 'required|in:active,critical,stable,discharged',
            'assigned_doctor_id'      => 'nullable|exists:users,id',
            'admission_date'          => 'nullable|date',
            'notes'                   => 'nullable|string',
            'emergency_contact_name'  => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'blood_group'             => 'nullable|string|max:10',
            'age'                     => 'nullable|integer|min:0|max:150',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')
            ->with('success', 'Patient record removed.');
    }
}
